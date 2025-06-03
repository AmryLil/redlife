(function () {
    "use strict";

    // Global variables
    let map,
        userMarker = null,
        pmiMarkers = [],
        selectedMarker = null,
        routeLayer = null;
    let isMapReady = false;
    let availableLocations = []; // Store available PMI locations
    let userCoordinates = null; // Store user coordinates for routing

    // Enhanced Haversine Formula Configuration
    const EARTH_RADIUS_KM = 6371; // Earth's radius in kilometers
    const MAX_SEARCH_RADIUS_KM = 50; // Maximum search radius in km
    const MAX_RESULTS = 20; // Maximum number of results to show
    const MIN_DISTANCE_THRESHOLD = 0.1; // Minimum distance in km to avoid duplicates

    // Utility functions
    function getElement(id) {
        return document.getElementById(id);
    }

    function setElementValue(id, value) {
        const el = getElement(id);
        if (el) {
            el.value = value;
            // Trigger change event untuk Livewire
            el.dispatchEvent(new Event("change"));
            return true;
        }
        console.warn(`Element ${id} not found`);
        return false;
    }

    function showStatus(message, type = "info") {
        const status = getElement("mapStatus");
        if (!status) return;

        status.textContent = message;
        status.style.display = "block";

        // Set colors based on type
        switch (type) {
            case "error":
                status.style.backgroundColor = "#fee2e2";
                status.style.color = "#991b1b";
                break;
            case "success":
                status.style.backgroundColor = "#d1fae5";
                status.style.color = "#065f46";
                break;
            case "loading":
                status.style.backgroundColor = "#fef3c7";
                status.style.color = "#92400e";
                break;
            default:
                status.style.backgroundColor = "#dbeafe";
                status.style.color = "#1e40af";
        }

        // Auto hide after 5 seconds (except loading)
        if (type !== "loading") {
            setTimeout(() => {
                status.style.display = "none";
            }, 5000);
        }
    }

    // Enhanced Haversine Formula Implementation
    function calculateHaversineDistance(lat1, lon1, lat2, lon2) {
        // Convert latitude and longitude from degrees to radians
        const toRadians = (degrees) => degrees * (Math.PI / 180);

        const lat1Rad = toRadians(lat1);
        const lat2Rad = toRadians(lat2);
        const deltaLatRad = toRadians(lat2 - lat1);
        const deltaLonRad = toRadians(lon2 - lon1);

        // Haversine formula
        const a =
            Math.sin(deltaLatRad / 2) * Math.sin(deltaLatRad / 2) +
            Math.cos(lat1Rad) *
                Math.cos(lat2Rad) *
                Math.sin(deltaLonRad / 2) *
                Math.sin(deltaLonRad / 2);

        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        // Distance in kilometers
        return EARTH_RADIUS_KM * c;
    }

    // Enhanced distance calculation with bearing
    function calculateDistanceWithBearing(lat1, lon1, lat2, lon2) {
        const distance = calculateHaversineDistance(lat1, lon1, lat2, lon2);

        // Calculate bearing (direction)
        const toRadians = (degrees) => degrees * (Math.PI / 180);
        const toDegrees = (radians) => radians * (180 / Math.PI);

        const lat1Rad = toRadians(lat1);
        const lat2Rad = toRadians(lat2);
        const deltaLonRad = toRadians(lon2 - lon1);

        const y = Math.sin(deltaLonRad) * Math.cos(lat2Rad);
        const x =
            Math.cos(lat1Rad) * Math.sin(lat2Rad) -
            Math.sin(lat1Rad) * Math.cos(lat2Rad) * Math.cos(deltaLonRad);

        let bearing = toDegrees(Math.atan2(y, x));
        bearing = (bearing + 360) % 360; // Normalize to 0-360 degrees

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

    // Filter locations by distance and remove duplicates
    function filterLocationsByDistance(
        locations,
        userLat,
        userLon,
        maxRadius = MAX_SEARCH_RADIUS_KM,
    ) {
        const filteredLocations = [];
        const processedCoordinates = new Set();

        locations.forEach((location) => {
            const lat = parseFloat(location.lat);
            const lon = parseFloat(location.lon);

            // Skip invalid coordinates
            if (isNaN(lat) || isNaN(lon)) {
                console.warn("Invalid coordinates:", location);
                return;
            }

            // Calculate distance using Haversine formula
            const distanceData = calculateDistanceWithBearing(
                userLat,
                userLon,
                lat,
                lon,
            );

            // Filter by maximum radius
            if (distanceData.distance > maxRadius) {
                return;
            }

            // Create coordinate key for duplicate detection
            const coordKey = `${lat.toFixed(4)},${lon.toFixed(4)}`;

            // Skip if too close to existing location (potential duplicate)
            let isDuplicate = false;
            for (const existingLocation of filteredLocations) {
                const existingDistance = calculateHaversineDistance(
                    lat,
                    lon,
                    existingLocation.lat,
                    existingLocation.lon,
                );
                if (existingDistance < MIN_DISTANCE_THRESHOLD) {
                    isDuplicate = true;
                    break;
                }
            }

            if (!isDuplicate && !processedCoordinates.has(coordKey)) {
                processedCoordinates.add(coordKey);

                const locationType = getLocationType(location.display_name);

                filteredLocations.push({
                    place_id: String(location.place_id),
                    display_name: String(location.display_name),
                    lat: lat,
                    lon: lon,
                    distance: distanceData.distance,
                    bearing: distanceData.bearing,
                    direction: distanceData.direction,
                    type: locationType.type,
                    icon: locationType.icon,
                    color: locationType.color,
                    priority: getLocationPriority(locationType.type),
                });
            }
        });

        return filteredLocations;
    }

    // Get location priority for sorting (lower number = higher priority)
    function getLocationPriority(type) {
        const priorities = {
            PMI: 1,
            UTD: 2,
            RS: 3,
            Klinik: 4,
            Kesehatan: 5,
        };
        return priorities[type] || 6;
    }

    // Advanced sorting with multiple criteria
    function sortLocationsByMultipleCriteria(locations) {
        return locations.sort((a, b) => {
            // Primary sort: Priority (PMI first, then UTD, etc.)
            if (a.priority !== b.priority) {
                return a.priority - b.priority;
            }

            // Secondary sort: Distance (closer first)
            if (Math.abs(a.distance - b.distance) > 0.1) {
                // Only if significant difference
                return a.distance - b.distance;
            }

            // Tertiary sort: Name length (shorter names first, usually more specific)
            return a.display_name.length - b.display_name.length;
        });
    }

    // Group locations by distance ranges
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
            if (distance <= 2) {
                groups.very_close.push(location);
            } else if (distance <= 5) {
                groups.close.push(location);
            } else if (distance <= 10) {
                groups.moderate.push(location);
            } else if (distance <= 25) {
                groups.far.push(location);
            } else {
                groups.very_far.push(location);
            }
        });

        return groups;
    }

    // Initialize map
    function initMap() {
        try {
            if (isMapReady || !getElement("map")) return;

            map = L.map("map").setView([-5.147665, 119.432732], 13);
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                maxZoom: 19,
                attribution: "© OpenStreetMap",
            }).addTo(map);

            isMapReady = true;
            console.log("Map initialized");
        } catch (error) {
            console.error("Map init error:", error);
            showStatus("Gagal memuat peta", "error");
        }
    }

    // Format duration from seconds to readable format
    function formatDuration(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);

        if (hours > 0) {
            return `${hours} jam ${minutes} menit`;
        }
        return `${minutes} menit`;
    }

    // Get route between two points using OSRM
    async function getRoute(startLat, startLon, endLat, endLon) {
        try {
            const url = `https://router.project-osrm.org/route/v1/driving/${startLon},${startLat};${endLon},${endLat}?overview=full&geometries=geojson&steps=true`;

            showStatus("Mencari rute terbaik...", "loading");

            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();

            if (data.routes && data.routes.length > 0) {
                return {
                    geometry: data.routes[0].geometry,
                    distance: data.routes[0].distance, // in meters
                    duration: data.routes[0].duration, // in seconds
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

    // Draw route on map
    function drawRoute(routeData) {
        // Remove existing route
        if (routeLayer && map.hasLayer(routeLayer)) {
            map.removeLayer(routeLayer);
        }

        if (!routeData || !routeData.geometry) {
            return;
        }

        // Create route layer with custom styling
        routeLayer = L.geoJSON(routeData.geometry, {
            style: {
                color: "#3b82f6",
                weight: 5,
                opacity: 0.8,
                dashArray: "10, 5",
            },
        }).addTo(map);

        // Fit map to show entire route
        const group = new L.featureGroup([
            routeLayer,
            userMarker,
            selectedMarker,
        ]);
        map.fitBounds(group.getBounds(), { padding: [20, 20] });

        // Show route info
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

    // Extract location name from display_name
    function extractLocationName(displayName) {
        // Try to extract meaningful location name
        const parts = displayName.split(",");
        if (parts.length >= 2) {
            // Return first two parts for more context
            return parts.slice(0, 2).join(", ").trim();
        }
        return displayName.length > 50
            ? displayName.substring(0, 50) + "..."
            : displayName;
    }

    // Enhanced populate location selector with distance grouping
    function populateLocationSelector(locations) {
        const selector = getElement("selectedLocation");
        const selectorContainer = getElement("locationSelector");

        if (!selector || !selectorContainer) {
            console.error("Location selector elements not found");
            return;
        }

        // Clear existing options
        selector.innerHTML =
            '<option value="">-- Pilih Lokasi Donor --</option>';

        // Group locations by distance
        const groups = groupLocationsByDistance(locations);

        // Add locations to dropdown with distance group headers
        const addGroupToSelector = (groupLocations, groupLabel) => {
            if (groupLocations.length > 0) {
                // Add group header
                const groupHeader = document.createElement("optgroup");
                groupHeader.label = groupLabel;

                groupLocations.forEach((location, localIndex) => {
                    const globalIndex = locations.indexOf(location);
                    const option = document.createElement("option");
                    option.value = globalIndex;
                    option.textContent = `${location.icon} ${location.type} - ${extractLocationName(location.display_name)} (${location.distance.toFixed(2)} km ${location.direction})`;
                    groupHeader.appendChild(option);
                });

                selector.appendChild(groupHeader);
            }
        };

        // Add groups in order of distance
        addGroupToSelector(groups.very_close, "🟢 Sangat Dekat (0-2 km)");
        addGroupToSelector(groups.close, "🔵 Dekat (2-5 km)");
        addGroupToSelector(groups.moderate, "🟡 Sedang (5-10 km)");
        addGroupToSelector(groups.far, "🟠 Jauh (10-25 km)");
        addGroupToSelector(groups.very_far, "🔴 Sangat Jauh (25+ km)");

        // Show selector
        selectorContainer.style.display = "block";

        // Add change event listener
        selector.onchange = function () {
            handleLocationSelection(this.value);
        };
    }

    // Handle location selection with routing
    async function handleLocationSelection(selectedIndex) {
        const selectedLocationInfo = getElement("selectedLocationInfo");
        const locationDetails = getElement("locationDetails");

        if (selectedIndex === "" || selectedIndex === null) {
            // Hide info and remove selected marker and route
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
            return;
        }

        const location = availableLocations[parseInt(selectedIndex)];
        if (!location) {
            console.error("Selected location not found");
            return;
        }

        // Remove previous selected marker and route
        if (selectedMarker && map.hasLayer(selectedMarker)) {
            map.removeLayer(selectedMarker);
        }
        if (routeLayer && map.hasLayer(routeLayer)) {
            map.removeLayer(routeLayer);
        }

        // Add new selected marker with different icon
        selectedMarker = L.marker([location.lat, location.lon], {
            // Create custom selected icon
            icon: L.divIcon({
                html: '<div style="background-color: #ef4444; width: 25px; height: 25px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>',
                className: "selected-marker",
                iconSize: [25, 25],
                iconAnchor: [12, 12],
            }),
        }).addTo(map);
        setElementValue("lokasi_terpilih", location.display_name);
        selectedMarker
            .bindPopup(
                `
                <strong>🎯 Lokasi Donor Terpilih</strong><br>
                <strong>${location.icon} ${location.type}</strong><br>
                <small>${extractLocationName(location.display_name)}</small><br>
                <small>Jarak: ${location.distance.toFixed(2)} km (${location.direction})</small><br>
                <small>Bearing: ${location.bearing.toFixed(1)}°</small>
            `,
            )
            .openPopup();

        // Center map on selected location
        map.setView([location.lat, location.lon], 15);

        // Get and draw route if user location is available
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

        // Show location info with route details
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
                    <div style="margin-bottom: 6px;"><strong>Nama:</strong> ${extractLocationName(location.display_name)}</div>
                    <div style="margin-bottom: 6px;"><strong>Alamat:</strong> ${location.display_name}</div>
                    <div style="margin-bottom: 6px;"><strong>Jarak Lurus:</strong> ${location.distance.toFixed(2)} km</div>
                    <div style="margin-bottom: 8px;"><strong>Arah:</strong> ${location.direction} (${location.bearing.toFixed(1)}°)</div>
                    ${routeHtml}
                `;
            selectedLocationInfo.style.display = "block";
        }

        // Send selected location data to Livewire (including route info and bearing)
        const locationData = {
            lokasi_pengguna: getElement("lokasi_pengguna")
                ? getElement("lokasi_pengguna").value
                : "",
            lokasi_donor_terpilih: {
                jenis: location.type,
                nama: extractLocationName(location.display_name),
                alamat: location.display_name,
                koordinat: `${location.lat.toFixed(6)}, ${location.lon.toFixed(6)}`,
                jarak: location.distance.toFixed(2),
                arah: location.direction,
                bearing: location.bearing.toFixed(1),
                place_id: location.place_id,
                icon: location.icon,
                priority: location.priority,
                route_info: routeInfo
                    ? {
                          distance: routeInfo.distance,
                          duration: routeInfo.duration,
                          duration_seconds: routeInfo.durationSeconds,
                      }
                    : null,
            },
        };

        // Send to Livewire
        try {
            let eventSent = false;

            // Method 1: Livewire v3 style
            if (window.Livewire && typeof Livewire.dispatch === "function") {
                Livewire.dispatch("lokasiDonorDipilih", locationData);
                console.log(
                    "✓ Selected location sent via Livewire.dispatch (v3)",
                );
                eventSent = true;
            }

            // Method 2: Livewire v2 style
            if (
                !eventSent &&
                window.livewire &&
                typeof window.livewire.emit === "function"
            ) {
                window.livewire.emit("lokasiDonorDipilih", locationData);
                console.log("✓ Selected location sent via livewire.emit (v2)");
                eventSent = true;
            }

            // Method 3: Fallback Livewire v2/v3
            if (
                !eventSent &&
                window.Livewire &&
                typeof Livewire.emit === "function"
            ) {
                Livewire.emit("lokasiDonorDipilih", locationData);
                console.log(
                    "✓ Selected location sent via Livewire.emit (fallback)",
                );
                eventSent = true;
            }

            if (!eventSent) {
                console.warn(
                    "No Livewire dispatch method available for selected location",
                );
            }
        } catch (error) {
            console.error(
                "Error sending selected location to Livewire:",
                error,
            );
        }

        console.log("Location selected:", locationData);
    }

    // Set user location
    function setUserLocation(lat, lon) {
        if (!isMapReady) {
            console.error("Map not ready");
            return;
        }

        try {
            // Store user coordinates for routing
            userCoordinates = { lat: lat, lon: lon };

            // Remove old marker
            if (userMarker) {
                map.removeLayer(userMarker);
            }

            // Add new marker
            userMarker = L.marker([lat, lon]).addTo(map);
            userMarker.bindPopup("📍 Lokasi Anda").openPopup();

            // Center map
            map.setView([lat, lon], 13);

            // Update form - kirim koordinat ke input lokasi_pengguna
            setElementValue(
                "lokasi_pengguna",
                `${lat.toFixed(6)}, ${lon.toFixed(6)}`,
            );

            // Find donor locations
            findPMI(lat, lon);
        } catch (error) {
            console.error("Set location error:", error);
            showStatus("Gagal menandai lokasi", "error");
        }
    }

    // Determine location type and icon
    function getLocationType(displayName) {
        const name = displayName.toLowerCase();
        if (name.includes("pmi") || name.includes("palang merah")) {
            return { type: "PMI", icon: "🏥", color: "#dc2626" };
        } else if (
            name.includes("utd") ||
            name.includes("unit transfusi darah")
        ) {
            return { type: "UTD", icon: "🩸", color: "#7c2d12" };
        } else if (
            name.includes("rumah sakit") ||
            name.includes("rs ") ||
            name.includes("hospital")
        ) {
            return { type: "RS", icon: "🏨", color: "#059669" };
        } else if (name.includes("klinik")) {
            return { type: "Klinik", icon: "🏥", color: "#0891b2" };
        }
        return { type: "Kesehatan", icon: "⚕️", color: "#6366f1" };
    }

    // Enhanced find donor locations with Haversine filtering and sorting
    function findPMI(userLat, userLon) {
        showStatus(
            "Mencari lokasi donor darah dengan formula Haversine...",
            "loading",
        );

        // Clear old markers and routes
        pmiMarkers.forEach((marker) => {
            if (map.hasLayer(marker)) {
                map.removeLayer(marker);
            }
        });
        pmiMarkers = [];

        if (routeLayer && map.hasLayer(routeLayer)) {
            map.removeLayer(routeLayer);
            routeLayer = null;
        }

        // Hide location selector while searching
        const selectorContainer = getElement("locationSelector");
        const selectedLocationInfo = getElement("selectedLocationInfo");
        if (selectorContainer) selectorContainer.style.display = "none";
        if (selectedLocationInfo) selectedLocationInfo.style.display = "none";

        // Define search queries for different types of donor locations
        const searchQueries = [
            "PMI+Makassar",
            "UTD+Makassar",
            "Unit+Transfusi+Darah+Makassar",
            "Rumah+Sakit+Makassar",
            "RS+Makassar",
            "Palang+Merah+Indonesia+Makassar",
        ];

        // Search all types concurrently
        const searchPromises = searchQueries.map((query) => {
            const searchUrl = `https://nominatim.openstreetmap.org/search?q=${query}&format=json&limit=10&countrycodes=id`;
            return fetch(searchUrl)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}`);
                    }
                    return response.json();
                })
                .catch((error) => {
                    console.warn(`Search failed for query: ${query}`, error);
                    return []; // Return empty array on error
                });
        });

        Promise.all(searchPromises)
            .then((searchResults) => {
                console.log("All search results:", searchResults);

                // Combine all results
                const allResults = [];
                searchResults.forEach((results) => {
                    if (Array.isArray(results)) {
                        allResults.push(...results);
                    }
                });

                if (allResults.length === 0) {
                    showStatus("Tidak ditemukan lokasi donor darah", "error");
                    return;
                }

                // Filter and process results using enhanced Haversine formula
                const processedResults = filterLocationsByDistance(
                    allResults,
                    userLat,
                    userLon,
                    MAX_SEARCH_RADIUS_KM,
                );

                if (processedResults.length === 0) {
                    showStatus(
                        `Tidak ada lokasi donor darah dalam radius ${MAX_SEARCH_RADIUS_KM} km`,
                        "error",
                    );
                    return;
                }

                // Sort using multiple criteria
                const sortedResults =
                    sortLocationsByMultipleCriteria(processedResults);

                // Limit results
                const finalResults = sortedResults.slice(0, MAX_RESULTS);

                // Store available locations
                availableLocations = finalResults;

                // Add markers for results with different colors
                finalResults.forEach((location, index) => {
                    try {
                        // Create custom colored marker
                        const marker = L.marker([location.lat, location.lon], {
                            icon: L.divIcon({
                                html: `<div style="background-color: ${location.color}; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 1px 3px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; font-size: 10px;">${location.icon}</div>`,
                                className: "custom-marker",
                                iconSize: [20, 20],
                                iconAnchor: [10, 10],
                            }),
                        }).addTo(map);

                        marker.bindPopup(`
                                <strong>${location.icon} ${location.type}${index === 0 ? " (Prioritas Tertinggi)" : ""}</strong><br>
                                <small>${extractLocationName(location.display_name)}</small><br>
                                <small>Jarak: ${location.distance.toFixed(2)} km</small><br>
                                <small>Arah: ${location.direction} (${location.bearing.toFixed(1)}°)</small>
                            `);
                        pmiMarkers.push(marker);
                    } catch (error) {
                        console.error("Marker error:", error);
                    }
                });

                // Populate location selector with enhanced grouping
                populateLocationSelector(finalResults);

                // Group results by distance for analysis
                const distanceGroups = groupLocationsByDistance(finalResults);
                const groupSummary = Object.entries(distanceGroups)
                    .filter(([_, locations]) => locations.length > 0)
                    .map(([groupName, locations]) => {
                        const groupLabels = {
                            very_close: "sangat dekat",
                            close: "dekat",
                            moderate: "sedang",
                            far: "jauh",
                            very_far: "sangat jauh",
                        };
                        return `${locations.length} ${groupLabels[groupName]}`;
                    })
                    .join(", ");

                // Send data to Livewire with enhanced information
                const locationData = {
                    lokasi_pengguna: `${userLat.toFixed(6)}, ${userLon.toFixed(6)}`,
                    locations: finalResults,
                    search_radius: MAX_SEARCH_RADIUS_KM,
                    distance_groups: distanceGroups,
                    haversine_used: true,
                };

                console.log(
                    "Sending enhanced location data to Livewire:",
                    locationData,
                );

                // Dispatch event ke Livewire
                try {
                    let eventSent = false;

                    // Method 1: Livewire v3 style
                    if (
                        window.Livewire &&
                        typeof Livewire.dispatch === "function"
                    ) {
                        Livewire.dispatch("lokasiDiupdate", locationData);
                        console.log(
                            "✓ Enhanced data sent via Livewire.dispatch (v3)",
                        );
                        eventSent = true;
                    }

                    // Method 2: Livewire v2 style
                    if (
                        !eventSent &&
                        window.livewire &&
                        typeof window.livewire.emit === "function"
                    ) {
                        window.livewire.emit("lokasiDiupdate", locationData);
                        console.log(
                            "✓ Enhanced data sent via livewire.emit (v2)",
                        );
                        eventSent = true;
                    }

                    // Method 3: Fallback Livewire v2/v3
                    if (
                        !eventSent &&
                        window.Livewire &&
                        typeof Livewire.emit === "function"
                    ) {
                        Livewire.emit("lokasiDiupdate", locationData);
                        console.log(
                            "✓ Enhanced data sent via Livewire.emit (fallback)",
                        );
                        eventSent = true;
                    }

                    // Method 4: Global event dispatch
                    if (!eventSent) {
                        window.dispatchEvent(
                            new CustomEvent("livewire:lokasiDiupdate", {
                                detail: locationData,
                            }),
                        );
                        console.log(
                            "✓ Enhanced data sent via custom event (fallback)",
                        );
                        eventSent = true;
                    }

                    if (!eventSent) {
                        console.error(
                            "❌ No Livewire dispatch method available",
                        );
                    }
                } catch (error) {
                    console.error(
                        "Error sending enhanced data to Livewire:",
                        error,
                    );
                    showStatus("Gagal mengirim data ke server", "error");
                }

                // Group results by type for enhanced status message
                const typeCount = finalResults.reduce((acc, loc) => {
                    acc[loc.type] = (acc[loc.type] || 0) + 1;
                    return acc;
                }, {});

                const typeSummary = Object.entries(typeCount)
                    .map(([type, count]) => `${count} ${type}`)
                    .join(", ");

                showStatus(
                    `Ditemukan ${finalResults.length} lokasi (${typeSummary}) dalam radius ${MAX_SEARCH_RADIUS_KM}km. Distribusi jarak: ${groupSummary}. Diurutkan berdasarkan prioritas & jarak Haversine.`,
                    "success",
                );
            })
            .catch((error) => {
                console.error("Enhanced search error:", error);
                showStatus("Gagal mencari lokasi donor darah", "error");
            });
    }

    // Get current location
    window.getCurrentLocation = function () {
        const btn = getElement("locationBtn");
        const btnText = getElement("btnText");

        if (!navigator.geolocation) {
            showStatus("Browser tidak mendukung geolokasi", "error");
            return;
        }

        if (!isMapReady) {
            initMap();
            setTimeout(() => getCurrentLocation(), 1000);
            return;
        }

        // Update button
        if (btn) btn.disabled = true;
        if (btnText) btnText.textContent = "Mengambil...";
        showStatus("Mengambil lokasi dengan presisi tinggi...", "loading");

        navigator.geolocation.getCurrentPosition(
            (position) => {
                setUserLocation(
                    position.coords.latitude,
                    position.coords.longitude,
                );

                // Reset button
                if (btn) btn.disabled = false;
                if (btnText) btnText.textContent = "Ambil Lokasi";

                console.log(
                    `User location set: ${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)} (accuracy: ${position.coords.accuracy}m)`,
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

                // Reset button
                if (btn) btn.disabled = false;
                if (btnText) btnText.textContent = "Ambil Lokasi";
            },
            {
                enableHighAccuracy: true,
                timeout: 15000, // Increased timeout for better accuracy
                maximumAge: 300000,
            },
        );
    };

    // Additional utility functions for enhanced functionality

    // Calculate the center point (centroid) of multiple locations
    window.calculateLocationsCentroid = function (locations) {
        if (!locations || locations.length === 0) return null;

        let totalLat = 0;
        let totalLon = 0;

        locations.forEach((location) => {
            totalLat += location.lat;
            totalLon += location.lon;
        });

        return {
            lat: totalLat / locations.length,
            lon: totalLon / locations.length,
        };
    };

    // Find the optimal location (closest to all other locations)
    window.findOptimalLocation = function (locations, userLat, userLon) {
        if (!locations || locations.length === 0) return null;

        let bestLocation = null;
        let minTotalDistance = Infinity;

        locations.forEach((location) => {
            let totalDistance = 0;

            // Calculate distance to user
            totalDistance += calculateHaversineDistance(
                userLat,
                userLon,
                location.lat,
                location.lon,
            );

            // Calculate distance to all other locations
            locations.forEach((otherLocation) => {
                if (location.place_id !== otherLocation.place_id) {
                    totalDistance += calculateHaversineDistance(
                        location.lat,
                        location.lon,
                        otherLocation.lat,
                        otherLocation.lon,
                    );
                }
            });

            if (totalDistance < minTotalDistance) {
                minTotalDistance = totalDistance;
                bestLocation = location;
            }
        });

        return bestLocation;
    };

    // Get locations within a specific radius
    window.getLocationsWithinRadius = function (
        locations,
        centerLat,
        centerLon,
        radiusKm,
    ) {
        if (!locations) return [];

        return locations.filter((location) => {
            const distance = calculateHaversineDistance(
                centerLat,
                centerLon,
                location.lat,
                location.lon,
            );
            return distance <= radiusKm;
        });
    };

    // Export location data to JSON (for debugging or external use)
    window.exportLocationData = function () {
        const data = {
            userCoordinates: userCoordinates,
            availableLocations: availableLocations,
            searchRadius: MAX_SEARCH_RADIUS_KM,
            timestamp: new Date().toISOString(),
            haversineFormula:
                "Enhanced implementation with bearing calculation",
        };

        console.log("Location data export:", data);
        return data;
    };

    // Performance monitoring for Haversine calculations
    window.benchmarkHaversine = function (iterations = 10000) {
        const startTime = performance.now();

        for (let i = 0; i < iterations; i++) {
            calculateHaversineDistance(
                -5.147665 + (Math.random() - 0.5) * 0.1,
                119.432732 + (Math.random() - 0.5) * 0.1,
                -5.147665 + (Math.random() - 0.5) * 0.1,
                119.432732 + (Math.random() - 0.5) * 0.1,
            );
        }

        const endTime = performance.now();
        const duration = endTime - startTime;

        console.log(
            `Haversine benchmark: ${iterations} calculations in ${duration.toFixed(2)}ms (${(duration / iterations).toFixed(4)}ms per calculation)`,
        );
        return {
            iterations: iterations,
            totalTime: duration,
            averageTime: duration / iterations,
        };
    };

    // Initialize map when page loads
    document.addEventListener("DOMContentLoaded", function () {
        initMap();
        console.log(
            "Enhanced Location Finder with Haversine Formula initialized",
        );
        console.log(
            `Configuration: Max radius ${MAX_SEARCH_RADIUS_KM}km, Max results ${MAX_RESULTS}, Min distance threshold ${MIN_DISTANCE_THRESHOLD}km`,
        );
    });

    // Debug information
    console.log("🌍 Enhanced Location Finder Features:");
    console.log("✓ Haversine formula for accurate distance calculation");
    console.log("✓ Bearing and compass direction calculation");
    console.log("✓ Multi-criteria sorting (priority, distance, name)");
    console.log("✓ Distance-based filtering and grouping");
    console.log("✓ Duplicate location detection and removal");
    console.log("✓ Performance optimized with configurable limits");
    console.log("✓ Enhanced location type detection and prioritization");
})();
