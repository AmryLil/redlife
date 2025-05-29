(function () {
    "use strict";

    // Global variables
    let map,
        userMarker = null,
        pmiMarkers = [];
    let isMapReady = false;
    let donationLocations = []; // Array untuk menyimpan data lokasi

    // Utility functions
    function getElement(id) {
        return document.getElementById(id);
    }

    function setElementValue(id, value) {
        const el = getElement(id);
        if (el) {
            el.value = value;
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

            // Update form
            setElementValue(
                "lokasi_pengguna",
                `${lat.toFixed(6)}, ${lon.toFixed(6)}`,
            );

            // Find PMI
            findPMI(lat, lon);
        } catch (error) {
            console.error("Set location error:", error);
            showStatus("Gagal menandai lokasi", "error");
        }
    }

    // Update Livewire component with location data
    function updateLivewireComponent(userLocation, locations) {
        try {
            // Dispatch event ke Livewire component
            if (typeof Livewire !== "undefined") {
                Livewire.dispatch("lokasiDiupdate", {
                    lokasi_pengguna: userLocation,
                    locations: locations,
                });
            } else if (typeof window.livewire !== "undefined") {
                // Fallback untuk Livewire v2
                window.livewire.emit("lokasiDiupdate", {
                    lokasi_pengguna: userLocation,
                    locations: locations,
                });
            } else {
                console.warn("Livewire tidak tersedia");
            }
        } catch (error) {
            console.error("Error updating Livewire:", error);
        }
    }

    // Find PMI locations
    function findPMI(userLat, userLon) {
        showStatus("Mencari lokasi PMI...", "loading");

        // Clear old PMI markers
        pmiMarkers.forEach((marker) => {
            if (map.hasLayer(marker)) {
                map.removeLayer(marker);
            }
        });
        pmiMarkers = [];
        donationLocations = []; // Reset locations array

        // Simple search URL without problematic parameters
        const searchUrl = `https://nominatim.openstreetmap.org/search?q=PMI+Makassar&format=json&limit=10&countrycodes=id`;

        fetch(searchUrl)
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                console.log("Search results:", data);

                if (!Array.isArray(data) || data.length === 0) {
                    showStatus("Tidak ditemukan lokasi PMI", "error");
                    return;
                }

                // Process results
                const results = data
                    .filter((item) => item.lat && item.lon)
                    .map((item) => ({
                        ...item,
                        distance: calculateDistance(
                            userLat,
                            userLon,
                            parseFloat(item.lat),
                            parseFloat(item.lon),
                        ),
                    }))
                    .sort((a, b) => a.distance - b.distance);

                if (results.length === 0) {
                    showStatus("Tidak ada lokasi PMI yang valid", "error");
                    return;
                }

                // Store locations for select options
                donationLocations = results.map((location) => ({
                    place_id: location.place_id,
                    display_name: location.display_name,
                    lat: location.lat,
                    lon: location.lon,
                    distance: location.distance,
                }));

                // Add markers for all results
                results.forEach((location, index) => {
                    try {
                        const marker = L.marker([
                            parseFloat(location.lat),
                            parseFloat(location.lon),
                        ]).addTo(map);

                        const popupContent = `
                                <div style="min-width: 200px;">
                                    <strong>${index === 0 ? "🏥 Terdekat: " : "🏥 "}PMI</strong><br>
                                    <small style="display: block; margin: 5px 0;">${location.display_name}</small>
                                    <small style="color: #666;">Jarak: ${location.distance.toFixed(2)} km</small>
                                </div>
                            `;

                        marker.bindPopup(popupContent);
                        pmiMarkers.push(marker);
                    } catch (error) {
                        console.error("Marker error:", error);
                    }
                });

                // Update Livewire component with location data
                const userLocation = `${userLat.toFixed(6)}, ${userLon.toFixed(6)}`;
                updateLivewireComponent(userLocation, donationLocations);

                showStatus(
                    `Ditemukan ${results.length} lokasi PMI. Silakan pilih dari dropdown.`,
                    "success",
                );
            })
            .catch((error) => {
                console.error("Search error:", error);
                showStatus("Gagal mencari lokasi PMI", "error");
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

    // Initialize map on page load
    document.addEventListener("DOMContentLoaded", function () {
        initMap();
    });
})();
