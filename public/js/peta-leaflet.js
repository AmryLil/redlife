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

    // PERBAIKAN: Fungsi khusus untuk update Filament form field
    function updateFilamentField(wireModel, value) {
        // Cari input dengan wire:model yang sesuai
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
                // Trigger berbagai event untuk memastikan Livewire mendeteksi perubahan
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

    // Calculate distance and bearing using Haversine formula
    function calculateDistanceWithBearing(lat1, lon1, lat2, lon2) {
        const toRadians = (degrees) => degrees * (Math.PI / 180);
        const toDegrees = (radians) => radians * (180 / Math.PI);

        // Distance calculation
        const lat1Rad = toRadians(lat1);
        const lat2Rad = toRadians(lat2);
        const deltaLat = toRadians(lat2 - lat1);
        const deltaLon = toRadians(lon2 - lon1);

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

        return {
            distance: distance,
            bearing: bearing,
            direction: getCompassDirection(bearing),
        };
    }

    // Get compass direction from bearing
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

    // Get location type and details
    function getLocationType(name) {
        const lowerName = name.toLowerCase();

        if (lowerName.includes("pmi") || lowerName.includes("palang merah")) {
            return { type: "PMI", icon: "🏥", color: "#dc2626", priority: 1 };
        }
        if (lowerName.includes("utd") || lowerName.includes("unit transfusi")) {
            return { type: "UTD", icon: "🩸", color: "#7c2d12", priority: 2 };
        }
        if (
            lowerName.includes("rumah sakit") ||
            lowerName.includes("rs ") ||
            lowerName.includes("hospital")
        ) {
            return { type: "RS", icon: "🏨", color: "#059669", priority: 3 };
        }
        if (lowerName.includes("klinik")) {
            return {
                type: "Klinik",
                icon: "🏥",
                color: "#0891b2",
                priority: 4,
            };
        }
        return { type: "Kesehatan", icon: "⚕️", color: "#6366f1", priority: 5 };
    }

    // Extract clean location name
    function extractLocationName(displayName) {
        const parts = displayName.split(",");
        if (parts.length >= 2) {
            return parts.slice(0, 2).join(", ").trim();
        }
        return displayName.length > 50
            ? displayName.substring(0, 50) + "..."
            : displayName;
    }

    // Process and filter locations
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

            // Check for duplicates
            const coordKey = `${lat.toFixed(4)},${lon.toFixed(4)}`;
            let isDuplicate = false;
            for (const existing of processed) {
                const existingDistance = calculateDistanceWithBearing(
                    lat,
                    lon,
                    existing.lat,
                    existing.lon,
                );
                if (existingDistance.distance < CONFIG.MIN_DISTANCE_THRESHOLD) {
                    isDuplicate = true;
                    break;
                }
            }

            if (!isDuplicate && !processedCoordinates.has(coordKey)) {
                processedCoordinates.add(coordKey);
                const locationInfo = getLocationType(location.display_name);

                processed.push({
                    place_id: String(location.place_id),
                    display_name: String(location.display_name),
                    name: extractLocationName(location.display_name),
                    lat: lat,
                    lon: lon,
                    distance: distanceData.distance,
                    bearing: distanceData.bearing,
                    direction: distanceData.direction,
                    type: locationInfo.type,
                    icon: locationInfo.icon,
                    color: locationInfo.color,
                    priority: locationInfo.priority,
                });
            }
        });

        // Sort by priority then distance
        return processed
            .sort((a, b) => {
                if (a.priority !== b.priority) return a.priority - b.priority;
                return a.distance - b.distance;
            })
            .slice(0, CONFIG.MAX_RESULTS);
    }

    // Group locations by distance
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

    // Get route using OSRM
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

    // Format duration
    function formatDuration(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);

        if (hours > 0) {
            return `${hours} jam ${minutes} menit`;
        }
        return `${minutes} menit`;
    }

    // Draw route on map
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

        // Fit map to show route
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

    // Initialize map
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

    // Set user location
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

    // Find donor locations
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

        const queries = [
            "PMI+Makassar",
            "UTD+Makassar",
            "Unit+Transfusi+Darah+Makassar",
            "Rumah+Sakit+Makassar",
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

                // Add markers with different colors
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

                const typeCount = processedLocations.reduce((acc, loc) => {
                    acc[loc.type] = (acc[loc.type] || 0) + 1;
                    return acc;
                }, {});

                const typeSummary = Object.entries(typeCount)
                    .map(([type, count]) => `${count} ${type}`)
                    .join(", ");

                showStatus(
                    `Ditemukan ${processedLocations.length} lokasi (${typeSummary})`,
                    "success",
                );
            })
            .catch((error) => {
                console.error("Search error:", error);
                showStatus("Gagal mencari lokasi", "error");
            });
    }

    // Populate location selector with distance groups
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
                    option.textContent = `${location.icon} ${location.type} - ${location.name} (${location.distance.toFixed(2)} km ${location.direction})`;
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

    // PERBAIKAN UTAMA: Handle location selection dengan update yang tepat ke Filament
    async function handleLocationSelection(selectedIndex) {
        const selectedLocationInfo = getElement("selectedLocationInfo");
        const locationDetails = getElement("locationDetails");

        if (selectedIndex === "" || selectedIndex === null) {
            // Reset selection
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

            // Clear semua field Filament
            updateFilamentField("data.lokasi_donor_id", "");
            updateFilamentField("data.summary_lokasi", "");
            updateFilamentField("data.selected_location_data", "");

            // Kirim data kosong ke Livewire
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

        // Remove previous markers and routes
        if (selectedMarker && map.hasLayer(selectedMarker)) {
            map.removeLayer(selectedMarker);
        }
        if (routeLayer && map.hasLayer(routeLayer)) {
            map.removeLayer(routeLayer);
        }

        // Add selected marker
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

        // Get route if user location available
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

        // PERBAIKAN: Siapkan data lokasi yang akan dikirim
        const locationDisplayName = location.display_name;
        const locationPlaceId = location.place_id;

        // Data untuk Livewire
        const selectedLocationData = {
            lokasi_donor_terpilih: {
                jenis: location.type,
                nama: location.name,
                alamat: locationDisplayName,
                koordinat: `${location.lat.toFixed(6)}, ${location.lon.toFixed(6)}`,
                jarak: location.distance.toFixed(2),
                arah: location.direction,
                bearing: location.bearing.toFixed(1),
                place_id: locationPlaceId,
                icon: location.icon,
                priority: location.priority,
                lat: location.lat,
                lon: location.lon,
                route_info: routeInfo
                    ? {
                          distance: routeInfo.distance,
                          duration: routeInfo.duration,
                          duration_seconds: routeInfo.durationSeconds,
                      }
                    : null,
            },
        };

        // PERBAIKAN: Update field Filament secara langsung
        updateFilamentField("data.lokasi_donor_id", locationPlaceId);
        updateFilamentField("data.summary_lokasi", locationDisplayName);
        updateFilamentField(
            "data.selected_location_data",
            JSON.stringify(selectedLocationData),
        );

        // PERBAIKAN: Update field khusus untuk step 3 (ringkasan)
        setElementValue("lokasi_terpilih", locationDisplayName);

        // Show detailed location info
        if (selectedLocationInfo && locationDetails) {
            let routeHtml = "";
            if (routeInfo) {
                routeHtml = `
                    <div style="margin: 8px 0; padding: 8px; background-color: #f0f9ff; border-left: 4px solid #3b82f6; border-radius: 4px;">
                        <strong>🛣️ Informasi Rute:</strong><br>
                        <div style="margin-top: 4px;"><strong>Jarak Rute:</strong> ${routeInfo.distance} km</div>
                        <div><strong>Estimasi Waktu:</strong> ${routeInfo.duration}</div>
                    </div>
                `;
            }

            locationDetails.innerHTML = `
                <div style="margin-bottom: 8px;"><strong>Jenis:</strong> ${location.icon} ${location.type}</div>
                <div style="margin-bottom: 6px;"><strong>Nama:</strong> ${location.name}</div>
                <div style="margin-bottom: 6px;"><strong>Alamat:</strong> ${location.display_name}</div>
                <div style="margin-bottom: 6px;"><strong>Jarak Lurus:</strong> ${location.distance.toFixed(2)} km</div>
                <div style="margin-bottom: 8px;"><strong>Arah:</strong> ${location.direction} (${location.bearing.toFixed(1)}°)</div>
                ${routeHtml}
            `;
            selectedLocationInfo.style.display = "block";
        }

        // PERBAIKAN: Kirim data lengkap ke Livewire dengan struktur yang benar
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

        // Kirim ke Livewire
        sendToLivewire(livewireData, "lokasiDonorDipilih");

        // PERBAIKAN: Tambahan untuk memastikan form terupdate
        setTimeout(() => {
            // Force refresh Livewire component
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

    // PERBAIKAN: Fungsi sendToLivewire yang lebih robust
    function sendToLivewire(data, eventName = "lokasiDiupdate") {
        console.log(`Sending to Livewire (${eventName}):`, data);

        try {
            let eventSent = false;

            // Coba berbagai cara untuk mengirim ke Livewire
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

            // Fallback: coba dengan Alpine.js events
            if (!eventSent && window.Alpine) {
                window.Alpine.store("locationData", data);
                document.dispatchEvent(
                    new CustomEvent(eventName, { detail: data }),
                );
                console.log("✓ Sent via Alpine/CustomEvent");
                eventSent = true;
            }

            // Fallback terakhir: DOM event
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

            // Emergency fallback: langsung update DOM
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

    // Get current location
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

    // Initialize when page loads
    document.addEventListener("DOMContentLoaded", function () {
        initMap();
        console.log("Enhanced Location Finder initialized");
        console.log(
            `Config: Max radius ${CONFIG.MAX_SEARCH_RADIUS_KM}km, Max results ${CONFIG.MAX_RESULTS}`,
        );
    });
})();
