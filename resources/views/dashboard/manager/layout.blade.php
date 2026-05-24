<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard | MGRH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/png" href="{{ asset('myapp.png') }}">
    <!-- FIXED: Added rel="stylesheet" -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS Variables */
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

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--dark);
            color: var(--text);
            line-height: 1.6;
        }

        a { text-decoration: none; color: inherit; }
        button { cursor: pointer; font-family: inherit; }

        /* Layout */
        .manager-container { display: flex; min-height: 100vh; }

        /* Sidebar */
        .manager-sidebar {
            width: 260px;
            background: var(--dark-secondary);
            border-right: 1px solid var(--gray);
            padding: 24px 16px;
            flex-shrink: 0;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            overflow-y: auto;
        }

        .manager-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 22px;
            font-weight: 800;
            padding: 0 12px;
            margin-bottom: 40px;
        }

        .manager-logo img { width: 40px; height: 40px; border-radius: 8px; object-fit: contain; }
        .manager-logo span { color: var(--primary); }

        .menu-title {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 24px 12px 12px;
        }

        .manager-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s;
            margin-bottom: 4px;
        }

        .manager-menu i { width: 20px; text-align: center; }
        .manager-menu:hover { background: var(--gray); color: var(--text); }

        .manager-menu.active {
            background: rgba(220, 38, 38, 0.15);
            color: var(--primary);
            border-left: 3px solid var(--primary);
        }

        /* Main Content */
        .manager-main { flex: 1; margin-left: 260px; padding: 24px; }

        /* Topbar */
        .manager-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
            background: var(--dark-secondary);
            border: 1px solid var(--gray);
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .manager-topbar h2 { font-size: 20px; font-weight: 600; }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .user-info { display: flex; align-items: center; gap: 12px; }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .user-name { font-size: 14px; font-weight: 600; }
        .user-role { font-size: 12px; color: var(--text-muted); }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            border: 1px solid var(--gray-light);
            color: var(--text-muted);
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .logout-btn:hover { border-color: var(--primary); color: var(--primary); }

        /* Dashboard Cards */
        .dashboard-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 32px; }

        .card {
            background: var(--dark-secondary);
            border: 1px solid var(--gray);
            border-radius: 12px;
            padding: 24px;
            transition: all 0.3s;
        }

        .card:hover { border-color: var(--primary); transform: translateY(-2px); }

        .card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }

        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .card-icon.red { background: rgba(220, 38, 38, 0.15); color: var(--primary); }
        .card-icon.blue { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
        .card-icon.green { background: rgba(34, 197, 94, 0.15); color: #22c55e; }
        .card-icon.yellow { background: rgba(234, 179, 8, 0.15); color: #eab308; }

        .card h3 { font-size: 13px; color: var(--text-muted); font-weight: 500; }
        .card p { font-size: 28px; font-weight: 700; }

        /* Section Header */
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .section-header h3 { font-size: 18px; font-weight: 600; }

        /* Table */
        .table-container { background: var(--dark-secondary); border: 1px solid var(--gray); border-radius: 12px; overflow: hidden; margin-bottom: 32px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 16px; text-align: left; border-bottom: 1px solid var(--gray); }

        .table th {
            background: var(--gray);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
        }

        .table td { font-size: 14px; }
        .table tr:last-child td { border-bottom: none; }
        .table tr:hover td { background: rgba(255, 255, 255, 0.02); }

        /* Status Badge */
        .status-badge { display: inline-block; padding: 6px 12px; border-radius: 100px; font-size: 12px; font-weight: 600; }
        .status-badge.pending { background: rgba(234, 179, 8, 0.15); color: #eab308; }
        .status-badge.progress { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
        .status-badge.resolved { background: rgba(34, 197, 94, 0.15); color: #22c55e; }
        .status-badge.active { background: rgba(34, 197, 94, 0.15); color: #22c55e; }
        .status-badge.inactive { background: rgba(107, 114, 128, 0.15); color: #6b7280; }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; border-radius: 8px; font-size: 14px; font-weight: 600; transition: all 0.3s; border: none; cursor: pointer; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-outline { background: transparent; border: 1px solid var(--gray-light); color: var(--text); }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }

        /* Responsive */
        @media (max-width: 1024px) { .dashboard-cards { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px) {
            .manager-sidebar { display: none; }
            .manager-main { margin-left: 0; }
            .dashboard-cards { grid-template-columns: 1fr; }
            .manager-topbar { flex-direction: column; gap: 16px; text-align: center; }
        }
    </style>
</head>

<body>

<div class="manager-container">

    <!-- Sidebar -->
    <aside class="manager-sidebar">
        <a href="{{ route('manager.dashboard') }}" class="manager-logo">
            MG<span>RH</span>
        </a>

        <div class="menu-title">Dashboard</div>
        <a href="{{ route('manager.dashboard') }}" class="manager-menu {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> Dashboard
        </a>

        <div class="menu-title">Operations</div>
        <a href="{{ route('manager.maintenance.index') }}" class="manager-menu {{ request()->routeIs('manager.maintenance.*') ? 'active' : '' }}">
            <i class="fas fa-tools"></i> Maintenance
        </a>
        <a href="{{ route('manager.complaints.index') }}" class="manager-menu {{ request()->routeIs('manager.complaints.*') ? 'active' : '' }}">
            <i class="fas fa-exclamation-circle"></i> Complaints
        </a>

        <div class="menu-title">Staff Planning</div>
        <a href="{{ route('manager.holidays.index') }}" class="manager-menu {{ request()->routeIs('manager.holidays.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-check"></i> Holiday Requests
        </a>
        <a href="{{ route('manager.holidays.calendar') }}" class="manager-menu {{ request()->routeIs('manager.holidays.calendar') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i> Holiday Calendar
        </a>
        <a href="{{ route('manager.rota.index') }}" class="manager-menu {{ request()->routeIs('manager.rota.*') ? 'active' : '' }}">
            <i class="fas fa-clock"></i> Rota
        </a>
        <a href="{{ route('manager.rota.view') }}" class="manager-menu {{ request()->routeIs('manager.rota.view') ? 'active' : '' }}">
            <i class="fas fa-eye"></i> View Rota
        </a>

        <div class="menu-title">Reports</div>
        <a href="{{ route('manager.reports.holiday.form') }}" class="manager-menu {{ request()->routeIs('manager.reports.*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i> Holiday Report
        </a>
        <a href="{{ route('manager.attendance.index') }}" class="manager-menu {{ request()->routeIs('manager.attendance.index') ? 'active' : '' }}">
            <i class="fas fa-clock"></i> Attendance
        </a>
        <!-- FIXED: class="manager-menu" -->
        <a href="{{ route('manager.attendance.monthly.form') }}" class="manager-menu {{ request()->routeIs('manager.attendance.monthly.form') ? 'active' : '' }}">
            <i class="fas fa-file-pdf"></i> Attendance Report
        </a>
    </aside>

    <!-- Main Content -->
    <main class="manager-main">
        
        <!-- Topbar -->
        <div class="manager-topbar">
            <h2><i class="fas fa-user-shield"></i> Manager Panel</h2>
            <div class="topbar-right">
                <div class="user-info">
                    <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                    <div>
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">Manager</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout-btn" type="submit">
                        <i class="fas fa-sign-out-alt"></i> Log Out
                    </button>
                </form>
            </div>
        </div>

        <!-- Content -->
        @yield('content')

    </main>

</div>

</body>
</html>