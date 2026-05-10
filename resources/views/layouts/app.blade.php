<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Virtual Queue System') — ESPRIT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:   #1A2B5E;
            --blue:   #2563EB;
            --teal:   #0891B2;
            --accent: #06B6D4;
            --green:  #059669;
            --orange: #D97706;
            --red:    #DC2626;
            --gray:   #64748B;
            --light:  #F8FAFC;
            --white:  #FFFFFF;
            --border: #E2E8F0;
            --shadow: 0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.06);
            --shadow-md: 0 4px 6px rgba(0,0,0,.07), 0 2px 4px rgba(0,0,0,.06);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--light);
            color: #0F172A;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── NAVBAR ── */
        .navbar {
            background: var(--navy);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            height: 60px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,.25);
        }
        .navbar-brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .navbar-brand span.accent { color: var(--accent); }
        .navbar-nav { margin-left: auto; display: flex; align-items: center; gap: .25rem; }
        .nav-link {
            color: rgba(255,255,255,.8);
            text-decoration: none;
            padding: .4rem .75rem;
            border-radius: 6px;
            font-size: .9rem;
            font-weight: 500;
            transition: background .15s, color .15s;
        }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,.12); color: #fff; }
        .nav-badge {
            background: var(--accent);
            color: #fff;
            border-radius: 999px;
            font-size: .7rem;
            padding: 0 .35rem;
            margin-left: .25rem;
        }

        /* ── SIDEBAR (admin/agent) ── */
        .layout { display: flex; flex: 1; }
        .sidebar {
            width: 240px;
            background: var(--white);
            border-right: 1px solid var(--border);
            padding: 1.25rem .75rem;
            min-height: calc(100vh - 60px);
            position: sticky;
            top: 60px;
            align-self: flex-start;
        }
        .sidebar-section { font-size: .7rem; font-weight: 700; color: var(--gray); text-transform: uppercase; letter-spacing: .08em; padding: .5rem .75rem .25rem; margin-top: .75rem; }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .55rem .75rem;
            border-radius: 8px;
            text-decoration: none;
            color: #334155;
            font-size: .875rem;
            font-weight: 500;
            transition: background .15s, color .15s;
        }
        .sidebar-link:hover  { background: var(--light); color: var(--navy); }
        .sidebar-link.active { background: #EFF6FF; color: var(--blue); }
        .sidebar-icon { width: 18px; text-align: center; opacity: .7; }

        /* ── MAIN CONTENT ── */
        .main { flex: 1; padding: 1.75rem; max-width: 1200px; margin: 0 auto; width: 100%; }

        /* ── CARDS ── */
        .card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }
        .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            font-weight: 600;
            font-size: .95rem;
            color: var(--navy);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-body { padding: 1.25rem; }

        /* ── STAT CARDS ── */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .stat-card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.25rem;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }
        .stat-card .label { font-size: .78rem; color: var(--gray); font-weight: 600; text-transform: uppercase; letter-spacing: .05em; }
        .stat-card .value { font-size: 2rem; font-weight: 700; color: var(--navy); line-height: 1.1; margin: .25rem 0; }
        .stat-card .sub   { font-size: .78rem; color: var(--gray); }
        .stat-card.blue   { border-top: 3px solid var(--blue); }
        .stat-card.teal   { border-top: 3px solid var(--teal); }
        .stat-card.green  { border-top: 3px solid var(--green); }
        .stat-card.orange { border-top: 3px solid var(--orange); }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .5rem 1rem;
            border-radius: 8px;
            font-size: .875rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: opacity .15s, transform .1s;
        }
        .btn:active { transform: scale(.98); }
        .btn-primary { background: var(--blue);   color: #fff; }
        .btn-teal    { background: var(--teal);   color: #fff; }
        .btn-success { background: var(--green);  color: #fff; }
        .btn-warning { background: var(--orange); color: #fff; }
        .btn-danger  { background: var(--red);    color: #fff; }
        .btn-outline { background: transparent; border: 1.5px solid var(--border); color: #334155; }
        .btn:hover   { opacity: .88; }
        .btn-sm { padding: .3rem .65rem; font-size: .8rem; }

        /* ── FORMS ── */
        .form-group { margin-bottom: 1rem; }
        .form-label { display: block; font-size: .85rem; font-weight: 600; color: #334155; margin-bottom: .35rem; }
        .form-control {
            width: 100%;
            padding: .55rem .85rem;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: .9rem;
            font-family: inherit;
            color: #0F172A;
            background: #fff;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
        .form-control.is-invalid { border-color: var(--red); }
        .invalid-feedback { color: var(--red); font-size: .8rem; margin-top: .2rem; }
        select.form-control { cursor: pointer; }

        /* ── TABLES ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        thead th { background: #F1F5F9; padding: .65rem 1rem; text-align: left; font-size: .75rem; font-weight: 700; color: var(--gray); text-transform: uppercase; letter-spacing: .06em; border-bottom: 1px solid var(--border); }
        tbody td { padding: .75rem 1rem; border-bottom: 1px solid #F1F5F9; vertical-align: middle; }
        tbody tr:hover { background: #FAFBFF; }
        tbody tr:last-child td { border-bottom: none; }

        /* ── BADGES ── */
        .badge { display: inline-flex; align-items: center; padding: .2rem .6rem; border-radius: 999px; font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
        .badge-success  { background: #D1FAE5; color: #065F46; }
        .badge-warning  { background: #FEF3C7; color: #92400E; }
        .badge-primary  { background: #DBEAFE; color: #1E40AF; }
        .badge-danger   { background: #FEE2E2; color: #991B1B; }
        .badge-secondary{ background: #F1F5F9; color: #475569; }
        .badge-open     { background: #D1FAE5; color: #065F46; }
        .badge-closed   { background: #FEE2E2; color: #991B1B; }
        .badge-paused   { background: #FEF3C7; color: #92400E; }

        /* ── ALERTS ── */
        .alert { padding: .75rem 1rem; border-radius: 8px; font-size: .875rem; margin-bottom: 1rem; border-left: 4px solid; }
        .alert-success { background: #F0FDF4; border-color: var(--green); color: #166534; }
        .alert-error   { background: #FEF2F2; border-color: var(--red);   color: #991B1B; }
        .alert-info    { background: #EFF6FF; border-color: var(--blue);  color: #1E40AF; }
        .alert-warning { background: #FFFBEB; border-color: var(--orange);color: #92400E; }

        /* ── QUEUE CARDS ── */
        .queue-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem; }
        .queue-card {
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: .75rem;
        }
        .queue-card .name { font-weight: 700; font-size: 1.05rem; color: var(--navy); }
        .queue-card .meta { display: flex; gap: .5rem; align-items: center; flex-wrap: wrap; font-size: .82rem; color: var(--gray); }
        .queue-card .actions { margin-top: auto; }

        /* ── TICKET CARD ── */
        .ticket-hero {
            background: linear-gradient(135deg, var(--navy), var(--blue));
            color: #fff;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .ticket-hero .position { font-size: 5rem; font-weight: 900; line-height: 1; }
        .ticket-hero .label    { font-size: .9rem; opacity: .75; margin-bottom: .25rem; }
        .ticket-hero .queue    { font-size: 1.1rem; font-weight: 600; margin-top: .25rem; }

        /* ── PAGE HEADER ── */
        .page-header { margin-bottom: 1.5rem; }
        .page-title  { font-size: 1.5rem; font-weight: 700; color: var(--navy); }
        .page-sub    { font-size: .875rem; color: var(--gray); margin-top: .2rem; }

        /* ── UTILITY ── */
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: .5rem; }
        .gap-3 { gap: .75rem; }
        .mt-1 { margin-top: .25rem; }
        .mt-2 { margin-top: .5rem; }
        .mt-3 { margin-top: .75rem; }
        .mb-1 { margin-bottom: .25rem; }
        .mb-3 { margin-bottom: .75rem; }
        .text-sm { font-size: .85rem; }
        .text-gray { color: var(--gray); }
        .text-center { text-align: center; }
        .w-full { width: 100%; }
        .empty-state { text-align: center; padding: 3rem; color: var(--gray); }
        .empty-state .icon { font-size: 2.5rem; margin-bottom: .5rem; }

        /* ── PAGINATION ── */
        .pagination { display: flex; gap: .25rem; justify-content: center; margin-top: 1.5rem; }
        .pagination a, .pagination span {
            padding: .4rem .7rem;
            border-radius: 6px;
            font-size: .85rem;
            text-decoration: none;
            color: #334155;
            border: 1px solid var(--border);
        }
        .pagination span.active { background: var(--blue); color: #fff; border-color: var(--blue); }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main { padding: 1rem; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>
    @yield('head')
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar">
    <a href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isAgent() ? route('agent.dashboard') : route('customer.dashboard'))) : route('login') }}" class="navbar-brand">
        <span>🎟</span> Virtual<span class="accent">Queue</span>
    </a>
    @auth
    <div class="navbar-nav">
        @if(auth()->user()->isCustomer())
            <a href="{{ route('customer.notifications') }}" class="nav-link">
                🔔 Notifications
                @if(auth()->user()->unreadNotifications()->count() > 0)
                    <span class="nav-badge">{{ auth()->user()->unreadNotifications()->count() }}</span>
                @endif
            </a>
            <a href="{{ route('customer.history') }}" class="nav-link">📋 History</a>
        @endif
        <a href="#" class="nav-link text-gray">{{ auth()->user()->name }}</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="nav-link" style="background:none;border:none;cursor:pointer">🚪 Logout</button>
        </form>
    </div>
    @endauth
</nav>

{{-- LAYOUT --}}
<div class="layout">
    @hasSection('sidebar')
        <aside class="sidebar">@yield('sidebar')</aside>
    @endif
    <main class="main">
        {{-- Flash messages --}}
        @foreach(['success','error','info','warning'] as $type)
            @if(session($type))
                <div class="alert alert-{{ $type }}">{{ session($type) }}</div>
            @endif
        @endforeach
        @yield('content')
    </main>
</div>

@yield('scripts')
</body>
</html>
