<!doctype html>
<html lang="id" class="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Login – Pendampingan Sampah</title>

  <script src="https://cdn.tailwindcss.com"></script>
  
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Inter', sans-serif; }
    
    /* Latar Belakang Gelap dengan Nuansa Biru Admin */
    .bg-admin {
      background-color: #0f172a; /* Slate 900 */
      background-image: 
        radial-gradient(at 0% 0%, hsla(222,47%,11%,1) 0, transparent 50%), 
        radial-gradient(at 50% 0%, hsla(243,75%,15%,1) 0, transparent 50%), 
        radial-gradient(at 100% 100%, hsla(217,33%,17%,1) 0, transparent 50%);
    }
  </style>
</head>

<body class="bg-admin text-white min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

  <div class="absolute top-[-5%] right-[10%] w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none animate-pulse"></div>
  <div class="absolute bottom-[-5%] left-[10%] w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

  <div class="relative w-full max-w-sm bg-slate-900/60 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl p-8 z-10">
    
    <div class="mb-8 text-center">
      <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-blue-500/20 text-blue-400 mb-4 border border-blue-500/20">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
      </div>
      <h1 class="text-2xl font-bold tracking-tight text-white">Login Admin</h1>
      <p class="text-xs text-slate-400 mt-2">Pendampingan Pengelolaan Sampah (KKN)</p>
    </div>

    @if ($errors->any())
      <div class="mb-6 p-3 rounded-xl bg-red-500/10 border border-red-500/20 text-red-300 text-xs flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
        <span>{{ $errors->first() }}</span>
      </div>
    @endif

    <form method="POST" action="/admin/login" class="space-y-6">
      @csrf

      <div>
        <label class="block text-xs font-medium text-slate-300 mb-1.5 ml-1">Email Address</label>
        <div class="relative group">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-4 w-4 text-slate-500 group-focus-within:text-blue-400 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
              <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
            </svg>
          </div>
          <input type="email" name="email" value="{{ old('email') }}" required 
            class="w-full pl-10 pr-4 py-2.5 bg-slate-950/50 border border-slate-700/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 text-sm text-white placeholder-slate-600 transition-all shadow-inner"
            placeholder="admin@example.com">
        </div>
      </div>

      <div>
        <label class="block text-xs font-medium text-slate-300 mb-1.5 ml-1">Password</label>
        <div class="relative group">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-4 w-4 text-slate-500 group-focus-within:text-blue-400 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
            </svg>
          </div>

          <input type="password" name="password" id="passwordInput" required 
            class="w-full pl-10 pr-10 py-2.5 bg-slate-950/50 border border-slate-700/50 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 text-sm text-white placeholder-slate-600 transition-all shadow-inner"
            placeholder="••••••••">
          
          <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-blue-400 focus:outline-none transition-colors">
            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <svg id="eyeSlashIcon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            </svg>
          </button>
        </div>
      </div>

      <button type="submit" 
        class="w-full py-2.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/25 transform hover:-translate-y-0.5 transition-all duration-200 text-sm">
        Masuk Admin
      </button>
    </form>
  </div>

  <script>
    function togglePassword() {
      const input = document.getElementById('passwordInput');
      const eyeIcon = document.getElementById('eyeIcon');
      const eyeSlashIcon = document.getElementById('eyeSlashIcon');
      
      if (input.type === 'password') {
        input.type = 'text';
        eyeIcon.classList.add('hidden');
        eyeSlashIcon.classList.remove('hidden');
      } else {
        input.type = 'password';
        eyeIcon.classList.remove('hidden');
        eyeSlashIcon.classList.add('hidden');
      }
    }
  </script>

</body>
</html>