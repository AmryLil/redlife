"use strict";

import { calculateHaversineDistance } from "./haversine.js";

const MAX_SEARCH_RADIUS_KM = 50;
const MIN_DISTANCE_THRESHOLD = 0.1;
const MAX_RESULTS = 20;

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

        if (isNaN(lat) || isNaN(lon)) {
            console.warn("Invalid coordinates:", location);
            return;
        }

        const distanceData = calculateDistanceWithBearing(
            userLat,
            userLon,
            lat,
            lon,
        );

        if (distanceData.distance > maxRadius) {
            return;
        }

        const coordKey = `${lat.toFixed(4)},${lon.toFixed(4)}`;

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
        if (a.priority !== b.priority) {
            return a.priority - b.priority;
        }

        if (Math.abs(a.distance - b.distance) > 0.1) {
            return a.distance - b.distance;
        }

        return a.display_name.length - b.display_name.length;
    });
}

// Group locations by distance ranges
function groupLocationsByDistance(locations) {
    const groups = {
        very_close: [],
        close: [],
        moderate: [],
        far: [],
        very_far: [],
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

// Determine location type and icon
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

export {
    filterLocationsByDistance,
    getLocationPriority,
    sortLocationsByMultipleCriteria,
    groupLocationsByDistance,
    getLocationType,
};
