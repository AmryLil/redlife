<!-- File: resources/views/components/peta-leaflet.blade.php -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div wire:ignore>
    <div id="map" style="height: 400px; border: 2px solid #ddd; border-radius: 8px; margin: 10px 0;"></div>
    <button onclick="getCurrentLocation()" id="locationBtn" type="button"
        style="margin-top: 8px; padding: 8px 16px; background-color: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer;">
        <span id="btnText">Ambil Lokasi</span>
    </button>
    <div id="mapStatus"
        style="display: none; margin-top: 8px; padding: 8px; border-radius: 4px; text-align: center; font-size: 14px;">
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    (function() {
        'use strict';

        // Global variables
        let map, userMarker = null,
            pmiMarkers = [];
        let isMapReady = false;

        // Utility functions
        function getElement(id) {
            return document.getElementById(id);
        }

        function setElementValue(id, value) {
            const el = getElement(id);
            if (el) {
                el.value = value;
                // Trigger change event untuk Livewire
                el.dispatchEvent(new Event('change'));
                return true;
            }
            console.warn(`Element ${id} not found`);
            return false;
        }

        function showStatus(message, type = 'info') {
            const status = getElement('mapStatus');
            if (!status) return;

            status.textContent = message;
            status.style.display = 'block';

            // Set colors based on type
            switch (type) {
                case 'error':
                    status.style.backgroundColor = '#fee2e2';
                    status.style.color = '#991b1b';
                    break;
                case 'success':
                    status.style.backgroundColor = '#d1fae5';
                    status.style.color = '#065f46';
                    break;
                case 'loading':
                    status.style.backgroundColor = '#fef3c7';
                    status.style.color = '#92400e';
                    break;
                default:
                    status.style.backgroundColor = '#dbeafe';
                    status.style.color = '#1e40af';
            }

            // Auto hide after 5 seconds (except loading)
            if (type !== 'loading') {
                setTimeout(() => {
                    status.style.display = 'none';
                }, 5000);
            }
        }

        // Initialize map
        function initMap() {
            try {
                if (isMapReady || !getElement('map')) return;

                map = L.map('map').setView([-5.147665, 119.432732], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                isMapReady = true;
                console.log('Map initialized');
            } catch (error) {
                console.error('Map init error:', error);
                showStatus('Gagal memuat peta', 'error');
            }
        }

        // Calculate distance between two points
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Earth radius in km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        }

        // Set user location
        function setUserLocation(lat, lon) {
            if (!isMapReady) {
                console.error('Map not ready');
                return;
            }

            try {
                // Remove old marker
                if (userMarker) {
                    map.removeLayer(userMarker);
                }

                // Add new marker
                userMarker = L.marker([lat, lon]).addTo(map);
                userMarker.bindPopup('📍 Lokasi Anda').openPopup();

                // Center map
                map.setView([lat, lon], 13);

                // Update form - kirim koordinat ke input lokasi_pengguna
                setElementValue('lokasi_pengguna', `${lat.toFixed(6)}, ${lon.toFixed(6)}`);

                // Find PMI
                findPMI(lat, lon);

            } catch (error) {
                console.error('Set location error:', error);
                showStatus('Gagal menandai lokasi', 'error');
            }
        }

        // Find PMI locations
        function findPMI(userLat, userLon) {
            showStatus('Mencari lokasi PMI...', 'loading');

            // Clear old PMI markers
            pmiMarkers.forEach(marker => {
                if (map.hasLayer(marker)) {
                    map.removeLayer(marker);
                }
            });
            pmiMarkers = [];

            // Search PMI locations
            const searchUrl =
                `https://nominatim.openstreetmap.org/search?q=PMI+Bone&format=json&limit=10&countrycodes=id`;

            fetch(searchUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Search results:', data);

                    if (!Array.isArray(data) || data.length === 0) {
                        showStatus('Tidak ditemukan lokasi PMI', 'error');
                        return;
                    }

                    // Process results
                    const results = data
                        .filter(item => item.lat && item.lon)
                        .map(item => ({
                            place_id: String(item.place_id), // Ensure string
                            display_name: String(item.display_name),
                            lat: parseFloat(item.lat),
                            lon: parseFloat(item.lon),
                            distance: calculateDistance(
                                userLat, userLon,
                                parseFloat(item.lat), parseFloat(item.lon)
                            )
                        }))
                        .sort((a, b) => a.distance - b.distance);

                    if (results.length === 0) {
                        showStatus('Tidak ada lokasi PMI yang valid', 'error');
                        return;
                    }

                    // Add markers for results
                    results.forEach((location, index) => {
                        try {
                            const marker = L.marker([location.lat, location.lon]).addTo(map);
                            marker.bindPopup(`
                                <strong>${index === 0 ? '🏥 Terdekat: ' : '🏥 '}PMI</strong><br>
                                <small>${location.display_name}</small><br>
                                <small>Jarak: ${location.distance.toFixed(2)} km</small>
                            `);
                            pmiMarkers.push(marker);
                        } catch (error) {
                            console.error('Marker error:', error);
                        }
                    });

                    // PENTING: Kirim data lokasi ke Livewire component
                    const locationData = {
                        lokasi_pengguna: `${userLat.toFixed(6)}, ${userLon.toFixed(6)}`,
                        locations: results
                    };

                    console.log('Sending location data to Livewire:', locationData);

                    // Dispatch event ke Livewire dengan penanganan yang lebih robust
                    try {
                        // Coba berbagai cara dispatch Livewire
                        let eventSent = false;

                        // Method 1: Livewire v3 style
                        if (window.Livewire && typeof Livewire.dispatch === 'function') {
                            Livewire.dispatch('lokasiDiupdate', locationData);
                            console.log('✓ Data sent via Livewire.dispatch (v3)');
                            eventSent = true;
                        }

                        // Method 2: Livewire v2 style
                        if (!eventSent && window.livewire && typeof window.livewire.emit === 'function') {
                            window.livewire.emit('lokasiDiupdate', locationData);
                            console.log('✓ Data sent via livewire.emit (v2)');
                            eventSent = true;
                        }

                        // Method 3: Fallback Livewire v2/v3
                        if (!eventSent && window.Livewire && typeof Livewire.emit === 'function') {
                            Livewire.emit('lokasiDiupdate', locationData);
                            console.log('✓ Data sent via Livewire.emit (fallback)');
                            eventSent = true;
                        }

                        // Method 4: Global event dispatch
                        if (!eventSent) {
                            // Dispatch custom event as fallback
                            window.dispatchEvent(new CustomEvent('livewire:lokasiDiupdate', {
                                detail: locationData
                            }));
                            console.log('✓ Data sent via custom event (fallback)');
                            eventSent = true;
                        }

                        if (!eventSent) {
                            console.error('❌ No Livewire dispatch method available');
                            console.log('Available methods:', {
                                'window.Livewire': !!window.Livewire,
                                'Livewire.dispatch': !!(window.Livewire && window.Livewire.dispatch),
                                'Livewire.emit': !!(window.Livewire && window.Livewire.emit),
                                'window.livewire': !!window.livewire,
                                'livewire.emit': !!(window.livewire && window.livewire.emit)
                            });
                        }

                    } catch (error) {
                        console.error('Error sending data to Livewire:', error);
                        showStatus('Gagal mengirim data ke server', 'error');
                    }

                    showStatus(
                        `Ditemukan ${results.length} lokasi. Terdekat: ${results[0].distance.toFixed(2)} km`,
                        'success'
                    );

                })
                .catch(error => {
                    console.error('Search error:', error);
                    showStatus('Gagal mencari lokasi PMI', 'error');
                });
        }

        // Get current location
        window.getCurrentLocation = function() {
            const btn = getElement('locationBtn');
            const btnText = getElement('btnText');

            if (!navigator.geolocation) {
                showStatus('Browser tidak mendukung geolokasi', 'error');
                return;
            }

            if (!isMapReady) {
                initMap();
                setTimeout(() => getCurrentLocation(), 1000);
                return;
            }

            // Update button
            if (btn) btn.disabled = true;
            if (btnText) btnText.textContent = 'Mengambil...';
            showStatus('Mengambil lokasi...', 'loading');

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    setUserLocation(position.coords.latitude, position.coords.longitude);

                    // Reset button
                    if (btn) btn.disabled = false;
                    if (btnText) btnText.textContent = 'Ambil Lokasi';
                },
                (error) => {
                    console.error('Geolocation error:', error);

                    let message = 'Gagal mengambil lokasi. ';
                    switch (error.code) {
                        case 1:
                            message += 'Akses ditolak.';
                            break;
                        case 2:
                            message += 'Posisi tidak tersedia.';
                            break;
                        case 3:
                            message += 'Timeout.';
                            break;
                        default:
                            message += 'Error tidak dikenal.';
                    }

                    showStatus(message, 'error');

                    // Reset button
                    if (btn) btn.disabled = false;
                    if (btnText) btnText.textContent = 'Ambil Lokasi';
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 300000
                }
            );
        };


        // Listen for custom events as fallback
        window.addEventListener('livewire:lokasiDiupdate', function(event) {
            console.log('Custom event received:', event.detail);
        });

    })();
</script>
