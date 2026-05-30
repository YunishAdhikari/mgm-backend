<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reception Dashboard</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
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
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            
            --glow: 0 0 20px rgba(139, 92, 246, 0.3);
            --glow-accent: 0 0 20px rgba(236, 72, 153, 0.3);
            
            --radius-lg: 1.5rem;
            --radius-md: 1rem;
            --radius-sm: 0.75rem;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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

        /* ============ SIDEBAR ============ */
        .sidebar {
            width: 280px;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            padding: 24px 16px;
            transition: transform 0.3s ease;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 200px;
            background: linear-gradient(180deg, rgba(139, 92, 246, 0.1) 0%, transparent 100%);
            pointer-events: none;
        }

        .sidebar-header {
            padding: 0 12px 24px;
            position: relative;
            z-index: 1;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            box-shadow: var(--glow);
        }

        .logo-text {
            font-size: 24px;
            font-weight: 800;
            background: linear-gradient(135deg, #fff 0%, #a78bfa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-nav {
            flex: 1;
            padding: 0 4px;
            overflow-y: auto;
            position: relative;
            z-index: 1;
        }

        .nav-section {
            margin-bottom: 8px;
        }

        .nav-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-dim);
            padding: 24px 12px 10px;
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
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(139, 92, 246, 0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }

        .nav-link:hover::before {
            transform: translateX(100%);
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.03);
            color: var(--text-main);
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.15), rgba(236, 72, 153, 0.1));
            color: var(--primary-hover);
            border: 1px solid rgba(139, 92, 246, 0.3);
            box-shadow: var(--glow);
        }

        .nav-icon {
            width: 20px;
            text-align: center;
            font-size: 18px;
        }

        .nav-link.active .nav-icon {
            color: var(--primary-hover);
        }

        /* ============ MAIN ============ */
        .main {
            flex: 1;
            margin-left: 280px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            background: var(--bg-card);
            padding: 20px 32px;
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
            background: linear-gradient(135deg, #fff 0%, #a1a1aa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-btn {
            padding: 12px 20px;
            border-radius: var(--radius-md);
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .topbar-btn-primary {
            background: linear-gradient(135deg, var(--danger), #b91c1c);
            color: white;
        }

        .topbar-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .content {
            flex: 1;
            padding: 28px 32px;
        }

        /* ============ MOBILE HEADER ============ */
        .mobile-header {
            display: none;
            background: var(--bg-sidebar);
            padding: 16px 20px;
            position: sticky;
            top: 0;
            z-index: 90;
            border-bottom: 1px solid var(--border);
            margin: -28px -32px 28px;
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
        }

        .mobile-logo {
            font-size: 22px;
            font-weight: 800;
            background: linear-gradient(135deg, #fff 0%, #a78bfa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            z-index: 99;
            backdrop-filter: blur(4px);
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main {
                margin-left: 0;
            }

            .mobile-header {
                display: block;
            }

            .sidebar-overlay.open {
                display: block;
            }
        }

        @media (max-width: 640px) {
            .topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
                padding: 16px 20px;
            }

            .topbar-btn {
                width: 100%;
                justify-content: center;
            }

            .content {
                padding: 20px;
            }

            .mobile-header {
                margin: -20px -20px 20px;
            }
        }

        /* ============ SCROLLBAR ============ */
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

        /* ============ ANIMATIONS ============ */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .content {
            animation: fadeIn 0.4s ease-out;
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
            <div class="mobile-logo">MGRH Reception</div>
            <div style="width: 44px;"></div>
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
                <div class="logo-text">MGRH </div>
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
                
                <a href="#" class="nav-link">
                    <span class="nav-icon"><i class="fa-solid fa-screwdriver-wrench"></i></span>
                    <span>Maintenance</span>
                </a>
            </div>
        </nav>
    </aside>

    <main class="main">
        <div class="topbar">
            <h1 class="topbar-title">Reception Portal</h1>
            
            <div class="topbar-right">
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

</body>
</html>