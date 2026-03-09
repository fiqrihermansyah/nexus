<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — DTM Nexus</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-pattern {
            background-color: #006747;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(255,255,255,0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255,255,255,0.08) 0%, transparent 40%),
                radial-gradient(circle at 60% 80%, rgba(0,0,0,0.1) 0%, transparent 40%);
        }
        .card-shadow { box-shadow: 0 24px 60px rgba(0,0,0,0.12), 0 4px 16px rgba(0,0,0,0.06); }
        .input-field { border: 1.5px solid #e5e7eb; border-radius: 10px; padding: 11px 14px; font-size: 14px; width: 100%; transition: all 0.2s; background: #fafafa; }
        .input-field:focus { outline: none; border-color: #006747; box-shadow: 0 0 0 4px rgba(0,103,71,0.1); background: white; }
        .btn-login { background: #006747; color: white; border-radius: 10px; padding: 12px; font-weight: 700; font-size: 14px; width: 100%; transition: all 0.2s; letter-spacing: 0.02em; }
        .btn-login:hover { background: #005238; box-shadow: 0 6px 20px rgba(0,103,71,0.35); transform: translateY(-1px); }
        .grid-bg {
            background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }
    </style>
</head>
<body class="min-h-screen bg-pattern grid-bg flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center mb-4">
                <img src="{{ asset('images/logo4.png') }}" alt="DTM Nexus Logo" class="h-32 w-auto">
            </div>
            <h1 class="text-3xl font-bold text-white tracking-tight">DTM Nexus</h1>
            <p class="text-white/60 text-sm mt-1">Administrative Division Management System</p>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl card-shadow p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-1">Selamat datang kembali</h2>
            <p class="text-gray-400 text-sm mb-7">Masuk untuk melanjutkan ke portal</p>

            @if($errors->any())
                <div class="bg-red-50 border border-red-100 text-red-600 text-sm rounded-xl p-4 mb-5 flex items-start gap-3">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="flex-shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="/login">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5 block">Username</label>
                        <input type="text" name="username" value="{{ old('username') }}" required
                            class="input-field" placeholder="Masukkan username">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5 block">Password</label>
                        <input type="password" name="password" required
                               class="input-field" placeholder="••••••••">
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-gray-500 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-emerald-600">
                            Ingat saya
                        </label>
                    </div>
                    <button type="submit" class="btn-login">
                        Masuk ke Dashboard
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-white/40 text-xs mt-6">
            © {{ date('Y') }} DTM Nexus — Internal Administration System
        </p>
    </div>
</body>
</html>
