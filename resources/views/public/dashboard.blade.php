<!doctype html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dashboard Publik – Pendampingan Pengelolaan Sampah</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <style>
    body { font-family: 'Inter', sans-serif; }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 99px; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

    /* Leaflet Popup Dark Mode Override */
    .leaflet-popup-content-wrapper, .leaflet-popup-tip {
      background: rgba(15, 23, 42, 0.95); /* Slate 900 */
      color: #fff;
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,0.1);
    }
    .leaflet-popup-content { margin: 12px; font-size: 13px; line-height: 1.5; }
    .leaflet-container a.leaflet-popup-close-button { color: #fff; }
    
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }

    /* Animasi Scroll Reveal */
    .reveal {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
    }
    .reveal.active {
      opacity: 1;
      transform: translateY(0);
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
  $shareUrl  = url()->current();
@endphp

<body class="bg-slate-950 text-slate-200 antialiased selection:bg-emerald-500/30 selection:text-emerald-200">

  <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
    <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-emerald-600/10 rounded-full blur-[100px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[100px]"></div>
  </div>

  <nav class="sticky top-0 z-50 border-b border-white/5 bg-slate-950/80 backdrop-blur-md transition-all duration-300" id="navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        
        <a href="#" class="flex items-center gap-3 group">
          <div class="w-10 h-10 rounded-2xl
                      bg-gradient-to-br from-emerald-400 to-teal-600
                      flex items-center justify-center text-white
                      shadow-lg shadow-emerald-500/25
                      transition-all duration-300
                      hover:scale-110 hover:rotate-12">
            
            <svg xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="w-5 h-5">
                
              <path d="M3 12a9 9 0 0 1 9-9 9 9 0 0 1 6.36 2.64"/>
              <path d="M21 3v6h-6"/>
              <path d="M21 12a9 9 0 0 1-9 9 9 9 0 0 1-6.36-2.64"/>
              <path d="M3 21v-6h6"/>
            </svg>
          </div>

          <div class="hidden sm:block leading-tight">
            <div class="font-bold text-white text-sm tracking-wide">Pendampingan Sampah</div>
            <div class="text-[10px] text-slate-400 font-medium uppercase tracking-wider group-hover:text-emerald-400 transition-colors">Desa Wiroditan</div>
          </div>
        </a>

        <div class="flex items-center gap-1 sm:gap-2">
          
          <div class="hidden md:flex gap-1 items-center">
            <a href="#peta" class="px-3 py-2 text-xs font-bold text-slate-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors">Peta</a>
            
            <div class="relative group">
              <button class="px-3 py-2 text-xs font-bold text-slate-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors flex items-center gap-1">
                Informasi
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 opacity-50 group-hover:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
              </button>
              
              <div class="absolute top-full right-0 mt-1 w-40 bg-slate-900 border border-white/10 rounded-xl shadow-xl overflow-hidden opacity-0 invisible group-hover:opacity-100 group-hover:visible translate-y-2 group-hover:translate-y-0 transition-all duration-200 origin-top-right">
                <a href="#edukasi" class="block px-4 py-3 text-xs text-slate-300 hover:bg-white/5 hover:text-white border-b border-white/5">
                  📚 Edukasi
                </a>
                <a href="#dokumentasi" class="block px-4 py-3 text-xs text-slate-300 hover:bg-white/5 hover:text-white">
                  📸 Dokumentasi
                </a>
              </div>
            </div>

            <a href="#faq" class="px-3 py-2 text-xs font-bold text-slate-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors">FAQ</a>
          </div>

          <div class="h-5 w-px bg-white/10 mx-2 hidden md:block"></div>

          @if(auth()->check() && auth()->user()->is_admin)
            <a href="/admin/map" class="px-3 py-2 text-xs font-bold text-blue-400 bg-blue-500/10 border border-blue-500/20 rounded-lg hover:bg-blue-500/20 transition-all flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
              Admin Map
            </a>
            <form method="POST" action="/admin/logout">
              @csrf
              <button type="submit" class="px-3 py-2 text-xs font-medium text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-colors">
                Logout
              </button>
            </form>
          @else
            <a href="/admin/login" class="px-3 py-2 text-xs font-medium text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
              Login Admin
            </a>
          @endif
        </div>

      </div>
    </div>
  </nav>

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-12">

    @if ($errors->any())
      <div class="reveal active rounded-xl border border-red-500/20 bg-red-500/10 p-4 text-sm text-red-200">
        <div class="flex items-center gap-2 font-bold text-red-400 mb-2">
          Gagal Menyimpan
        </div>
        <ul class="list-disc list-inside space-y-1 opacity-90">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @if (session('success'))
      <div class="reveal active rounded-xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm text-emerald-200 flex items-center gap-2">
        {{ session('success') }}
      </div>
    @endif

    @if(auth()->check() && auth()->user()->is_admin)
      <div class="flex justify-end reveal">
        <button id="toggleEditBtn" type="button" class="px-4 py-2 bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-300 text-xs font-bold uppercase tracking-wider rounded-lg border border-indigo-500/20 transition-all flex items-center gap-2">
          Mode Edit: OFF
        </button>
      </div>

      <div id="adminEditPanel" class="hidden rounded-2xl border border-indigo-500/20 bg-indigo-900/10 p-6 backdrop-blur-sm reveal">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-indigo-300 font-bold flex items-center gap-2">
            ✏️ Edit Konten Dashboard
          </h3>
          <span class="px-2 py-1 bg-indigo-500/20 rounded text-[10px] text-indigo-200 font-mono">AUTOSAVE: OFF</span>
        </div>
        
        <form method="POST" action="{{ route('admin.dashboard-content.update') }}" enctype="multipart/form-data" class="space-y-6">
          @csrf
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1">
              <label class="text-xs font-bold text-slate-300 uppercase tracking-wide">Judul Hero</label>
              <input type="text" name="hero_title" value="{{ old('hero_title', $heroTitle) }}" class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-2 text-sm outline-none focus:border-indigo-500">
            </div>
            <div class="space-y-1">
              <label class="text-xs font-bold text-slate-300 uppercase tracking-wide">Kontak (Footer)</label>
              <input type="text" name="kontak" value="{{ old('kontak', $kontak) }}" class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-2 text-sm outline-none focus:border-indigo-500">
            </div>
            <div class="md:col-span-2 space-y-1">
              <label class="text-xs font-bold text-slate-300 uppercase tracking-wide">Deskripsi Hero</label>
              <textarea name="hero_desc" rows="2" class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-2 text-sm outline-none focus:border-indigo-500">{{ old('hero_desc', $heroDesc) }}</textarea>
            </div>
            <div class="md:col-span-2 space-y-1">
              <label class="text-xs font-bold text-slate-300 uppercase tracking-wide">Tentang Program</label>
              <textarea name="tentang_desc" rows="2" class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-2 text-sm outline-none focus:border-indigo-500">{{ old('tentang_desc', $tentang) }}</textarea>
            </div>
          </div>
          
          <div class="border-t border-white/5 my-6"></div>
          
          <div class="space-y-4">
            <h4 class="text-sm font-bold text-emerald-400">Edukasi Singkat (6 Kartu)</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              @for($i=0; $i<6; $i++)
                @php $t = $edukasi[$i]['title'] ?? ''; $b = $edukasi[$i]['body'] ?? ''; @endphp
                <div class="p-4 bg-slate-900/30 rounded-xl border border-white/5 space-y-2">
                  <div class="text-[10px] text-slate-500 font-mono">KARTU #{{ $i+1 }}</div>
                  <input type="text" name="edukasi_title[]" value="{{ old('edukasi_title.'.$i, $t) }}" placeholder="Judul" class="w-full bg-slate-950/50 border border-white/10 rounded-lg px-3 py-2 text-xs outline-none focus:border-emerald-500">
                  <textarea name="edukasi_body[]" rows="2" placeholder="Isi..." class="w-full bg-slate-950/50 border border-white/10 rounded-lg px-3 py-2 text-xs outline-none focus:border-emerald-500">{{ old('edukasi_body.'.$i, $b) }}</textarea>
                </div>
              @endfor
            </div>
          </div>

          <div class="border-t border-white/5 my-6"></div>

          <div class="space-y-4">
            <h4 class="text-sm font-bold text-blue-400">Dokumentasi (3 Foto)</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              @for($i=0; $i<3; $i++)
                @php
                  $dt = $dok[$i]['title'] ?? '';
                  $db = $dok[$i]['body'] ?? '';
                  $di = $dok[$i]['image'] ?? null;
                @endphp
                <div class="p-4 bg-slate-900/30 rounded-xl border border-white/5 space-y-3">
                  <div class="text-[10px] text-slate-500 font-mono">DOK #{{ $i+1 }}</div>
                  <input type="text" name="dok_title[]" value="{{ old('dok_title.'.$i, $dt) }}" placeholder="Judul Foto" class="w-full bg-slate-950/50 border border-white/10 rounded-lg px-3 py-2 text-xs outline-none focus:border-blue-500">
                  
                  <div class="space-y-2">
                    <input type="file" name="dok_image[]" accept="image/*" class="dokFileInput w-full text-[10px] text-slate-400 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:bg-slate-700 file:text-white cursor-pointer" data-index="{{ $i }}">
                    <input type="hidden" name="dok_remove[{{ $i }}]" value="0" class="dokRemoveInput" data-index="{{ $i }}">
                    
                    <div id="previewBox{{ $i }}" class="hidden rounded-lg overflow-hidden border border-white/10 bg-black/20 h-32 relative">
                      <img id="previewImg{{ $i }}" src="" class="w-full h-full object-cover">
                    </div>

                    <div class="flex gap-2">
                      <button type="button" class="btn-remove-photo flex-1 py-1 bg-red-500/10 text-red-400 text-[10px] rounded border border-red-500/20" data-index="{{ $i }}">Hapus</button>
                      <button type="button" class="btn-undo-photo hidden flex-1 py-1 bg-blue-500/10 text-blue-400 text-[10px] rounded border border-blue-500/20" data-index="{{ $i }}">Undo</button>
                    </div>

                    @if($di)
                      <input type="hidden" class="dokCurrentUrl" data-index="{{ $i }}" value="{{ asset(ltrim($di, '/')) }}">
                      <div class="text-[10px] text-emerald-400">Foto Tersimpan ✅</div>
                    @else
                      <input type="hidden" class="dokCurrentUrl" data-index="{{ $i }}" value="">
                    @endif
                  </div>
                  <textarea name="dok_body[]" rows="2" placeholder="Caption..." class="w-full bg-slate-950/50 border border-white/10 rounded-lg px-3 py-2 text-xs outline-none focus:border-blue-500">{{ old('dok_body.'.$i, $db) }}</textarea>
                </div>
              @endfor
            </div>
          </div>

          <div class="pt-4 flex justify-end">
            <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/20 transform hover:-translate-y-0.5 transition-all">
              Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    @endif

    <div id="atas" class="reveal relative rounded-3xl overflow-hidden border border-white/10 shadow-2xl bg-gradient-to-br from-slate-900 to-slate-950">
      <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-100"></div>
      
      <div class="relative p-8 md:p-12 grid grid-cols-1 lg:grid-cols-5 gap-12 items-center">
        <div class="lg:col-span-3 space-y-6">
          <div class="space-y-2">
            <h1 class="text-3xl md:text-5xl font-black tracking-tight text-white leading-tight">
              {{ $heroTitle }}
            </h1>
            <p class="text-slate-400 text-lg leading-relaxed">
              {{ $heroDesc }}
            </p>
          </div>

          <div class="flex flex-wrap gap-2">
            <span class="px-3 py-1 rounded-full border border-white/10 bg-white/5 text-xs text-slate-300 backdrop-blur-md">📍 Desa Wiroditan</span>
            <span class="px-3 py-1 rounded-full border border-white/10 bg-white/5 text-xs text-slate-300 backdrop-blur-md">🗺️ Area Pekalongan</span>
          </div>

          <div class="flex flex-wrap gap-4 pt-2">
            <a href="#peta" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/20 transition-all transform hover:-translate-y-1 flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12 1.586l-4 4v12.828l4-4V1.586zM3.707 3.293A1 1 0 002 4v10a1 1 0 00.293.707L6 18.414V5.586L3.707 3.293zM17.707 5.293L14 1.586v12.828l2.293 2.293A1 1 0 0018 16V6a1 1 0 00-.293-.707z" clip-rule="evenodd" /></svg>
              Lihat Peta
            </a>
            <a href="#edukasi" class="px-6 py-3 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-semibold rounded-xl transition-all hover:border-white/20 flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" /></svg>
              Baca Edukasi
            </a>
          </div>
        </div>

        <div class="lg:col-span-2">
          <div class="bg-black/20 backdrop-blur-sm border border-white/10 rounded-2xl p-6 relative transform hover:rotate-1 transition-transform duration-500">
            <div class="absolute -top-3 -right-3 w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/30 animate-pulse">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" /></svg>
            </div>
            <h3 class="text-white font-bold text-lg mb-4">Tujuan Program</h3>
            <ul class="space-y-3 text-sm text-slate-300">
              <li class="flex gap-3 items-start">
                <svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Peningkatan pemahaman warga tentang pengelolaan sampah mandiri.
              </li>
              <li class="flex gap-3 items-start">
                <svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Solusi praktis melalui pembuatan biopori & tungku minim asap.
              </li>
              <li class="flex gap-3 items-start">
                <svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Transparansi kegiatan lapangan melalui peta digital interaktif.
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div id="qr-share" class="reveal bg-slate-900/50 rounded-3xl border border-white/10 p-8 flex flex-col items-center justify-center text-center shadow-xl">
      <div class="max-w-xl space-y-6">
        <div>
          <h2 class="text-2xl font-bold text-white mb-2">QR Code & Share</h2>
          <p class="text-slate-400 text-sm">Scan untuk akses cepat dashboard ini di perangkat lain.</p>
        </div>

        <div class="bg-white p-4 rounded-2xl inline-block shadow-2xl">
          <canvas id="qrCanvas" class="block w-[280px] h-[280px]"></canvas>
        </div>

        <div class="flex flex-wrap justify-center gap-3">
          <button id="btnCopyLink" type="button" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 border border-white/10 text-white rounded-xl text-sm font-bold transition-all flex items-center gap-2">
            Salin Link
          </button>
          <button id="btnShare" type="button" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-sm font-bold shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2">
            Share
          </button>
        </div>
        
        <div id="qrToast" class="hidden px-4 py-2 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold rounded-lg animate-fade-in-up">
          Link berhasil disalin! ✅
        </div>
      </div>
    </div>

    <div id="ringkasan" class="reveal space-y-4">
      <div class="flex items-end justify-between px-2">
        <div>
          <h2 class="text-xl font-bold text-white">Ringkasan Dampak</h2>
          <p class="text-xs text-slate-400 mt-1">Update realtime dari database</p>
        </div>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-slate-900/50 border border-white/5 rounded-2xl p-5 hover:bg-slate-800/50 transition-colors group">
          <div class="text-xs text-slate-400 font-medium uppercase tracking-wide mb-1">Total Titik</div>
          <div class="text-3xl font-black text-white group-hover:text-emerald-400 transition-colors" id="statTotal">-</div>
          <div class="text-[10px] text-slate-500 mt-2">Semua data masuk</div>
        </div>
        <div class="bg-slate-900/50 border border-white/5 rounded-2xl p-5 hover:bg-slate-800/50 transition-colors group">
          <div class="text-xs text-emerald-400 font-medium uppercase tracking-wide mb-1">Terpetakan</div>
          <div class="text-3xl font-black text-white group-hover:text-emerald-400 transition-colors" id="statMapped">-</div>
          <div class="text-[10px] text-slate-500 mt-2">Muncul di peta</div>
        </div>
        <div class="bg-slate-900/50 border border-white/5 rounded-2xl p-5 hover:bg-slate-800/50 transition-colors group">
          <div class="text-xs text-blue-400 font-medium uppercase tracking-wide mb-1">Biopori</div>
          <div class="text-3xl font-black text-white group-hover:text-blue-400 transition-colors" id="statBiopori">-</div>
          <div class="text-[10px] text-slate-500 mt-2">Lubang resapan</div>
        </div>
        <div class="bg-slate-900/50 border border-white/5 rounded-2xl p-5 hover:bg-slate-800/50 transition-colors group">
          <div class="text-xs text-orange-400 font-medium uppercase tracking-wide mb-1">Tungku</div>
          <div class="text-3xl font-black text-white group-hover:text-orange-400 transition-colors" id="statTungku">-</div>
          <div class="text-[10px] text-slate-500 mt-2">Minim asap</div>
        </div>
      </div>
    </div>

    <div id="peta" class="reveal bg-slate-900/50 border border-white/10 rounded-3xl p-1 shadow-2xl">
      <div class="p-6 pb-2 md:flex justify-between items-end mb-4">
        <div>
          <h2 class="text-xl font-bold text-white flex items-center gap-2">
            Peta Sebaran
          </h2>
          <p class="text-sm text-slate-400 mt-1 max-w-2xl">
            Klik titik untuk melihat detail. Data ini bersifat publik namun tidak menampilkan informasi sensitif (nama KK/privasi warga).
          </p>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-4 gap-1 p-1">
        <div class="bg-slate-950/50 rounded-2xl border border-white/5 p-4 flex flex-col h-[500px]">
          <div class="mb-4 space-y-3">
            <div class="relative">
              <input id="search" type="text" placeholder="Cari Kode (PROKER-...)" class="w-full bg-black/30 border border-white/10 rounded-xl pl-9 pr-3 py-2 text-xs text-white focus:border-emerald-500 outline-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-3 top-2.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <div class="flex justify-between text-[10px] text-slate-500 px-1">
              <span>Hasil Filter:</span>
              <span id="listCount" class="font-mono text-white">0</span>
            </div>
          </div>
          <div id="list" class="flex-1 overflow-y-auto space-y-2 pr-1 custom-scrollbar">
            <div class="text-center py-8 text-xs text-slate-500 italic">Memuat data...</div>
          </div>
        </div>

        <div class="lg:col-span-3 h-[500px] rounded-2xl overflow-hidden border border-white/10 relative z-0">
          <div id="map" class="w-full h-full bg-slate-900"></div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 reveal">
      
      <div id="edukasi" class="space-y-4">
        <div class="flex items-center gap-2">
          <div class="p-2 bg-indigo-500/10 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
          </div>
          <h2 class="text-xl font-bold text-white">Edukasi Singkat</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          @for($i=0; $i<6; $i++)
            @php 
                $t = $edukasi[$i]['title'] ?? 'Tips #'.($i+1); 
                $b = $edukasi[$i]['body'] ?? 'Konten belum tersedia.';
                $preview = strlen($b) > 100 ? substr($b, 0, 100) . '...' : $b;
            @endphp
            
            {{-- UBAH DIV MENJADI A (LINK) --}}
            <a href="{{ route('public.detail', ['type' => 'edukasi', 'index' => $i]) }}" 
               class="block bg-slate-900/50 border border-white/5 rounded-xl p-4 hover:border-indigo-500/50 hover:bg-slate-800/80 transition-all group relative overflow-hidden">
              
              {{-- Efek Glow saat hover --}}
              <div class="absolute inset-0 bg-indigo-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>

              <div class="relative z-10">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-sm font-bold text-indigo-300 group-hover:text-indigo-200 transition-colors">{{ $t }}</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-600 group-hover:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">
                    {{ $preview }}
                </p>
              </div>
            </a>
          @endfor
        </div>
      </div>

      <div id="dokumentasi" class="space-y-4">
        <div class="flex items-center gap-2">
          <div class="p-2 bg-pink-500/10 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
          </div>
          <h2 class="text-xl font-bold text-white">Dokumentasi</h2>
        </div>
        <div class="space-y-3">
          @for($i=0; $i<3; $i++)
            @php
              $dt = $dok[$i]['title'] ?? ('Foto Kegiatan '.($i+1));
              $db = $dok[$i]['body'] ?? 'Deskripsi kegiatan...';
              $di = $dok[$i]['image'] ?? null;
              $preview = strlen($db) > 120 ? substr($db, 0, 120) . '...' : $db;
            @endphp

            {{-- UBAH DIV MENJADI A (LINK) --}}
            <a href="{{ route('public.detail', ['type' => 'dokumentasi', 'index' => $i]) }}" 
               class="flex gap-4 bg-slate-900/50 border border-white/5 rounded-xl p-3 overflow-hidden hover:bg-slate-800/80 hover:border-pink-500/30 transition-all group">
              
              {{-- Gambar Thumbnail --}}
              <div class="w-24 h-24 sm:w-32 sm:h-24 shrink-0 rounded-lg bg-slate-800 flex items-center justify-center overflow-hidden border border-white/5 relative">
                @if($di)
                  <img src="{{ asset(ltrim($di, '/')) }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                @else
                  <span class="text-[10px] text-slate-600 font-bold">NO IMG</span>
                @endif
                
                {{-- Overlay Icon Mata --}}
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
              </div>

              {{-- Text Content --}}
              <div class="flex-1 py-1 flex flex-col justify-center">
                <h4 class="text-sm font-bold text-white mb-1 group-hover:text-pink-300 transition-colors">{{ $dt }}</h4>
                <p class="text-xs text-slate-400 leading-relaxed line-clamp-2 group-hover:text-slate-300">{{ $preview }}</p>
                <div class="mt-2 text-[10px] text-emerald-500 font-medium opacity-0 group-hover:opacity-100 transition-opacity transform translate-x-[-10px] group-hover:translate-x-0 duration-300">
                    Baca Selengkapnya &rarr;
                </div>
              </div>
            </a>
          @endfor
        </div>
      </div>
    </div>

    <div id="faq" class="reveal max-w-3xl mx-auto space-y-4 pt-8 border-t border-white/5">
      <h2 class="text-center text-xl font-bold text-white mb-6">Pertanyaan Umum (FAQ)</h2>
      <div class="space-y-2">
        <details class="group bg-slate-900/50 border border-white/5 rounded-xl open:border-emerald-500/30 transition-all">
          <summary class="flex items-center justify-between p-4 cursor-pointer font-medium text-sm text-slate-200 list-none">
            <span>Apa fungsi dashboard ini?</span>
            <span class="transition group-open:rotate-180">
              <svg fill="none" height="20" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="20"><path d="M6 9l6 6 6-6"></path></svg>
            </span>
          </summary>
          <div class="text-xs text-slate-400 px-4 pb-4 leading-relaxed">
            Sebagai media transparansi dan dokumentasi digital untuk program KKN, mencakup peta sebaran titik proker dan materi edukasi.
          </div>
        </details>
        <details class="group bg-slate-900/50 border border-white/5 rounded-xl open:border-emerald-500/30 transition-all">
          <summary class="flex items-center justify-between p-4 cursor-pointer font-medium text-sm text-slate-200 list-none">
            <span>Apakah data warga ditampilkan?</span>
            <span class="transition group-open:rotate-180">
              <svg fill="none" height="20" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="20"><path d="M6 9l6 6 6-6"></path></svg>
            </span>
          </summary>
          <div class="text-xs text-slate-400 px-4 pb-4 leading-relaxed">
            Tidak. Kami sangat menjaga privasi. Titik di peta hanya menunjukkan lokasi alat (biopori/tungku) tanpa menyertakan nama KK atau data sensitif lainnya.
          </div>
        </details>
      </div>
    </div>

    <footer class="border-t border-white/5 pt-8 pb-12 text-center space-y-2">
      <div class="font-bold text-white text-sm">Tim KKN • Desa Wiroditan • Pekalongan</div>
      <div class="text-xs text-slate-500">{{ $kontak }}</div>
      <div class="text-[10px] text-slate-700 mt-4">© {{ date('Y') }} Pendampingan Sampah. Built with Laravel.</div>
    </footer>

  </main>

  <script>
    // 1. Scroll Reveal Animation
    document.addEventListener('DOMContentLoaded', () => {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('active');
          }
        });
      }, { threshold: 0.1 });

      document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    });

    // 2. Logic Toggle Admin Edit
    const toggleBtn = document.getElementById("toggleEditBtn");
    const editPanel = document.getElementById("adminEditPanel");
    if(toggleBtn && editPanel) {
      toggleBtn.addEventListener('click', () => {
        editPanel.classList.toggle('hidden');
        toggleBtn.textContent = editPanel.classList.contains('hidden') ? 'Mode Edit: OFF' : 'Tutup Edit';
        toggleBtn.classList.toggle('bg-indigo-500');
        toggleBtn.classList.toggle('text-white');
      });
    }

    // 3. Preview Image Logic
    document.querySelectorAll('.dokFileInput').forEach(input => {
      input.addEventListener('change', function() {
        const idx = this.dataset.index;
        const file = this.files[0];
        const previewBox = document.getElementById('previewBox' + idx);
        const previewImg = document.getElementById('previewImg' + idx);
        
        if (file) {
          previewImg.src = URL.createObjectURL(file);
          previewBox.classList.remove('hidden');
          document.querySelector(`.dokRemoveInput[data-index="${idx}"]`).value = "0";
        }
      });
    });

    document.querySelectorAll('.btn-remove-photo').forEach(btn => {
      btn.addEventListener('click', function() {
        const idx = this.dataset.index;
        document.getElementById('previewBox' + idx).classList.add('hidden');
        this.classList.add('hidden');
        document.querySelector(`.btn-undo-photo[data-index="${idx}"]`).classList.remove('hidden');
        document.querySelector(`.dokRemoveInput[data-index="${idx}"]`).value = "1";
        document.querySelector(`.dokFileInput[data-index="${idx}"]`).value = ""; 
      });
    });

    document.querySelectorAll('.btn-undo-photo').forEach(btn => {
      btn.addEventListener('click', function() {
        const idx = this.dataset.index;
        const currentUrl = document.querySelector(`.dokCurrentUrl[data-index="${idx}"]`).value;
        if(currentUrl) {
          document.getElementById('previewImg' + idx).src = currentUrl;
          document.getElementById('previewBox' + idx).classList.remove('hidden');
        }
        this.classList.add('hidden');
        document.querySelector(`.btn-remove-photo[data-index="${idx}"]`).classList.remove('hidden');
        document.querySelector(`.dokRemoveInput[data-index="${idx}"]`).value = "0";
      });
    });

    // 4. Init Previews
    document.querySelectorAll('.dokCurrentUrl').forEach(el => {
      if(el.value) {
        const idx = el.dataset.index;
        document.getElementById('previewImg' + idx).src = el.value;
        document.getElementById('previewBox' + idx).classList.remove('hidden');
      }
    });
  </script>

  <script type="application/json" id="psShareUrlJson">@json($shareUrl)</script>
  <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

  <script>
    (function(){
      // Fallback Logic: Jika textContent kosong/error, gunakan URL browser
      let shareUrl = "";
      try {
        const raw = document.getElementById("psShareUrlJson").textContent;
        shareUrl = JSON.parse(raw);
      } catch(e) {}
      if(!shareUrl) shareUrl = window.location.href;

      const canvas = document.getElementById("qrCanvas");
      
      function generateClientSideQR() {
        if(window.QRCode && canvas) {
          QRCode.toCanvas(canvas, shareUrl, { 
            width: 280, 
            margin: 2,
            color: { dark: '#0f172a', light: '#ffffff' }
          }, function(error){
            if(error) {
              console.warn("Client QR Failed, switching to API");
              useApiFallback();
            }
          });
        } else {
          setTimeout(() => {
            if(window.QRCode) generateClientSideQR();
            else useApiFallback();
          }, 1000);
        }
      }

      function useApiFallback() {
        const img = document.createElement("img");
        img.src = `https://api.qrserver.com/v1/create-qr-code/?size=280x280&data=${encodeURIComponent(shareUrl)}`;
        img.className = "block w-[280px] h-[280px]";
        canvas.replaceWith(img);
      }

      generateClientSideQR();

      const btnCopy = document.getElementById("btnCopyLink");
      const btnShare = document.getElementById("btnShare");
      const toast = document.getElementById("qrToast");

      async function copyText() {
        try {
          await navigator.clipboard.writeText(shareUrl);
          showToast();
        } catch(err) {
          const ta = document.createElement("textarea");
          ta.value = shareUrl;
          document.body.appendChild(ta);
          ta.select();
          document.execCommand("copy");
          document.body.removeChild(ta);
          showToast();
        }
      }

      function showToast() {
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 2000);
      }

      if(btnCopy) btnCopy.addEventListener('click', copyText);
      if(btnShare) {
        btnShare.addEventListener('click', async () => {
          if(navigator.share) {
            try { await navigator.share({ title: 'Dashboard Pendampingan', url: shareUrl }); } catch(e) {}
          } else {
            copyText();
          }
        });
      }
    })();
  </script>

  <script>
    const WIRODITAN = { lat: -6.954838942297599, lng: 109.60949282629723 };
    const map = L.map("map", { zoomControl: false }).setView([WIRODITAN.lat, WIRODITAN.lng], 15);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", { maxZoom: 19 }).addTo(map);
    L.control.zoom({ position: "topright" }).addTo(map);

    const layer = L.layerGroup().addTo(map);
    const markerBySeq = new Map();
    let allPoints = [];
    let filterMode = "all";

    const iconBlue = new L.Icon({
      iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png",
      shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
      iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
    });

    fetch("/api/map-points", { headers: { "Accept": "application/json" } })
      .then(res => res.json())
      .then(data => {
        allPoints = (data.points || []).map(p => ({
          ...p, lat: p.lat ? Number(p.lat) : null, lng: p.lng ? Number(p.lng) : null
        }));
        updateStats();
        renderList();
      })
      .catch(err => console.error("Gagal load map:", err));

    function updateStats() {
      const total = allPoints.length;
      const mapped = allPoints.filter(p => p.lat && p.lng).length;
      document.getElementById("statTotal").innerText = total;
      document.getElementById("statMapped").innerText = mapped;
      document.getElementById("statBiopori").innerText = total;
      document.getElementById("statTungku").innerText = total;
    }

    const chips = {
      all: document.getElementById("chipAll"),
      active: document.getElementById("chipActive"),
      hasCoord: document.getElementById("chipHasCoord")
    };

    function setChip(mode) {
      filterMode = mode;
      Object.values(chips).forEach(btn => btn.className = "flex-1 py-1.5 rounded-lg border border-white/10 bg-white/5 text-slate-400 transition-all hover:bg-white/10");
      chips[mode].className = "flex-1 py-1.5 rounded-lg border border-emerald-500/20 bg-emerald-500/10 text-emerald-400 transition-all shadow-sm";
      renderList();
    }

    chips.all.onclick = () => setChip('all');
    chips.active.onclick = () => setChip('active');
    chips.hasCoord.onclick = () => setChip('hasCoord');
    
    document.getElementById("search").addEventListener("input", renderList);

    function renderList() {
      layer.clearLayers();
      const q = document.getElementById("search").value.toLowerCase();
      const listEl = document.getElementById("list");
      listEl.innerHTML = "";

      const filtered = allPoints.filter(p => {
        if(filterMode === 'active' && p.status !== 'active') return false;
        if(filterMode === 'hasCoord' && (!p.lat || !p.lng)) return false;
        if(q && !p.pair_code.toLowerCase().includes(q)) return false;
        return true;
      });

      document.getElementById("listCount").innerText = filtered.length;

      if(filtered.length === 0) {
        listEl.innerHTML = `<div class="text-center py-4 text-xs text-slate-500">Tidak ada data.</div>`;
        return;
      }

      filtered.forEach(p => {
        if(p.lat && p.lng) {
          const m = L.marker([p.lat, p.lng], { icon: iconBlue }).addTo(layer);
          m.bindPopup(`
            <div class="font-sans text-center">
              <b class="text-emerald-400 text-sm">${p.pair_code}</b><br>
              <span class="text-[10px] bg-white/10 px-1 rounded text-slate-300">Status: ${p.status}</span>
              ${p.note ? `<p class="text-xs mt-1 text-slate-400">"${p.note}"</p>` : ''}
              <a href="https://www.google.com/maps?q=${p.lat},${p.lng}" target="_blank" class="block mt-2 text-[10px] text-blue-400 underline hover:text-blue-300">Buka Google Maps &rarr;</a>
            </div>
          `);
          markerBySeq.set(p.seq, m);
        
          const item = document.createElement("div");
          item.className = "p-3 bg-white/5 hover:bg-white/10 border border-white/5 rounded-xl cursor-pointer transition-colors group";
          item.innerHTML = `
            <div class="flex justify-between items-start">
              <div class="font-bold text-xs text-emerald-400 group-hover:text-emerald-300 transition-colors">${p.pair_code}</div>
              <div class="text-[10px] text-slate-500 font-mono">${p.status}</div>
            </div>
            <div class="text-[10px] text-slate-400 mt-1 truncate">${p.lat.toFixed(5)}, ${p.lng.toFixed(5)}</div>
          `;
          item.onclick = () => {
            map.setView([p.lat, p.lng], 17);
            const m = markerBySeq.get(p.seq);
            if(m) m.openPopup();
          };
          listEl.appendChild(item);
        }
      });
    }
  </script>
</body>
</html>