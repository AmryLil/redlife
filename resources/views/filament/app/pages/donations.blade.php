<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencari Lokasi Donor Darah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .map-container {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .glass-effect {
            backdrop-filter: blur(16px);
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .floating-card {
            transform: translateY(0);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .floating-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        .gradient-red {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 50%, #f87171 100%);
        }

        .gradient-blue {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 50%, #60a5fa 100%);
        }

        .pulse-dot {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .5;
            }
        }

        .ripple-effect {
            position: relative;
            overflow: hidden;
        }

        .ripple-effect::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .ripple-effect:active::before {
            width: 300px;
            height: 300px;
        }

        .status-bar {
            transition: all 0.3s ease;
            transform: translateY(-100%);
        }

        .status-bar.show {
            transform: translateY(0);
        }

        .leaflet-popup-content-wrapper {
            border-radius: 12px !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .custom-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-red-50 min-h-screen">
    <!-- Status Bar -->
    <div id="mapStatus"
        class="status-bar fixed top-0 left-0 right-0 z-50 px-4 py-3 text-center text-white font-medium shadow-lg">
        <div class="flex items-center justify-center space-x-2">
            <i class="fas fa-info-circle"></i>
            <span>Status akan muncul di sini</span>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <div
                class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl mb-4 shadow-xl">
                <i class="fas fa-heart text-white text-2xl"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-2">
                Pencari Lokasi
                <span class="bg-gradient-to-r from-red-600 to-red-500 bg-clip-text text-transparent">
                    Donor Darah
                </span>
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Temukan lokasi donor darah terdekat dari posisi Anda dengan mudah dan cepat
            </p>
        </div>

        <!-- Control Panel -->
        <div class="glass-effect rounded-2xl p-6 mb-8 floating-card">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-center">
                <!-- Location Button -->
                <button id="locationBtn" onclick="getCurrentLocation()"
                    class="group relative bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-xl ripple-effect min-w-[200px]">
                    <div class="flex items-center justify-center space-x-3">
                        <i
                            class="fas fa-location-arrow text-xl group-hover:rotate-12 transition-transform duration-300"></i>
                        <span id="btnText">Ambil Lokasi</span>
                    </div>
                    <div
                        class="absolute inset-0 rounded-xl bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300">
                    </div>
                </button>

                <!-- Hidden Inputs -->
                <input type="hidden" id="lokasi_pengguna" />
                <input type="hidden" id="lokasi_terpilih" />
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Map Section -->
            <div class="xl:col-span-2">
                <div class="glass-effect rounded-2xl p-6 floating-card">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-map-marked-alt text-blue-600 mr-3"></i>
                            Peta Lokasi
                        </h2>
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <div class="w-3 h-3 bg-green-500 rounded-full pulse-dot"></div>
                            <span>Live</span>
                        </div>
                    </div>

                    <div class="map-container">
                        <div id="map" class="w-full h-96 md:h-[500px] lg:h-[600px] bg-gray-100"></div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Location Selector -->
                <div id="locationSelector" class="glass-effect rounded-2xl p-6 floating-card" style="display: none;">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-list-ul text-red-600 mr-3"></i>
                        Pilih Lokasi Donor
                    </h3>

                    <div class="relative">
                        <select id="selectedLocation"
                            class="custom-select w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 appearance-none text-gray-700 font-medium">
                            <option value="">-- Pilih Lokasi Donor --</option>
                        </select>
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>

                    <div class="mt-4 text-sm text-gray-500 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Lokasi diurutkan berdasarkan jarak terdekat
                    </div>
                </div>

                <!-- Selected Location Info -->
                <div id="selectedLocationInfo" class="glass-effect rounded-2xl overflow-hidden floating-card"
                    style="display: none;">
                    <div class="gradient-red p-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-map-pin mr-3"></i>
                            Detail Lokasi Terpilih
                        </h3>
                    </div>

                    <div id="locationDetails" class="p-0">
                        <!-- Content will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Info Cards -->
                <div class="space-y-4">
                    <!-- Tips Card -->
                    <div class="glass-effect rounded-xl p-4 floating-card">
                        <div class="flex items-start space-x-3">
                            <div
                                class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-lightbulb text-yellow-600 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 text-sm">Tips</h4>
                                <p class="text-xs text-gray-600 mt-1">Aktifkan GPS untuk hasil yang lebih akurat</p>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Card -->
                    <div class="glass-effect rounded-xl p-4 floating-card border-l-4 border-red-500">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone text-red-600 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 text-sm">Darurat</h4>
                                <p class="text-xs text-gray-600 mt-1">PMI Makassar: (0411) 123456</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="glass-effect rounded-xl p-6 text-center floating-card">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-map-marker-alt text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">50km</h3>
                <p class="text-gray-600 text-sm">Radius Pencarian</p>
            </div>

            <div class="glass-effect rounded-xl p-6 text-center floating-card">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-hospital text-green-600 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">20+</h3>
                <p class="text-gray-600 text-sm">Lokasi Tersedia</p>
            </div>

            <div class="glass-effect rounded-xl p-6 text-center floating-card">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-route text-red-600 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">GPS</h3>
                <p class="text-gray-600 text-sm">Navigasi Real-time</p>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay"
        class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center"
        style="display: none;">
        <div class="glass-effect rounded-2xl p-8 text-center">
            <div
                class="w-16 h-16 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto mb-4">
            </div>
            <p class="text-gray-700 font-medium">Mencari lokasi donor darah...</p>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="paste.txt"></script>

    <script>
        // Additional UI enhancements
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth scrolling
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });

            // Add intersection observer for animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe all floating cards
            document.querySelectorAll('.floating-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });

            // Add ripple effect to buttons
            document.querySelectorAll('.ripple-effect').forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');

                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });

        // Enhanced status display
        function showStatus(message, type = 'info') {
            const status = document.getElementById('mapStatus');
            if (!status) return;

            const icons = {
                error: 'fas fa-exclamation-triangle',
                success: 'fas fa-check-circle',
                loading: 'fas fa-spinner fa-spin',
                info: 'fas fa-info-circle'
            };

            const colors = {
                error: 'bg-red-500',
                success: 'bg-green-500',
                loading: 'bg-yellow-500',
                info: 'bg-blue-500'
            };

            status.innerHTML = `
                <div class="flex items-center justify-center space-x-2">
                    <i class="${icons[type] || icons.info}"></i>
                    <span>${message}</span>
                </div>
            `;

            status.className =
                `status-bar show ${colors[type] || colors.info} text-white px-4 py-3 text-center font-medium shadow-lg fixed top-0 left-0 right-0 z-50`;

            if (type !== 'loading') {
                setTimeout(() => {
                    status.classList.remove('show');
                }, 5000);
            }
        }
    </script>
</body>

</html>
