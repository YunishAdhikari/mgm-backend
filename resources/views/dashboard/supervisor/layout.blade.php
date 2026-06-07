<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('myapp.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #8b5cf6;
            --primary-hover: #a78bfa;
            --secondary: #ec4899;
            --accent: #06b6d4;
            
            --bg-dark: #09090b;
            --bg-sidebar: #18181b;
            --bg-card: #27272a;
            --bg-input: #1c1c1f;
            
            --text-main: #fafafa;
            --text-muted: #a1a1aa;
            --text-dim: #71717a;
            
            --border: #3f3f46;
            --border-light: #52525b;
            
            --success: #10b981;
            --danger: #ef4444;
            
            --glow: 0 0 20px rgba(139, 92, 246, 0.3);
            --glow-accent: 0 0 20px rgba(236, 72, 153, 0.3);
            
            --shadow-lg: 0 10px 30px rgba(0,0,0,0.5);
            --radius-lg: 1.5rem;
            --radius-md: 1rem;
            --radius-sm: 0.75rem;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-dark);
            color: var(--text-main);
            line-height: 1.5;
        }

        .layout {
            display: flex;
            min-height: 100vh;
            background: 
                radial-gradient(ellipse at 10% 20%, rgba(139, 92, 246, 0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 90% 80%, rgba(236, 72, 153, 0.06) 0%, transparent 50%),
                var(--bg-dark);
        }

        /* --- Sidebar --- */
        .sidebar {
            width: 280px;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            padding: 24px 16px;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: transform 0.3s ease, background 0.3s;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 150px;
            background: linear-gradient(180deg, rgba(139, 92, 246, 0.1) 0%, transparent 100%);
            pointer-events: none;
        }

        .logo {
            font-size: 32px;
            font-weight: 800;
            background: linear-gradient(135deg, #fff 0%, #a78bfa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 4px;
            letter-spacing: -0.02em;
            padding-left: 12px;
        }

        .role-label {
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 32px;
            padding-left: 12px;
        }

        .menu-title {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin: 28px 16px 10px;
        }

        .menu {
            display: flex;
            align-items: center;
            gap: 14px;
            color: var(--text-muted);
            text-decoration: none;
            padding: 14px 16px;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 15px;
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }

        .menu::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(139, 92, 246, 0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }

        .menu:hover::before {
            transform: translateX(100%);
        }

        .menu i {
            width: 20px;
            text-align: center;
            font-size: 18px;
            transition: transform 0.2s ease;
        }

        .menu:hover i {
            transform: scale(1.1);
        }

        .menu:hover {
            background: rgba(255,255,255,0.03);
            color: var(--text-main);
        }

        .menu.active {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.15), rgba(236, 72, 153, 0.1));
            color: var(--primary-hover);
            border: 1px solid rgba(139, 92, 246, 0.3);
            box-shadow: var(--glow);
        }

        .menu.active i {
            color: var(--primary-hover);
        }

        .menu-dot {
            position: absolute;
            right: 16px;
            width: 6px;
            height: 6px;
            background: var(--secondary);
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.2s, box-shadow 0.2s;
        }

        .menu.active .menu-dot {
            opacity: 1;
            box-shadow: var(--glow-accent);
        }

        /* --- Main Content --- */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 24px;
            min-height: 100vh;
        }

        .topbar {
            background: var(--bg-card);
            padding: 24px 32px;
            border-radius: var(--radius-lg);
            margin-bottom: 32px;
            border: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }

        .topbar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        }

        .topbar-text h2 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 4px;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, #fff 0%, #a1a1aa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .topbar-text p {
            color: var(--text-muted);
            font-size: 15px;
            font-weight: 500;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid rgba(239, 68, 68, 0.3);
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            padding: 14px 24px;
            border-radius: var(--radius-md);
            font-weight: 700;
            font-size: 15px;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.25s ease;
            white-space: nowrap;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }

        /* --- Mobile Header --- */
        .mobile-header {
            display: none;
            background: var(--bg-sidebar);
            padding: 16px 20px;
            position: sticky;
            top: 0;
            z-index: 90;
            border-bottom: 1px solid var(--border);
            margin: -24px -24px 24px;
        }

        .mobile-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .menu-toggle {
            background: var(--bg-card);
            border: 1px solid var(--border);
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 20px;
            color: var(--text-main);
            transition: all 0.2s;
        }

        .menu-toggle:hover {
            border-color: var(--primary);
            box-shadow: var(--glow);
        }

        .mobile-logo {
            font-size: 24px;
            font-weight: 800;
            background: linear-gradient(135deg, #fff 0%, #a78bfa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* --- Overlay --- */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            z-index: 99;
            backdrop-filter: blur(4px);
        }

        /* --- Scrollbar --- */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-dark);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-dim);
        }

        /* --- Responsive --- */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-header {
                display: block;
            }

            .sidebar-overlay.open {
                display: block;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
                padding: 20px;
            }

            .logout-btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 640px) {
            .topbar-text h2 {
                font-size: 22px;
            }

            .topbar {
                padding: 20px;
                border-radius: var(--radius-md);
            }

            .menu {
                padding: 12px 14px;
                font-size: 14px;
            }

            .logo {
                font-size: 26px;
            }
        }

        /* --- Animations --- */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .topbar {
            animation: slideIn 0.4s ease-out;
        }
    </style>
</head>

<body>

<div class="layout">
    <!-- Mobile Header -->
    <div class="mobile-header">
        <div class="mobile-header-content">
            <button class="menu-toggle" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="mobile-logo">MGRH</div>
            <div style="width: 44px;"></div>
        </div>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <div class="logo">MGRH</div>
        <div class="role-label">Supervisor Panel</div>

        @php
        $department = strtolower(auth()->user()->department->name ?? '');
        @endphp

        <div class="menu-title">Main</div>

        <a href="{{ route('supervisor.dashboard') }}" class="menu {{ request()->routeIs('supervisor.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge"></i>
            <span>Dashboard</span>
            <span class="menu-dot"></span>
        </a>

        <a href="{{ route('supervisor.holidays.calendar') }}" class="menu {{ request()->routeIs('supervisor.holidays.calendar') ? 'active' : '' }}">
            <i class="fa-solid fa-calendar-days"></i>
            <span>Holiday Calendar</span>
            <span class="menu-dot"></span>
        </a>

        <a href="{{ route('supervisor.rota.index') }}" class="menu {{ request()->routeIs('supervisor.rota.index') ? 'active' : '' }}">
            <i class="fa-solid fa-clipboard-list"></i>
            <span>Rota Maker</span>
            <span class="menu-dot"></span>
        </a>

        <a href="{{ route('supervisor.rota.view') }}" class="menu {{ request()->routeIs('supervisor.rota.view') ? 'active' : '' }}">
            <i class="fa-solid fa-table"></i>
            <span>View Rota</span>
            <span class="menu-dot"></span>
        </a>

        @if(in_array($department, ['reception', 'front office']))
            <div class="menu-title">Reception</div>

            <a href="{{ route('reception.restaurant.bookings.index') }}" class="menu {{ request()->routeIs('reception.restaurant.bookings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-utensils"></i>
                <span>Restaurant Bookings</span>
            </a>

            <a href="#" class="menu">
                <i class="fa-solid fa-spa"></i>
                <span>Spa Bookings</span>
            </a>
        @endif

        @if(in_array($department, ['f&b', 'fb', 'f and b', 'food and beverage']))
            <div class="menu-title">F&B</div>

            <a href="{{ route('fb.restaurant.bookings.index') }}" class="menu {{ request()->routeIs('fb.restaurant.bookings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-utensils"></i>
                <span>Restaurant Bookings</span>
            </a>

            <a href="{{ route('fb.floor-plan') }}" class="menu {{ request()->routeIs('fb.floor-plan') ? 'active' : '' }}">
                <i class="fa-solid fa-chair"></i>
                <span>Floor Plan</span>
            </a>
        @endif

        {{-- <a href="{{ route('housekeeping.board.index') }}" class="nav-link">
            <i class="fa-solid fa-broom"></i>
            <span>Housekeeping Board</span>
        </a> --}}
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div class="topbar-text">
                <h2>Supervisor Panel</h2>
                <p>Manage department rota drafts and view holiday availability.</p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </button>
            </form>
        </div>

        @yield('content')
    </main>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        
        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');
        
        if (overlay.classList.contains('open')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }

    document.addEventListener('click', function(e) {
        const sidebar = document.getElementById('sidebar');
        const toggle = document.querySelector('.menu-toggle');
        
        if (window.innerWidth <= 1024) {
            if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('open');
                document.querySelector('.sidebar-overlay').classList.remove('open');
                document.body.style.overflow = '';
            }
        }
    });

    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('sidebar');
        if (window.innerWidth > 1024) {
            sidebar.classList.remove('open');
            document.querySelector('.sidebar-overlay').classList.remove('open');
            document.body.style.overflow = '';
        }
    });
</script>

</body>
</html>