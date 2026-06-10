<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Reception Dashboard') - MGRH</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    @yield('styles')

    <style>
        :root {
            /* ============ RED & BLACK THEME ============ */
            --primary: #ef4444;
            --primary-hover: #f87171;
            --primary-light: #fca5a5;
            --primary-dark: #b91c1c;
            --accent: #fbbf24;
            --accent-glow: 0 0 20px rgba(251, 191, 36, 0.4);
            
            --bg-dark: #050505;
            --bg-sidebar: #0c0c0c;
            --bg-card: #171717;
            --bg-input: #1a1a1a;
            
            --text-main: #fafafa;
            --text-muted: #a1a1aa;
            --text-dim: #52525b;
            
            --border: #27272a;
            --border-light: #3f3f46;
            
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            
            --glow-red: 0 0 25px rgba(239, 68, 68, 0.35);
            --glow-accent: 0 0 20px rgba(251, 191, 36, 0.3);
            
            --radius-lg: 20px;
            --radius-md: 14px;
            --radius-sm: 10px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-dark);
            color: var(--text-main);
            line-height: 1.5;
            overflow-x: hidden;
        }

        .layout {
            display: flex;
            min-height: 100vh;
            background: 
                radial-gradient(ellipse at 15% 15%, rgba(239, 68, 68, 0.12) 0%, transparent 45%),
                radial-gradient(ellipse at 85% 85%, rgba(185, 28, 28, 0.08) 0%, transparent 45%),
                radial-gradient(ellipse at 50% 50%, rgba(20, 20, 20, 1) 0%, var(--bg-dark) 100%);
        }

        /* ============ SIDEBAR ============ */
        .sidebar {
            width: 276px;
            background: linear-gradient(180deg, var(--bg-sidebar) 0%, #080808 100%);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            padding: 24px 16px;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 100;
            overflow: hidden;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: -100px;
            left: -100px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(239, 68, 68, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .sidebar::after {
            content: '';
            position: absolute;
            bottom: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(185, 28, 28, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .sidebar-header {
            padding: 0 8px 36px;
            position: relative;
            z-index: 1;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .logo-icon {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: var(--glow-red);
            animation: pulse-glow 3s ease-in-out infinite;
            position: relative;
        }

        .logo-icon::after {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 18px;
            border: 1px solid rgba(239, 68, 68, 0.4);
            animation: border-pulse 2s ease-in-out infinite;
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: var(--glow-red); }
            50% { box-shadow: 0 0 35px rgba(239, 68, 68, 0.5); }
        }

        @keyframes border-pulse {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 0.8; }
        }

        .logo-text {
            font-size: 24px;
            font-weight: 900;
            background: linear-gradient(135deg, #fff 0%, #fca5a5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 0 4px;
            overflow-y: auto;
            position: relative;
            z-index: 1;
        }

        .nav-section { margin-bottom: 10px; }

        .nav-section-title {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--text-dim);
            padding: 24px 12px 14px;
        }

        .nav-link {
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
            margin-bottom: 4px;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 0;
            background: linear-gradient(180deg, var(--primary), var(--primary-dark));
            border-radius: 0 4px 4px 0;
            transition: height 0.25s ease;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.03);
            color: var(--text-main);
            transform: translateX(4px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.12), rgba(185, 28, 28, 0.08));
            color: var(--primary-light);
        }

        .nav-link.active::before {
            height: 24px;
        }

        .nav-link.active .nav-icon {
            color: var(--primary-light);
        }

        .nav-icon {
            width: 22px;
            text-align: center;
            font-size: 17px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
        }

        /* ============ MAIN ============ */
        .main {
            flex: 1;
            margin-left: 276px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            background: rgba(12, 12, 12, 0.85);
            backdrop-filter: blur(20px);
            padding: 20px 36px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .topbar-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .status-indicator {
            width: 10px;
            height: 10px;
            background: var(--success);
            border-radius: 50%;
            position: relative;
            animation: status-pulse 2s ease-in-out infinite;
        }

        @keyframes status-pulse {
            0%, 100% { 
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
                transform: scale(1);
            }
            50% { 
                box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
                transform: scale(1.1);
            }
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-btn {
            padding: 14px 22px;
            border-radius: var(--radius-md);
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.25s ease;
            cursor: pointer;
            border: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topbar-btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: var(--glow-red);
        }

        .topbar-btn-primary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
        }

        .topbar-btn-secondary {
            background: var(--bg-card);
            border: 1px solid var(--border);
            color: var(--text-muted);
        }

        .topbar-btn-secondary:hover {
            background: var(--bg-input);
            color: var(--text-main);
            border-color: var(--border-light);
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 10px 18px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 14px;
            box-shadow: var(--glow-red);
        }

        .user-info { display: flex; flex-direction: column; }

        .user-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
        }

        .user-role {
            font-size: 12px;
            color: var(--primary-light);
            font-weight: 600;
        }

        .content {
            flex: 1;
            padding: 32px 36px;
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ============ MOBILE ============ */
        .mobile-header {
            display: none;
            background: var(--bg-sidebar);
            padding: 18px 20px;
            position: sticky;
            top: 0;
            z-index: 90;
            border-bottom: 1px solid var(--border);
        }

        .mobile-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .menu-toggle {
            background: var(--bg-card);
            border: 1px solid var(--border);
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 20px;
            color: var(--text-main);
            transition: all 0.2s ease;
        }

        .menu-toggle:hover { background: var(--bg-input); }

        .mobile-logo {
            font-size: 22px;
            font-weight: 900;
            background: linear-gradient(135deg, #fff 0%, #fca5a5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0, 0.8);
            z-index: 99;
            backdrop-filter: blur(8px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.open { opacity: 1; }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .mobile-header { display: block; }
            .sidebar-overlay.open { display: block; }
        }

        @media (max-width: 640px) {
            .topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 18px;
                padding: 18px 20px;
            }
            .topbar-right {
                width: 100%;
                flex-wrap: wrap;
                gap: 12px;
            }
            .topbar-btn { flex: 1; justify-content: center; min-width: 140px; }
            .content { padding: 20px; }
            .mobile-header { margin: -18px -20px 20px; }
        }

        /* ============ SCROLLBAR ============ */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-dim); }
    </style>
    
    @yield('extra_styles')
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
            <div style="width: 46px;"></div>
        </div>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fa-solid fa-hotel"></i>
                </div>
                <div class="logo-text">MGRH</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Menu</div>
                
                <a href="{{ route('reception.dashboard') }}" class="nav-link {{ Request::is('reception/dashboard*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-gauge-high"></i></span>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('reception.restaurant.bookings.index') }}" class="nav-link {{ Request::is('reception/restaurant*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-utensils"></i></span>
                    <span>F&B</span>
                </a>
                
                <a href="#" class="nav-link">
                    <span class="nav-icon"><i class="fa-solid fa-spa"></i></span>
                    <span>Spa</span>
                </a>

                <a href="{{ route('reception.group-buffets.index') }}" class="nav-link">
                    <span class="nav-icon"><i class="fa-solid fa-users"></i></span>
                    <span>Group Buffets</span>
                </a>

                                <a href="{{ route('reception.restaurant.bookings.list') }}" class="nav-link">
                    <span class="nav-icon"><i class="fa-solid fa-list"></i></span>
                    <span>Booking List</span>
                </a>

                <a href="{{ route('reception.restaurant.bookings.report') }}" class="nav-link">
                    <span class="nav-icon"><i class="fa-solid fa-chart-column"></i></span>
                    <span>Restaurant Report</span>
                </a>

                <a href="{{ route('reception.room-status.index') }}" class="nav-link">
                    <span class="nav-icon"><i class="fa-solid fa-broom"></i></span>
                    <span>Housekeeping</span>
                </a>
            </div>
        </nav>
    </aside>

    <main class="main">
        <div class="topbar">
            <div class="topbar-title">
                <span class="status-indicator"></span>
                @yield('page_title', 'Dashboard')
            </div>
            
            <div class="topbar-right">
                <div class="user-badge">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="user-info">
                        <span class="user-name">{{ Auth::user()->name ?? 'User' }}</span>
                        <span class="user-role">Receptionist</span>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="topbar-btn topbar-btn-primary" type="submit">
                        <i class="fa-solid fa-right-from-bracket"></i> Log Out
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

    // Close sidebar when clicking outside
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

    // Handle resize
    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('sidebar');
        if (window.innerWidth > 1024) {
            sidebar.classList.remove('open');
            document.querySelector('.sidebar-overlay').classList.remove('open');
            document.body.style.overflow = '';
        }
    });
</script>

@yield('scripts')

</body>
</html>