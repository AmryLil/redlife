{{-- resources/views/filament/components/location-map.blade.php --}}
<div wire:ignore>
    <div class="mb-4">
        <div class="flex gap-2 mb-4">
            <button type="button" id="get-current-location"
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                📍 Ambil Lokasi Saya
            </button>
            <button type="button" id="enable-manual-selection"
                class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors">
                🖱️ Pilih Lokasi Manual
            </button>
            <button type="button" id="refresh-locations"
                class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition-colors">
                🔄 Perbarui Lokasi
            </button>
        </div>

        <!-- Loading indicator -->
        <div id="map-loading" class="hidden flex items-center justify-center p-4 bg-gray-100 rounded mb-4">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
            <span class="ml-2 text-gray-600">Memuat peta...</span>
        </div>

        <!-- Location status -->
        <div id="location-status" class="hidden mb-4 p-3 rounded text-sm"></div>

        <div id="donation-map" style="height: 400px; width: 100%; border-radius: 8px;"></div>

        <div class="mt-4">
            <div class="flex justify-between items-center mb-2">
                <h4 class="font-semibold text-gray-700">Lokasi Donor Terdekat:</h4>
                <span id="locations-count" class="text-sm text-gray-500"></span>
            </div>

            <!-- Search box for locations -->
            <div class="mb-3">
                <input type="text" id="location-search" placeholder="Cari lokasi donor darah..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <ul id="nearest-locations-list" class="space-y-2 max-h-64 overflow-y-auto">
                <li class="text-gray-500 text-sm p-2 text-center">
                    Aktifkan lokasi Anda untuk melihat tempat donor terdekat
                </li>
            </ul>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>


<style>
    .leaflet-popup-content-wrapper {
        border-radius: 8px;
    }

    .leaflet-popup-content {
        margin: 8px 12px;
        line-height: 1.4;
    }

    #nearest-locations-list::-webkit-scrollbar {
        width: 4px;
    }

    #nearest-locations-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 2px;
    }

    #nearest-locations-list::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 2px;
    }

    #nearest-locations-list::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }

    .custom-marker {
        background: transparent !important;
        border: none !important;
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }
    }
