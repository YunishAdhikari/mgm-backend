@php
    $user = auth()->user();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DoP Dashboard') - MGM One</title>
    <link rel="icon" type="image/png" href="{{ asset('myapp.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @yield('styles')

    <style>
        :root {
            --sidebar-width: 290px;
            --bg-body: #060607;
            --bg-sidebar: #09090b;
            --bg-card: #121214;
            --bg-soft: #18181b;
            --bg-input: #1f1f23;
            
            /* Overhauled Brand Pure Red System */
            --primary: #ef4444;
            --primary-2: #dc2626;
            --primary-3: #991b1b;
            
            --text-main: #ffffff;
            --text-muted: #a1a1aa;
            --text-dim: #71717a;
            --border: #27272a;
            --green: #22c55e;
            --orange: #f97316;
            --red: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Exo 2', sans-serif;
            min-height: 100vh;
            /* Updated Ambient Backlight to Red/Black */
            background:
                radial-gradient(circle at 10% 10%, rgba(239, 68, 68, 0.18), transparent 35%),
                radial-gradient(circle at 90% 20%, rgba(153, 27, 27, 0.12), transparent 30%),
                radial-gradient(circle at 70% 95%, rgba(239, 68, 68, 0.08), transparent 35%),
                var(--bg-body);
            color: var(--text-main);
            overflow-x: hidden;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button {
            font-family: inherit;
            cursor: pointer;
            border: none;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 100;
            background: rgba(9, 9, 11, 0.94);
            backdrop-filter: blur(18px);
            border-right: 1px solid rgba(239, 68, 68, 0.15);
            padding: 22px 16px;
            overflow-y: auto;
            transition: .3s ease;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 10px 10px 26px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            margin-bottom: 22px;
        }

        .brand-icon {
            width: 56px;
            height: 56px;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--primary), var(--primary-2), var(--primary-3));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #ffffff;
            box-shadow: 0 0 35px rgba(239, 68, 68, 0.35);
        }

        .brand h1 {
            font-size: 25px;
            font-weight: 900;
            line-height: 1;
        }

        .brand p {
            margin-top: 5px;
            color: var(--primary);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 1.4px;
            text-transform: uppercase;
        }

        .section-title {
            color: var(--text-dim);
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 22px 12px 10px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 14px 15px;
            border-radius: 14px;
            color: var(--text-muted);
            font-weight: 800;
            margin-bottom: 7px;
            border: 1px solid transparent;
            transition: .22s ease;
        }

        .nav-link i {
            width: 22px;
            text-align: center;
            font-size: 16px;
        }

        .nav-link:hover {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.3);
            color: white;
            transform: translateX(5px);
        }

        .nav-link.active {
            color: white;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.95), rgba(153, 27, 27, 0.85));
            border: 1px solid rgba(239, 68, 68, 0.4);
            box-shadow: 0 15px 40px rgba(239, 68, 68, 0.25);
        }

        .main {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .topbar {
            height: 82px;
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(6, 6, 7, 0.75);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 34px;
        }

        .mobile-btn {
            display: none;
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: rgba(239, 68, 68, 0.14);
            color: white;
            border: 1px solid rgba(239, 68, 68, 0.35);
            font-size: 18px;
        }

        .page-title strong {
            display: block;
            font-size: 25px;
            font-weight: 900;
        }

        .page-title span {
            display: block;
            margin-top: 4px;
            color: var(--text-dim);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            border-radius: 999px;
            padding: 8px 14px 8px 8px;
        }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 900;
        }

        .user-pill strong {
            display: block;
            font-size: 13px;
        }

        .user-pill span {
            display: block;
            color: var(--text-dim);
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .logout-btn {
            padding: 12px 18px;
            border-radius: 14px;
            background: rgba(239, 68, 68, 0.12);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.35);
            font-weight: 900;
            transition: .22s ease;
        }

        .logout-btn:hover {
            background: var(--red);
            color: white;
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.35);
        }

        .content {
            padding: 34px;
        }

        .card {
            background: linear-gradient(180deg, #141416, #0a0a0c);
            border: 1px solid rgba(239, 68, 68, 0.15);
            border-radius: 24px;
            padding: 26px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.55);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 22px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: linear-gradient(180deg, #141416, #0a0a0c);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 22px;
            padding: 24px;
            transition: .25s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            right: -50px;
            top: -50px;
            width: 140px;
            height: 140px;
            background: radial-gradient(circle, rgba(239, 68, 68, 0.15), transparent 70%);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: rgba(239, 68, 68, 0.5);
            box-shadow: 0 20px 60px rgba(239, 68, 68, 0.15);
        }

        .stat-icon {
            width: 54px;
            height: 54px;
            border-radius: 17px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 18px;
        }

        .stat-icon.red { background: rgba(239, 68, 68, 0.2); color: #f87171; }
        .stat-icon.green { background: rgba(34, 197, 94, 0.14); color: #4ade80; }
        .stat-icon.orange { background: rgba(249, 115, 22, 0.14); color: #fb923c; }
        .stat-icon.dark { background: rgba(239, 68, 68, 0.15); color: #fca5a5; }

        .stat-value {
            font-size: 32px;
            font-weight: 900;
        }

        .stat-label {
            margin-top: 5px;
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 13px 20px;
            border-radius: 14px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .8px;
            font-size: 13px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            color: white;
            box-shadow: 0 15px 35px rgba(239, 68, 68, 0.25);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            color: white;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(6px);
            z-index: 90;
        }

        @media(max-width: 1024px) {
            .sidebar {
                transform: translateX(-110%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.open {
                display: block;
            }

            .main {
                margin-left: 0;
            }

            .mobile-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .topbar {
                padding: 0 20px;
            }

            .content {
                padding: 22px;
            }

            .user-pill {
                display: none;
            }
        }

        @media(max-width: 600px) {
            .page-title strong {
                font-size: 19px;
            }

            .topbar-right {
                gap: 8px;
            }

            .logout-btn span {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="layout">

    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div>
                <h1>MGM One</h1>
                <p>Group Operations</p>
            </div>
        </div>

        <div class="section-title">Overview</div>

        <a href="{{ route('dop.dashboard') }}"
           class="nav-link {{ request()->routeIs('dop.dashboard') ? 'active' : '' }}">
            <i class="fas fa-gauge-high"></i>
            <span>DoP Dashboard</span>
        </a>

        <div class="section-title">Hotel Monitoring</div>

        <a href="{{ route('dop.hotels.overview') }}"
           class="nav-link {{ request()->routeIs('dop.hotels.overview') ? 'active' : '' }}">
            <i class="fas fa-hotel"></i>
            <span>Hotels Overview</span>
        </a>
        <a href="{{ route('dop.maintenance.index') }}"
           class="nav-link {{ request()->routeIs('dop.maintenance.*') ? 'active' : '' }}">
            <i class="fas fa-screwdriver-wrench"></i>
            <span>Maintenance</span>
        </a>
        <a href="{{ route('dop.complaints.index') }}"
           class="nav-link {{ request()->routeIs('dop.complaints.*') ? 'active' : '' }}">
            <i class="fas fa-triangle-exclamation"></i>
            <span>Complaints</span>
        </a>

        <a href="{{ route('dop.staffing.index') }}"
           class="nav-link {{ request()->routeIs('dop.staffing.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>Staffing</span>
        </a>

        <a href="{{ route('dop.housekeeping.index') }}"
           class="nav-link {{ request()->routeIs('dop.housekeeping.*') ? 'active' : '' }}">
            <i class="fas fa-broom"></i>
            <span>Housekeeping</span>
        </a>
        
        <div class="section-title">Reports</div>

        <a href="{{ route('dop.reports.index') }}"
   class="nav-link {{ request()->routeIs('dop.reports.*') ? 'active' : '' }}">
    <i class="fas fa-chart-column"></i>
    <span>Group Reports</span>
</a>
    </aside>

    <main class="main">
        <div class="topbar">
            <button type="button" class="mobile-btn" onclick="openSidebar()">
                <i class="fas fa-bars"></i>
            </button>

            <div class="page-title">
                <strong>@yield('page-title', 'Director of Operations')</strong>
                <span>@yield('subtitle', 'MGM One Group Dashboard')</span>
            </div>

            <div class="topbar-right">
                <div class="user-pill">
                    <div class="avatar">
                        {{ strtoupper(substr($user->name ?? 'D', 0, 1)) }}
                    </div>
                    <div>
                        <strong>{{ $user->name ?? 'DoP User' }}</strong>
                        <span>{{ $user->role->name ?? 'Director of Operations' }}</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="content">
            @yield('content')
        </div>
    </main>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('open');
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
    }

    overlay?.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSidebar();
        }
    });
</script>

@yield('scripts')

</body>
</html>