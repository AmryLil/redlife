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

function populateLocationSelector(locations) {
    const selector = getElement("selectedLocation");
    const selectorContainer = getElement("locationSelector");

    if (!selector || !selectorContainer) {
        console.error("Location selector elements not found");
        return;
    }

    // Clear existing options
    selector.innerHTML = '<option value="">-- Pilih Lokasi Donor --</option>';

    // Group locations by distance
    const groups = groupLocationsByDistance(locations);

    // Add locations to dropdown with distance group headers
    const addGroupToSelector = (groupLocations, groupLabel) => {
        if (groupLocations.length > 0) {
            // Add group header
            const groupHeader = document.createElement("optgroup");
            groupHeader.label = groupLabel;

            groupLocations.forEach((location, localIndex) => {
                const globalIndex = locations.indexOf(location);
                const option = document.createElement("option");
                option.value = globalIndex;
                option.textContent = `${location.icon} ${location.type} - ${extractLocationName(location.display_name)} (${location.distance.toFixed(2)} km ${location.direction})`;
                groupHeader.appendChild(option);
            });

            selector.appendChild(groupHeader);
        }
    };

    // Add groups in order of distance
    addGroupToSelector(groups.very_close, "🟢 Sangat Dekat (0-2 km)");
    addGroupToSelector(groups.close, "🔵 Dekat (2-5 km)");
    addGroupToSelector(groups.moderate, "🟡 Sedang (5-10 km)");
    addGroupToSelector(groups.far, "🟠 Jauh (10-25 km)");
    addGroupToSelector(groups.very_far, "🔴 Sangat Jauh (25+ km)");

    // Show selector
    selectorContainer.style.display = "block";

    // Add change event listener
    selector.onchange = function () {
        handleLocationSelection(this.value);
    };
}
