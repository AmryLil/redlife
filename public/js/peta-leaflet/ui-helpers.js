"use strict";

// Get element by ID
function getElement(id) {
    return document.getElementById(id);
}

// Set element value and dispatch change event
function setElementValue(id, value) {
    const el = getElement(id);
    if (el) {
        el.value = value;
        el.dispatchEvent(new Event("change"));
        return true;
    }
    console.warn(`Element ${id} not found`);
    return false;
}

// Show status message with type-based styling
function showStatus(message, type = "info") {
    const status = getElement("mapStatus");
    if (!status) return;

    status.textContent = message;
    status.style.display = "block";

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

    if (type !== "loading") {
        setTimeout(() => {
            status.style.display = "none";
        }, 5000);
    }
}

// Extract location name from display_name
function extractLocationName(displayName) {
    const parts = displayName.split(",");
    if (parts.length >= 2) {
        return parts.slice(0, 2).join(", ").trim();
    }
    return displayName.length > 50
        ? displayName.substring(0, 50) + "..."
        : displayName;
}

// Populate location selector with grouped options
function populateLocationSelector(locations) {
    const selector = getElement("selectedLocation");
    const selectorContainer = getElement("locationSelector");

    if (!selector || !selectorContainer) {
        console.error("Location selector elements not found");
        return;
    }

    selector.innerHTML = '<option value="">-- Pilih Lokasi Donor --</option>';

    const groups = groupLocationsByDistance(locations);

    const addGroupToSelector = (groupLocations, groupLabel) => {
        if (groupLocations.length > 0) {
            const groupHeader = document.createElement("optgroup");
            groupHeader.label = groupLabel;

            groupLocations.forEach((location) => {
                const globalIndex = locations.indexOf(location);
                const option = document.createElement("option");
                option.value = globalIndex;
                option.textContent = `${location.icon} ${location.type} - ${extractLocationName(location.display_name)} (${location.distance.toFixed(2)} km ${location.direction})`;
                groupHeader.appendChild(option);
            });

            selector.appendChild(groupHeader);
        }
    };

    addGroupToSelector(groups.very_close, "🟢 Sangat Dekat (0-2 km)");
    addGroupToSelector(groups.close, "🔵 Dekat (2-5 km)");
    addGroupToSelector(groups.moderate, "🟡 Sedang (5-10 km)");
    addGroupToSelector(groups.far, "🟠 Jauh (10-25 km)");
    addGroupToSelector(groups.very_far, "🔴 Sangat Jauh (25+ km)");

    selectorContainer.style.display = "block";

    selector.onchange = function () {
        if (typeof handleLocationSelection === "function") {
            handleLocationSelection(this.value);
        }
    };
}

export {
    getElement,
    setElementValue,
    showStatus,
    extractLocationName,
    populateLocationSelector,
};
