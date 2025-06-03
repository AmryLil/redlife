"use strict";

// Convert degrees to radians
function toRadians(degrees) {
    return degrees * (Math.PI / 180);
}

// Convert radians to degrees
function toDegrees(radians) {
    return radians * (180 / Math.PI);
}

// Calculate Haversine distance between two points in km
function calculateHaversineDistance(lat1, lon1, lat2, lon2) {
    const lat1Rad = toRadians(lat1);
    const lat2Rad = toRadians(lat2);
    const deltaLatRad = toRadians(lat2 - lat1);
    const deltaLonRad = toRadians(lon2 - lon1);

    const a =
        Math.sin(deltaLatRad / 2) * Math.sin(deltaLatRad / 2) +
        Math.cos(lat1Rad) *
            Math.cos(lat2Rad) *
            Math.sin(deltaLonRad / 2) *
            Math.sin(deltaLonRad / 2);

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    const EARTH_RADIUS_KM = 6371;
    return EARTH_RADIUS_KM * c;
}

// Calculate distance with bearing and compass direction
function calculateDistanceWithBearing(lat1, lon1, lat2, lon2) {
    const distance = calculateHaversineDistance(lat1, lon1, lat2, lon2);

    const lat1Rad = toRadians(lat1);
    const lat2Rad = toRadians(lat2);
    const deltaLonRad = toRadians(lon2 - lon1);

    const y = Math.sin(deltaLonRad) * Math.cos(lat2Rad);
    const x =
        Math.cos(lat1Rad) * Math.sin(lat2Rad) -
        Math.sin(lat1Rad) * Math.cos(lat2Rad) * Math.cos(deltaLonRad);

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

export {
    calculateHaversineDistance,
    calculateDistanceWithBearing,
    getCompassDirection,
};
