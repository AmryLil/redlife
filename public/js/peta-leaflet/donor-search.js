"use strict";

import {
    getElement,
    setElementValue,
    showStatus,
    extractLocationName,
    populateLocationSelector,
} from "./ui-helpers.js";

import {
    filterLocationsByDistance,
    sortLocationsByMultipleCriteria,
    groupLocationsByDistance,
} from "./location-utils.js";

import { calculateHaversineDistance } from "./haversine.js";

import {
    map,
    pmiMarkers,
    selectedMarker,
    routeLayer,
    isMapReady,
    drawRoute,
} from "./map-init.js";

let availableLocations = [];
let userCoordinates = null;

// Search donor locations with multiple queries and async fetches
function findPMI(userLat, userLon) {
    showStatus(
        "Mencari lokasi donor darah dengan formula Haversine...",
        "loading",
    );

    pmiMarkers.forEach((marker) => {
        if (map.hasLayer(marker)) {
            map.removeLayer(marker);
        }
    });
    pmiMarkers.length = 0;

    if (routeLayer && map.hasLayer(routeLayer)) {
        map.removeLayer(routeLayer);
        routeLayer = null;
    }

    const selectorContainer = getElement("locationSelector");
    const selectedLocationInfo = getElement("selectedLocationInfo");
    if (selectorContainer) selectorContainer.style.display = "none";
    if (selectedLocationInfo) selectedLocationInfo.style.display = "none";

    const searchQueries = [
        "PMI+Kabupaten+Bone",
        "UTD+Kabupaten+Bone",
        "Unit+Transfusi+Darah+Kabupaten+Bone",
        "Rumah+Sakit+Kabupaten+Bone",
        "RS+Kabupaten+Bone",
        "Palang+Merah+Indonesia+Kabupaten+Bone",
    ];

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
                return [];
            });
    });

    Promise.all(searchPromises)
        .then((searchResults) => {
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

            const processedResults = filterLocationsByDistance(
                allResults,
                userLat,
                userLon,
            );

            if (processedResults.length === 0) {
                showStatus(
                    "Tidak ada lokasi donor darah dalam radius 50 km",
                    "error",
                );
                return;
            }

            const sortedResults =
                sortLocationsByMultipleCriteria(processedResults);

            const finalResults = sortedResults.slice(0, 20);

            availableLocations = finalResults;

            finalResults.forEach((location, index) => {
                try {
                    const marker = L.marker([location.lat, location.lon], {
                        icon: L.divIcon({
                            html: `<div style="background-color: ${location.color}; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 1px 3px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; font-size: 10px;">${location.icon}</div>`,
                            className: "custom-marker",
                            iconSize: [20, 20],
                            iconAnchor: [10, 10],
                        }),
                    }).addTo(map);

                    marker.bindPopup(
                        `<strong>${location.icon} ${location.type}${index === 0 ? " (Prioritas Tertinggi)" : ""}</strong><br>
                        <small>${extractLocationName(location.display_name)}</small><br>
                        <small>Jarak: ${location.distance.toFixed(2)} km</small><br>
                        <small>Arah: ${location.direction} (${location.bearing.toFixed(1)}°)</small>`,
                    );
                    pmiMarkers.push(marker);
                } catch (error) {
                    console.error("Marker error:", error);
                }
            });

            populateLocationSelector(finalResults);

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

            const locationData = {
                lokasi_pengguna: `${userLat.toFixed(6)}, ${userLon.toFixed(6)}`,
                locations: finalResults,
                search_radius: 50,
                distance_groups: distanceGroups,
                haversine_used: true,
            };

            try {
                let eventSent = false;

                if (
                    window.Livewire &&
                    typeof Livewire.dispatch === "function"
                ) {
                    Livewire.dispatch("lokasiDiupdate", locationData);
                    eventSent = true;
                }

                if (
                    !eventSent &&
                    window.livewire &&
                    typeof window.livewire.emit === "function"
                ) {
                    window.livewire.emit("lokasiDiupdate", locationData);
                    eventSent = true;
                }

                if (
                    !eventSent &&
                    window.Livewire &&
                    typeof Livewire.emit === "function"
                ) {
                    Livewire.emit("lokasiDiupdate", locationData);
                    eventSent = true;
                }

                if (!eventSent) {
                    window.dispatchEvent(
                        new CustomEvent("livewire:lokasiDiupdate", {
                            detail: locationData,
                        }),
                    );
                    eventSent = true;
                }

                if (!eventSent) {
                    console.error("❌ No Livewire dispatch method available");
                }
            } catch (error) {
                console.error(
                    "Error sending enhanced data to Livewire:",
                    error,
                );
                showStatus("Gagal mengirim data ke server", "error");
            }

            const typeCount = finalResults.reduce((acc, loc) => {
                acc[loc.type] = (acc[loc.type] || 0) + 1;
                return acc;
            }, {});

            const typeSummary = Object.entries(typeCount)
                .map(([type, count]) => `${count} ${type}`)
                .join(", ");

            showStatus(
                `Ditemukan ${finalResults.length} lokasi (${typeSummary}) dalam radius 50km. Distribusi jarak: ${groupSummary}. Diurutkan berdasarkan prioritas & jarak Haversine.`,
                "success",
            );
        })
        .catch((error) => {
            console.error("Enhanced search error:", error);
            showStatus("Gagal mencari lokasi donor darah", "error");
        });
}

// Handle location selection with routing and Livewire integration
async function handleLocationSelection(selectedIndex) {
    const selectedLocationInfo = getElement("selectedLocationInfo");
    const locationDetails = getElement("locationDetails");

    if (selectedIndex === "" || selectedIndex === null) {
        if (selectedLocationInfo) selectedLocationInfo.style.display = "none";
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
            `<strong>🎯 Lokasi Donor Terpilih</strong><br>
            <strong>${location.icon} ${location.type}</strong><br>
            <small>${extractLocationName(location.display_name)}</small><br>
            <small>Jarak: ${location.distance.toFixed(2)} km (${location.direction})</small><br>
            <small>Bearing: ${location.bearing.toFixed(1)}°</small>`,
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

    if (selectedLocationInfo && locationDetails) {
        let routeHtml = "";
        if (routeInfo) {
            routeHtml = `<div style="margin: 8px 0; padding: 8px; background-color: #f0f9ff; border-left: 4px solid #3b82f6; border-radius: 4px;">
                <strong>🛣️ Informasi Rute:</strong><br>
                <div style="margin-top: 4px;"><strong>Jarak Rute:</strong> ${routeInfo.distance} km</div>
                <div><strong>Estimasi Waktu:</strong> ${routeInfo.duration}</div>
            </div>`;
        }

        locationDetails.innerHTML = `<div style="margin-bottom: 8px;"><strong>Jenis:</strong> ${location.icon} ${location.type}</div>
            <div style="margin-bottom: 6px;"><strong>Nama:</strong> ${extractLocationName(location.display_name)}</div>
            <div style="margin-bottom: 6px;"><strong>Alamat:</strong> ${location.display_name}</div>
            <div style="margin-bottom: 6px;"><strong>Jarak Lurus:</strong> ${location.distance.toFixed(2)} km</div>
            <div style="margin-bottom: 8px;"><strong>Arah:</strong> ${location.direction} (${location.bearing.toFixed(1)}°)</div>
            ${routeHtml}`;
        selectedLocationInfo.style.display = "block";
    }

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

    try {
        let eventSent = false;

        if (window.Livewire && typeof Livewire.dispatch === "function") {
            Livewire.dispatch("lokasiDonorDipilih", locationData);
            eventSent = true;
        }

        if (
            !eventSent &&
            window.livewire &&
            typeof window.livewire.emit === "function"
        ) {
            window.livewire.emit("lokasiDonorDipilih", locationData);
            eventSent = true;
        }

        if (
            !eventSent &&
            window.Livewire &&
            typeof Livewire.emit === "function"
        ) {
            Livewire.emit("lokasiDonorDipilih", locationData);
            eventSent = true;
        }

        if (!eventSent) {
            console.warn(
                "No Livewire dispatch method available for selected location",
            );
        }
    } catch (error) {
        console.error("Error sending selected location to Livewire:", error);
    }

    console.log("Location selected:", locationData);
}

export {
    findPMI,
    handleLocationSelection,
    availableLocations,
    userCoordinates,
};
