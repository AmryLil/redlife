  <x-filament::page>
      <h3 class="text-lg font-semibold mb-4">Lokasi Terdekat Berdasarkan Jarak</h3>

      <div id="controls" class="mb-4">
          <button onclick="getCurrentLocation()" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">📍 Ambil Lokasi
              Saya</button>
          <button onclick="enableManualSelection()" class="bg-green-500 text-white px-4 py-2 rounded">🖱️ Pilih Lokasi
              Manual</button>
      </div>

      <div id="map" class="w-full h-[500px] mb-4 rounded"></div>
      <ul id="lokasi-list" class="list-disc pl-5 space-y-1"></ul>

      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
      <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
      <script>
          const map = L.map("map").setView([-5.147665, 119.432732], 13);
          L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
              maxZoom: 19,
              attribution: "© OpenStreetMap",
          }).addTo(map);

          let userMarker = null;
          let manualMode = false;

          function haversine(lat1, lon1, lat2, lon2) {
              const R = 6371;
              const dLat = ((lat2 - lat1) * Math.PI) / 180;
              const dLon = ((lon2 - lon1) * Math.PI) / 180;
              const a =
                  Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos((lat1 * Math.PI) / 180) *
                  Math.cos((lat2 * Math.PI) / 180) *
                  Math.sin(dLon / 2) *
                  Math.sin(dLon / 2);
              const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
              return R * c;
          }

          function setUserLocation(lat, lon) {
              if (userMarker) {
                  map.removeLayer(userMarker);
              }

              userMarker = L.marker([lat, lon])
                  .addTo(map)
                  .bindPopup("Lokasi Anda")
                  .openPopup();

              map.setView([lat, lon], 13);

              document.getElementById("lokasi-list").innerHTML = "";

              fetch(
                      "https://nominatim.openstreetmap.org/search?q=PMI+makassar&format=json"
                  )
                  .then((res) => res.json())
                  .then((data) => {
                      const resultsWithDistance = data.map((item) => {
                          const lat2 = parseFloat(item.lat);
                          const lon2 = parseFloat(item.lon);
                          const distance = haversine(lat, lon, lat2, lon2);
                          return {
                              ...item,
                              distance
                          };
                      });

                      resultsWithDistance.sort(
                          (a, b) => a.distance - b.distance
                      );

                      resultsWithDistance.forEach((loc) => {
                          L.marker([loc.lat, loc.lon])
                              .addTo(map)
                              .bindPopup(
                                  `${loc.display_name}<br>Jarak: ${loc.distance.toFixed(2)} km`
                              );

                          const li = document.createElement("li");
                          li.textContent = `(${loc.distance.toFixed(2)} km) ${loc.display_name}`;
                          document
                              .getElementById("lokasi-list")
                              .appendChild(li);
                      });
                  });
          }

          function getCurrentLocation() {
              manualMode = false;
              navigator.geolocation.getCurrentPosition(
                  (position) => {
                      const lat = position.coords.latitude;
                      const lon = position.coords.longitude;
                      setUserLocation(lat, lon);
                  },
                  () => {
                      alert(
                          "Gagal mengambil lokasi. Pastikan izin lokasi diaktifkan."
                      );
                  }
              );
          }

          function enableManualSelection() {
              manualMode = true;
              alert("Klik di peta untuk memilih lokasi Anda.");

              map.once("click", (e) => {
                  const {
                      lat,
                      lng
                  } = e.latlng;
                  setUserLocation(lat, lng);
              });
          }

          // Auto fetch lokasi saat halaman terbuka
          getCurrentLocation();
      </script>
  </x-filament::page>
