<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Map – Wiroditan</title>

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <style>
    body { margin:0; font-family: Arial, sans-serif; }

    #topbar{
      padding:10px;
      background:#111827;
      color:#fff;
      display:flex;
      gap:10px;
      align-items:center;
      flex-wrap: wrap;
      font-size:14px;
      position: relative;
      z-index: 999;
    }
    #topbar input, #topbar button, #topbar a{
      padding:6px 10px;
      border-radius:8px;
      border:0;
      outline:none;
    }
    #topbar button { cursor:pointer; }
    #topbar a { cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; }

    .btn-danger { background:#ef4444; color:#fff; }
    .btn { background:#e5e7eb; color:#111827; }
    .btn-public { background:#374151; color:#fff; } /* ✅ tombol dashboard publik */
    .small { opacity:.9; font-size:12px; }
    code { background: rgba(255,255,255,.12); padding:2px 6px; border-radius:6px; }

    #wrap { position: relative; height: calc(100vh - 60px); }
    #map { height: 100%; }

    /* sidebar list */
    #sidebar {
      position: absolute;
      top: 10px;
      left: 10px;
      width: 260px;
      max-height: calc(100% - 20px);
      overflow: auto;
      background: rgba(17,24,39,.92);
      color: #fff;
      border-radius: 12px;
      padding: 10px;
      z-index: 900;
      box-shadow: 0 10px 30px rgba(0,0,0,.25);
    }
    #sidebar h3 { margin: 0 0 8px; font-size: 14px; }
    .item {
      display:flex;
      justify-content: space-between;
      align-items:center;
      gap:8px;
      padding:8px;
      border-radius:10px;
      background: rgba(255,255,255,.06);
      margin-bottom:8px;
    }
    .item:hover { background: rgba(255,255,255,.10); }
    .item-left { cursor:pointer; flex:1; }
    .item-title { font-weight:700; font-size: 13px; }
    .item-sub { font-size: 11px; opacity:.85; margin-top:2px; }
    .mini-btn {
      padding:6px 8px;
      border-radius:8px;
      border:0;
      cursor:pointer;
      background:#ef4444;
      color:#fff;
      font-size: 12px;
      flex: 0 0 auto;
    }
    .muted { opacity:.7; font-size: 12px; }

    /* biar enak di HP */
    @media (max-width: 600px) {
      #sidebar { width: 220px; }
    }
  </style>
</head>

<body>
  <div id="topbar">
    <b>Admin Map (Pekalongan)</b>

    <!-- ✅ tombol balik ke dashboard publik -->
    <a href="/" class="btn-public">← Dashboard Publik</a>

    <button class="btn" id="btnLocate">GPS saya (merah)</button>
    <button class="btn" id="btnCenter">Ke Wiroditan</button>
    <button class="btn" id="btnRefresh">Refresh titik</button>

    <button class="btn" id="btnAddNew">Tambah Titik Proker (klik peta dulu)</button>

    <button class="btn-danger" id="btnClearAllPoints">Hapus Semua Titik (lat/lng null)</button>

    <form method="POST" action="/admin/logout" style="margin-left:auto;">
      @csrf
      <button type="submit" class="btn">Logout</button>
    </form>

    <span class="small">Dipilih: <code id="picked">-</code></span>
  </div>

  <div id="wrap">
    <div id="sidebar">
      <h3>Daftar Titik</h3>
      <div id="list" class="muted">Loading...</div>
    </div>

    <div id="map"></div>
  </div>

<script>
  // Wiroditan (yang benar)
  const WIRODITAN = { lat: -6.954838942297599, lng: 109.60949282629723 };

  // batasi area Pekalongan biar nggak bisa “scroll Indonesia”
  const PEKALONGAN_BOUNDS = L.latLngBounds(
    [-7.15, 109.40],
    [-6.75, 110.00]
  );

  const shadow = "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png";

  const iconRed = new L.Icon({
    iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png",
    shadowUrl: shadow,
    iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41],
  });

  const iconBlue = new L.Icon({
    iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png",
    shadowUrl: shadow,
    iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41],
  });

  // helper fetch JSON yang aman (biar gak “HTML Laravel” dianggap sukses)
  async function apiFetch(url, options = {}) {
    const headers = options.headers || {};
    headers['Accept'] = 'application/json';
    headers['X-Requested-With'] = 'XMLHttpRequest';

    // untuk route /api/... yang sekarang memakai session + CSRF
    const method = (options.method || 'GET').toUpperCase();
    if (method !== 'GET' && method !== 'HEAD') {
      const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      if (token) headers['X-CSRF-TOKEN'] = token;
    }

    if (options.body && !headers['Content-Type']) {
      headers['Content-Type'] = 'application/json';
    }

    const res = await fetch(url, { ...options, headers });

    const ct = (res.headers.get('content-type') || '').toLowerCase();
    const raw = ct.includes('application/json') ? await res.json() : await res.text();

    if (typeof raw === 'string' && raw.includes('<!DOCTYPE html')) {
      throw { status: res.status, raw: raw.slice(0, 300) + '...' };
    }

    if (!res.ok) {
      throw { status: res.status, raw };
    }

    return raw;
  }

  // init map (zoom control kita pindah ke kanan atas biar gak tabrakan sidebar)
  const map = L.map('map', {
    center: [WIRODITAN.lat, WIRODITAN.lng],
    zoom: 15,
    minZoom: 12,
    maxZoom: 18,
    maxBounds: PEKALONGAN_BOUNDS,
    maxBoundsViscosity: 1.0,
    zoomControl: false
  });

  L.control.zoom({ position: 'topright' }).addTo(map);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 })
    .addTo(map);

  map.fitBounds(PEKALONGAN_BOUNDS);

  // state
  let pickedLatLng = null;
  let pickedMarker = null;
  let gpsMarker = null;
  let gpsCircle = null;

  const pickedEl = document.getElementById('picked');
  const listEl = document.getElementById('list');

  function setPickedText() {
    if (!pickedLatLng) { pickedEl.textContent = '-'; return; }
    pickedEl.textContent = `${pickedLatLng.lat.toFixed(6)}, ${pickedLatLng.lng.toFixed(6)}`;
  }

  // layer marker DB
  const dbLayer = L.layerGroup().addTo(map);

  // simpan marker by seq biar list bisa zoom + open popup
  const markerBySeq = new Map();

  // klik peta = pilih titik (preview)
  map.on('click', (e) => {
    pickedLatLng = { lat: e.latlng.lat, lng: e.latlng.lng };
    setPickedText();

    if (pickedMarker) pickedMarker.remove();
    pickedMarker = L.marker([pickedLatLng.lat, pickedLatLng.lng], { icon: iconBlue })
      .addTo(map)
      .bindPopup("Titik dipilih ✅ lalu klik 'Tambah Titik Proker'")
      .openPopup();
  });

  async function refreshAllPoints() {
    dbLayer.clearLayers();
    markerBySeq.clear();
    listEl.innerHTML = '<div class="muted">Loading...</div>';

    const data = await apiFetch('/api/map-points');
    const points = data.points || [];

    // MARKER: hanya yang punya koordinat
    points.forEach(p => {
      if (p.lat === null || p.lng === null) return;

      const m = L.marker([p.lat, p.lng], { icon: iconBlue }).addTo(dbLayer);
      m.bindPopup(`
        <b>Titik #${p.seq}</b><br>
        <small>Kode: <code>${p.pair_code}</code></small><br>
        <small>${p.note ?? ''}</small><br><br>
        <button onclick="deletePoint(${p.seq})"
          style="padding:6px 10px;border:0;border-radius:8px;cursor:pointer;background:#ef4444;color:white;">
          Hapus titik #${p.seq}
        </button>
      `);

      markerBySeq.set(p.seq, m);
    });

    // SIDEBAR: hanya tampilkan yang punya koordinat (biar setelah clear-all, list ikut kosong)
    const visible = points.filter(p => p.lat !== null && p.lng !== null);

    if (!visible.length) {
      listEl.innerHTML = '<div class="muted">Belum ada titik tersimpan.</div>';
      return;
    }

    listEl.innerHTML = '';
    visible.forEach(p => {
      const div = document.createElement('div');
      div.className = 'item';

      const left = document.createElement('div');
      left.className = 'item-left';
      left.innerHTML = `
        <div class="item-title">Titik #${p.seq}</div>
        <div class="item-sub">${p.lat.toFixed(6)}, ${p.lng.toFixed(6)}</div>
      `;
      left.onclick = () => {
        map.flyTo([p.lat, p.lng], 17, { duration: 0.8 });
        const mk = markerBySeq.get(p.seq);
        if (mk) mk.openPopup();
      };

      const btn = document.createElement('button');
      btn.className = 'mini-btn';
      btn.textContent = 'Hapus';
      btn.onclick = async (ev) => {
        ev.stopPropagation();
        await deletePoint(p.seq);
      };

      div.appendChild(left);
      div.appendChild(btn);
      listEl.appendChild(div);
    });
  }

  window.deletePoint = async function(seq) {
    const ok = confirm(`Yakin hapus titik #${seq}? Setelah ini, nomor titik akan dirapikan.`);
    if (!ok) return;

    try {
      const res = await apiFetch(`/api/proker-points/${seq}`, { method: 'DELETE' });
      alert(res.message || 'Berhasil');
      await refreshAllPoints();
    } catch (e) {
      alert(`Gagal hapus titik #${seq}. (${e.status})\n${typeof e.raw === 'string' ? e.raw : JSON.stringify(e.raw)}`);
    }
  }

  // GPS
  document.getElementById('btnLocate').addEventListener('click', () => {
    if (!navigator.geolocation) { alert('Browser tidak mendukung GPS'); return; }

    navigator.geolocation.getCurrentPosition(
      (pos) => {
        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;
        const acc = pos.coords.accuracy;

        if (gpsMarker) gpsMarker.remove();
        if (gpsCircle) gpsCircle.remove();

        gpsMarker = L.marker([lat, lng], { icon: iconRed }).addTo(map)
          .bindPopup("📍 GPS saya (merah)")
          .openPopup();

        gpsCircle = L.circle([lat, lng], { radius: acc }).addTo(map);
        map.setView([lat, lng], 16);
      },
      (err) => alert('Gagal ambil GPS: ' + err.message),
      { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
  });

  // center Wiroditan
  document.getElementById('btnCenter').addEventListener('click', () => {
    map.setView([WIRODITAN.lat, WIRODITAN.lng], 16);
  });

  // refresh
  document.getElementById('btnRefresh').addEventListener('click', async () => {
    try { await refreshAllPoints(); }
    catch(e){ alert(`Gagal refresh (${e.status})\n${typeof e.raw === 'string' ? e.raw : JSON.stringify(e.raw)}`); }
  });

  // tambah titik proker
  document.getElementById('btnAddNew').addEventListener('click', async () => {
    if (!pickedLatLng) { alert('Klik peta dulu untuk pilih titik'); return; }

    const note = prompt('Catatan lokasi (opsional):', 'Titik proker (biopori+tungku)');
    const payload = {
      lat: Number(pickedLatLng.lat.toFixed(7)),
      lng: Number(pickedLatLng.lng.toFixed(7)),
      note: note || null,
      household_id: 1
    };

    try {
      const res = await apiFetch('/api/proker-points', {
        method: 'POST',
        body: JSON.stringify(payload)
      });

      if (pickedMarker) pickedMarker.remove();
      pickedMarker = null;
      pickedLatLng = null;
      setPickedText();

      alert(res.message || 'Titik dibuat');
      await refreshAllPoints();
    } catch (e) {
      alert(`Gagal simpan titik. (${e.status})\n${typeof e.raw === 'string' ? e.raw : JSON.stringify(e.raw)}`);
    }
  });

  // hapus semua titik (kosongkan koordinat)
  document.getElementById('btnClearAllPoints').addEventListener('click', async () => {
    const ok = confirm("Yakin mau hapus SEMUA TITIK? (lat/lng jadi kosong, data tetap ada)");
    if (!ok) return;

    try {
      const res = await apiFetch('/api/admin/clear-all-points', { method: 'PATCH' });
      alert(res.message || 'Selesai');
      await refreshAllPoints();
    } catch (e) {
      alert(`Gagal. (${e.status})\n${typeof e.raw === 'string' ? e.raw : JSON.stringify(e.raw)}`);
    }
  });

  // load awal
  (async () => {
    try { await refreshAllPoints(); }
    catch(e){ alert(`Gagal load awal (${e.status})\n${typeof e.raw === 'string' ? e.raw : JSON.stringify(e.raw)}`); }
  })();
</script>
</body>
</html>
