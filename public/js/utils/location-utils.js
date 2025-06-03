function getLocationType(displayName) {
    const name = displayName.toLowerCase();
    if (name.includes("pmi") || name.includes("palang merah")) {
        return { type: "PMI", icon: "🏥", color: "#dc2626" };
    } else if (name.includes("utd") || name.includes("unit transfusi darah")) {
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
