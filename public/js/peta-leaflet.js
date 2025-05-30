(function () {
    "use strict";

    // Global variables
    let map,
        userMarker = null,
        pmiMarkers = [],
        selectedMarker = null;
    let isMapReady = false;
    let availableLocations = []; // Store available PMI locations

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

    // Calculate distance between two points
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Earth radius in km
        const dLat = ((lat2 - lat1) * Math.PI) / 180;
        const dLon = ((lon2 - lon1) * Math.PI) / 180;
        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos((lat1 * Math.PI) / 180) *
                Math.cos((lat2 * Math.PI) / 180) *
                Math.sin(dLon / 2) *
                Math.sin(dLon / 2);
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
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

    // Populate location selector dropdown
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

        // Add locations to dropdown with type icons
        locations.forEach((location, index) => {
            const option = document.createElement("option");
            option.value = index;
            option.textContent = `${location.icon} ${location.type} - ${extractLocationName(location.display_name)} (${location.distance.toFixed(2)} km)`;
            selector.appendChild(option);
        });

        // Show selector
        selectorContainer.style.display = "block";

        // Add change event listener
        selector.onchange = function () {
            handleLocationSelection(this.value);
        };
    }

    // Handle location selection
    function handleLocationSelection(selectedIndex) {
        const selectedLocationInfo = getElement("selectedLocationInfo");
        const locationDetails = getElement("locationDetails");

        if (selectedIndex === "" || selectedIndex === null) {
            // Hide info and remove selected marker
            if (selectedLocationInfo)
                selectedLocationInfo.style.display = "none";
            if (selectedMarker && map.hasLayer(selectedMarker)) {
                map.removeLayer(selectedMarker);
                selectedMarker = null;
            }
            return;
        }

        const location = availableLocations[parseInt(selectedIndex)];
        if (!location) {
            console.error("Selected location not found");
            return;
        }

        // Remove previous selected marker
        if (selectedMarker && map.hasLayer(selectedMarker)) {
            map.removeLayer(selectedMarker);
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

        selectedMarker
            .bindPopup(
                `
                <strong>🎯 Lokasi Donor Terpilih</strong><br>
                <strong>${location.icon} ${location.type}</strong><br>
                <small>${extractLocationName(location.display_name)}</small><br>
                <small>Jarak: ${location.distance.toFixed(2)} km</small>
            `,
            )
            .openPopup();

        // Center map on selected location
        map.setView([location.lat, location.lon], 15);

        // Show location info
        if (selectedLocationInfo && locationDetails) {
            locationDetails.innerHTML = `
                    <div style="margin-bottom: 8px;"><strong>Jenis:</strong> ${location.icon} ${location.type}</div>
                    <div style="margin-bottom: 6px;"><strong>Nama:</strong> ${extractLocationName(location.display_name)}</div>
                    <div style="margin-bottom: 6px;"><strong>Alamat:</strong> ${location.display_name}</div>
                    <div><strong>Jarak:</strong> ${location.distance.toFixed(2)} km dari lokasi Anda</div>
                `;
            selectedLocationInfo.style.display = "block";
        }

        // Send selected location data to Livewire
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
                place_id: location.place_id,
                icon: location.icon,
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

    // Find donor locations (PMI, UTD, RS)
    function findPMI(userLat, userLon) {
        showStatus("Mencari lokasi donor darah...", "loading");

        // Clear old markers
        pmiMarkers.forEach((marker) => {
            if (map.hasLayer(marker)) {
                map.removeLayer(marker);
            }
        });
        pmiMarkers = [];

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
            const searchUrl = `https://nominatim.openstreetmap.org/search?q=${query}&format=json&limit=5&countrycodes=id`;
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

                // Combine and deduplicate results
                const allResults = [];
                const seenPlaceIds = new Set();

                searchResults.forEach((results) => {
                    if (Array.isArray(results)) {
                        results.forEach((item) => {
                            // Skip if no coordinates or already seen
                            if (
                                !item.lat ||
                                !item.lon ||
                                seenPlaceIds.has(item.place_id)
                            ) {
                                return;
                            }

                            seenPlaceIds.add(item.place_id);
                            allResults.push(item);
                        });
                    }
                });

                if (allResults.length === 0) {
                    showStatus("Tidak ditemukan lokasi donor darah", "error");
                    return;
                }

                // Process and enhance results
                const processedResults = allResults
                    .map((item) => {
                        const locationType = getLocationType(item.display_name);
                        return {
                            place_id: String(item.place_id),
                            display_name: String(item.display_name),
                            lat: parseFloat(item.lat),
                            lon: parseFloat(item.lon),
                            distance: calculateDistance(
                                userLat,
                                userLon,
                                parseFloat(item.lat),
                                parseFloat(item.lon),
                            ),
                            type: locationType.type,
                            icon: locationType.icon,
                            color: locationType.color,
                        };
                    })
                    .sort((a, b) => a.distance - b.distance) // Sort by distance
                    .slice(0, 15); // Limit to 15 closest results

                if (processedResults.length === 0) {
                    showStatus(
                        "Tidak ada lokasi donor darah yang valid",
                        "error",
                    );
                    return;
                }

                // Store available locations
                availableLocations = processedResults;

                // Add markers for results with different colors
                processedResults.forEach((location, index) => {
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
                                <strong>${location.icon} ${location.type}${index === 0 ? " (Terdekat)" : ""}</strong><br>
                                <small>${extractLocationName(location.display_name)}</small><br>
                                <small>Jarak: ${location.distance.toFixed(2)} km</small>
                            `);
                        pmiMarkers.push(marker);
                    } catch (error) {
                        console.error("Marker error:", error);
                    }
                });

                // Populate location selector
                populateLocationSelector(processedResults);

                // Send data to Livewire
                const locationData = {
                    lokasi_pengguna: `${userLat.toFixed(6)}, ${userLon.toFixed(6)}`,
                    locations: processedResults,
                };

                console.log("Sending location data to Livewire:", locationData);

                // Dispatch event ke Livewire
                try {
                    let eventSent = false;

                    // Method 1: Livewire v3 style
                    if (
                        window.Livewire &&
                        typeof Livewire.dispatch === "function"
                    ) {
                        Livewire.dispatch("lokasiDiupdate", locationData);
                        console.log("✓ Data sent via Livewire.dispatch (v3)");
                        eventSent = true;
                    }

                    // Method 2: Livewire v2 style
                    if (
                        !eventSent &&
                        window.livewire &&
                        typeof window.livewire.emit === "function"
                    ) {
                        window.livewire.emit("lokasiDiupdate", locationData);
                        console.log("✓ Data sent via livewire.emit (v2)");
                        eventSent = true;
                    }

                    // Method 3: Fallback Livewire v2/v3
                    if (
                        !eventSent &&
                        window.Livewire &&
                        typeof Livewire.emit === "function"
                    ) {
                        Livewire.emit("lokasiDiupdate", locationData);
                        console.log("✓ Data sent via Livewire.emit (fallback)");
                        eventSent = true;
                    }

                    // Method 4: Global event dispatch
                    if (!eventSent) {
                        window.dispatchEvent(
                            new CustomEvent("livewire:lokasiDiupdate", {
                                detail: locationData,
                            }),
                        );
                        console.log("✓ Data sent via custom event (fallback)");
                        eventSent = true;
                    }

                    if (!eventSent) {
                        console.error(
                            "❌ No Livewire dispatch method available",
                        );
                    }
                } catch (error) {
                    console.error("Error sending data to Livewire:", error);
                    showStatus("Gagal mengirim data ke server", "error");
                }

                // Group results by type for status message
                const typeCount = processedResults.reduce((acc, loc) => {
                    acc[loc.type] = (acc[loc.type] || 0) + 1;
                    return acc;
                }, {});

                const typeSummary = Object.entries(typeCount)
                    .map(([type, count]) => `${count} ${type}`)
                    .join(", ");

                showStatus(
                    `Ditemukan ${processedResults.length} lokasi: ${typeSummary}. Pilih dari dropdown.`,
                    "success",
                );
            })
            .catch((error) => {
                console.error("Search error:", error);
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
        showStatus("Mengambil lokasi...", "loading");

        navigator.geolocation.getCurrentPosition(
            (position) => {
                setUserLocation(
                    position.coords.latitude,
                    position.coords.longitude,
                );

                // Reset button
                if (btn) btn.disabled = false;
                if (btnText) btnText.textContent = "Ambil Lokasi";
            },
            (error) => {
                console.error("Geolocation error:", error);

                let message = "Gagal mengambil lokasi. ";
                switch (error.code) {
                    case 1:
                        message += "Akses ditolak.";
                        break;
                    case 2:
                        message += "Posisi tidak tersedia.";
                        break;
                    case 3:
                        message += "Timeout.";
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
                timeout: 10000,
                maximumAge: 300000,
            },
        );
    };

    // Initialize map when page loads
    document.addEventListener("DOMContentLoaded", function () {
        initMap();
    });
})();
