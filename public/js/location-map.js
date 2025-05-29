// public/js/location-map.js

document.addEventListener("DOMContentLoaded", function () {
    // Get data from Laravel
    const data = window.locationMapData || {};

    // Configuration
    const CONFIG = {
        defaultCenter: [-5.147665, 119.432732], // Makassar center
        defaultZoom: 12,
        icons: {
            user: "📍",
            pmi: "🏥",
            hospital: "🏥",
            clinic: "🏥",
            puskesmas: "🏥",
            default: "📍",
        },
        colors: {
            user: "#3b82f6",
            pmi: "#dc2626",
            hospital: "#059669",
            clinic: "#7c3aed",
            puskesmas: "#ea580c",
            default: "#6b7280",
        },
    };

    // Global variables
    let map;
    let userMarker;
    let locationMarkers = [];
    let userLocation = {
        lat: null,
        lng: null,
    };
    let nearestLocations = data.nearestLocations || [];
    let pmiLocations = data.pmiLocations || [];
    let manualSelectionEnabled = false;
    let livewireComponent = data.livewireComponent;

    // Initialize map
    function initializeMap() {
        showLoading(true);

        try {
            map = L.map("donation-map").setView(
                CONFIG.defaultCenter,
                CONFIG.defaultZoom,
            );

            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution: "© OpenStreetMap contributors",
            }).addTo(map);

            // Add click handler for manual selection
            map.on("click", handleMapClick);

            showLoading(false);
            displayPMILocations();
        } catch (error) {
            console.error("Error initializing map:", error);
            showLocationStatus("Gagal memuat peta", "error");
            showLoading(false);
        }
    }

    // Show/hide loading
    function showLoading(show) {
        const loading = document.getElementById("map-loading");
        if (loading) {
            loading.classList.toggle("hidden", !show);
        }
    }

    // Show location status
    function showLocationStatus(message, type = "info") {
        const statusEl = document.getElementById("location-status");
        if (statusEl) {
            statusEl.textContent = message;
            statusEl.className = `mb-4 p-3 rounded text-sm ${
                type === "error"
                    ? "bg-red-100 text-red-700"
                    : type === "success"
                      ? "bg-green-100 text-green-700"
                      : "bg-blue-100 text-blue-700"
            }`;
            statusEl.classList.remove("hidden");

            // Auto hide after 5 seconds
            setTimeout(() => {
                statusEl.classList.add("hidden");
            }, 5000);
        }
    }

    // Create custom marker
    function createCustomMarker(lat, lng, type = "default", title = "") {
        const icon = CONFIG.icons[type] || CONFIG.icons.default;
        const color = CONFIG.colors[type] || CONFIG.colors.default;

        return L.marker([lat, lng], {
            icon: L.divIcon({
                html: `<div style="background-color: ${color}; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3); font-size: 14px;">${icon}</div>`,
                className: "custom-marker",
                iconSize: [30, 30],
                iconAnchor: [15, 15],
            }),
            title: title,
        });
    }

    // Display PMI locations on map
    function displayPMILocations() {
        // Clear existing markers
        locationMarkers.forEach((marker) => map.removeLayer(marker));
        locationMarkers = [];

        pmiLocations.forEach((location) => {
            const marker = createCustomMarker(
                location.latitude,
                location.longitude,
                location.type.toLowerCase().replace(" ", ""),
                location.name,
            );

            const popupContent = `
                <div class="p-2">
                    <h5 class="font-semibold text-blue-700">${location.name}</h5>
                    <p class="text-sm text-gray-600 mt-1">${location.address}</p>
                    <p class="text-xs text-blue-600 mt-1">${location.type}</p>
                    ${location.distance ? `<p class="text-xs text-green-600 mt-1">Jarak: ${location.distance.toFixed(1)} km</p>` : ""}
                </div>
            `;

            marker.bindPopup(popupContent);
            marker.addTo(map);
            locationMarkers.push(marker);
        });

        updateLocationsCount();
    }

    // Update user location
    function updateUserLocation(lat, lng) {
        userLocation = {
            lat,
            lng,
        };

        // Remove existing user marker
        if (userMarker) {
            map.removeLayer(userMarker);
        }

        // Add new user marker
        userMarker = createCustomMarker(lat, lng, "user", "Lokasi Anda");
        userMarker.bindPopup(
            '<div class="p-2"><h5 class="font-semibold text-blue-700">📍 Lokasi Anda</h5></div>',
        );
        userMarker.addTo(map);

        // Center map on user location
        map.setView([lat, lng], 14);

        // Calculate distances and update nearest locations
        calculateDistances();
        updateNearestLocationsList();

        // Send location to Livewire component
        if (window.livewire && livewireComponent) {
            livewireComponent.call("updateUserLocation", lat, lng);
        }
    }

    // Calculate distances to all PMI locations
    function calculateDistances() {
        if (!userLocation.lat || !userLocation.lng) return;

        pmiLocations.forEach((location) => {
            location.distance = haversineDistance(
                userLocation.lat,
                userLocation.lng,
                location.latitude,
                location.longitude,
            );
        });

        // Sort by distance
        pmiLocations.sort((a, b) => a.distance - b.distance);
        nearestLocations = pmiLocations.slice(0, 10); // Take 10 nearest
    }

    // Haversine distance formula
    function haversineDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Earth's radius in kilometers
        const dLat = deg2rad(lat2 - lat1);
        const dLon = deg2rad(lon2 - lon1);

        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(deg2rad(lat1)) *
                Math.cos(deg2rad(lat2)) *
                Math.sin(dLon / 2) *
                Math.sin(dLon / 2);

        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    function deg2rad(deg) {
        return deg * (Math.PI / 180);
    }

    // Update nearest locations list
    function updateNearestLocationsList() {
        const listEl = document.getElementById("nearest-locations-list");
        if (!listEl) return;

        if (nearestLocations.length === 0) {
            listEl.innerHTML =
                '<li class="text-gray-500 text-sm p-2 text-center">Tidak ada lokasi ditemukan</li>';
            return;
        }

        const searchTerm =
            document.getElementById("location-search")?.value.toLowerCase() ||
            "";
        const filteredLocations = nearestLocations.filter(
            (location) =>
                location.name.toLowerCase().includes(searchTerm) ||
                location.address.toLowerCase().includes(searchTerm) ||
                location.type.toLowerCase().includes(searchTerm),
        );

        listEl.innerHTML = filteredLocations
            .map(
                (location, index) => `
            <li class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors"
                onclick="focusOnLocation(${location.latitude}, ${location.longitude}, '${location.name}')">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h5 class="font-medium text-gray-800">${location.name}</h5>
                        <p class="text-sm text-gray-600 mt-1">${location.address}</p>
                        <div class="flex gap-2 mt-2">
                            <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded">${location.type}</span>
                            ${location.distance ? `<span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded">${location.distance.toFixed(1)} km</span>` : ""}
                        </div>
                    </div>
                    <span class="text-lg">${CONFIG.icons[location.type.toLowerCase().replace(" ", "")] || CONFIG.icons.default}</span>
                </div>
            </li>
        `,
            )
            .join("");
    }

    // Focus on specific location
    window.focusOnLocation = function (lat, lng, name) {
        map.setView([lat, lng], 16);

        // Find and open popup for this location
        locationMarkers.forEach((marker) => {
            if (
                Math.abs(marker.getLatLng().lat - lat) < 0.0001 &&
                Math.abs(marker.getLatLng().lng - lng) < 0.0001
            ) {
                marker.openPopup();
            }
        });
    };

    // Update locations count
    function updateLocationsCount() {
        const countEl = document.getElementById("locations-count");
        if (countEl) {
            countEl.textContent = `${nearestLocations.length} lokasi ditemukan`;
        }
    }

    // Handle map click for manual selection
    function handleMapClick(e) {
        if (!manualSelectionEnabled) return;

        const { lat, lng } = e.latlng;
        updateUserLocation(lat, lng);
        showLocationStatus("Lokasi berhasil dipilih secara manual", "success");

        // Disable manual selection after selecting
        manualSelectionEnabled = false;
        document
            .getElementById("enable-manual-selection")
            .classList.remove("bg-red-500", "hover:bg-red-600");
        document
            .getElementById("enable-manual-selection")
            .classList.add("bg-green-500", "hover:bg-green-600");
        document.getElementById("enable-manual-selection").textContent =
            "🖱️ Pilih Lokasi Manual";
    }

    // Event listeners
    document
        .getElementById("get-current-location")
        ?.addEventListener("click", function () {
            if (!navigator.geolocation) {
                showLocationStatus(
                    "Geolocation tidak didukung oleh browser ini",
                    "error",
                );
                return;
            }

            showLocationStatus("Mengambil lokasi Anda...", "info");

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    updateUserLocation(lat, lng);
                    showLocationStatus("Lokasi berhasil diambil", "success");
                },
                function (error) {
                    let message = "Gagal mengambil lokasi: ";
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            message += "Izin lokasi ditolak";
                            break;
                        case error.POSITION_UNAVAILABLE:
                            message += "Lokasi tidak tersedia";
                            break;
                        case error.TIMEOUT:
                            message += "Timeout";
                            break;
                        default:
                            message += "Error tidak dikenal";
                            break;
                    }
                    showLocationStatus(message, "error");
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 300000,
                },
            );
        });

    document
        .getElementById("enable-manual-selection")
        ?.addEventListener("click", function () {
            manualSelectionEnabled = !manualSelectionEnabled;

            if (manualSelectionEnabled) {
                this.classList.remove("bg-green-500", "hover:bg-green-600");
                this.classList.add("bg-red-500", "hover:bg-red-600");
                this.textContent = "❌ Batalkan Pilih Manual";
                showLocationStatus(
                    "Klik pada peta untuk memilih lokasi",
                    "info",
                );
            } else {
                this.classList.remove("bg-red-500", "hover:bg-red-600");
                this.classList.add("bg-green-500", "hover:bg-green-600");
                this.textContent = "🖱️ Pilih Lokasi Manual";
            }
        });

    document
        .getElementById("refresh-locations")
        ?.addEventListener("click", function () {
            if (livewireComponent) {
                showLocationStatus("Memperbarui data lokasi...", "info");
                livewireComponent.call("refreshLocations");
            }
        });

    // Search functionality
    document
        .getElementById("location-search")
        ?.addEventListener("input", function () {
            updateNearestLocationsList();
        });

    // Listen for Livewire updates
    if (livewireComponent) {
        livewireComponent.on("updateNearestLocations", function (locations) {
            nearestLocations = locations;
            pmiLocations = locations;
            displayPMILocations();
            updateNearestLocationsList();
            updateLocationsCount();
        });
    }

    // Initialize everything
    initializeMap();
});
