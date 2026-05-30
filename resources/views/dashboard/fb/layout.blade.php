<!DOCTYPE html>
<html>
<head>
    <title>F&B Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f8fafc;
            color: #111827;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: #111827;
            color: white;
            padding: 24px;
        }

        .sidebar h2 {
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: #d1d5db;
            text-decoration: none;
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .sidebar a:hover {
            background: #374151;
            color: white;
        }

        .main {
            flex: 1;
        }

        .topbar {
            background: white;
            padding: 18px 28px;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 800;
        }

        .content {
            padding: 24px;
        }
    </style>
</head>
<body>

<div class="layout">
    <aside class="sidebar">
        <h2>F&B</h2>

        <a href="{{ route('fb.dashboard') }}">Dashboard</a>
        <a href="{{ route('restaurant.bookings.index') }}">Restaurant Bookings</a>
        <a href="{{ route('restaurant.tables.floor-plan') }}">Floor Plan</a>
    </aside>

    <main class="main">
        <div class="topbar">
            F&B Portal
        </div>

        <div class="content">
            @yield('content')
        </div>
    </main>
</div>

</body>
</html>