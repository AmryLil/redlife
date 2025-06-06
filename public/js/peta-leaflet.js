(function () {
    "use strict";

    // Global variables
    let map;
    let userMarker = null;
    let selectedMarker = null;
    let locationMarkers = [];
    let routeLayer = null;
    let availableLocations = [];
    let userCoordinates = null;

    // Configuration
    const CONFIG = {
        EARTH_RADIUS_KM: 6371,
        MAX_SEARCH_RADIUS_KM: 50,
        MAX_RESULTS: 20,
        DEFAULT_ZOOM: 13,
        MIN_DISTANCE_THRESHOLD: 0.1,
    };

    // Utility functions
    function getElement(id) {
        return document.getElementById(id);
    }

    function setElementValue(id, value) {
        const el = getElement(id);
        if (el) {
            el.value = value;
            el.dispatchEvent(new Event("change"));
            return true;
        }
        return false;
    }

    function updateFilamentField(wireModel, value) {
        const selectors = [
            `input[wire\\:model="${wireModel}"]`,
            `input[wire\\:model\\.defer="${wireModel}"]`,
            `input[wire\\:model\\.lazy="${wireModel}"]`,
            `select[wire\\:model="${wireModel}"]`,
            `textarea[wire\\:model="${wireModel}"]`,
        ];

        for (let selector of selectors) {
            const element = document.querySelector(selector);
            if (element) {
                element.value = value;
                element.dispatchEvent(new Event("input", { bubbles: true }));
                element.dispatchEvent(new Event("change", { bubbles: true }));
                element.dispatchEvent(new Event("blur", { bubbles: true }));
                console.log(`Updated Filament field ${wireModel}:`, value);
                return true;
            }
        }

        console.warn(`Filament field not found: ${wireModel}`);
        return false;
    }

    function showStatus(message, type = "info") {
        const status = getElement("mapStatus");
        if (!status) return;

        status.textContent = message;
        status.style.display = "block";

        const colors = {
            error: { bg: "#fee2e2", color: "#991b1b" },
            success: { bg: "#d1fae5", color: "#065f46" },
            loading: { bg: "#fef3c7", color: "#92400e" },
            info: { bg: "#dbeafe", color: "#1e40af" },
        };

        const color = colors[type] || colors.info;
        status.style.backgroundColor = color.bg;
        status.style.color = color.color;

        if (type !== "loading") {
            setTimeout(() => (status.style.display = "none"), 5000);
        }
    }

    // Haversine formula untuk menghitung jarak dan bearing
    let calculateDistanceCallCount = 0;

    function calculateDistanceWithBearing(lat1, lon1, lat2, lon2) {
        calculateDistanceCallCount++; // tambah counter tiap panggil fungsi

        const toRadians = (degrees) => degrees * (Math.PI / 180);
        const toDegrees = (radians) => radians * (180 / Math.PI);

        const lat1Rad = toRadians(lat1);
        const lat2Rad = toRadians(lat2);
        const deltaLat = toRadians(lat2 - lat1);
        const deltaLon = toRadians(lon2 - lon1);

        // Haversine formula untuk jarak
        const a =
            Math.sin(deltaLat / 2) * Math.sin(deltaLat / 2) +
            Math.cos(lat1Rad) *
                Math.cos(lat2Rad) *
                Math.sin(deltaLon / 2) *
                Math.sin(deltaLon / 2);

        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        const distance = CONFIG.EARTH_RADIUS_KM * c;

        // Bearing calculation
        const y = Math.sin(deltaLon) * Math.cos(lat2Rad);
        const x =
            Math.cos(lat1Rad) * Math.sin(lat2Rad) -
            Math.sin(lat1Rad) * Math.cos(lat2Rad) * Math.cos(deltaLon);

        let bearing = toDegrees(Math.atan2(y, x));
        bearing = (bearing + 360) % 360;

        const result = {
            distance: distance,
            bearing: bearing,
            direction: getCompassDirection(bearing),
        };

        // Log hasil perhitungan Haversine dengan index call

        console.log(`Call #${calculateDistanceCallCount} - Haversine calculation:
        From: ${lat1.toFixed(6)}, ${lon1.toFixed(6)}
        To: ${lat2.toFixed(6)}, ${lon2.toFixed(6)}
        Distance: ${distance.toFixed(3)} km
        Bearing: ${bearing.toFixed(1)}°
        Direction: ${result.direction}`);

        return result;
    }

    function getCompassDirection(bearing) {
        const directions = [
            "Utara",
            "Timur Laut",
            "Timur",
            "Tenggara",
            "Selatan",
            "Barat Daya",
            "Barat",
            "Barat Laut",
        ];
        const index = Math.round(bearing / 45) % 8;
        return directions[index];
    }

    function extractLocationName(displayName) {
        const parts = displayName.split(",");
        if (parts.length >= 2) {
            return parts.slice(0, 2).join(", ").trim();
        }
        return displayName.length > 50
            ? displayName.substring(0, 50) + "..."
            : displayName;
    }

    // Process locations - disederhanakan tanpa pembagian tipe
    // Process locations - disederhanakan tanpa pembagian tipe
    function processLocations(locations, userLat, userLon) {
        const processed = [];
        const processedCoordinates = new Set();

        locations.forEach((location) => {
            const lat = parseFloat(location.lat);
            const lon = parseFloat(location.lon);

            if (isNaN(lat) || isNaN(lon)) return;

            const distanceData = calculateDistanceWithBearing(
                userLat,
                userLon,
                lat,
                lon,
            );
            if (distanceData.distance > CONFIG.MAX_SEARCH_RADIUS_KM) return;

            // Check for duplicates - MASALAH DI SINI
            const coordKey = `${lat.toFixed(4)},${lon.toFixed(4)}`;

            if (!processedCoordinates.has(coordKey)) {
                processedCoordinates.add(coordKey);

                processed.push({
                    place_id: String(location.place_id),
                    display_name: String(location.display_name),
                    name: extractLocationName(location.display_name),
                    lat: lat,
                    lon: lon,
                    distance: distanceData.distance,
                    bearing: distanceData.bearing,
                    direction: distanceData.direction,
                    type: "Donor Darah",
                    icon: "🩸",
                    color: "#dc2626",
                    priority: 1,
                });
            }
        });

        return processed
            .sort((a, b) => a.distance - b.distance)
            .slice(0, CONFIG.MAX_RESULTS);
    }

    function groupLocationsByDistance(locations) {
        const groups = {
            very_close: [], // 0-2 km
            close: [], // 2-5 km
            moderate: [], // 5-10 km
            far: [], // 10-25 km
            very_far: [], // 25+ km
        };

        locations.forEach((location) => {
            const distance = location.distance;
            if (distance <= 2) groups.very_close.push(location);
            else if (distance <= 5) groups.close.push(location);
            else if (distance <= 10) groups.moderate.push(location);
            else if (distance <= 25) groups.far.push(location);
            else groups.very_far.push(location);
        });

        return groups;
    }

    async function getRoute(startLat, startLon, endLat, endLon) {
        try {
            const url = `https://router.project-osrm.org/route/v1/driving/${startLon},${startLat};${endLon},${endLat}?overview=full&geometries=geojson&steps=true`;
            showStatus("Mencari rute terbaik...", "loading");

            const response = await fetch(url);
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();

            if (data.routes && data.routes.length > 0) {
                return {
                    geometry: data.routes[0].geometry,
                    distance: data.routes[0].distance,
                    duration: data.routes[0].duration,
                    legs: data.routes[0].legs,
                };
            }

            throw new Error("No route found");
        } catch (error) {
            console.error("Routing error:", error);
            showStatus("Gagal mendapatkan rute", "error");
            return null;
        }
    }

    function formatDuration(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        if (hours > 0) {
            return `${hours} jam ${minutes} menit`;
        }
        return `${minutes} menit`;
    }

    function drawRoute(routeData) {
        if (routeLayer && map.hasLayer(routeLayer)) {
            map.removeLayer(routeLayer);
        }

        if (!routeData || !routeData.geometry) return;

        routeLayer = L.geoJSON(routeData.geometry, {
            style: {
                color: "#3b82f6",
                weight: 5,
                opacity: 0.8,
                dashArray: "10, 5",
            },
        }).addTo(map);

        const group = new L.featureGroup([
            routeLayer,
            userMarker,
            selectedMarker,
        ]);
        map.fitBounds(group.getBounds(), { padding: [20, 20] });

        const distanceKm = (routeData.distance / 1000).toFixed(2);
        const duration = formatDuration(routeData.duration);

        showStatus(
            `Rute ditemukan: ${distanceKm} km, estimasi ${duration}`,
            "success",
        );

        return {
            distance: distanceKm,
            duration: duration,
            durationSeconds: routeData.duration,
        };
    }

    function initMap() {
        if (!getElement("map")) return;

        map = L.map("map").setView(
            [-5.147665, 119.432732],
            CONFIG.DEFAULT_ZOOM,
        );
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution: "© OpenStreetMap",
        }).addTo(map);

        console.log("Map initialized");
    }

    function setUserLocation(lat, lon) {
        if (!map) return;

        userCoordinates = { lat, lon };

        if (userMarker) map.removeLayer(userMarker);

        userMarker = L.marker([lat, lon]).addTo(map);
        userMarker.bindPopup("📍 Lokasi Anda").openPopup();

        map.setView([lat, lon], CONFIG.DEFAULT_ZOOM);

        setElementValue(
            "lokasi_pengguna",
            `${lat.toFixed(6)}, ${lon.toFixed(6)}`,
        );

        findDonorLocations(lat, lon);
    }

    function findDonorLocations(userLat, userLon) {
        showStatus("Mencari lokasi donor darah...", "loading");

        // Clear old markers
        locationMarkers.forEach((marker) => map.removeLayer(marker));
        locationMarkers = [];

        if (routeLayer) map.removeLayer(routeLayer);

        const selector = getElement("locationSelector");
        const info = getElement("selectedLocationInfo");
        if (selector) selector.style.display = "none";
        if (info) info.style.display = "none";

        // Simplified search queries
        const queries = [
            "PMI+Makassar",
            "UTD+Makassar",
            "Unit+Transfusi+Darah+Makassar",
            "Rumah+Sakit+Umum+Makassar",
            "RS+Makassar",
            "Palang+Merah+Indonesia+Makassar",
        ];

        const searchPromises = queries.map((query) => {
            const url = `https://nominatim.openstreetmap.org/search?q=${query}&format=json&limit=10&countrycodes=id`;
            return fetch(url)
                .then((response) => (response.ok ? response.json() : []))
                .catch(() => []);
        });

        Promise.all(searchPromises)
            .then((results) => {
                const allLocations = results.flat();

                if (allLocations.length === 0) {
                    showStatus("Tidak ditemukan lokasi donor darah", "error");
                    return;
                }

                const processedLocations = processLocations(
                    allLocations,
                    userLat,
                    userLon,
                );

                if (processedLocations.length === 0) {
                    showStatus(
                        `Tidak ada lokasi dalam radius ${CONFIG.MAX_SEARCH_RADIUS_KM} km`,
                        "error",
                    );
                    return;
                }

                availableLocations = processedLocations;

                // Add markers with unified style
                processedLocations.forEach((location) => {
                    const marker = L.marker([location.lat, location.lon], {
                        icon: L.divIcon({
                            html: `<div style="background-color: ${location.color}; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 1px 3px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; font-size: 10px;">${location.icon}</div>`,
                            className: "custom-marker",
                            iconSize: [20, 20],
                            iconAnchor: [10, 10],
                        }),
                    }).addTo(map);

                    marker.bindPopup(`
                        <strong>${location.icon} ${location.type}</strong><br>
                        <small>${location.name}</small><br>
                        <small>Jarak: ${location.distance.toFixed(2)} km</small><br>
                        <small>Arah: ${location.direction} (${location.bearing.toFixed(1)}°)</small>
                    `);
                    locationMarkers.push(marker);
                });

                populateSelector(processedLocations);
                sendToLivewire({
                    lokasi_pengguna: `${userLat.toFixed(6)}, ${userLon.toFixed(6)}`,
                    locations: processedLocations,
                    distance_groups:
                        groupLocationsByDistance(processedLocations),
                });

                showStatus(
                    `Ditemukan ${processedLocations.length} lokasi donor darah`,
                    "success",
                );
            })
            .catch((error) => {
                console.error("Search error:", error);
                showStatus("Gagal mencari lokasi", "error");
            });
    }

    function populateSelector(locations) {
        const selector = getElement("selectedLocation");
        const selectorContainer = getElement("locationSelector");

        if (!selector || !selectorContainer) return;

        selector.innerHTML =
            '<option value="">-- Pilih Lokasi Donor --</option>';

        const groups = groupLocationsByDistance(locations);

        const addGroup = (groupLocations, groupLabel) => {
            if (groupLocations.length > 0) {
                const groupHeader = document.createElement("optgroup");
                groupHeader.label = groupLabel;

                groupLocations.forEach((location) => {
                    const globalIndex = locations.indexOf(location);
                    const option = document.createElement("option");
                    option.value = globalIndex;
                    option.textContent = `${location.icon} ${location.name} (${location.distance.toFixed(2)} km ${location.direction})`;
                    groupHeader.appendChild(option);
                });

                selector.appendChild(groupHeader);
            }
        };

        addGroup(groups.very_close, "🟢 Sangat Dekat (0-2 km)");
        addGroup(groups.close, "🔵 Dekat (2-5 km)");
        addGroup(groups.moderate, "🟡 Sedang (5-10 km)");
        addGroup(groups.far, "🟠 Jauh (10-25 km)");
        addGroup(groups.very_far, "🔴 Sangat Jauh (25+ km)");

        selectorContainer.style.display = "block";

        selector.onchange = function () {
            handleLocationSelection(this.value);
        };
    }

    async function handleLocationSelection(selectedIndex) {
        const selectedLocationInfo = getElement("selectedLocationInfo");
        const locationDetails = getElement("locationDetails");

        if (selectedIndex === "" || selectedIndex === null) {
            if (selectedLocationInfo)
                selectedLocationInfo.style.display = "none";
            if (selectedMarker && map.hasLayer(selectedMarker)) {
                map.removeLayer(selectedMarker);
                selectedMarker = null;
            }
            if (routeLayer && map.hasLayer(routeLayer)) {
                map.removeLayer(routeLayer);
                routeLayer = null;
            }

            updateFilamentField("data.lokasi_donor_id", "");
            updateFilamentField("data.summary_lokasi", "");
            updateFilamentField("data.selected_location_data", "");

            sendToLivewire(
                {
                    lokasi_donor_terpilih: null,
                    selected_location_data: null,
                    lokasi_donor_id: "",
                    summary_lokasi: "",
                },
                "lokasiDonorDipilih",
            );

            return;
        }

        const location = availableLocations[parseInt(selectedIndex)];
        if (!location) return;

        console.log("Selected location:", location);

        if (selectedMarker && map.hasLayer(selectedMarker)) {
            map.removeLayer(selectedMarker);
        }
        if (routeLayer && map.hasLayer(routeLayer)) {
            map.removeLayer(routeLayer);
        }

        selectedMarker = L.marker([location.lat, location.lon], {
            icon: L.divIcon({
                html: '<div style="background-color: #ef4444; width: 25px; height: 25px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>',
                className: "selected-marker",
                iconSize: [25, 25],
                iconAnchor: [12, 12],
            }),
        }).addTo(map);

        selectedMarker
            .bindPopup(
                `
            <strong>🎯 Lokasi Donor Terpilih</strong><br>
            <strong>${location.icon} ${location.type}</strong><br>
            <small>${location.name}</small><br>
            <small>Jarak: ${location.distance.toFixed(2)} km (${location.direction})</small><br>
            <small>Bearing: ${location.bearing.toFixed(1)}°</small>
        `,
            )
            .openPopup();

        map.setView([location.lat, location.lon], 15);

        let routeInfo = null;
        if (userCoordinates) {
            const routeData = await getRoute(
                userCoordinates.lat,
                userCoordinates.lon,
                location.lat,
                location.lon,
            );
            if (routeData) {
                routeInfo = drawRoute(routeData);
            }
        }

        const locationDisplayName = location.display_name;
        const locationPlaceId = location.place_id;

        let city = "Makassar";
        if (locationDisplayName) {
            const parts = locationDisplayName
                .split(",")
                .map((part) => part.trim());
            const cityPart = parts.find(
                (part) =>
                    part.toLowerCase().includes("makassar") ||
                    part.toLowerCase().includes("sulawesi"),
            );
            if (cityPart) {
                city = cityPart.replace(/^(Kota\s+|Kabupaten\s+)/i, "").trim();
            }
        }

        const selectedLocationData = {
            lokasi_donor_terpilih: {
                jenis: location.type,
                nama: location.name,
                alamat: locationDisplayName,
                kota: city,
                koordinat: `${location.lat.toFixed(6)}, ${location.lon.toFixed(6)}`,
                jarak: location.distance.toFixed(2),
                arah: location.direction,
                bearing: location.bearing.toFixed(1),
                place_id: locationPlaceId,
                icon: location.icon,
                priority: location.priority,
                lat: location.lat,
                lon: location.lon,
                latitude: location.lat,
                longitude: location.lon,
                route_info: routeInfo
                    ? {
                          distance: routeInfo.distance,
                          duration: routeInfo.duration,
                          duration_seconds: routeInfo.durationSeconds,
                      }
                    : null,
            },
        };

        updateFilamentField("data.lokasi_donor_id", locationPlaceId);
        updateFilamentField("data.summary_lokasi", locationDisplayName);
        updateFilamentField(
            "data.selected_location_data",
            JSON.stringify(selectedLocationData),
        );

        setElementValue("lokasi_terpilih", locationDisplayName);

        // Ganti bagian locationDetails.innerHTML di dalam function handleLocationSelection
        // Mulai dari baris sekitar 400-an dalam code asli

        if (selectedLocationInfo && locationDetails) {
            let routeHtml = "";
            if (routeInfo) {
                routeHtml = `
            <div class="route-info-card">
                <div class="route-header">
                    <span class="route-icon">🛣️</span>
                    <span class="route-title">Informasi Rute</span>
                </div>
                <div class="route-details">
                    <div class="route-item">
                        <span class="route-label">📏 Jarak Rute:</span>
                        <span class="route-value">${routeInfo.distance} km</span>
                    </div>
                    <div class="route-item">
                        <span class="route-label">⏱️ Estimasi Waktu:</span>
                        <span class="route-value">${routeInfo.duration}</span>
                    </div>
                </div>
            </div>
        `;
            }

            locationDetails.innerHTML = `
        <div class="location-card">
            <div class="location-header">
                <div class="location-type">
                    <span class="type-icon">${location.icon}</span>
                    <span class="type-text">${location.type}</span>
                </div>
                <div class="location-badge">
                    <span class="distance-badge">${location.distance.toFixed(2)} km</span>
                </div>
            </div>
            
            <div class="location-body">
                <div class="location-name">
                    <h3>${location.name}</h3>
                </div>
                
                <div class="location-details-grid">
                    <div class="detail-item">
                        <div class="detail-icon">📍</div>
                        <div class="detail-content">
                            <div class="detail-label">Alamat</div>
                            <div class="detail-value">${location.display_name}</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">📐</div>
                        <div class="detail-content">
                            <div class="detail-label">Jarak Lurus</div>
                            <div class="detail-value">${location.distance.toFixed(2)} km</div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-icon">🧭</div>
                        <div class="detail-content">
                            <div class="detail-label">Arah</div>
                            <div class="detail-value">${location.direction} (${location.bearing.toFixed(1)}°)</div>
                        </div>
                    </div>
                </div>
            </div>
            
            ${routeHtml}
        </div>
        
        <style>
            .location-card {
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                border-radius: 16px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                border: 1px solid #e2e8f0;
                overflow: hidden;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            }
            
            .location-header {
                background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
                color: white;
                padding: 16px 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .location-type {
                display: flex;
                align-items: center;
                gap: 8px;
            }
            
            .type-icon {
                font-size: 20px;
                filter: drop-shadow(0 1px 2px rgba(0,0,0,0.2));
            }
            
            .type-text {
                font-weight: 600;
                font-size: 16px;
                text-shadow: 0 1px 2px rgba(0,0,0,0.1);
            }
            
            .distance-badge {
                background: rgba(255, 255, 255, 0.2);
                padding: 6px 12px;
                border-radius: 20px;
                font-weight: 600;
                font-size: 14px;
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }
            
            .location-body {
                padding: 20px;
            }
            
            .location-name h3 {
                margin: 0 0 16px 0;
                color: #1e293b;
                font-size: 18px;
                font-weight: 700;
                line-height: 1.3;
            }
            
            .location-details-grid {
                display: grid;
                gap: 16px;
            }
            
            .detail-item {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                padding: 12px;
                background: #f8fafc;
                border-radius: 12px;
                border-left: 4px solid #dc2626;
                transition: all 0.2s ease;
            }
            
            .detail-item:hover {
                background: #f1f5f9;
                transform: translateX(2px);
            }
            
            .detail-icon {
                font-size: 18px;
                width: 24px;
                height: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: white;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                flex-shrink: 0;
            }
            
            .detail-content {
                flex: 1;
                min-width: 0;
            }
            
            .detail-label {
                font-size: 12px;
                color: #64748b;
                font-weight: 500;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 2px;
            }
            
            .detail-value {
                color: #334155;
                font-weight: 600;
                font-size: 14px;
                word-break: break-word;
            }
            
            .route-info-card {
                margin: 0 20px 20px 20px;
                background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
            }
            
            .route-header {
                padding: 12px 16px;
                display: flex;
                align-items: center;
                gap: 8px;
                color: white;
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            }
            
            .route-icon {
                font-size: 16px;
            }
            
            .route-title {
                font-weight: 600;
                font-size: 14px;
            }
            
            .route-details {
                padding: 16px;
                display: grid;
                gap: 12px;
            }
            
            .route-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 8px 12px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 8px;
                backdrop-filter: blur(10px);
            }
            
            .route-label {
                color: rgba(255, 255, 255, 0.9);
                font-size: 13px;
                font-weight: 500;
            }
            
            .route-value {
                color: white;
                font-weight: 700;
                font-size: 14px;
            }
            
            @media (max-width: 640px) {
                .location-header {
                    padding: 12px 16px;
                }
                
                .location-body {
                    padding: 16px;
                }
                
                .location-name h3 {
                    font-size: 16px;
                }
                
                .detail-item {
                    padding: 10px;
                }
                
                .route-info-card {
                    margin: 0 16px 16px 16px;
                }
            }
        </style>
    `;
            selectedLocationInfo.style.display = "block";
        }

        const livewireData = {
            lokasi_pengguna: getElement("lokasi_pengguna")
                ? getElement("lokasi_pengguna").value
                : "",
            lokasi_donor_terpilih: selectedLocationData.lokasi_donor_terpilih,
            lokasi_donor_id: locationPlaceId,
            summary_lokasi: locationDisplayName,
            selected_location_data: JSON.stringify(selectedLocationData),
        };

        console.log("Sending complete data to Livewire:", livewireData);

        sendToLivewire(livewireData, "lokasiDonorDipilih");

        setTimeout(() => {
            if (window.Livewire && window.Livewire.find) {
                const component = document.querySelector("[wire\\:id]");
                if (component && component.getAttribute("wire:id")) {
                    const livewireComponent = window.Livewire.find(
                        component.getAttribute("wire:id"),
                    );
                    if (livewireComponent && livewireComponent.$refresh) {
                        livewireComponent.$refresh();
                    }
                }
            }
        }, 100);
    }

    function sendToLivewire(data, eventName = "lokasiDiupdate") {
        console.log(`Sending to Livewire (${eventName}):`, data);

        try {
            let eventSent = false;

            if (window.Livewire?.dispatch) {
                window.Livewire.dispatch(eventName, data);
                eventSent = true;
                console.log("✓ Sent via Livewire.dispatch");
            } else if (window.livewire?.emit) {
                window.livewire.emit(eventName, data);
                eventSent = true;
                console.log("✓ Sent via livewire.emit");
            } else if (window.Livewire?.emit) {
                window.Livewire.emit(eventName, data);
                eventSent = true;
                console.log("✓ Sent via Livewire.emit");
            }

            if (!eventSent && window.Alpine) {
                window.Alpine.store("locationData", data);
                document.dispatchEvent(
                    new CustomEvent(eventName, { detail: data }),
                );
                console.log("✓ Sent via Alpine/CustomEvent");
                eventSent = true;
            }

            if (!eventSent) {
                const event = new CustomEvent(eventName, {
                    detail: data,
                    bubbles: true,
                    cancelable: true,
                });
                document.dispatchEvent(event);
                console.log("✓ Sent via DOM CustomEvent");
            }
        } catch (error) {
            console.error("Error sending to Livewire:", error);

            if (eventName === "lokasiDonorDipilih" && data.summary_lokasi) {
                const summaryField = document.querySelector(
                    'input[wire\\:model="data.summary_lokasi"]',
                );
                if (summaryField) {
                    summaryField.value = data.summary_lokasi;
                    summaryField.dispatchEvent(
                        new Event("input", { bubbles: true }),
                    );
                }
            }
        }
    }

    window.getCurrentLocation = function () {
        const btn = getElement("locationBtn");
        const btnText = getElement("btnText");

        if (!navigator.geolocation) {
            showStatus("Browser tidak mendukung geolokasi", "error");
            return;
        }

        if (!map) {
            initMap();
            setTimeout(() => getCurrentLocation(), 1000);
            return;
        }

        if (btn) btn.disabled = true;
        if (btnText) btnText.textContent = "Mengambil...";
        showStatus("Mengambil lokasi dengan presisi tinggi...", "loading");

        navigator.geolocation.getCurrentPosition(
            (position) => {
                setUserLocation(
                    position.coords.latitude,
                    position.coords.longitude,
                );

                if (btn) btn.disabled = false;
                if (btnText) btnText.textContent = "Ambil Lokasi";

                console.log(
                    `User location: ${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)} (accuracy: ${position.coords.accuracy}m)`,
                );
            },
            (error) => {
                console.error("Geolocation error:", error);

                let message = "Gagal mengambil lokasi. ";
                switch (error.code) {
                    case 1:
                        message +=
                            "Akses ditolak. Pastikan izin lokasi diaktifkan.";
                        break;
                    case 2:
                        message +=
                            "Posisi tidak tersedia. Coba lagi dalam beberapa saat.";
                        break;
                    case 3:
                        message += "Timeout. Koneksi mungkin lambat.";
                        break;
                    default:
                        message += "Error tidak dikenal.";
                }

                showStatus(message, "error");

                if (btn) btn.disabled = false;
                if (btnText) btnText.textContent = "Ambil Lokasi";
            },
            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 300000,
            },
        );
    };

    document.addEventListener("DOMContentLoaded", function () {
        initMap();
        console.log("Simplified Location Finder initialized");
        console.log(
            `Config: Max radius ${CONFIG.MAX_SEARCH_RADIUS_KM}km, Max results ${CONFIG.MAX_RESULTS}`,
        );
    });
})();
