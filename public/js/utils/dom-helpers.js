function getElement(id) {
    return document.getElementById(id);
}

function setElementValue(id, value) {
    const el = getElement(id);
    if (el) {
        el.value = value;
        // Trigger change event untuk Livewire
        el.dispatchEvent(new Event("change"));
        return true;
    }
    console.warn(`Element ${id} not found`);
    return false;
}

function extractLocationName(displayName) {
    // Try to extract meaningful location name
    const parts = displayName.split(",");
    if (parts.length >= 2) {
        // Return first two parts for more context
        return parts.slice(0, 2).join(", ").trim();
    }
    return displayName.length > 50
        ? displayName.substring(0, 50) + "..."
        : displayName;
}

function formatDuration(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);

    if (hours > 0) {
        return `${hours} jam ${minutes} menit`;
    }
    return `${minutes} menit`;
}
