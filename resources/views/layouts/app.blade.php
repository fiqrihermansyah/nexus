<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DTM Nexus') — Admin Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --primary:#006747; --primary-light:#00855c; --primary-dark:#004f36; --accent:#E6F4EF; --accent-2:#c8e8dc; }
        * { font-family:'Plus Jakarta Sans',sans-serif; }
        .mono { font-family:'JetBrains Mono',monospace; }
        .sidebar { background:#0a0f0d; }
        .sidebar-item { transition:all 0.15s ease; }
        .sidebar-item:hover { background:rgba(0,103,71,0.3); }
        .sidebar-item.active { background:var(--primary); }
        .sidebar-logo-dot { background:var(--primary); }
        ::-webkit-scrollbar { width:5px; height:5px; }
        ::-webkit-scrollbar-track { background:#f1f5f2; }
        ::-webkit-scrollbar-thumb { background:var(--accent-2); border-radius:10px; }
        .stat-card { background:white; border:1px solid #e8f0ec; transition:all 0.2s; }
        .stat-card:hover { box-shadow:0 8px 30px rgba(0,103,71,0.08); transform:translateY(-1px); }
        .badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:999px; font-size:11px; font-weight:600; letter-spacing:0.02em; }
        .badge-pending  { background:#f3f4f6; color:#6b7280; border:1px solid #e5e7eb; }
        .badge-progress { background:#fef9c3; color:#92400e; border:1px solid #fde68a; }
        .badge-done     { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
        .badge-discard  { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
        .data-table th { font-size:11px; font-weight:600; letter-spacing:0.06em; text-transform:uppercase; color:#6b7280; }
        .data-table tr:hover td { background:#f9fdf9 !important; }
        .btn-primary { background:var(--primary); color:white; border-radius:8px; padding:8px 18px; font-size:13px; font-weight:600; transition:all 0.15s; }
        .btn-primary:hover { background:var(--primary-light); box-shadow:0 4px 14px rgba(0,103,71,0.3); }
        .btn-secondary { background:white; color:#374151; border:1px solid #e5e7eb; border-radius:8px; padding:8px 18px; font-size:13px; font-weight:500; transition:all 0.15s; }
        .btn-secondary:hover { background:#f9fafb; border-color:#d1d5db; }
        .form-input { border:1px solid #e5e7eb; border-radius:8px; padding:9px 12px; font-size:13px; transition:all 0.15s; width:100%; background:white; }
        .form-input:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(0,103,71,0.1); }
        .toast { position:fixed; bottom:24px; right:24px; z-index:9999; animation:slideUp 0.3s ease; }
        @keyframes slideUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
        .modal-backdrop { background:rgba(0,0,0,0.5); backdrop-filter:blur(2px); }
        .active-indicator { width:3px; background:white; border-radius:0 2px 2px 0; position:absolute; left:0; top:50%; transform:translateY(-50%); height:20px; }
        .page-header { background:linear-gradient(135deg,var(--accent) 0%,white 100%); }
        .timeline-dot { width:10px; height:10px; border-radius:50%; }

        /* Custom checkbox */
        .freq-check { display:none; }
        .freq-label {
            display:inline-flex; align-items:center; justify-content:center;
            width:28px; height:28px; border-radius:6px; cursor:pointer;
            border:1.5px solid #e5e7eb; background:white; transition:all 0.15s;
            font-size:11px; font-weight:700; color:#9ca3af;
        }
        .freq-check:checked + .freq-label { border-color:var(--primary); background:var(--accent); color:var(--primary); }

        /* Frequency badges in table */
        .freq-badge { display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:5px; font-size:9px; font-weight:800; letter-spacing:0.03em; }
        .freq-badge-on  { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
        .freq-badge-off { background:#f9fafb; color:#d1d5db; border:1px solid #f3f4f6; }

        /* Query code block */
        .query-block { background:#0f1117; color:#a8ff78; border-radius:10px; padding:14px 16px; font-family:'JetBrains Mono',monospace; font-size:12px; line-height:1.7; overflow-x:auto; white-space:pre-wrap; word-break:break-all; }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-gray-50"
    x-data="{
        sidebarOpen: false,
        toastMsg: '',
        toastType: 'success',
        toastVisible: false
    }"
    x-init="
        @if(session('success')) toastMsg = '{{ addslashes(session('success')) }}'; toastType = 'success'; toastVisible = true; setTimeout(() => toastVisible = false, 4000); @endif
        @if(session('error'))   toastMsg = '{{ addslashes(session('error')) }}';   toastType = 'error';   toastVisible = true; setTimeout(() => toastVisible = false, 4000); @endif
    ">

<div class="flex h-full">
    <!-- Sidebar -->
    <aside class="sidebar w-60 flex-shrink-0 flex flex-col h-screen sticky top-0 overflow-y-auto">
        <div class="p-5 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg sidebar-logo-dot flex items-center justify-center">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <div class="text-white font-bold text-sm tracking-tight">DTM Nexus</div>
                    <div class="text-white/40 text-xs">Administration System</div>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-0.5">
            <p class="text-white/30 text-xs font-semibold uppercase tracking-widest px-3 mb-2 mt-1">Main Menu</p>

            @php
            $navItems = [
                ['route'=>'dashboard',         'label'=>'Dashboard',      'icon'=>'<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'],
                ['route'=>'memo.index',        'label'=>'Memo Request',   'icon'=>'<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>'],
                ['route'=>'memo.create',       'label'=>'Tambah Memo',    'icon'=>'<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>'],
                ['route'=>'job-schedule.index','label'=>'Job Schedule',   'icon'=>'<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>'],
            ];
            @endphp

            @foreach($navItems as $item)
            @php
                $isActive = request()->routeIs($item['route']) ||
                    ($item['route'] === 'job-schedule.index' && request()->routeIs('job-schedule.*'));
            @endphp
            <a href="{{ route($item['route']) }}"
               class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-lg relative {{ $isActive ? 'active' : '' }}">
                @if($isActive)<span class="active-indicator"></span>@endif
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="{{ $isActive ? 'white' : 'rgba(255,255,255,0.5)' }}"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    {!! $item['icon'] !!}
                </svg>
                <span class="text-sm {{ $isActive ? 'text-white font-semibold' : 'text-white/60' }}">{{ $item['label'] }}</span>
            </a>
            @endforeach

            @if(auth()->user()->isAdmin())
            <p class="text-white/30 text-xs font-semibold uppercase tracking-widest px-3 mb-2 mt-4">Admin</p>
            <a href="{{ route('users.index') }}"
               class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-lg relative {{ request()->routeIs('users.*') ? 'active' : '' }}">
                @if(request()->routeIs('users.*'))<span class="active-indicator"></span>@endif
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="{{ request()->routeIs('users.*') ? 'white' : 'rgba(255,255,255,0.5)' }}"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                </svg>
                <span class="text-sm {{ request()->routeIs('users.*') ? 'text-white font-semibold' : 'text-white/60' }}">User Management</span>
            </a>
            @endif
        </nav>

        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-emerald-700 flex items-center justify-center text-white text-xs font-bold">
                    {{ substr(auth()->user()->name, 0, 2) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-white text-xs font-semibold truncate">{{ auth()->user()->name }}</div>
                    <div class="text-white/40 text-xs capitalize">{{ auth()->user()->role }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg sidebar-item text-white/50 hover:text-white text-sm">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-100 px-6 py-3.5 flex items-center justify-between sticky top-0 z-40">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <input type="text" placeholder="Cari memo, job, divisi..." class="pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400 w-64 transition-all">
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button class="relative p-2 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
                </button>
                <div class="flex items-center gap-2 pl-3 border-l border-gray-100">
                    <div class="w-8 h-8 rounded-full bg-emerald-700 flex items-center justify-center text-white text-xs font-bold">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                    <div class="hidden sm:block">
                        <div class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-400 capitalize">{{ auth()->user()->role }}</div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto">
            @yield('content')
        </main>
    </div>
</div>

<!-- Toast -->
<div class="toast" x-show="toastVisible" x-transition>
    <div :class="toastType === 'success' ? 'bg-emerald-700' : 'bg-red-600'"
         class="flex items-center gap-3 text-white px-5 py-3.5 rounded-xl shadow-2xl text-sm font-medium">
        <svg x-show="toastType==='success'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        <svg x-show="toastType==='error'"   width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span x-text="toastMsg"></span>
        <button @click="toastVisible=false" class="ml-2 opacity-70 hover:opacity-100 text-lg leading-none">×</button>
    </div>
</div>

@stack('scripts')
</body>
</html>
