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
