<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Admin Dashboard') - MGRH</title>
    <link rel="icon" type="image/png" href="{{ asset('myapp.png') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Arial, sans-serif;
    }

    :root {
      --sidebar-width: 260px;
    }

    html, body {
      height: 100%;
    }

    body {
      background: #f3f3f3;
    }

    /* ===== LAYOUT CONTAINER ===== */
    .layout {
      display: flex;
      height: 100vh;
    }

    /* ===== SIDEBAR (FIXED) ===== */
    .sidebar {
      width: var(--sidebar-width);
      background: #1a1a2e;
      padding: 20px 15px;
      flex-shrink: 0;
      overflow-y: auto;
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      z-index: 100;
    }

    .sidebar::-webkit-scrollbar {
      width: 4px;
    }

    .sidebar::-webkit-scrollbar-thumb {
      background: #333;
      border-radius: 4px;
    }

    .logo {
      font-size: 24px;
      font-weight: 800;
      color: #fff;
      margin-bottom: 20px;
      padding: 0 10px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo-icon {
      width: 40px;
      height: 40px;
      background: linear-gradient(135deg, #ff1548, #ff15c4);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 18px;
    }

    .logo-text {
      display: flex;
      flex-direction: column;
    }

    .logo-text span:first-child {
      color: #fff;
    }

    .logo-text span:last-child {
      color: #ff15c4;
      font-size: 11px;
      font-weight: 600;
    }

    .menu-section {
      margin-bottom: 25px;
    }

    .menu-title {
      font-size: 11px;
      font-weight: 700;
      color: #666;
      margin-bottom: 12px;
      padding: 0 10px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .menu-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 14px;
      border-radius: 8px;
      color: #aaa;
      text-decoration: none;
      margin-bottom: 4px;
      transition: 0.2s;
      border-left: 3px solid transparent;
    }

    .menu-item:hover {
      background: rgba(255, 255, 255, 0.05);
      color: #fff;
    }

    .menu-item.active {
      background: rgba(21, 131, 255, 0.15);
      color: #1583ff;
      border-left-color: #1583ff;
    }

    .menu-item .left {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .menu-item i {
      width: 18px;
      text-align: center;
    }

    /* ===== DROPDOWN ===== */
    .menu-dropdown {
      width: 100%;
    }

    .dropdown-toggle {
      width: 100%;
      border: none;
      background: transparent;
      cursor: pointer;
      font-size: 14px;
    }

    .dropdown-toggle:hover {
      background: rgba(255, 255, 255, 0.05);
      color: #fff;
    }

    .arrow {
      font-size: 11px;
      transition: 0.2s;
    }

    .menu-dropdown:hover .arrow {
      transform: rotate(180deg);
    }

    .submenu {
      display: none;
      margin-left: 18px;
      margin-top: 4px;
      padding-left: 12px;
      border-left: 2px solid #333;
    }

    .menu-dropdown:hover .submenu {
      display: block;
    }

    .submenu-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 12px;
      margin-bottom: 4px;
      border-radius: 8px;
      color: #888;
      text-decoration: none;
      font-size: 13px;
      transition: 0.2s;
    }

    .submenu-item:hover,
    .submenu-item.active {
      background: rgba(21, 131, 255, 0.1);
      color: #1583ff;
    }

    /* ===== MAIN CONTENT AREA ===== */
    .main-wrapper {
      flex: 1;
      margin-left: var(--sidebar-width);
      display: flex;
      flex-direction: column;
      height: 100vh;
    }

    /* ===== TOPBAR (FIXED) - NO SEARCH ===== */
    .topbar {
      background: linear-gradient(135deg, #ff1548, #ff15c4);
      padding: 14px 20px;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 16px;
      flex-shrink: 0;
      position: sticky;
      top: 0;
      z-index: 50;
    }

    .logout-form {
      flex-shrink: 0;
    }

    .logout-btn {
      border: none;
      outline: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      padding: 12px 20px;
      background: white;
      color: #dc2626;
      font-size: 14px;
      font-weight: 700;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    .logout-btn:hover {
      background: #fff1f2;
    }

    /* ===== BREADCRUMB ===== */
    .breadcrumb {
      background: #fff;
      border: 1px solid #e5e5e5;
      padding: 14px 18px;
      border-radius: 10px;
      margin: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      flex-shrink: 0;
    }

    .breadcrumb a {
      color: #1583ff;
      text-decoration: none;
      font-weight: 600;
    }

    .breadcrumb a:hover {
      text-decoration: underline;
    }

    .breadcrumb .separator {
      color: #999;
    }

    .breadcrumb .current {
      color: #666;
    }

    /* ===== SCROLLABLE CONTENT (VERTICAL ONLY) ===== */
    .content-area {
      flex: 1;
      overflow-y: auto;
      overflow-x: hidden;  /* No horizontal scroll */
      padding: 0 20px 20px;
    }

    .card {
      background: #fff;
      border: 1px solid #e5e5e5;
      border-radius: 12px;
      padding: 20px;
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
      margin-bottom: 20px;
      border-bottom: 1px solid #eee;
      padding-bottom: 15px;
    }

    .card-header h2 {
      font-size: 22px;
      color: #222;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .card-header h2 i {
      color: #ff1548;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1100px) {
      .sidebar {
        display: none;
      }

      .main-wrapper {
        margin-left: 0;
      }
    }

    @media (max-width: 768px) {
      .topbar {
        flex-direction: column;
        align-items: stretch;
      }

      .logout-form {
        width: 100%;
      }

      .logout-btn {
        width: 100%;
        justify-content: center;
      }

      .breadcrumb {
        margin: 15px;
      }

      .content-area {
        padding: 0 15px 15px;
      }
    }
  </style>
</head>

<body>
  <div class="layout">

    <!-- Sidebar (Fixed) -->
    <aside class="sidebar">
      
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
            <i class="fas fa-house"></i>
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

        
        <a href="{{ route('admin.attendance.settings') }}" class="menu-item {{ request()->routeIs('admin.attendance.settings') ? 'active' : '' }}">
          <div class="left">
            <i class="fas fa-clock"></i>
            <span>Attendance Settings</span>
          </div>
        </a>

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

    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
      
      <!-- Topbar (Fixed - No Search) -->
      <div class="topbar">
        <form method="POST" action="{{ route('logout') }}" class="logout-form">
          @csrf
          <button type="submit" class="logout-btn">
            <i class="fas fa-right-from-bracket"></i>
            <span>Log Out</span>
          </button>
        </form>
      </div>

      <!-- Breadcrumb -->
      <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Home</a>
        <span class="separator">/</span>
        <span class="current">@yield('page-title', 'Dashboard')</span>
      </div>

      <!-- Scrollable Content (Vertical Only) -->
      <div class="content-area">
        @yield('content')
      </div>

    </div>

  </div>
</body>
</html>