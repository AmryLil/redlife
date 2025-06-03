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
        "PMI+Kabupaten+Bone",
        "UTD+Kabupaten+Bone",
        "Unit+Transfusi+Darah+Kabupaten+Bone",
        "Rumah+Sakit+Kabupaten+Bone",
        "RS+Kabupaten+Bone",
        "Palang+Merah+Indonesia+Kabupaten+Bone",
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
                    console.log("✓ Enhanced data sent via livewire.emit (v2)");
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
                    console.error("❌ No Livewire dispatch method available");
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
