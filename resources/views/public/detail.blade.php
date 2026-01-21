<!doctype html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ $item['title'] ?? 'Detail' }} – Pendampingan Sampah</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>body { font-family: 'Inter', sans-serif; }</style>
</head>

<body class="bg-slate-950 text-slate-200 antialiased min-h-screen flex flex-col selection:bg-emerald-500/30 selection:text-emerald-200">

  <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
    <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-emerald-600/10 rounded-full blur-[100px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[100px]"></div>
  </div>

  <nav class="sticky top-0 z-50 border-b border-white/5 bg-slate-950/80 backdrop-blur-md">
    <div class="max-w-4xl mx-auto px-4 h-16 flex items-center justify-between">
      <a href="/" class="flex items-center gap-2 group text-slate-300 hover:text-white transition-colors">
        <div class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center group-hover:bg-emerald-600 group-hover:border-emerald-500 transition-all">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
        </div>
        <span class="text-sm font-bold">Kembali ke Dashboard</span>
      </a>
      <span class="px-3 py-1 rounded-full border border-white/10 bg-white/5 text-xs text-slate-400">
        {{ $category }}
      </span>
    </div>
  </nav>

  <main class="flex-1 max-w-3xl mx-auto px-4 py-12 w-full">
    
    <div class="bg-slate-900/50 border border-white/10 rounded-3xl p-1 shadow-2xl backdrop-blur-sm">
      
      @if($type === 'dokumentasi' && !empty($item['image']))
        <div class="rounded-t-2xl overflow-hidden border-b border-white/5 relative group">
          <img src="{{ asset(ltrim($item['image'], '/')) }}" 
               alt="{{ $item['title'] }}" 
               class="w-full h-auto max-h-[500px] object-cover">
          <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent"></div>
        </div>
      @endif

      <div class="p-8 md:p-10 space-y-6">
        
        <div class="space-y-2">
          <h1 class="text-3xl md:text-4xl font-black text-white leading-tight tracking-tight">
            {{ $item['title'] ?? 'Tanpa Judul' }}
          </h1>
          <div class="h-1 w-20 bg-gradient-to-r from-emerald-500 to-blue-500 rounded-full"></div>
        </div>

        <div class="prose prose-invert prose-lg max-w-none text-slate-300 leading-relaxed">
          {{-- Menampilkan text dengan baris baru (nl2br) --}}
          {!! nl2br(e($item['body'] ?? 'Tidak ada konten.')) !!}
        </div>

      </div>
    </div>

    <div class="mt-12 text-center border-t border-white/5 pt-8">
      <p class="text-slate-500 text-xs">Pendampingan Pengelolaan Sampah • Desa Wiroditan</p>
    </div>

  </main>

</body>
</html>