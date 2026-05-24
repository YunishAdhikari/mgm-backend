<!DOCTYPE html>
<html>
<head>
    <title>Supervisor Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('myapp.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #111827;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            padding: 22px 16px;
            position: fixed;
            inset: 0 auto 0 0;
        }

        .logo {
            font-size: 28px;
            font-weight: 900;
            color: #ff15c4;
            margin-bottom: 8px;
        }

        .role-label {
            color: #6b7280;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 28px;
        }

        .menu-title {
            font-size: 12px;
            font-weight: 900;
            color: #9ca3af;
            text-transform: uppercase;
            margin: 22px 12px 10px;
        }

        .menu {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #374151;
            text-decoration: none;
            padding: 13px 14px;
            border-radius: 14px;
            margin-bottom: 8px;
            font-weight: 800;
            transition: 0.2s;
        }

        .menu:hover {
            background: #eef6ff;
            color: #1583ff;
        }

        .menu.active {
            background: linear-gradient(135deg, #1583ff, #ff15c4);
            color: white;
        }

        .content {
            margin-left: 260px;
            width: calc(100% - 260px);
            padding: 24px;
        }

        .topbar {
            background: white;
            padding: 18px 22px;
            border-radius: 22px;
            margin-bottom: 24px;
            box-shadow: 0 10px 28px rgba(0,0,0,0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
        }

        .topbar h2 {
            margin: 0;
            font-size: 24px;
        }

        .topbar p {
            margin: 5px 0 0;
            color: #6b7280;
            font-size: 14px;
        }

        .logout-btn {
            border: none;
            background: #fee2e2;
            color: #991b1b;
            padding: 11px 16px;
            border-radius: 13px;
            font-weight: 900;
            cursor: pointer;
            white-space: nowrap;
        }

        .logout-btn:hover {
            background: #fecaca;
        }

        @media(max-width: 850px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                position: relative;
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #e5e7eb;
            }

            .content {
                margin-left: 0;
                width: 100%;
                padding: 16px;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .logout-btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    <aside class="sidebar">
        <div class="logo">MGRH</div>
        <div class="role-label">Supervisor Panel</div>

        <div class="menu-title">Main</div>

        <a href="{{ route('supervisor.dashboard') }}"
           class="menu {{ request()->routeIs('supervisor.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('supervisor.holidays.calendar') }}"
           class="menu {{ request()->routeIs('supervisor.holidays.calendar') ? 'active' : '' }}">
            <i class="fa-solid fa-calendar-days"></i>
            <span>Holiday Calendar</span>
        </a>

        <a href="{{ route('supervisor.rota.index') }}"
           class="menu {{ request()->routeIs('supervisor.rota.index') ? 'active' : '' }}">
            <i class="fa-solid fa-clipboard-list"></i>
            <span>Rota Maker</span>
        </a>

        <a href="{{ route('supervisor.rota.view') }}"
            class="menu {{ request()->routeIs('supervisor.rota.view') ? 'active' : '' }}">
                <i class="fa-solid fa-table"></i>
                <span>View Rota</span>
            </a>
    </aside>

    <main class="content">

        <div class="topbar">
            <div>
                <h2>Supervisor Panel</h2>
                <p>Manage department rota drafts and view holiday availability.</p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </button>
            </form>
        </div>

        @yield('content')

    </main>

</div>

</body>
</html>