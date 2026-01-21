<!doctype html>
<html lang="id" class="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Map – Wiroditan</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <style>
    body { font-family: 'Inter', sans-serif; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    
    /* Popup Custom Dark Mode */
    .leaflet-popup-content-wrapper, .leaflet-popup-tip {
      background: rgba(15, 23, 42, 0.95);
      color: #fff;
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,0.1);
    }
    .leaflet-popup-content { margin: 12px; font-size: 13px; line-height: 1.5; }
    .leaflet-container a.leaflet-popup-close-button { color: #fff; }
  </style>
</head>

<body class="h-screen w-screen flex flex-col bg-slate-950 text-white overflow-hidden">

  <div class="flex-none z-[1000] bg-slate-900/80 backdrop-blur-md border-b border-white/10 p-3 flex flex-wrap items-center gap-3 shadow-lg">
    <div class="font-bold text-emerald-400 text-sm tracking-wide uppercase mr-2 flex items-center gap-2">
      <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
      Admin Map
    </div>

    <a href="/" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-medium rounded-lg border border-white/5 transition-colors">
      &larr; Publik
    </a>

    <button onclick="cariLokasiSaya()" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold rounded-lg shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
      </svg>
      Cari Saya
    </button>

    <button onclick="keWiroditan()" class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-white text-xs font-medium rounded-lg border border-white/10 transition-colors">
      Ke Desa
    </button>
    <button onclick="refreshData()" class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-white text-xs font-medium rounded-lg border border-white/10 transition-colors">
      🔄 Refresh
    </button>

    <div class="flex-1"></div>

    <button id="btnAddNew" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-lg shadow-lg shadow-emerald-500/20 transition-all border border-emerald-500/50">
      + Tambah Titik
    </button>

    <form method="POST" action="/admin/logout">
      @csrf
      <button type="submit" class="ml-2 px-3 py-1.5 bg-red-500/10 hover:bg-red-600 text-red-400 hover:text-white text-xs font-medium rounded-lg transition-colors border border-red-500/20">
        Logout
      </button>
    </form>
  </div>

  <div class="relative flex-1 w-full h-full bg-slate-900">
    <div class="absolute top-4 right-4 z-[900] bg-slate-900/90 backdrop-blur border border-white/10 px-3 py-2 rounded-lg text-xs shadow-xl">
      <span class="text-slate-400 block mb-1">Koordinat Dipilih:</span>
      <code id="pickedInfo" class="text-emerald-400 font-mono font-bold block text-sm">-</code>
    </div>

    <div id="map" class="w-full h-full z-0"></div>
  </div>

<script>
  // ===========================================
  // 1. CONFIG & ICONS
  // ===========================================
  const WIRODITAN = { lat: -6.954838942297599, lng: 109.60949282629723 };
  const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  // ICON BIRU (Titik Proker)
  const iconBlue = new L.Icon({
    iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png",
    shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
    iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41],
  });

  // ICON MERAH (Lokasi Saya) - Menggunakan SVG Inline (Anti Gagal)
  const iconRedSVG = L.divIcon({
    className: 'bg-transparent border-0',
    html: `
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ef4444" 
           class="w-10 h-10 drop-shadow-2xl filter drop-shadow-[0_4px_4px_rgba(0,0,0,0.5)]">
        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
      </svg>
    `,
    iconSize: [40, 40],
    iconAnchor: [20, 40], // Ujung bawah pin
    popupAnchor: [0, -40]
  });

  // ===========================================
  // 2. INIT MAP
  // ===========================================
  const map = L.map('map', { zoomControl: false }).setView([WIRODITAN.lat, WIRODITAN.lng], 15);
  
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
    maxZoom: 19,
    attribution: '© OpenStreetMap'
  }).addTo(map);

  L.control.zoom({ position: 'bottomright' }).addTo(map);

  // STATE VARIABLES
  let gpsMarker = null;
  let gpsCircle = null;
  let pickedMarker = null;
  let pickedLatLng = null;
  let dbLayer = L.layerGroup().addTo(map); // Layer untuk titik database

  // ===========================================
  // 3. LOGIC GPS (BARU & FINAL)
  // ===========================================
  function cariLokasiSaya() {
    if (!navigator.geolocation) {
      alert("Browser Anda tidak mendukung GPS.");
      return;
    }

    // Tampilkan indikator loading (opsional)
    const btn = document.querySelector('button[onclick="cariLokasiSaya()"]');
    const oriText = btn.innerHTML;
    btn.innerHTML = 'Mencari...';
    btn.disabled = true;

    // Fungsi Sukses
    const onGeoSuccess = (pos) => {
      const lat = pos.coords.latitude;
      const lng = pos.coords.longitude;
      const acc = pos.coords.accuracy;

      // Hapus marker lama jika ada
      if (gpsMarker) map.removeLayer(gpsMarker);
      if (gpsCircle) map.removeLayer(gpsCircle);

      // 1. Buat Marker Merah (Pakai SVG agar pasti muncul)
      gpsMarker = L.marker([lat, lng], { icon: iconRedSVG }).addTo(map);
      
      // 2. Buat Lingkaran Akurasi
      gpsCircle = L.circle([lat, lng], {
        radius: acc,
        color: '#ef4444',
        fillColor: '#ef4444',
        fillOpacity: 0.15,
        weight: 1
      }).addTo(map);

      // 3. Popup Info
      gpsMarker.bindPopup(`
        <div class="text-center">
          <b class="text-red-400 text-sm">Lokasi Anda Disini!</b><br>
          <span class="text-xs text-slate-300">Akurasi: ${Math.round(acc)} meter</span>
        </div>
      `).openPopup();

      // 4. Terbang ke lokasi
      map.flyTo([lat, lng], 18, { duration: 1.5 });

      // Reset Tombol
      btn.innerHTML = oriText;
      btn.disabled = false;
    };

    // Fungsi Error
    const onGeoError = (err) => {
      console.warn("GPS Error:", err);
      // Jika error timeout/denied di mode akurat, coba mode 'kasar' (tanpa GPS, pakai wifi)
      if (err.code === 3 || err.code === 1) { 
        alert("Gagal mendeteksi lokasi. Pastikan GPS menyala dan Izin Lokasi di Browser disetujui.");
      } else {
        alert("Gagal mengambil lokasi: " + err.message);
      }
      btn.innerHTML = oriText;
      btn.disabled = false;
    };

    // OPSI: Coba High Accuracy dulu, tapi jangan maksa (timeout 5 detik)
    // enableHighAccuracy: false adalah kunci agar HP tidak menolak jika sinyal lemah
    const options = {
      enableHighAccuracy: false, // Ubah ke false agar lebih cepat & anti-gagal
      timeout: 10000,
      maximumAge: 0
    };

    navigator.geolocation.getCurrentPosition(onGeoSuccess, onGeoError, options);
  }

  function keWiroditan() {
    map.flyTo([WIRODITAN.lat, WIRODITAN.lng], 16);
  }

  // ===========================================
  // 4. INTERAKSI PETA (KLIK & TAMBAH)
  // ===========================================
  
  // Klik Peta -> Pilih Titik
  map.on('click', (e) => {
    pickedLatLng = e.latlng;
    
    // Update Text
    document.getElementById('pickedInfo').innerText = 
      `${pickedLatLng.lat.toFixed(6)}, ${pickedLatLng.lng.toFixed(6)}`;

    // Pindahkan Marker Pilih (Biru Sementara)
    if (pickedMarker) map.removeLayer(pickedMarker);
    
    pickedMarker = L.marker([pickedLatLng.lat, pickedLatLng.lng], { icon: iconBlue, opacity: 0.7 })
      .addTo(map)
      .bindPopup("Titik dipilih. Klik tombol <b>+ Tambah Titik</b> di atas.")
      .openPopup();
  });

  // Tombol Tambah Titik
  document.getElementById('btnAddNew').addEventListener('click', async () => {
    if (!pickedLatLng) {
      alert("Silakan klik peta terlebih dahulu untuk memilih lokasi!");
      return;
    }

    const note = prompt("Beri catatan untuk titik ini (Opsional):", "Titik Proker Baru");
    
    // Kirim ke Server
    try {
      const payload = {
        lat: pickedLatLng.lat,
        lng: pickedLatLng.lng,
        note: note,
        household_id: null // Penting: null agar tidak error validasi
      };

      const res = await fetch('/api/proker-points', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
      });

      const json = await res.json();
      if (!res.ok) throw new Error(json.message || "Gagal menyimpan");

      alert("Berhasil! Titik tersimpan.");
      
      // Reset State
      if (pickedMarker) map.removeLayer(pickedMarker);
      pickedMarker = null;
      pickedLatLng = null;
      document.getElementById('pickedInfo').innerText = "-";
      
      refreshData(); // Reload titik dari DB

    } catch (e) {
      alert("Error: " + e.message);
    }
  });

  // ===========================================
  // 5. LOAD DATA DARI SERVER
  // ===========================================
  async function refreshData() {
    dbLayer.clearLayers();
    try {
      const res = await fetch('/api/map-points');
      const data = await res.json();
      
      (data.points || []).forEach(p => {
        if (!p.lat || !p.lng) return;

        const m = L.marker([p.lat, p.lng], { icon: iconBlue }).addTo(dbLayer);
        m.bindPopup(`
          <div class="font-sans text-center">
            <b class="text-emerald-500">Titik #${p.seq}</b><br>
            <span class="text-xs bg-slate-700 px-1 rounded text-white">${p.pair_code}</span>
            <p class="my-2 text-xs">${p.note || ''}</p>
            <button onclick="hapusTitik(${p.seq})" class="bg-red-500 text-white text-[10px] px-2 py-1 rounded hover:bg-red-600 w-full">
              Hapus Titik
            </button>
          </div>
        `);
      });
    } catch (e) {
      console.error("Gagal load titik:", e);
    }
  }

  // Hapus Titik Global Function
  window.hapusTitik = async (seq) => {
    if(!confirm("Yakin hapus titik ini?")) return;
    try {
      const res = await fetch(`/api/proker-points/${seq}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': token }
      });
      if(res.ok) {
        alert("Terhapus.");
        refreshData();
      } else {
        alert("Gagal hapus.");
      }
    } catch(e) { alert("Error koneksi"); }
  }

  // Auto Load saat pertama buka
  refreshData();

</script>
</body>
</html>