<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Admin Dashboard') - MGRH</title>
  <link rel="icon" type="image/png" href="{{ asset('myapp.png') }}">
  @yield('styles')

  <!-- Google Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root {
      --sidebar-width: 280px;

      --bg: #09090b;
      --sidebar: #18181b;
      --card: #27272a;
      --input: #1c1c1f;
      --primary: #8b5cf6;
      --secondary: #ec4899;
      --text: #fafafa;
      --muted: #a1a1aa;
      --dim: #71717a;
      --border: #3f3f46;

      --purple-glow: 0 0 20px rgba(139, 92, 246, 0.3);
      --pink-glow: 0 0 24px rgba(236, 72, 153, 0.22);
      --soft-shadow: 0 24px 80px rgba(0, 0, 0, 0.45);
      --gradient: linear-gradient(135deg, var(--primary), var(--secondary));
      --transition: all 0.28s ease;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    html,
    body {
      min-height: 100%;
    }

    body {
      color: var(--text);
      background:
        radial-gradient(ellipse at top left, rgba(139, 92, 246, 0.18), transparent 34%),
        radial-gradient(ellipse at top right, rgba(236, 72, 153, 0.13), transparent 32%),
        radial-gradient(ellipse at bottom, rgba(139, 92, 246, 0.12), transparent 38%),
        var(--bg);
      overflow-x: hidden;
    }

    body.sidebar-open {
      overflow: hidden;
    }

    /* ===== LAYOUT CONTAINER ===== */
    .layout {
      display: flex;
      min-height: 100vh;
    }

    /* ===== MOBILE OVERLAY ===== */
    .sidebar-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.72);
      backdrop-filter: blur(8px);
      opacity: 0;
      visibility: hidden;
      z-index: 90;
      transition: var(--transition);
    }

    .sidebar-overlay.active {
      opacity: 1;
      visibility: visible;
    }

    /* ===== SIDEBAR ===== */
    .sidebar {
      width: var(--sidebar-width);
      background:
        linear-gradient(180deg, rgba(139, 92, 246, 0.12), transparent 28%),
        var(--sidebar);
      border-right: 1px solid rgba(63, 63, 70, 0.75);
      padding: 22px 16px;
      flex-shrink: 0;
      overflow-y: auto;
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      z-index: 100;
      box-shadow: var(--soft-shadow);
      transition: transform 0.32s ease;
    }

    .sidebar::before {
      content: "";
      position: absolute;
      top: -120px;
      left: 18px;
      width: 220px;
      height: 220px;
      background: radial-gradient(circle, rgba(139, 92, 246, 0.44), transparent 68%);
      filter: blur(8px);
      pointer-events: none;
    }

    .sidebar::after {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 150px;
      background: linear-gradient(135deg, rgba(139, 92, 246, 0.22), rgba(236, 72, 153, 0.08), transparent);
      pointer-events: none;
      opacity: 0.9;
    }

    .sidebar::-webkit-scrollbar {
      width: 5px;
    }

    .sidebar::-webkit-scrollbar-track {
      background: transparent;
    }

    .sidebar::-webkit-scrollbar-thumb {
      background: rgba(139, 92, 246, 0.55);
      border-radius: 999px;
    }

    .logo {
      position: relative;
      z-index: 2;
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 4px 8px 22px;
      margin-bottom: 14px;
      border-bottom: 1px solid rgba(63, 63, 70, 0.58);
    }

    .logo-icon {
      width: 46px;
      height: 46px;
      background: var(--gradient);
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 20px;
      box-shadow: var(--purple-glow), var(--pink-glow);
      position: relative;
      overflow: hidden;
    }

    .logo-icon::after {
      content: "";
      position: absolute;
      inset: 1px;
      background: linear-gradient(135deg, rgba(255,255,255,0.28), transparent 45%);
      border-radius: inherit;
    }

    .logo-text {
      display: flex;
      flex-direction: column;
      line-height: 1.15;
      min-width: 0;
    }

    .logo-text span:first-child {
      font-size: 23px;
      font-weight: 900;
      letter-spacing: -0.5px;
      background: linear-gradient(135deg, #ffffff, #c4b5fd, #f9a8d4);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }

    .logo-text span:last-child {
      color: var(--muted);
      font-size: 10.5px;
      font-weight: 700;
      margin-top: 4px;
      white-space: nowrap;
    }

    .menu-section {
      position: relative;
      z-index: 2;
      margin-bottom: 24px;
    }

    .menu-title {
      font-size: 10px;
      font-weight: 800;
      color: var(--dim);
      margin-bottom: 10px;
      padding: 0 12px;
      text-transform: uppercase;
      letter-spacing: 1.4px;
    }

    .menu-item,
    .submenu-item {
      position: relative;
      isolation: isolate;
      overflow: hidden;
    }

    .menu-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
      padding: 12px 14px;
      border-radius: 14px;
      color: var(--muted);
      text-decoration: none;
      margin-bottom: 6px;
      transition: var(--transition);
      border: 1px solid transparent;
      background: transparent;
    }

    .menu-item::before,
    .submenu-item::before {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(120deg, transparent, rgba(139, 92, 246, 0.16), rgba(236, 72, 153, 0.11), transparent);
      transform: translateX(-115%);
      transition: transform 0.55s ease;
      z-index: -1;
    }

    .menu-item:hover::before,
    .submenu-item:hover::before {
      transform: translateX(115%);
    }

    .menu-item:hover {
      color: var(--text);
      background: rgba(255, 255, 255, 0.045);
      border-color: rgba(139, 92, 246, 0.25);
      transform: translateX(4px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18);
    }

    .menu-item.active,
    .submenu-item.active {
      color: var(--text);
      background: linear-gradient(135deg, rgba(139, 92, 246, 0.96), rgba(236, 72, 153, 0.76));
      border-color: rgba(255, 255, 255, 0.12);
      box-shadow: var(--purple-glow);
      animation: activePulse 2.2s ease-in-out infinite;
    }

    .menu-item .left {
      display: flex;
      align-items: center;
      gap: 12px;
      min-width: 0;
    }

    .menu-item i,
    .submenu-item i {
      width: 18px;
      text-align: center;
      font-size: 15px;
    }

    .menu-item span {
      font-size: 14px;
      font-weight: 700;
    }

    /* ===== DROPDOWN ===== */
    .menu-dropdown {
      width: 100%;
    }

    .dropdown-toggle {
      border: none;
      cursor: pointer;
      font-size: 14px;
    }

    .arrow {
      font-size: 11px;
      transition: transform 0.25s ease;
      color: var(--dim);
    }

    .menu-dropdown:hover .arrow {
      transform: rotate(180deg);
      color: var(--text);
    }

    .submenu {
      display: none;
      margin: 6px 0 8px 18px;
      padding-left: 12px;
      border-left: 1px solid rgba(139, 92, 246, 0.35);
    }

    .menu-dropdown:hover .submenu {
      display: block;
      animation: fadeIn 0.25s ease both;
    }

    .submenu-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 12px;
      margin-bottom: 5px;
      border-radius: 12px;
      color: var(--muted);
      text-decoration: none;
      font-size: 13px;
      font-weight: 700;
      transition: var(--transition);
      border: 1px solid transparent;
    }

    .submenu-item:hover {
      color: var(--text);
      background: rgba(139, 92, 246, 0.10);
      border-color: rgba(139, 92, 246, 0.20);
      transform: translateX(3px);
    }

    /* ===== MAIN CONTENT AREA ===== */
    .main-wrapper {
      flex: 1;
      margin-left: var(--sidebar-width);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ===== TOPBAR ===== */
    .topbar {
      min-height: 72px;
      background:
        linear-gradient(135deg, rgba(139, 92, 246, 0.94), rgba(124, 58, 237, 0.92), rgba(236, 72, 153, 0.72)),
        rgba(24, 24, 27, 0.9);
      border-bottom: 1px solid rgba(255, 255, 255, 0.11);
      padding: 14px 22px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 16px;
      flex-shrink: 0;
      position: sticky;
      top: 0;
      z-index: 50;
      box-shadow: 0 18px 45px rgba(0, 0, 0, 0.30);
      backdrop-filter: blur(16px);
    }

    .mobile-menu-btn {
      display: none;
      border: 1px solid rgba(255, 255, 255, 0.20);
      background: rgba(255, 255, 255, 0.12);
      color: white;
      width: 44px;
      height: 44px;
      border-radius: 14px;
      cursor: pointer;
      font-size: 17px;
      transition: var(--transition);
    }

    .mobile-menu-btn:hover {
      background: rgba(255, 255, 255, 0.20);
      box-shadow: var(--purple-glow);
    }

    .topbar-title {
      display: flex;
      flex-direction: column;
      gap: 3px;
    }

    .topbar-title strong {
      font-size: 18px;
      font-weight: 900;
      letter-spacing: -0.4px;
      background: linear-gradient(135deg, #ffffff, #ddd6fe);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }

    .topbar-title span {
      font-size: 12px;
      color: rgba(255, 255, 255, 0.72);
      font-weight: 600;
    }

    .topbar-actions {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 12px;
      margin-left: auto;
    }

    .logout-form {
      flex-shrink: 0;
    }

    .logout-btn {
      border: 1px solid rgba(255, 255, 255, 0.14);
      outline: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      padding: 12px 18px;
      background: rgba(9, 9, 11, 0.50);
      color: var(--text);
      font-size: 14px;
      font-weight: 800;
      border-radius: 14px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
      transition: var(--transition);
      backdrop-filter: blur(14px);
    }

    .logout-btn:hover {
      transform: translateY(-2px);
      background: rgba(9, 9, 11, 0.72);
      box-shadow: var(--purple-glow);
    }

    /* ===== BREADCRUMB ===== */
    .breadcrumb {
      background: rgba(39, 39, 42, 0.74);
      border: 1px solid rgba(63, 63, 70, 0.78);
      padding: 14px 18px;
      border-radius: 18px;
      margin: 22px;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      flex-shrink: 0;
      box-shadow: 0 18px 50px rgba(0, 0, 0, 0.18);
      backdrop-filter: blur(16px);
      animation: fadeIn 0.45s ease both;
    }

    .breadcrumb a {
      color: #c4b5fd;
      text-decoration: none;
      font-weight: 800;
      transition: var(--transition);
    }

    .breadcrumb a:hover {
      color: var(--text);
      text-shadow: var(--purple-glow);
    }

    .breadcrumb .separator {
      color: var(--dim);
    }

    .breadcrumb .current {
      color: var(--muted);
      font-weight: 700;
    }

    /* ===== SCROLLABLE CONTENT ===== */
    .content-area {
      flex: 1;
      overflow-y: auto;
      overflow-x: hidden;
      padding: 0 22px 22px;
    }

    .content-area::-webkit-scrollbar {
      width: 7px;
    }

    .content-area::-webkit-scrollbar-track {
      background: transparent;
    }

    .content-area::-webkit-scrollbar-thumb {
      background: rgba(139, 92, 246, 0.45);
      border-radius: 999px;
    }

    .card {
      background:
        linear-gradient(180deg, rgba(255,255,255,0.035), transparent),
        var(--card);
      border: 1px solid rgba(63, 63, 70, 0.85);
      border-radius: 22px;
      padding: 22px;
      box-shadow: 0 24px 70px rgba(0, 0, 0, 0.28);
      animation: cardFadeIn 0.55s ease both;
    }

    .card:hover {
      border-color: rgba(139, 92, 246, 0.45);
      box-shadow: var(--purple-glow), 0 24px 70px rgba(0, 0, 0, 0.32);
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
      margin-bottom: 20px;
      border-bottom: 1px solid rgba(63, 63, 70, 0.82);
      padding-bottom: 16px;
    }

    .card-header h2 {
      font-size: 22px;
      color: var(--text);
      display: flex;
      align-items: center;
      gap: 12px;
      font-weight: 900;
      letter-spacing: -0.5px;
      background: linear-gradient(135deg, #ffffff, #c4b5fd, #f9a8d4);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }

    .card-header h2 i {
      color: var(--primary);
      filter: drop-shadow(0 0 10px rgba(139, 92, 246, 0.55));
    }

    input,
    select,
    textarea {
      background: var(--input);
      color: var(--text);
      border: 1px solid var(--border);
      border-radius: 13px;
      outline: none;
      transition: var(--transition);
    }

    input:focus,
    select:focus,
    textarea:focus {
      border-color: var(--primary);
      box-shadow: var(--purple-glow);
    }

    table {
      color: var(--text);
    }

    /* ===== ANIMATIONS ===== */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(8px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes cardFadeIn {
      from {
        opacity: 0;
        transform: translateY(14px) scale(0.985);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    @keyframes activePulse {
      0%, 100% {
        box-shadow: 0 0 18px rgba(139, 92, 246, 0.28);
      }
      50% {
        box-shadow: 0 0 28px rgba(139, 92, 246, 0.48), 0 0 22px rgba(236, 72, 153, 0.20);
      }
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1100px) {
      .sidebar {
        transform: translateX(-105%);
      }

      .sidebar.active {
        transform: translateX(0);
      }

      .main-wrapper {
        margin-left: 0;
      }

      .mobile-menu-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
      }
    }

    @media (max-width: 768px) {
      :root {
        --sidebar-width: 292px;
      }

      .topbar {
        padding: 12px 15px;
      }

      .topbar-title span {
        display: none;
      }

      .topbar-title strong {
        font-size: 15px;
      }

      .logout-btn {
        padding: 11px 13px;
      }

      .logout-btn span {
        display: none;
      }

      .breadcrumb {
        margin: 15px;
        border-radius: 16px;
        flex-wrap: wrap;
      }

      .content-area {
        padding: 0 15px 15px;
      }

      .card {
        border-radius: 18px;
        padding: 16px;
      }

      .card-header {
        flex-direction: column;
        align-items: flex-start;
      }

      .card-header h2 {
        font-size: 19px;
      }
    }

    @media (max-width: 420px) {
      :root {
        --sidebar-width: 86vw;
      }

      .logo-text span:last-child {
        white-space: normal;
      }
    }
  </style>
</head>

<body>
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <div class="layout">

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">

      <!-- Logo -->
      <div class="logo">
        <div class="logo-icon">
          <i class="fas fa-hotel"></i>
        </div>
        <div class="logo-text">
          <span>MGRH</span>
          <span>Muthu Glasgow River Hotel</span>
        </div>
      </div>

      <!-- Main Menu -->
      <div class="menu-section">
        <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <div class="left">
            <i class="fas fa-gauge-high"></i>
            <span>Dashboard</span>
          </div>
        </a>
      </div>

      <!-- News Section -->
      <div class="menu-section">
        <div class="menu-title">News & Media</div>

        <div class="menu-dropdown">
          <button type="button" class="menu-item dropdown-toggle">
            <div class="left">
              <i class="fas fa-newspaper"></i>
              <span>News / Blogs</span>
            </div>
            <i class="fas fa-chevron-down arrow"></i>
          </button>

          <div class="submenu">
            <a href="{{ route('admin.news.create') }}" class="submenu-item {{ request()->routeIs('admin.news.create') ? 'active' : '' }}">
              <i class="fas fa-plus"></i>
              Add News
            </a>
            <a href="{{ route('admin.news.index') }}" class="submenu-item {{ request()->routeIs('admin.news.index') ? 'active' : '' }}">
              <i class="fas fa-list"></i>
              View News
            </a>
          </div>
        </div>
      </div>

      <!-- Management Section -->
      <div class="menu-section">
        <div class="menu-title">Management</div>

        <a href="#" class="menu-item">
          <div class="left">
            <i class="fas fa-circle-exclamation"></i>
            <span>Complaints</span>
          </div>
        </a>

        {{-- <a href="{{ route('admin.attendance.settings') }}" class="menu-item {{ request()->routeIs('admin.attendance.settings') ? 'active' : '' }}">
          <div class="left">
            <i class="fas fa-clock"></i>
            <span>Attendance Settings</span>
          </div>
        </a> --}}

        <!-- Maintenance Dropdown -->
        <div class="menu-dropdown">
          <button type="button" class="menu-item dropdown-toggle">
            <div class="left">
              <i class="fas fa-screwdriver-wrench"></i>
              <span>Maintenance</span>
            </div>
            <i class="fas fa-chevron-down arrow"></i>
          </button>

          <div class="submenu">
            <a href="{{ route('admin.maintenance.create') }}" class="submenu-item {{ request()->routeIs('admin.maintenance.create') ? 'active' : '' }}">
              <i class="fas fa-plus"></i>
              Add Maintenance
            </a>
            <a href="{{ route('admin.maintenance.index') }}" class="submenu-item {{ request()->routeIs('admin.maintenance.index') ? 'active' : '' }}">
              <i class="fas fa-list"></i>
              View Maintenance
            </a>
          </div>
        </div>
      </div>

      <!-- Employee Management -->
      <div class="menu-section">
        <div class="menu-title">Employee Management</div>

        <a href="{{ route('addemp') }}" class="menu-item {{ request()->routeIs('addemp') ? 'active' : '' }}">
          <div class="left">
            <i class="fas fa-user-plus"></i>
            <span>Add Employee</span>
          </div>
        </a>

        <a href="{{ route('dashboard.admin.showemp') }}" class="menu-item {{ request()->routeIs('dashboard.admin.showemp') ? 'active' : '' }}">
          <div class="left">
            <i class="fas fa-users"></i>
            <span>Employee List</span>
          </div>
        </a>
      </div>

      <!-- F&B Management -->
      <div class="menu-section">
        <div class="menu-title">F&B Management</div>

        <a href="{{ route('restaurant.tables.index') }}" class="menu-item {{ request()->routeIs('restaurant.tables.index') ? 'active' : '' }}">
          <div class="left">
            <i class="fas fa-hotel"></i>
            <span>Restaurant Tables</span>
          </div>
        </a>

        <a href="{{ route('restaurant.settings.index') }}" class="menu-item {{ request()->routeIs('restaurant.settings.index') ? 'active' : '' }}">
          <div class="left">
            <i class="fas fa-clock"></i>
            <span>Restaurant Timing</span>
          </div>
        </a>


         
      

        {{-- <a href="" class="menu-item">
          <div class="left">
            <i class="fas fa-users"></i>
            <span>Employee List</span>
          </div>
        </a> --}}
      </div>

       <div class="menu-section">
        <div class="menu-title">Room Management </div>
         <a href="{{ route('admin.room-types.index') }}" class="menu-item {{ request()->routeIs('admin.room-types.index') ? 'active' : '' }}">
          <div class="left">  
            <i class="fas fa-bed"></i>
             <span> Room Types</span>
              </div>
          </a>

          <a href="{{ route('rooms.index') }}" class="menu-item {{ request()->routeIs('rooms.index') ? 'active' : '' }}">
          <div class="left">  
            <i class="fas fa-bed"></i>
             <span>Rooms</span>
              </div>
          </a>
        </div>


    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">

      <!-- Topbar -->
      <div class="topbar">
        <button type="button" class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Open menu">
          <i class="fas fa-bars"></i>
        </button>

        <div class="topbar-title">
          <strong>@yield('page-title', 'Dashboard')</strong>
          {{-- <span>Premium admin control panel</span> --}}
        </div>

        <div class="topbar-actions">
          <form method="POST" action="{{ route('logout') }}" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">
              <i class="fas fa-right-from-bracket"></i>
              <span>Log Out</span>
            </button>
          </form>
        </div>
      </div>

      <!-- Breadcrumb -->
      {{-- <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Home</a>
        <span class="separator">/</span>
        <span class="current">@yield('page-title', 'Dashboard')</span>
      </div> --}}
     

      <!-- Scrollable Content -->
      <div class="content-area">
        @yield('content')
      </div>

    </div>
  </div>

  <script>
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');

    function openSidebar() {
      sidebar.classList.add('active');
      sidebarOverlay.classList.add('active');
      document.body.classList.add('sidebar-open');
    }

    function closeSidebar() {
      sidebar.classList.remove('active');
      sidebarOverlay.classList.remove('active');
      document.body.classList.remove('sidebar-open');
    }

    mobileMenuBtn?.addEventListener('click', openSidebar);
    sidebarOverlay?.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        closeSidebar();
      }
    });
  </script>
</body>
</html>
