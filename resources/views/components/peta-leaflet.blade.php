<!-- File: resources/views/components/peta-leaflet.blade.php -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div wire:ignore>
    <div id="map" style="height: 400px; border: 2px solid #ddd; border-radius: 8px; margin: 10px 0;"></div>

    <div id="locationSelector" style="display: none; flex: 1;">
        <label for="selectedLocation" style="display: block; font-weight: 500; margin-bottom: 4px; font-size: 14px;">
            Pilih Lokasi Donor:
        </label>
        <select id="selectedLocation" name="lokasi_donor"
            style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; background-color: white; font-size: 14px;">
            <option value="">-- Pilih Lokasi Donor --</option>
        </select>
    </div>

    <div style="display: flex; gap: 10px; align-items: center; margin: 10px 0;">
        <button onclick="getCurrentLocation()" id="locationBtn" type="button"
            style="padding: 8px 16px; background-color: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer; white-space: nowrap;">
            <span id="btnText">Ambil Lokasi</span>
        </button>

        <!-- Form untuk memilih lokasi donor -->

    </div>

    <div id="mapStatus"
        style="display: none; margin-top: 8px; padding: 8px; border-radius: 4px; text-align: center; font-size: 14px;">
    </div>

    <!-- Info lokasi terpilih -->
    <div id="selectedLocationInfo"
        style="display: none; margin-top: 10px; padding: 12px; background-color: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 6px;">
        <h4 style="margin: 0 0 8px 0; color: #0c4a6e; font-size: 16px;">📍 Lokasi Donor Terpilih:</h4>
        <div id="locationDetails" style="font-size: 14px; color: #374151;"></div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/peta-leaflet.js') }}"></script>
