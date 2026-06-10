<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'HK Supervisor')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            /* ============ RED THEME ============ */
            --primary: #ef4444;
            --primary-hover: #dc2626;
            --primary-light: #f87171;
            --primary-glow: 0 0 20px rgba(239, 68, 68, 0.3);
            
            --bg-dark: #09090b;
            --bg-sidebar: #18181b;
            --bg-card: #27272a;
            
            --text-main: #fafafa;
            --text-muted: #a1a1aa;
            --text-dim: #71717a;
            
            --border: #3f3f46;
            --border-hover: #52525b;
            
            --danger: #f87171;
            
            --radius-lg: 18px;
            --radius-md: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Arial, sans-serif;
            background: var(--bg-dark);
            color: var(--text-main);
            line-height: 1.5;
        }

        .app {
            display: flex;
            min-height: 100vh;
            background: 
                radial-gradient(ellipse at 10% 20%, rgba(239, 68, 68, 0.08) 0%, transparent 50%),
                var(--bg-dark);
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: var(--bg-sidebar);
            padding: 24px 16px;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 200px;
            background: linear-gradient(180deg, rgba(239, 68, 68, 0.12) 0%, transparent 100%);
            pointer-events: none;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
            font-weight: 900;
            margin-bottom: 32px;
            padding: 0 12px;
            position: relative;
            z-index: 1;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--primary) 0%, #b91c1c 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            box-shadow: var(--primary-glow);
        }

        .brand-text {
            background: linear-gradient(135deg, #fff 0%, var(--primary-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-section {
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .nav-title {
            color: var(--text-dim);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 20px 12px 10px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border-radius: var(--radius-md);
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.2s ease;
            margin-bottom: 4px;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.04);
            color: var(--text-main);
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.08));
            color: var(--primary-light);
            border: 1px solid rgba(239, 68, 68, 0.25);
            box-shadow: var(--primary-glow);
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 24px;
            background: var(--primary);
            border-radius: 3px;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 18px;
            color: var(--primary-light);
        }

        .nav-link.active i {
            color: var(--primary-light);
        }

        .logout-btn {
            color: var(--danger);
        }

        .logout-btn i {
            color: var(--danger);
        }

        /* Main Content */
        .main {
            flex: 1;
            margin-left: 280px;
            padding: 26px 30px;
            min-height: 100vh;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--bg-sidebar);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 20px 26px;
            margin-bottom: 24px;
        }

        .topbar-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-main);
        }

        .topbar-title::before {
            content: '';
            display: inline-block;
            width: 10px;
            height: 10px;
            background: var(--primary);
            border-radius: 50%;
            margin-right: 12px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            50% { box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary) 0%, #b91c1c 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
        }

        .user-role {
            font-size: 12px;
            color: var(--text-dim);
        }

        /* Content Card */
        .content-card {
            background: var(--bg-sidebar);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px;
            animation: fadeSlideUp 0.4s ease-out;
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive */
        @media (max-width: 900px) {
            .app { flex-direction: column; }
            .sidebar { 
                width: 100%; 
                height: auto; 
                position: relative;
                border-right: none;
                border-bottom: 1px solid var(--border);
            }
            .main { margin-left: 0; }
        }

        @media (max-width: 640px) {
            .topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }
            .topbar-right { width: 100%; justify-content: space-between; }
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--border-hover); }
    </style>

    @yield('extra_styles')
</head>

<body>
<div class="app">

    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">
                <i class="fas fa-broom"></i>
            </div>
            <span class="brand-text">MGM HK</span>
        </div>

        <div class="nav-section">
            <div class="nav-title">Main</div>

            <a href="{{ route('housekeeping-supervisor.dashboard') }}" class="nav-link {{ Request::is('housekeeping-supervisor/dashboard*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                Dashboard
            </a>

            <a href="{{ route('housekeeping-supervisor.board.index') }}" class="nav-link">
                <i class="fas fa-tasks"></i>
                Daily Room Status Board
            </a>

            <a href="{{ route('housekeeping-supervisor.allocation.index') }}" class="nav-link">
                <i class="fas fa-clipboard-list"></i>
                Room Allocation
            </a>

            <a href="{{ route('housekeeping-supervisor.staff-working-today.index') }}" class="nav-link">
                <i class="fas fa-user-clock"></i>
                Staff Working Today
            </a>

            <a href="{{ route('housekeeping-supervisor.inspection.index') }}" class="nav-link">
                <i class="fas fa-clipboard-check"></i>
                Inspection Queue
            </a>
            <a href="{{ route('housekeeping-supervisor.inspectedRooms') }}" class="nav-link">
                <i class="fas fa-clipboard-check"></i>
                <span>Inspected Rooms</span>
            </a>

            <a href="{{ route('housekeeping-supervisor.dnd-pending.index') }}" class="nav-link">
                <i class="fas fa-door-closed"></i>
                DND / Pending Rooms
            </a>

            <a href="{{ route('housekeeping-supervisor.out-of-order.index') }}" class="nav-link">
                <i class="fas fa-tools"></i>
                Out of Order Rooms
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-title">Planning</div>

            <a href="{{ route('housekeeping-supervisor.hk-rota.index') }}" class="nav-link">
                <i class="fas fa-calendar-alt"></i>
                HK Rota
            </a>

            <a href="{{ route('housekeeping-supervisor.hk-rota.view') }}" class="nav-link">
                <i class="fas fa-calendar-week"></i>
                Rota View
            </a>

            <a href="{{ route('housekeeping-supervisor.holidays.calendar') }}" class="nav-link">
                <i class="fas fa-sun"></i>
                Holidays Calendar
            </a>

            <a href="{{ route('housekeeping-supervisor.reports.productivity') }}" class="nav-link">
                <i class="fas fa-chart-pie"></i>
                Productivity Report
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-title">Account</div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="main">
        <div class="topbar">
            <h1 class="topbar-title">@yield('page-title', 'Housekeeping Supervisor')</h1>
            
            <div class="topbar-right">
                <div class="user-badge">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'HK', 0, 2)) }}
                    </div>
                    <div>
                        <div class="user-name">{{ Auth::user()->name ?? 'HK Supervisor' }}</div>
                        <div class="user-role">Housekeeping</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-card">
            @yield('content')
        </div>
    </main>

</div>

@yield('scripts')

</body>
</html>