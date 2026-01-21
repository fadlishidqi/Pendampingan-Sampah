<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dashboard Publik – Pendampingan Pengelolaan Sampah</title>

  <link rel="preconnect" href="https://unpkg.com">
  <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""
  />
  <script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""
  ></script>

  <style>
    :root{
      --bg: #0b1220;
      --card: rgba(17,24,39,.92);
      --muted: rgba(255,255,255,.75);
      --line: rgba(255,255,255,.12);
      --accent: #22c55e;
      --accent2: #93c5fd;
      --text: #ffffff;
      --danger: #ef4444;
    }
    *{ box-sizing: border-box; }
    body{
      margin:0;
      font-family: Arial, sans-serif;
      background: linear-gradient(180deg, #0b1220 0%, #070b14 100%);
      color: var(--text);
    }
    a{ color: inherit; text-decoration: none; }
    a:hover{ text-decoration: underline; }

    .topbar{
      position: sticky;
      top: 0;
      z-index: 999;
      backdrop-filter: blur(10px);
      background: rgba(11,18,32,.82);
      border-bottom: 1px solid var(--line);
    }
    .topbar-inner{
      max-width: 1180px;
      margin: 0 auto;
      padding: 12px 16px;
      display:flex;
      gap: 14px;
      align-items:center;
      justify-content: space-between;
    }
    .brand{
      display:flex;
      gap:10px;
      align-items:center;
      min-width: 220px;
    }
    .logo{
      width: 34px;
      height: 34px;
      border-radius: 10px;
      background: rgba(34,197,94,.2);
      border: 1px solid rgba(34,197,94,.35);
      display:grid;
      place-items:center;
      font-weight: 900;
      color: var(--accent);
    }
    .brand-title{ line-height: 1.1; }
    .brand-title b{ display:block; font-size: 13px; }
    .brand-title span{ font-size: 11px; color: var(--muted); }

    .nav{
      display:flex;
      gap: 12px;
      align-items:center;
      flex-wrap: wrap;
      justify-content: flex-end;
    }
    .nav a{
      padding: 8px 10px;
      border-radius: 10px;
      border: 1px solid transparent;
      color: rgba(255,255,255,.9);
      font-size: 13px;
    }
    .nav a:hover{
      border-color: var(--line);
      background: rgba(255,255,255,.04);
      text-decoration: none;
    }
    .btn-admin{
      background: rgba(147,197,253,.12);
      border: 1px solid rgba(147,197,253,.28) !important;
      color: var(--accent2);
      font-weight: 700;
    }

    .nav form { margin: 0; }
    .nav form button{
      padding: 8px 10px;
      border-radius: 10px;
      border: 1px solid rgba(239,68,68,.35);
      background: rgba(239,68,68,.12);
      color: #fecaca;
      font-size: 13px;
      font-weight: 800;
      cursor: pointer;
    }
    .nav form button:hover{ background: rgba(239,68,68,.18); }

    .container{
      max-width: 1180px;
      margin: 0 auto;
      padding: 22px 16px 46px;
    }

    .hero{
      border: 1px solid var(--line);
      border-radius: 18px;
      padding: 18px;
      background: radial-gradient(1200px 480px at 20% 0%, rgba(34,197,94,.18), rgba(17,24,39,.92) 55%);
      box-shadow: 0 12px 40px rgba(0,0,0,.25);
    }
    .hero-grid{
      display:grid;
      grid-template-columns: 1.2fr .8fr;
      gap: 16px;
      align-items: center;
    }
    @media (max-width: 860px){ .hero-grid{ grid-template-columns: 1fr; } }
    .hero h1{ margin: 0 0 8px; font-size: 24px; letter-spacing: .2px; }
    .hero p{ margin: 0 0 14px; color: var(--muted); line-height: 1.5; font-size: 14px; }
    .badges{ display:flex; gap: 8px; flex-wrap: wrap; margin-bottom: 12px; }
    .badge{
      font-size: 12px;
      color: rgba(255,255,255,.9);
      border: 1px solid var(--line);
      background: rgba(255,255,255,.04);
      padding: 6px 10px;
      border-radius: 999px;
    }
    .cta-row{ display:flex; gap: 10px; flex-wrap: wrap; margin-top: 10px; }
    .btn{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      padding: 10px 12px;
      border-radius: 12px;
      border: 1px solid var(--line);
      background: rgba(255,255,255,.06);
      font-size: 13px;
      font-weight: 800;
      cursor: pointer;
      color: white;
    }
    .btn:hover{ background: rgba(255,255,255,.10); }
    .btn-primary{
      background: rgba(34,197,94,.16);
      border-color: rgba(34,197,94,.35);
      color: var(--accent);
    }

    .hero-card{
      border-radius: 16px;
      border: 1px solid var(--line);
      background: rgba(0,0,0,.18);
      padding: 14px;
    }
    .hero-card h3{ margin: 0 0 8px; font-size: 14px; }
    .hero-card ul{ margin: 0; padding-left: 18px; color: var(--muted); font-size: 13px; line-height: 1.55; }

    .section{
      margin-top: 18px;
      border: 1px solid var(--line);
      border-radius: 18px;
      background: var(--card);
      padding: 16px;
      box-shadow: 0 12px 40px rgba(0,0,0,.22);
    }
    .section-header{
      display:flex;
      align-items:flex-end;
      justify-content: space-between;
      gap: 12px;
      margin-bottom: 12px;
      flex-wrap: wrap;
    }
    .section-header h2{ margin: 0; font-size: 16px; }
    .section-header .sub{ color: var(--muted); font-size: 12px; line-height: 1.4; }

    .stats{
      display:grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 10px;
    }
    @media (max-width: 860px){ .stats{ grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px){ .stats{ grid-template-columns: 1fr; } }
    .stat{
      border: 1px solid var(--line);
      border-radius: 14px;
      padding: 12px;
      background: rgba(255,255,255,.04);
    }
    .stat .label{ font-size: 12px; color: var(--muted); margin-bottom: 8px; }
    .stat .value{ font-size: 22px; font-weight: 900; letter-spacing: .3px; }
    .stat .hint{ margin-top: 8px; font-size: 11px; color: rgba(255,255,255,.65); }

    .map-wrap{
      display:grid;
      grid-template-columns: 340px 1fr;
      gap: 10px;
    }
    @media (max-width: 980px){ .map-wrap{ grid-template-columns: 1fr; } }
    .panel{
      border: 1px solid var(--line);
      background: rgba(255,255,255,.04);
      border-radius: 14px;
      padding: 12px;
    }
    .panel h3{ margin: 0 0 8px; font-size: 14px; }
    .panel .muted{ color: var(--muted); font-size: 12px; }
    .controls{ display:flex; gap: 8px; flex-wrap: wrap; margin-bottom: 10px; }
    .chip{
      border: 1px solid var(--line);
      background: rgba(255,255,255,.04);
      color: rgba(255,255,255,.9);
      padding: 7px 10px;
      border-radius: 999px;
      font-size: 12px;
      cursor: pointer;
      user-select: none;
    }
    .chip.active{
      border-color: rgba(34,197,94,.35);
      background: rgba(34,197,94,.12);
      color: var(--accent);
      font-weight: 800;
    }
    .search{
      width: 100%;
      padding: 10px 12px;
      border-radius: 12px;
      border: 1px solid var(--line);
      outline: none;
      background: rgba(0,0,0,.18);
      color: white;
      margin-bottom: 10px;
    }
    .list{
      display:flex;
      flex-direction: column;
      gap: 8px;
      max-height: 420px;
      overflow:auto;
      padding-right: 4px;
    }
    .item{
      border: 1px solid var(--line);
      background: rgba(0,0,0,.16);
      border-radius: 12px;
      padding: 10px;
      cursor: pointer;
    }
    .item:hover{ background: rgba(0,0,0,.22); }
    .item b{ font-size: 13px; }
    .item .meta{
      margin-top: 4px;
      font-size: 11px;
      color: rgba(255,255,255,.72);
      line-height: 1.35;
    }
    #map{
      height: 520px;
      border-radius: 14px;
      border: 1px solid var(--line);
      overflow: hidden;
    }

    .cards{
      display:grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
    }
    @media (max-width: 860px){ .cards{ grid-template-columns: 1fr; } }
    .card{
      border: 1px solid var(--line);
      background: rgba(255,255,255,.04);
      border-radius: 14px;
      padding: 12px;
    }
    .card h3{ margin: 0 0 6px; font-size: 14px; }
    .card p{ margin: 0; color: var(--muted); font-size: 13px; line-height: 1.5; }

    .gallery{
      display:grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
    }
    @media (max-width: 860px){ .gallery{ grid-template-columns: 1fr; } }
    .photo{
      border: 1px solid var(--line);
      background: rgba(255,255,255,.04);
      border-radius: 14px;
      overflow:hidden;
    }
    .photo .thumb{
      height: 150px;
      background: linear-gradient(135deg, rgba(34,197,94,.16), rgba(147,197,253,.10));
      border-bottom: 1px solid var(--line);
      display:grid;
      place-items:center;
      color: rgba(255,255,255,.85);
      font-weight: 900;
      letter-spacing: .8px;
      overflow:hidden;
    }
    .photo .thumb img{
      width:100%;
      height:150px;
      object-fit: cover;
      display:block;
    }
    .photo .cap{ padding: 10px 12px; }
    .photo .cap b{ font-size: 13px; }
    .photo .cap div{
      margin-top: 4px;
      color: var(--muted);
      font-size: 12px;
      line-height: 1.4;
    }

    details{
      border: 1px solid var(--line);
      background: rgba(0,0,0,.16);
      border-radius: 14px;
      padding: 10px 12px;
      margin-bottom: 10px;
    }
    summary{ cursor: pointer; font-weight: 800; font-size: 13px; }
    details p{ margin: 8px 0 0; color: var(--muted); font-size: 13px; line-height: 1.55; }

    .footer{
      margin-top: 18px;
      padding: 14px 16px;
      border-top: 1px solid var(--line);
      color: rgba(255,255,255,.75);
      font-size: 12px;
      line-height: 1.6;
      text-align:center;
    }
    .kecil{ font-size: 11px; color: rgba(255,255,255,.65); }

    .gmaps-btn{
      display:inline-flex;
      align-items:center;
      gap:8px;
      margin-top:10px;
      padding:7px 10px;
      border-radius:10px;
      border:1px solid rgba(0,0,0,.15);
      text-decoration:none;
      color:#111827;
      background:#ffffff;
      font-size:12px;
      font-weight:800;
      line-height:1;
    }
    .gmaps-btn:hover{ background:#f3f4f6; text-decoration:none; }

    .admin-edit{
      border: 1px solid rgba(147,197,253,.28);
      background: rgba(147,197,253,.08);
      border-radius: 18px;
      padding: 14px;
      margin-bottom: 18px;
    }
    .admin-edit h3{
      margin:0 0 8px;
      font-size: 14px;
      color: var(--accent2);
      display:flex;
      align-items:center;
      justify-content: space-between;
      gap: 10px;
      flex-wrap: wrap;
    }
    .admin-edit .hint{
      color: rgba(255,255,255,.75);
      font-size: 12px;
      margin-bottom: 10px;
      line-height: 1.45;
    }
    .admin-edit .grid{
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
    }
    @media (max-width: 860px){ .admin-edit .grid{ grid-template-columns: 1fr; } }
    .admin-edit label{
      display:block;
      font-size: 12px;
      color: rgba(255,255,255,.85);
      margin-bottom: 6px;
      font-weight: 800;
    }
    .admin-edit input[type="text"],
    .admin-edit textarea{
      width:100%;
      padding: 10px 12px;
      border-radius: 12px;
      border: 1px solid rgba(255,255,255,.18);
      outline:none;
      background: rgba(0,0,0,.18);
      color:#fff;
      font-size: 13px;
    }
    .admin-edit textarea{ min-height: 92px; resize: vertical; }
    .admin-edit .row{
      display:flex;
      gap:10px;
      flex-wrap: wrap;
      align-items:center;
      justify-content: flex-end;
      margin-top: 12px;
    }
    .admin-edit .btn-save{
      padding: 10px 12px;
      border-radius: 12px;
      border: 1px solid rgba(34,197,94,.35);
      background: rgba(34,197,94,.16);
      color: var(--accent);
      font-weight: 900;
      cursor: pointer;
    }
    .admin-edit .btn-save:hover{ background: rgba(34,197,94,.22); }

    .flash-ok{
      border: 1px solid rgba(34,197,94,.35);
      background: rgba(34,197,94,.10);
      color: rgba(255,255,255,.92);
      padding: 10px 12px;
      border-radius: 14px;
      margin-bottom: 12px;
      font-size: 13px;
    }

    .btn-toggle{
      padding: 8px 10px;
      border-radius: 10px;
      border: 1px solid rgba(147,197,253,.28);
      background: rgba(147,197,253,.12);
      color: rgba(255,255,255,.92);
      font-weight: 900;
      font-size: 12px;
      cursor: pointer;
    }
    .btn-toggle:hover{ background: rgba(147,197,253,.16); }

    /* ✅ Preview box */
    .preview-box{
      margin-top:8px;
      border: 1px solid rgba(255,255,255,.14);
      border-radius: 12px;
      overflow:hidden;
      background: rgba(0,0,0,.18);
      height: 120px;
      display:none;
    }
    .preview-box img{
      width:100%;
      height:120px;
      object-fit: cover;
      display:block;
    }

    /* ✅ tombol merah + undo */
    .photo-action-row{
      margin-top:8px;
      display:flex;
      gap:8px;
      flex-wrap: wrap;
      align-items:center;
    }
    .btn-remove-photo{
      padding:7px 10px;
      border-radius:10px;
      border:1px solid rgba(239,68,68,.45);
      background: rgba(239,68,68,.18);
      color:#fecaca;
      font-size:12px;
      font-weight:900;
      cursor:pointer;
    }
    .btn-remove-photo:hover{ background: rgba(239,68,68,.28); }

    .btn-undo-photo{
      padding:7px 10px;
      border-radius:10px;
      border:1px solid rgba(147,197,253,.35);
      background: rgba(147,197,253,.14);
      color: rgba(255,255,255,.92);
      font-size:12px;
      font-weight:900;
      cursor:pointer;
      display:none;
    }
    .btn-undo-photo:hover{ background: rgba(147,197,253,.18); }

    .small-note{
      margin-top:6px;
      font-size:11px;
      color: rgba(255,255,255,.72);
      line-height:1.35;
    }

    /* =========================
       ✅ QR CODE + SHARE (CENTER + BIG)
       ========================= */
    .qr-wrap{
      display:flex;
      justify-content:center;
    }

    .qr-box{
      width:100%;
      max-width:520px;
      border: 1px solid var(--line);
      background: rgba(0,0,0,.16);
      border-radius: 18px;
      padding: 18px;
      display:flex;
      flex-direction: column;
      align-items: center;
      gap: 14px;
    }

    .qr-canvas{
      width: 320px;
      height: 320px;
      border-radius: 16px;
      background: #fff;
      display:grid;
      place-items:center;
      overflow:hidden;
    }
    .qr-canvas canvas{
      width: 320px;
      height: 320px;
      display:block;
    }
    @media (max-width: 420px){
      .qr-canvas{ width: 260px; height: 260px; }
      .qr-canvas canvas{ width: 260px; height: 260px; }
    }

    .qr-actions{
      display:flex;
      gap: 10px;
      flex-wrap: wrap;
      width: 100%;
      justify-content: center;
    }
    .btn-qr{
      padding: 10px 14px;
      border-radius: 12px;
      border: 1px solid var(--line);
      background: rgba(255,255,255,.06);
      color: white;
      font-size: 13px;
      font-weight: 900;
      cursor:pointer;
    }
    .btn-qr:hover{ background: rgba(255,255,255,.10); }
    .btn-qr.primary{
      background: rgba(34,197,94,.16);
      border-color: rgba(34,197,94,.35);
      color: var(--accent);
    }
    .qr-note{
      font-size: 12px;
      color: rgba(255,255,255,.72);
      line-height: 1.45;
      text-align: center;
      max-width: 420px;
    }
    .qr-toast{
      margin-top: 8px;
      display:none;
      border: 1px solid rgba(34,197,94,.35);
      background: rgba(34,197,94,.10);
      color: rgba(255,255,255,.92);
      padding: 10px 12px;
      border-radius: 14px;
      font-size: 12px;
      font-weight: 800;
    }
  </style>
</head>

@php
  $heroTitle = $content->hero_title ?? 'Pendampingan Pengelolaan Sampah Berkelanjutan';
  $heroDesc  = $content->hero_desc ?? '';
  $edukasi   = is_array($content->edukasi_cards ?? null) ? $content->edukasi_cards : [];
  $dok       = is_array($content->dokumentasi_cards ?? null) ? $content->dokumentasi_cards : [];
  $tentang   = $content->tentang_desc ?? '';
  $kontak    = $content->kontak ?? '';

  $publicBase = rtrim(env('APP_PUBLIC_URL', config('app.url')), '/');
  $shareUrl = $publicBase . '/';
@endphp

<body>
  <div class="topbar">
    <div class="topbar-inner">
      <div class="brand">
        <div class="logo">PS</div>
        <div class="brand-title">
          <b>Pendampingan Sampah</b>
          <span>Dashboard Publik • Edukasi • Peta Titik Proker</span>
        </div>
      </div>

      <div class="nav">
        <a href="#peta">Peta</a>
        <a href="#edukasi">Edukasi</a>
        <a href="#dokumentasi">Dokumentasi</a>
        <a href="#tentang">Tentang</a>

        @if(auth()->check() && auth()->user()->is_admin)
          <a class="btn-admin" href="/admin/map">Buka Admin Map</a>
          <form method="POST" action="/admin/logout">
            @csrf
            <button type="submit">Logout Admin</button>
          </form>
        @else
          <a class="btn-admin" href="/admin/login">Login Admin</a>
        @endif
      </div>
    </div>
  </div>

  <div class="container">

    @if ($errors->any())
      <div class="flash-ok" style="border-color: rgba(239,68,68,.45); background: rgba(239,68,68,.10);">
        <b>Gagal simpan:</b>
        <ul style="margin:8px 0 0; padding-left:18px;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @if (session('success'))
      <div class="flash-ok">{{ session('success') }}</div>
    @endif

    @if(auth()->check() && auth()->user()->is_admin)
      <div style="margin-bottom:10px; display:flex; justify-content:flex-end;">
        <button class="btn-toggle" id="toggleEditBtn" type="button">Mode Edit: OFF</button>
      </div>

      <div class="admin-edit" id="adminEditPanel" style="display:none;">
        <h3>
          <span>✏️ Mode Edit Dashboard (Admin)</span>
          <span style="font-size:12px; font-weight:800; color:rgba(255,255,255,.8);">Tersimpan</span>
        </h3>
        <div class="hint">
          Edit teks + upload foto di sini, lalu klik <b>Simpan</b>. Perubahan akan terlihat juga untuk pengunjung publik (tanpa login).
          <br>Catatan: bagian <b>Ringkasan Dampak + Peta Titik</b> tidak ikut diedit (otomatis dari /api/map-points).
        </div>

        <form method="POST" action="{{ route('admin.dashboard-content.update') }}" enctype="multipart/form-data">
          @csrf

          <div class="grid">
            <div>
              <label>Judul Hero</label>
              <input type="text" name="hero_title" value="{{ old('hero_title', $heroTitle) }}">
            </div>
            <div>
              <label>Kontak (Footer)</label>
              <input type="text" name="kontak" value="{{ old('kontak', $kontak) }}">
            </div>

            <div style="grid-column: 1 / -1;">
              <label>Deskripsi Hero</label>
              <textarea name="hero_desc">{{ old('hero_desc', $heroDesc) }}</textarea>
            </div>

            <div style="grid-column: 1 / -1;">
              <label>Tentang Program (Ringkasan)</label>
              <textarea name="tentang_desc">{{ old('tentang_desc', $tentang) }}</textarea>
            </div>
          </div>

          <div style="margin-top:14px; font-weight:900; color:rgba(255,255,255,.9);">Edukasi Singkat (6 kartu)</div>
          <div class="grid" style="margin-top:10px;">
            @for($i=0; $i<6; $i++)
              @php
                $t = $edukasi[$i]['title'] ?? '';
                $b = $edukasi[$i]['body'] ?? '';
              @endphp
              <div>
                <label>Judul Edukasi #{{ $i+1 }}</label>
                <input type="text" name="edukasi_title[]" value="{{ old('edukasi_title.'.$i, $t) }}">
              </div>
              <div>
                <label>Isi Edukasi #{{ $i+1 }}</label>
                <textarea name="edukasi_body[]">{{ old('edukasi_body.'.$i, $b) }}</textarea>
              </div>
            @endfor
          </div>

          <div style="margin-top:14px; font-weight:900; color:rgba(255,255,255,.9);">Dokumentasi (3 kartu + upload foto)</div>
          <div class="grid" style="margin-top:10px;">
            @for($i=0; $i<3; $i++)
              @php
                $dt = $dok[$i]['title'] ?? '';
                $db = $dok[$i]['body'] ?? '';
                $di = $dok[$i]['image'] ?? null;
              @endphp
              <div>
                <label>Judul Dokumentasi #{{ $i+1 }}</label>
                <input type="text" name="dok_title[]" value="{{ old('dok_title.'.$i, $dt) }}">

                <div style="margin-top:8px;">
                  <label style="margin-bottom:6px;">Upload Foto #{{ $i+1 }} (opsional)</label>

                  <input
                    type="file"
                    name="dok_image[]"
                    accept="image/*"
                    class="dokFileInput"
                    data-index="{{ $i }}"
                    style="color:rgba(255,255,255,.85); font-size:12px;"
                  >

                  <input type="hidden"
                         name="dok_remove[{{ $i }}]"
                         value="0"
                         class="dokRemoveInput"
                         data-index="{{ $i }}">

                  <div class="preview-box" id="previewBox{{ $i }}">
                    <img id="previewImg{{ $i }}" src="" alt="Preview {{ $i+1 }}">
                  </div>

                  <div class="photo-action-row">
                    <button type="button" class="btn-remove-photo" data-index="{{ $i }}">🗑 Hapus Foto</button>
                    <button type="button" class="btn-undo-photo" data-index="{{ $i }}">↩ Undo</button>
                  </div>

                  <div class="small-note" id="undoNote{{ $i }}" style="display:none;">
                    Undo mengembalikan foto lama. Jika tadi sudah memilih file baru, pilih ulang file jika perlu.
                  </div>

                  @if($di)
                    <input type="hidden" class="dokCurrentUrl" data-index="{{ $i }}" value="{{ asset(ltrim($di, '/')) }}">
                    <div style="margin-top:6px; font-size:12px; color:rgba(255,255,255,.75);">
                      Saat ini: <span style="font-weight:800;">sudah ada foto</span>
                    </div>
                  @else
                    <input type="hidden" class="dokCurrentUrl" data-index="{{ $i }}" value="">
                  @endif
                </div>
              </div>
              <div>
                <label>Caption Dokumentasi #{{ $i+1 }}</label>
                <textarea name="dok_body[]">{{ old('dok_body.'.$i, $db) }}</textarea>
              </div>
            @endfor
          </div>

          <div class="row">
            <button type="submit" class="btn-save">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    @endif

    <!-- HERO -->
    <div class="hero" id="atas">
      <div class="hero-grid">
        <div>
          <h1>{{ $heroTitle }}</h1>
          <p>{{ $heroDesc }}</p>

          <div class="badges">
            <div class="badge">Lokasi: Desa Wiroditan</div>
            <div class="badge">Area: Pekalongan</div>
            <div class="badge">Mode: Publik (read-only)</div>
          </div>

          <div class="cta-row">
            <a class="btn btn-primary" href="#peta">Lihat Peta Titik</a>
            <a class="btn" href="#edukasi">Baca Edukasi</a>
          </div>
        </div>

        <div class="hero-card">
          <h3>Tujuan Singkat</h3>
          <ul>
            <li>Meningkatkan pemahaman warga tentang pengelolaan sampah.</li>
            <li>Mendorong solusi sederhana (biopori & tungku minim asap).</li>
            <li>Menyediakan peta titik proker sebagai bukti kegiatan lapangan.</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- ✅ QR CODE + SHARE -->
    <div class="section" id="qr-share">
      <div class="section-header">
        <div>
          <h2>QR Code & Share</h2>
          <div class="sub">Struktur awal untuk akses cepat dashboard publik (pakai <b>APP_PUBLIC_URL</b>).</div>
        </div>
        <div class="sub"><span class="kecil">Belum untuk cetak — hanya tampilan & struktur.</span></div>
      </div>

      <div class="qr-wrap">
        <div class="qr-box">
          <div class="qr-canvas" aria-label="QR Code">
            <canvas id="qrCanvas" width="320" height="320"></canvas>
          </div>

          <div class="qr-actions">
            <button class="btn-qr primary" type="button" id="btnCopyLink">Copy Link</button>
            <button class="btn-qr" type="button" id="btnShare">Share</button>
          </div>

          <div class="qr-note">
            Jika tombol <b>Share</b> tidak tersedia di browser kamu, otomatis fallback ke <b>Copy Link</b>.
          </div>

          <div class="qr-toast" id="qrToast">Link berhasil disalin ✅</div>
        </div>
      </div>
    </div>

    <!-- STATS -->
    <div class="section" id="ringkasan">
      <div class="section-header">
        <div>
          <h2>Ringkasan Dampak</h2>
          <div class="sub">Angka akan otomatis terisi dari data titik proker.</div>
        </div>
        <div class="sub">Sumber data: <b>/api/map-points</b></div>
      </div>

      <div class="stats">
        <div class="stat">
          <div class="label">Total Titik Proker</div>
          <div class="value" id="statTotal">-</div>
          <div class="hint">Semua titik (termasuk yang belum ada koordinat).</div>
        </div>
        <div class="stat">
          <div class="label">Titik Terpetakan</div>
          <div class="value" id="statMapped">-</div>
          <div class="hint">Titik yang punya lat/lng (muncul di peta).</div>
        </div>
        <div class="stat">
          <div class="label">Biopori</div>
          <div class="value" id="statBiopori">-</div>
          <div class="hint">1 titik = 1 paket proker (biopori+tungku).</div>
        </div>
        <div class="stat">
          <div class="label">Tungku Minim Asap</div>
          <div class="value" id="statTungku">-</div>
          <div class="hint">Sama jumlahnya dengan paket proker.</div>
        </div>
      </div>
    </div>

    <!-- MAP -->
    <div class="section" id="peta">
      <div class="section-header">
        <div>
          <h2>Peta Titik Proker</h2>
          <div class="sub">Klik titik untuk melihat info singkat. Data bersifat publik dan tidak memuat identitas rumah/KK.</div>
        </div>
        <div class="sub">
          <span class="kecil">Tips: gunakan pencarian “PROKER-0001” untuk fokus ke titik tertentu.</span>
        </div>
      </div>

      <div class="map-wrap">
        <div class="panel">
          <h3>Kontrol</h3>

          <div class="controls">
            <div class="chip active" id="chipAll">Semua</div>
            <div class="chip" id="chipActive">Status: Active</div>
            <div class="chip" id="chipHasCoord">Punya Koordinat</div>
          </div>

          <input class="search" id="search" placeholder="Cari kode (contoh: PROKER-0007)..." />

          <div class="muted" style="margin-bottom:10px;">
            Total daftar (hasil filter): <b id="listCount">-</b>
          </div>

          <div class="list" id="list">
            <div class="muted">Memuat titik...</div>
          </div>

          <div class="muted" style="margin-top:10px;">
            <b>Catatan privasi:</b> Dashboard publik hanya menampilkan kode, status, dan koordinat titik proker (tanpa data privat).
          </div>
        </div>

        <div id="map"></div>
      </div>
    </div>

    <!-- EDUKASI -->
    <div class="section" id="edukasi">
      <div class="section-header">
        <div>
          <h2>Edukasi Singkat</h2>
          <div class="sub">Konten ringkas yang bisa kamu kembangkan jadi infografis / modul pelatihan warga.</div>
        </div>
      </div>

      <div class="cards">
        @for($i=0; $i<6; $i++)
          @php
            $t = $edukasi[$i]['title'] ?? '-';
            $b = $edukasi[$i]['body'] ?? '';
          @endphp
          <div class="card">
            <h3>{{ $t }}</h3>
            <p>{{ $b }}</p>
          </div>
        @endfor
      </div>
    </div>

    <!-- DOKUMENTASI -->
    <div class="section" id="dokumentasi">
      <div class="section-header">
        <div>
          <h2>Dokumentasi Kegiatan</h2>
          <div class="sub">Admin bisa upload foto & edit caption langsung dari web.</div>
        </div>
      </div>

      <div class="gallery">
        @for($i=0; $i<3; $i++)
          @php
            $dt = $dok[$i]['title'] ?? ('FOTO '.($i+1));
            $db = $dok[$i]['body'] ?? '';
            $di = $dok[$i]['image'] ?? null;
          @endphp
          <div class="photo">
            <div class="thumb">
              @if($di)
                <img src="{{ asset(ltrim($di, '/')) }}" alt="Dokumentasi {{ $i+1 }}">
              @else
                FOTO {{ $i+1 }}
              @endif
            </div>
            <div class="cap">
              <b>{{ $dt }}</b>
              <div>{{ $db }}</div>
            </div>
          </div>
        @endfor
      </div>
    </div>

    <!-- FAQ -->
    <div class="section" id="faq">
      <div class="section-header">
        <div>
          <h2>FAQ</h2>
          <div class="sub">Pertanyaan umum agar publik paham konteks dashboard.</div>
        </div>
      </div>

      <details>
        <summary>Dashboard ini menampilkan apa?</summary>
        <p>Dashboard menampilkan ringkasan program dan peta titik proker (biopori + tungku minim asap) sebagai dokumentasi kegiatan.</p>
      </details>
      <details>
        <summary>Apakah titik di peta menunjukkan rumah warga?</summary>
        <p>Tidak. Dashboard publik hanya menampilkan titik proker untuk kebutuhan pemantauan program, tanpa memuat identitas KK atau alamat detail.</p>
      </details>
      <details>
        <summary>Kenapa ada titik yang tidak muncul di peta?</summary>
        <p>Titik yang belum memiliki koordinat (lat/lng kosong) tidak ditampilkan di peta. Namun tetap dihitung di “Total Titik Proker”.</p>
      </details>
      <details>
        <summary>Siapa yang bisa mengedit titik?</summary>
        <p>Hanya admin yang memiliki akses edit. Publik hanya bisa melihat (read-only).</p>
      </details>
    </div>

    <!-- TENTANG -->
    <div class="section" id="tentang">
      <div class="section-header">
        <div>
          <h2>Tentang Program</h2>
          <div class="sub">Informasi singkat untuk keperluan laporan, presentasi, dan transparansi.</div>
        </div>
      </div>

      <div class="card" style="margin-bottom:12px;">
        <h3>Ringkasan</h3>
        <p>{{ $tentang }}</p>
      </div>

      <div class="footer">
        <div><b>Tim KKN</b> • Desa Wiroditan • Pekalongan</div>
        <div class="kecil">Kontak resmi: {{ $kontak }}</div>
      </div>
    </div>
  </div>

<script>
  // Toggle panel edit
  (function(){
    const btn = document.getElementById("toggleEditBtn");
    const panel = document.getElementById("adminEditPanel");
    if (!btn || !panel) return;

    const KEY = "ps_admin_edit_open";
    function setState(open){
      panel.style.display = open ? "block" : "none";
      btn.textContent = open ? "Mode Edit: ON" : "Mode Edit: OFF";
      localStorage.setItem(KEY, open ? "1" : "0");
    }

    const saved = localStorage.getItem(KEY);
    setState(saved === "1");

    btn.addEventListener("click", () => {
      const isOpen = panel.style.display !== "none";
      setState(!isOpen);
    });
  })();
</script>

<script>
  // Preview + Hapus + Undo
  (function(){
    const fileInputs = document.querySelectorAll(".dokFileInput");
    const currentUrls = document.querySelectorAll(".dokCurrentUrl");
    const removeButtons = document.querySelectorAll(".btn-remove-photo");
    const undoButtons = document.querySelectorAll(".btn-undo-photo");

    const prevState = {};

    function showPreview(index, src){
      const box = document.getElementById("previewBox" + index);
      const img = document.getElementById("previewImg" + index);
      if (!box || !img) return;

      if (!src){
        img.src = "";
        box.style.display = "none";
        return;
      }
      img.src = src;
      box.style.display = "block";
    }

    function setRemove(index, isRemove){
      const ri = document.querySelector('.dokRemoveInput[data-index="'+index+'"]');
      if (ri) ri.value = isRemove ? "1" : "0";
    }

    function setUndoVisible(index, visible){
      const ub = document.querySelector('.btn-undo-photo[data-index="'+index+'"]');
      const note = document.getElementById('undoNote'+index);
      if (ub) ub.style.display = visible ? "inline-flex" : "none";
      if (note) note.style.display = visible ? "block" : "none";
    }

    currentUrls.forEach(el => {
      const idx = el.dataset.index;
      const url = el.value || "";
      if (url) showPreview(idx, url);
    });

    fileInputs.forEach(input => {
      input.addEventListener("change", () => {
        const idx = input.dataset.index;
        const file = input.files && input.files[0] ? input.files[0] : null;

        setRemove(idx, false);
        setUndoVisible(idx, false);

        if (!file){
          const cur = document.querySelector('.dokCurrentUrl[data-index="'+idx+'"]');
          showPreview(idx, cur ? (cur.value || "") : "");
          return;
        }

        showPreview(idx, URL.createObjectURL(file));
      });
    });

    removeButtons.forEach(btn => {
      btn.addEventListener("click", () => {
        const idx = btn.dataset.index;

        const box = document.getElementById("previewBox" + idx);
        const img = document.getElementById("previewImg" + idx);
        const currentPreview = (box && box.style.display !== "none" && img) ? (img.src || "") : "";
        prevState[idx] = { previewSrc: currentPreview, hadPreview: !!currentPreview };

        setRemove(idx, true);
        const fi = document.querySelector('.dokFileInput[data-index="'+idx+'"]');
        if (fi) fi.value = "";

        showPreview(idx, "");
        setUndoVisible(idx, true);
      });
    });

    undoButtons.forEach(btn => {
      btn.addEventListener("click", () => {
        const idx = btn.dataset.index;
        setRemove(idx, false);

        const state = prevState[idx] || null;
        if (state && state.hadPreview && state.previewSrc) {
          showPreview(idx, state.previewSrc);
        } else {
          const cur = document.querySelector('.dokCurrentUrl[data-index="'+idx+'"]');
          showPreview(idx, cur ? (cur.value || "") : "");
        }

        setUndoVisible(idx, false);
      });
    });
  })();
</script>

<!-- ✅ QR generator lib (client-side) -->
<script type="application/json" id="psShareUrlJson">@json($shareUrl)</script>
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

<script>
  // ✅ QR Code + Copy + Share
  (function(){
    const shareUrl = JSON.parse(document.getElementById("psShareUrlJson").textContent || '""');

    const canvas = document.getElementById("qrCanvas");
    const btnCopy = document.getElementById("btnCopyLink");
    const btnShare = document.getElementById("btnShare");
    const toast = document.getElementById("qrToast");

    function showToast(msg){
      if (!toast) return;
      toast.textContent = msg || "OK";
      toast.style.display = "block";
      clearTimeout(showToast._t);
      showToast._t = setTimeout(() => toast.style.display = "none", 1400);
    }

    async function copyToClipboard(text){
      try{
        await navigator.clipboard.writeText(text);
        showToast("Link berhasil disalin ✅");
        return true;
      }catch(e){
        try{
          const ta = document.createElement("textarea");
          ta.value = text;
          ta.style.position = "fixed";
          ta.style.left = "-9999px";
          document.body.appendChild(ta);
          ta.select();
          document.execCommand("copy");
          document.body.removeChild(ta);
          showToast("Link berhasil disalin ✅");
          return true;
        }catch(err){
          showToast("Gagal menyalin ❌");
          return false;
        }
      }
    }

    try{
      if (window.QRCode && canvas){
        QRCode.toCanvas(canvas, shareUrl, {
          errorCorrectionLevel: "M",
          margin: 1,
          width: 320
        }, function (err) {
          if (err) {
            console.error(err);
            showToast("QR gagal dibuat ❌");
          }
        });
      }
    }catch(e){
      console.error(e);
      showToast("QR gagal dibuat ❌");
    }

    if (btnCopy){
      btnCopy.addEventListener("click", () => copyToClipboard(shareUrl));
    }

    if (btnShare){
      btnShare.addEventListener("click", async () => {
        if (navigator.share){
          try{
            await navigator.share({
              title: "Dashboard Pendampingan Sampah",
              text: "Akses dashboard publik Pendampingan Sampah:",
              url: shareUrl
            });
          }catch(e){
            await copyToClipboard(shareUrl);
          }
        } else {
          await copyToClipboard(shareUrl);
        }
      });
    }
  })();
</script>

<script>
  // ===== PETA (kode kamu, tidak diubah) =====
  const WIRODITAN = { lat: -6.954838942297599, lng: 109.60949282629723 };
  const PEKALONGAN_BOUNDS = L.latLngBounds([-7.15, 109.40], [-6.75, 110.00]);

  const shadowUrl = "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png";
  const iconBlue = new L.Icon({
    iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png",
    shadowUrl: shadowUrl,
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41],
  });

  async function apiGet(url){
    const res = await fetch(url, { headers: { "Accept": "application/json" }});
    const ct = (res.headers.get("content-type") || "").toLowerCase();
    const data = ct.includes("application/json") ? await res.json() : await res.text();
    if (!res.ok) throw data;
    return data;
  }

  function esc(s){
    return String(s ?? "")
      .replaceAll("&","&amp;")
      .replaceAll("<","&lt;")
      .replaceAll(">","&gt;")
      .replaceAll("\"","&quot;")
      .replaceAll("'","&#039;");
  }

  function googleMapsUrl(lat, lng){
    const la = Number(lat);
    const ln = Number(lng);
    if (!Number.isFinite(la) || !Number.isFinite(ln)) return null;
    return `https://www.google.com/maps?q=${la},${ln}`;
  }

  const map = L.map("map", {
    center: [WIRODITAN.lat, WIRODITAN.lng],
    zoom: 15,
    minZoom: 12,
    maxZoom: 18,
    maxBounds: PEKALONGAN_BOUNDS,
    maxBoundsViscosity: 1.0,
    zoomControl: false,
  });

  L.control.zoom({ position: "topright" }).addTo(map);

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", { maxZoom: 19 }).addTo(map);
  map.fitBounds(PEKALONGAN_BOUNDS);

  const layer = L.layerGroup().addTo(map);
  const markerBySeq = new Map();

  let allPoints = [];
  let filterMode = "all";
  const chipAll = document.getElementById("chipAll");
  const chipActive = document.getElementById("chipActive");
  const chipHasCoord = document.getElementById("chipHasCoord");
  const searchEl = document.getElementById("search");
  const listEl = document.getElementById("list");
  const listCountEl = document.getElementById("listCount");

  function setActiveChip(mode){
    filterMode = mode;
    [chipAll, chipActive, chipHasCoord].forEach(c => c.classList.remove("active"));
    if (mode === "all") chipAll.classList.add("active");
    if (mode === "active") chipActive.classList.add("active");
    if (mode === "hasCoord") chipHasCoord.classList.add("active");
    render();
  }

  chipAll.addEventListener("click", () => setActiveChip("all"));
  chipActive.addEventListener("click", () => setActiveChip("active"));
  chipHasCoord.addEventListener("click", () => setActiveChip("hasCoord"));
  searchEl.addEventListener("input", () => render());

  function applyFilter(points){
    const q = (searchEl.value || "").trim().toLowerCase();
    return points.filter(p => {
      if (filterMode === "active" && String(p.status || "").toLowerCase() !== "active") return false;
      if (filterMode === "hasCoord" && (p.lat === null || p.lng === null)) return false;
      if (!q) return true;
      const code = String(p.pair_code || "").toLowerCase();
      const note = String(p.note || "").toLowerCase();
      return code.includes(q) || note.includes(q);
    });
  }

  function render(){
    layer.clearLayers();
    markerBySeq.clear();

    const filtered = applyFilter(allPoints);
    listCountEl.textContent = filtered.length;

    const listVisible = filtered.filter(p => p.lat !== null && p.lng !== null);

    if (!filtered.length){
      listEl.innerHTML = '<div class="muted">Tidak ada titik yang cocok dengan filter.</div>';
      return;
    }

    filtered.forEach(p => {
      if (p.lat === null || p.lng === null) return;

      const m = L.marker([p.lat, p.lng], { icon: iconBlue }).addTo(layer);

      const code = esc(p.pair_code || ("#" + p.seq));
      const status = esc(p.status || "-");
      const note = esc(p.note || "");

      const gUrl = googleMapsUrl(p.lat, p.lng);
      const gBtn = gUrl ? `<a class="gmaps-btn" href="${gUrl}" target="_blank" rel="noopener noreferrer">📍 Lihat di Google Maps</a>` : ``;

      m.bindPopup(
        `<div style="font-family:Arial; font-size:13px;">
          <b>${code}</b><br>
          <div style="opacity:.85; margin-top:4px;">Status: <b>${status}</b></div>
          ${note ? `<div style="opacity:.85; margin-top:4px;">Catatan: ${note}</div>` : ``}
          ${gBtn}
        </div>`
      );

      markerBySeq.set(p.seq, m);
    });

    listEl.innerHTML = "";
    if (!listVisible.length){
      listEl.innerHTML = '<div class="muted">Ada data titik, tapi belum ada yang memiliki koordinat.</div>';
      return;
    }

    listVisible.forEach(p => {
      const div = document.createElement("div");
      div.className = "item";

      const code = p.pair_code || ("#" + p.seq);
      const meta = `LatLng: ${Number(p.lat).toFixed(6)}, ${Number(p.lng).toFixed(6)} • Status: ${p.status || "-"}`;

      div.innerHTML = `<b>${esc(code)}</b><div class="meta">${esc(meta)}</div>`;

      div.addEventListener("click", () => {
        map.setView([p.lat, p.lng], 17);
        const m = markerBySeq.get(p.seq);
        if (m) m.openPopup();
      });

      listEl.appendChild(div);
    });
  }

  async function loadPoints(){
    const res = await apiGet("/api/map-points");
    allPoints = (res.points || []).map(p => ({
      ...p,
      lat: p.lat !== null ? Number(p.lat) : null,
      lng: p.lng !== null ? Number(p.lng) : null,
    }));

    const total = allPoints.length;
    const mapped = allPoints.filter(p => p.lat !== null && p.lng !== null).length;

    document.getElementById("statTotal").textContent = total;
    document.getElementById("statMapped").textContent = mapped;
    document.getElementById("statBiopori").textContent = total;
    document.getElementById("statTungku").textContent = total;

    render();
  }

  loadPoints().catch(err => {
    console.error(err);
    document.getElementById("statTotal").textContent = "!";
    document.getElementById("statMapped").textContent = "!";
    document.getElementById("statBiopori").textContent = "!";
    document.getElementById("statTungku").textContent = "!";
    listEl.innerHTML = '<div class="muted">Gagal memuat data peta. Cek server & route /api/map-points.</div>';
  });
</script>

</body>
</html>
