"use strict";

let map,
    userMarker = null,
    pmiMarkers = [],
    selectedMarker = null,
    routeLayer = null;
let isMapReady = false;

function initMap() {
    try {
        if (isMapReady || !document.getElementById("map")) return;

        map = L.map("map").setView([-5.147665, 119.432732], 13);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution: "© OpenStreetMap",
        }).addTo(map);

        isMapReady = true;
        console.log("Map initialized");
    } catch (error) {
        console.error("Map init error:", error);
        // Assuming showStatus is imported from ui-helpers.js
        if (typeof showStatus === "function") {
            showStatus("Gagal memuat peta", "error");
        }
    }
}

function drawRoute(routeData) {
    if (routeLayer && map.hasLayer(routeLayer)) {
        map.removeLayer(routeLayer);
    }

    if (!routeData || !routeData.geometry) {
        return;
    }

    routeLayer = L.geoJSON(routeData.geometry, {
        style: {
            color: "#3b82f6",
            weight: 5,
            opacity: 0.8,
            dashArray: "10, 5",
        },
    }).addTo(map);

    const group = new L.featureGroup([routeLayer, userMarker, selectedMarker]);
    map.fitBounds(group.getBounds(), { padding: [20, 20] });

    const distanceKm = (routeData.distance / 1000).toFixed(2);
    const duration = formatDuration(routeData.duration);

    if (typeof showStatus === "function") {
        showStatus(
            `Rute ditemukan: ${distanceKm} km, estimasi ${duration}`,
            "success",
        );
    }

    return {
        distance: distanceKm,
        duration: duration,
        durationSeconds: routeData.duration,
    };
}

function formatDuration(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);

    if (hours > 0) {
        return `${hours} jam ${minutes} menit`;
    }
    return `${minutes} menit`;
}

export {
    map,
    userMarker,
    pmiMarkers,
    selectedMarker,
    routeLayer,
    isMapReady,
    initMap,
    drawRoute,
    formatDuration,
};
