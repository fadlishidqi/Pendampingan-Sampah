<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Login – Pendampingan Sampah</title>

  <style>
    body {
      margin: 0;
      min-height: 100vh;
      display: grid;
      place-items: center;
      font-family: Arial, sans-serif;
      background: #0b1220;
    }
    .card {
      width: min(520px, calc(100% - 32px));
      background: rgba(17,24,39,.92);
      color: #fff;
      border-radius: 16px;
      box-shadow: 0 12px 40px rgba(0,0,0,.35);
      padding: 18px;
    }
    h1 { margin: 0 0 6px; font-size: 18px; }
    p { margin: 0 0 14px; opacity: .85; font-size: 13px; }

    .grid { display:grid; grid-template-columns: 1fr; gap: 12px; }
    .divider {
      display:flex; align-items:center; gap:10px;
      opacity:.75; font-size:12px; margin: 6px 0;
    }
    .divider:before, .divider:after {
      content:""; height:1px; background: rgba(255,255,255,.12); flex:1;
    }

    label { display:block; font-size: 12px; margin: 10px 0 6px; opacity: .9; }
    input {
      width: 100%;
      padding: 10px 12px;
      border-radius: 12px;
      border: 0;
      outline: none;
      background: rgba(255,255,255,.09);
      color: #fff;
    }

    .row { display:flex; justify-content: space-between; align-items:center; gap: 10px; margin-top: 10px; }

    .btn {
      width: 100%;
      padding: 10px 12px;
      border-radius: 12px;
      border: 0;
      cursor: pointer;
      background: #e5e7eb;
      color: #111827;
      font-weight: 800;
    }
    .btn-primary { background: #22c55e; color: #0b1220; }
    .btn-dark { background: rgba(255,255,255,.10); color: #fff; border: 1px solid rgba(255,255,255,.14); }

    .error {
      margin-top: 10px;
      padding: 10px 12px;
      border-radius: 12px;
      background: rgba(239,68,68,.18);
      border: 1px solid rgba(239,68,68,.35);
      color: #fee2e2;
      font-size: 13px;
    }
    .muted { opacity: .75; font-size: 12px; margin-top: 10px; line-height: 1.4; }
    code { background: rgba(255,255,255,.12); padding: 2px 6px; border-radius: 8px; }
  </style>
</head>

<body>
  <div class="card">
    <h1>Login</h1>
    <p>Pendampingan Pengelolaan Sampah (KKN)</p>

    @if ($errors->any())
      <div class="error">{{ $errors->first() }}</div>
    @endif

    <div class="grid">
      <!-- Guest Login Button -->
      <form method="POST" action="/login/guest">
        @csrf
        <button class="btn btn-dark" type="submit">Masuk sebagai Guest (lihat peta saja)</button>
      </form>

      <div class="divider">atau login dengan akun</div>

      <!-- Email/Password Login -->
      <form method="POST" action="/login">
        @csrf

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="••••••••" required>

        <div class="row">
          <label style="display:flex; gap:8px; align-items:center; margin:0;">
            <input type="checkbox" name="remember" value="1" style="width:auto;">
            <span style="font-size:12px; opacity:.9;">Ingat saya</span>
          </label>
        </div>

        <button class="btn btn-primary" type="submit" style="margin-top:12px;">Masuk</button>
      </form>

      <div class="muted">
        Jika akun kamu admin, setelah login otomatis masuk <code>/admin/map</code> (bisa edit titik).
        <br>
        Jika bukan admin, otomatis masuk <code>/dashboard</code> (read-only).
      </div>
    </div>
  </div>
</body>
</html>
