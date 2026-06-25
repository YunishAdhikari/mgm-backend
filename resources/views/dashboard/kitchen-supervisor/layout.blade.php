<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kitchen Dashboard') - MGRH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <!-- Custom Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('myapp.png') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #dc2626;
            --primary-dark: #b91c1c;
            --primary-light: #f87171;
            --dark: #0f0f0f;
            --dark-secondary: #1a1a1a;
            --gray: #262626;
            --gray-light: #404040;
            --text: #ffffff;
            --text-muted: #9ca3af;
            --text-dim: #6b7280;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
            padding: 24px 0;
            overflow-y: auto;
            z-index: 100;
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }

        .sidebar-logo {
            padding: 0 20px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .sidebar-logo h1 {
            color: #fff;
            font-size: 24px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-logo h1 span { color: #dc2626; }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: #dc2626;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        .role-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            margin: 16px 20px 0;
            background: rgba(220, 38, 38, 0.15);
            border-radius: 8px;
            color: #dc2626;
            font-size: 12px;
            font-weight: 600;
        }

        .nav-section { margin-top: 24px; }

        .nav-title {
            padding: 8px 20px;
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            margin: 2px 0;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            border-left-color: rgba(220, 38, 38, 0.5);
        }

        .nav-link.active {
            background: rgba(220, 38, 38, 0.15);
            color: #dc2626;
            border-left-color: #dc2626;
        }

        .nav-link i { width: 20px; text-align: center; font-size: 15px; }
        .nav-link .arrow { margin-left: auto; font-size: 10px; opacity: 0.5; }

        .sub-nav { display: none; padding-left: 20px; }
        .sub-nav.show { display: block; }

        .sub-nav-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px 10px 36px;
            color: #64748b;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .sub-nav-link::before {
            content: '';
            width: 4px;
            height: 4px;
            background: #64748b;
            border-radius: 50%;
        }

        .sub-nav-link:hover { color: #dc2626; }
        .sub-nav-link:hover::before { background: #dc2626; }

        /* ===== MAIN CONTENT ===== */
        .main { margin-left: 260px; min-height: 100vh; background: #f1f5f9; }

        .topbar {
            background: white;
            padding: 20px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-info h2 { font-size: 20px; font-weight: 700; color: #1e293b; }
        .topbar-info p { color: #64748b; font-size: 13px; margin-top: 4px; }

        .topbar-actions { display: flex; align-items: center; gap: 16px; }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: #f1f5f9;
            border-radius: 10px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: #dc2626;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
        }

        .user-details { display: flex; flex-direction: column; }
        .user-name { font-size: 14px; font-weight: 600; color: #1e293b; }
        .user-role { font-size: 11px; color: #64748b; }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #fee2e2;
            color: #dc2626;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-logout:hover { background: #dc2626; color: white; }

        .page-content { padding: 28px; }

        /* ===== WELCOME BANNER ===== */
        .welcome-banner {
            background: linear-gradient(135deg, #dc2626 0%, #991b1c 100%);
            color: white;
            padding: 32px 36px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
            margin-bottom: 28px;
            box-shadow: 0 12px 32px rgba(220, 38, 38, 0.25);
        }

        .welcome-banner h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 10px;
        }

        .welcome-banner p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 15px;
        }

        .welcome-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            flex-shrink: 0;
        }

        /* ===== QUICK ACTIONS ===== */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }

        .action-card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            text-decoration: none;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            transition: all 0.25s;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
            border-color: #dc2626;
        }

        .action-icon {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .action-icon.green { background: #dcfce7; color: #16a34a; }
        .action-icon.blue { background: #dbeafe; color: #2563eb; }
        .action-icon.orange { background: #ffedd5; color: #ea580c; }
        .action-icon.purple { background: #f3e8ff; color: #9333ea; }
        .action-icon.pink { background: #fce7f3; color: #db2777; }
        .action-icon.teal { background: #f0fdfa; color: #0d9488; }

        .action-info h3 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .action-info p {
            font-size: 13px;
            color: #64748b;
        }

        /* ===== STATS GRID ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: white;
            padding: 22px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            gap: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .stat-info h3 {
            font-size: 26px;
            font-weight: 800;
            color: #1e293b;
        }

        .stat-info p {
            font-size: 13px;
            color: #64748b;
            margin-top: 2px;
        }

        /* ===== CARDS & TABLES ===== */
        .card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i { color: #dc2626; }

        .table-wrapper { overflow-x: auto; border-radius: 12px; border: 1px solid #e2e8f0; }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            text-align: left;
            padding: 14px 18px;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        td {
            padding: 16px 18px;
            font-size: 14px;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }

        tr:hover td { background: #f8fafc; }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary { background: #dc2626; color: white; }
        .btn-primary:hover { background: #b91c1c; }

        .btn-outline {
            background: transparent;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        .btn-outline:hover { background: #f1f5f9; color: #1e293b; }

        .btn-sm { padding: 6px 12px; font-size: 12px; }

        /* ===== BADGES ===== */
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-success { background: #dcfce7; color: #16a34a; }
        .badge-warning { background: #fef3c7; color: #d97706; }
        .badge-danger { background: #fee2e2; color: #dc2626; }
        .badge-info { background: #dbeafe; color: #2563eb; }
        .badge-gray { background: #f1f5f9; color: #64748b; }

        /* ===== ALERTS ===== */
        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 14px 18px;
            border-radius: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
        }

        .alert-warning {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .alert-warning h3 {
            color: #dc2626;
            font-size: 14px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-items {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .alert-item {
            background: white;
            color: #dc2626;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid #fecaca;
        }

        /* ===== FORMS ===== */
        .form-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        }

        .form-group { margin-bottom: 16px; }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius:            10px;
            padding: 12px 14px;
            font-size: 14px;
            outline: none;
            transition: all 0.2s;
            background: #f8fafc;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #dc2626;
            background: white;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .submit-btn {
            width: 100%;
            border: none;
            background: #dc2626;
            color: white;
            padding: 14px 18px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s;
        }

        .submit-btn:hover {
            background: #b91c1c;
            transform: translateY(-2px);
        }

        /* ===== ITEM LIST ===== */
        .item-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .item-row {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.2s;
        }

        .item-row:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }

        .item-row.low-stock {
            border-color: #fecaca;
            background: #fefafafa;
        }

        .item-info { flex: 1; }

        .item-info h3 {
            margin: 0 0 4px;
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
        }

        .item-info p {
            margin: 0;
            font-size: 12px;
            color: #64748b;
        }

        .item-qty {
            text-align: right;
            min-width: 70px;
        }

        .item-qty strong {
            display: block;
            font-size: 22px;
            font-weight: 800;
            color: #1e293b;
        }

        .item-qty span {
            font-size: 12px;
            color: #64748b;
        }

        .item-actions {
            display: flex;
            gap: 8px;
        }

        .update-btn {
            padding: 8px 14px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .delete-btn {
            padding: 8px 14px;
            background: #fee2e2;
            color: #dc2626;
            border: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 48px;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 40px;
            margin-bottom: 12px;
            opacity: 0.5;
        }

        /* ===== MODAL ===== */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal.active { display: flex; }

        .modal-content {
            background: white;
            width: 100%;
            max-width: 500px;
            border-radius: 16px;
            padding: 24px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-header h3 { font-size: 18px; font-weight: 700; }
        .modal-header button {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #64748b;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1200px) {
            .quick-actions, .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main { margin-left: 0; }
            .quick-actions, .stats-grid { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
            .welcome-banner {
                flex-direction: column;
                text-align: left;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <h1>
            <div class="logo-icon"><i class="fas fa-utensils"></i></div>
            MG<span style="color:#dc2626">RH</span>
        </h1>
    </div>

    <div class="role-badge">
        <i class="fas fa-hat-chef"></i>
        Kitchen Supervisor
    </div>

    <!-- Dashboard -->
    <div class="nav-section">
        <div class="nav-title">Main</div>
        <a href="{{ route('kitchen.supervisor.dashboard') }}" 
           class="nav-link {{ request()->routeIs('kitchen.supervisor.dashboard') ? 'active' : '' }}">
            <i class="fas fa-gauge-high"></i>
            <span>Dashboard</span>
        </a>
    </div>

    <!-- Inventory -->
    <div class="nav-section">
        <div class="nav-title">Inventory</div>
        <a href="{{ route('kitchen.inventory.index') }}" 
           class="nav-link {{ request()->routeIs('kitchen.inventory.index') ? 'active' : '' }}">
            <i class="fas fa-boxes-stacked"></i>
            <span>Stock Items</span>
            <i class="fas fa-chevron-right arrow"></i>
        </a>
        <a href="{{ route('kitchen.inventory.current') }}" class="nav-link">
            <i class="fas fa-check-circle"></i>
            <span>Current Stock</span>
        </a>
        <a href="{{ route('kitchen.inventory.history') }}" class="nav-link">
            <i class="fas fa-history"></i>
            <span>Stock History</span>
        </a>
        <a href="{{ route('kitchen.recipes.current') }}" class="nav-link">
            <i class="fas fa-carrot"></i>
            <span>Recipe Ingredients</span>
        </a>
        <a href="{{ route('kitchen.wastage.index') }}" class="nav-link">
            <i class="fas fa-trash-can"></i>
            <span>Wastage</span>
        </a>
    </div>

    <!-- Recipes -->
    <div class="nav-section">
        <div class="nav-title">Menu</div>
        <a href="{{ route('kitchen.recipes.index') }}" class="nav-link">
            <i class="fas fa-book-open"></i>
            <span>Recipes</span>
        </a>
        <a href="{{ route('kitchen.buffets.index') }}" class="nav-link">
            <i class="fas fa-bowl-food"></i>
            <span>Buffet Menus</span>
        </a>
    </div>

    <!-- Management -->
    <div class="nav-section">
        <div class="nav-title">Management</div>
        <a href="{{ route('kitchen.rota.index') }}" class="nav-link">
            <i class="fas fa-calendar-days"></i>
            <span>Rota</span>
        </a>
        <a href="{{ route('kitchensupervisor.rota.view') }}" class="nav-link">
            <i class="fas fa-clock"></i>View Rota
        </a>
        <a href="#" class="nav-link">
            <i class="fas fa-note-sticky"></i>
            <span>Handover Notes</span>
        </a>
        <a href="{{ route('kitchen.ai.prep') }}" class="btn btn-success">
                🤖 AI Prep Plan
            </a>
    </div>
</aside>

<!-- Main Content -->
<main class="main">
    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-info">
            <h2>@yield('page-title', 'Dashboard')</h2>
            <p>@yield('page-subtitle', 'Overview of kitchen operations')</p>
        </div>
        <div class="topbar-actions">
            <div class="user-profile">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="user-details">
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <span class="user-role">Head Chef</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Page Content -->
    <div class="page-content">
        
        <!-- Welcome Banner (only on dashboard) -->
        @hasSection('show-welcome')
        <div class="welcome-banner">
            <div>
                <h1><i class="fas fa-hat-chef"></i> Kitchen Operations</h1>
                <p>Control kitchen inventory, menu items, stock movement, and daily operations.</p>
            </div>
            <div class="welcome-icon">
                <i class="fas fa-kitchen-set"></i>
            </div>
        </div>
        @endif

        <!-- Page Header -->
        @hasSection('page-header')
            <div class="page-header">
                @yield('page-header')
            </div>
        @endif

        @yield('content')
    </div>
</main>

</body>
</html>