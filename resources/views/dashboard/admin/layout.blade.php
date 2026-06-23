<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Admin Dashboard') - MGRH</title>
  <link rel="icon" type="image/png" href="{{ asset('myapp.png') }}">
  
  <!-- Google Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  @yield('styles')

  <style>
    /* ===== RED & BLACK THEME ===== */
    :root {
      --sidebar-width: 275px;
      
      --bg-body: #0a0a0a;
      --bg-sidebar: #0d0d0d;
      --bg-card: #141414;
      --bg-card-hover: #1a1a1a;
      --bg-input: #1c1c1c;
      
      --primary: #e82d2d;
      --primary-hover: #ff3b3b;
      --primary-dark: #8b0000;
      --primary-glow: rgba(232, 45, 45, 0.4);
      --secondary: #2d2d2d;
      
      --text-main: #ffffff;
      --text-muted: #a0a0a0;
      --text-dim: #666666;
      
      --border: #2a2a2a;
      --border-active: #e82d2d;
      
      --radius: 12px;
      --radius-sm: 8px;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    
    html { scroll-behavior: smooth; }
    
    body {
      font-family: 'Exo 2', sans-serif;
      background-color: var(--bg-body);
      color: var(--text-main);
      min-height: 100vh;
      overflow-x: hidden;
      background: 
        radial-gradient(ellipse at 0% 0%, rgba(232, 45, 45, 0.12), transparent 40%),
        radial-gradient(ellipse at 100% 100%, rgba(232, 45, 45, 0.08), transparent 40%),
        var(--bg-body);
    }

    body.sidebar-open { overflow: hidden; }

    a { text-decoration: none; color: inherit; }
    button { font-family: inherit; cursor: pointer; border: none; outline: none; background: transparent;}

    .layout { display: flex; min-height: 100vh; }

    /* ===== MOBILE OVERLAY ===== */
    .sidebar-overlay {
      position: fixed; inset: 0;
      background: rgba(0, 0, 0, 0.8);
      backdrop-filter: blur(6px);
      z-index: 90;
      opacity: 0; visibility: hidden;
      transition: all 0.3s ease;
    }
    .sidebar-overlay.active { opacity: 1; visibility: visible; }

    /* ===== SIDEBAR - FIXED SCROLLING ===== */
    .sidebar {
      width: var(--sidebar-width);
      background: var(--bg-sidebar);
      border-right: 2px solid var(--border);
      padding: 20px 0;
      position: fixed;
      top: 0; left: 0;
      height: 100vh;
      z-index: 100;
      transform: translateX(0);
      transition: transform 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
      display: flex;
      flex-direction: column;
      box-shadow: 0 0 60px rgba(232, 45, 45, 0.1);
      
      /* SCROLLING FIX */
      overflow-y: auto;
      overflow-x: hidden;
      scrollbar-width: thin;
      scrollbar-color: var(--primary) var(--bg-sidebar);
    }

    /* Custom Scrollbar for Sidebar */
    .sidebar::-webkit-scrollbar {
      width: 6px;
    }
    .sidebar::-webkit-scrollbar-track {
      background: var(--bg-sidebar);
    }
    .sidebar::-webkit-scrollbar-thumb {
      background: var(--primary);
      border-radius: 3px;
    }
    .sidebar::-webkit-scrollbar-thumb:hover {
      background: var(--primary-hover);
    }

    @media (max-width: 1024px) {
      .sidebar { transform: translateX(-105%); }
      .sidebar.active { transform: translateX(0); }
      .main-wrapper { margin-left: 0 !important; }
    }

    /* Logo Section */
    .logo {
      display: flex; align-items: center; gap: 14px;
      padding: 0 22px 28px;
      margin-bottom: 10px;
      border-bottom: 1px solid var(--border);
      flex-shrink: 0;
    }

    .logo-icon {
      width: 48px; height: 48px;
      background: linear-gradient(135deg, var(--primary), #8b0000);
      border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      color: white; font-size: 20px;
      box-shadow: 0 0 25px var(--primary-glow);
      flex-shrink: 0;
    }

    .logo-text span:first-child {
      font-size: 26px; font-weight: 900; letter-spacing: -1px;
      display: block; line-height: 1;
      color: white;
    }

    .logo-text span:last-child {
      font-size: 11px; color: var(--primary); font-weight: 700;
      letter-spacing: 1px; text-transform: uppercase;
    }

    /* Menu */
    .menu-section {
      padding: 0 12px;
      margin-bottom: 20px;
      flex-shrink: 0;
    }
    
    .menu-title {
      font-size: 10px; font-weight: 800;
      color: var(--text-dim); text-transform: uppercase;
      letter-spacing: 2px; margin-bottom: 12px; padding-left: 12px;
    }

    .menu-item {
      display: flex; align-items: center;
      padding: 14px 16px;
      margin-bottom: 4px;
      border-radius: var(--radius-sm);
      color: var(--text-muted);
      font-weight: 600; font-size: 14px;
      transition: all 0.2s ease;
      border: 1px solid transparent;
    }

    .menu-item:hover {
      color: white;
      background: linear-gradient(90deg, rgba(232, 45, 45, 0.15), transparent);
      border-color: rgba(232, 45, 45, 0.3);
      transform: translateX(6px);
      box-shadow: -4px 0 20px rgba(232, 45, 45, 0.2);
    }

    .menu-item.active {
      color: white;
      background: linear-gradient(90deg, var(--primary), var(--primary-dark));
      border: 1px solid var(--primary);
      box-shadow: 0 0 20px var(--primary-glow);
    }

    .menu-item i { width: 28px; margin-right: 10px; text-align: center; font-size: 16px; }
    
    .arrow { margin-left: auto; font-size: 10px; transition: transform 0.2s; }
    .menu-dropdown:hover .arrow { transform: rotate(180deg); }

    .submenu {
      display: none;
      margin: 4px 0 8px 18px;
      padding-left: 16px;
      border-left: 2px solid var(--border);
    }
    .menu-dropdown:hover .submenu { display: block; }

    .submenu-item {
      display: flex; align-items: center;
      padding: 10px 12px;
      margin: 4px 0;
      color: var(--text-muted); font-size: 13px;
      border-radius: 6px; font-weight: 500;
      transition: all 0.2s;
    }
    .submenu-item:hover { 
      color: white; 
      background: rgba(232, 45, 45, 0.15); 
      border-left: 2px solid var(--primary);
    }

    /* MAIN WRAPPER */
    .main-wrapper {
      flex: 1;
      margin-left: var(--sidebar-width);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    @media (max-width: 1024px) {
      .main-wrapper { margin-left: 0; }
    }

    /* TOPBAR */
    .topbar {
      height: 76px;
      background: rgba(13, 13, 13, 0.95);
      backdrop-filter: blur(10px);
      border-bottom: 2px solid var(--border);
      padding: 0 32px;
      display: flex; align-items: center; justify-content: space-between;
      position: sticky; top: 0; z-index: 50;
      box-shadow: 0 4px 30px rgba(0,0,0,0.5);
      flex-shrink: 0;
    }

    .mobile-menu-btn {
      display: none;
      width: 44px; height: 44px;
      background: rgba(232, 45, 45, 0.1);
      border: 1px solid var(--primary);
      border-radius: 10px;
      color: var(--primary);
      font-size: 18px;
      transition: all 0.2s;
    }
    .mobile-menu-btn:hover {
      background: var(--primary);
      color: white;
      box-shadow: 0 0 20px var(--primary-glow);
    }
    @media (max-width: 1024px) {
      .mobile-menu-btn { display: flex; align-items: center; justify-content: center; }
    }

    .page-title strong {
      font-size: 24px; font-weight: 800;
      display: block;
      letter-spacing: -0.5px;
    }
    
    .page-title span {
      font-size: 12px; color: var(--text-dim); font-weight: 600;
      letter-spacing: 1px;
    }

    .topbar-actions { display: flex; gap: 16px; align-items: center; }

    .logout-btn {
      display: flex; align-items: center; gap: 10px;
      padding: 12px 22px;
      background: rgba(232, 45, 45, 0.1);
      border: 1px solid rgba(232, 45, 45, 0.3);
      border-radius: 10px;
      color: var(--primary);
      font-weight: 700; font-size: 14px;
      transition: all 0.2s;
    }
    .logout-btn:hover {
      background: var(--primary);
      color: white;
      box-shadow: 0 0 25px var(--primary-glow);
    }

    /* CONTENT AREA - SCROLLABLE */
    .content-area {
      padding: 32px;
      flex: 1;
      overflow-y: auto;
      overflow-x: hidden;
    }

    /* Card Styling */
    .card {
      background: var(--bg-card);
      border: 2px solid var(--border);
      border-radius: 16px;
      padding: 28px;
      margin-bottom: 24px;
      transition: all 0.3s ease;
      position: relative;
    }
    
    .card::after {
      content: ''; position: absolute; top: 0; right: 0;
      width: 100px; height: 100px;
      background: radial-gradient(circle, rgba(232, 45, 45, 0.1), transparent 70%);
      pointer-events: none;
    }

    .card:hover {
      border-color: var(--primary);
      box-shadow: 0 8px 40px rgba(232, 45, 45, 0.15), 0 0 0 1px var(--primary);
      transform: translateY(-4px);
    }

    .card-header {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 24px;
      padding-bottom: 16px;
      border-bottom: 2px solid var(--border);
    }

    .card-header h2 {
      font-size: 22px; font-weight: 800;
      display: flex; align-items: center; gap: 14px;
      letter-spacing: -0.5px;
    }

    .card-header h2 i { 
      color: var(--primary); 
      text-shadow: 0 0 15px var(--primary-glow);
    }

    /* Form Elements */
    .form-group { margin-bottom: 22px; }
    
    .form-label {
      display: block;
      font-size: 13px; font-weight: 700;
      color: var(--text-muted); margin-bottom: 10px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    input, select, textarea {
      width: 100%;
      padding: 14px 18px;
      background: var(--bg-input);
      border: 2px solid var(--border);
      border-radius: 10px;
      color: var(--text-main);
      font-weight: 600;
      font-size: 14px;
      transition: all 0.2s;
    }

    input:focus, select:focus, textarea:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(232, 45, 45, 0.15);
      outline: none;
    }

    input::placeholder { color: var(--text-dim); }

    /* Buttons */
    .btn {
      display: inline-flex; align-items: center; justify-content: center;
      padding: 14px 28px;
      border-radius: 10px;
      font-weight: 800; font-size: 14px;
      transition: all 0.2s;
      gap: 10px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
      border: 1px solid var(--primary);
      box-shadow: 0 0 25px var(--primary-glow);
    }
    .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 35px rgba(232, 45, 45, 0.5);
    }

    .btn-secondary {
      background: transparent;
      border: 2px solid var(--border);
      color: var(--text-main);
    }
    .btn-secondary:hover {
      border-color: var(--primary);
      color: var(--primary);
    }

    /* Tables */
    .table-container { overflow-x: auto; }
    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      text-align: left;
    }
    th {
      padding: 16px;
      font-size: 11px; font-weight: 800;
      color: var(--text-dim);
      text-transform: uppercase;
      letter-spacing: 1.5px;
      border-bottom: 2px solid var(--border);
      background: var(--bg-card);
    }
    td {
      padding: 18px 16px;
      font-size: 14px;
      border-bottom: 1px solid var(--border);
      color: var(--text-main);
    }
    tr:hover td {
      background: rgba(232, 45, 45, 0.05);
    }

    /* Status Badges */
    .badge {
      padding: 6px 14px;
      border-radius: 6px;
      font-size: 11px; font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .badge-success { background: rgba(34, 197, 94, 0.2); color: #4ade80; }
    .badge-warning { background: rgba(234, 179, 8, 0.2); color: #facc15; }
    .badge-danger { background: rgba(239, 68, 68, 0.2); color: #f87171; }

    /* Stats Grid */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 24px;
      margin-bottom: 28px;
    }

    .stat-card {
      background: var(--bg-card);
      border: 2px solid var(--border);
      border-radius: 16px;
      padding: 28px;
      transition: all 0.3s;
    }
    .stat-card:hover {
      border-color: var(--primary);
      transform: translateY(-5px);
      box-shadow: 0 10px 40px rgba(232, 45, 45, 0.2);
    }

    .stat-icon {
      width: 56px; height: 56px;
      border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      font-size: 24px;
      margin-bottom: 20px;
    }
        .stat-icon.red { background: rgba(232, 45, 45, 0.15); color: var(--primary); }
    .stat-icon.dark { background: rgba(255, 255, 255, 0.1); color: white; }
    .stat-icon.green { background: rgba(34, 197, 94, 0.15); color: #4ade80; }
    .stat-icon.orange { background: rgba(249, 115, 22, 0.15); color: #fb923c; }

    .stat-value {
      font-size: 32px; font-weight: 900;
      letter-spacing: -1px; margin-bottom: 6px;
    }
    .stat-label {
      font-size: 13px; color: var(--text-muted); font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    /* Utilities */
    .mb-4 { margin-bottom: 16px; }
    .mb-6 { margin-bottom: 24px; }
    .mt-4 { margin-top: 16px; }
    .text-center { text-align: center; }
    .d-flex { display: flex; }
    .justify-between { justify-content: space-between; }
    .items-center { align-items: center; }
    .gap-3 { gap: 12px; }
    .gap-4 { gap: 16px; }

    /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(15px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in { animation: fadeIn 0.4s ease forwards; }

    /* Responsive */
    @media (max-width: 768px) {
      .topbar { padding: 0 20px; }
      .card { padding: 20px; }
      .content-area { padding: 20px; }
      .stats-grid { grid-template-columns: 1fr; }
      .page-title strong { font-size: 18px; }
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
         <span>MGM One</span>
          <span>Multi Hotel Platform</span>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="menu-section">
        <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <i class="fas fa-gauge-high"></i>
          <span>Dashboard</span>
        </a>
      </nav>

      <!-- News Section -->
      <div class="menu-section">
        <div class="menu-title">News & Media</div>
        <div class="menu-dropdown">
          <button type="button" class="menu-item dropdown-toggle">
            <i class="fas fa-newspaper"></i>
            <span>News / Blogs</span>
            <i class="fas fa-chevron-down arrow"></i>
          </button>
          <div class="submenu">
            <a href="{{ route('admin.news.create') }}" class="submenu-item {{ request()->routeIs('admin.news.create') ? 'active' : '' }}">
              <i class="fas fa-plus"></i> Add News
            </a>
            <a href="{{ route('admin.news.index') }}" class="submenu-item {{ request()->routeIs('admin.news.index') ? 'active' : '' }}">
              <i class="fas fa-list"></i> View News
            </a>
          </div>
        </div>
      </div>

      <!-- Management Section -->
<div class="menu-section">
    <div class="menu-title">Management</div>

    <a href="{{ route('admin.hotels.index') }}"
       class="menu-item {{ request()->routeIs('admin.hotels.*') ? 'active' : '' }}">
        <i class="fas fa-hotel"></i>
        <span>Hotels</span>
    </a>
    
    <a href="{{ route('admin.departments.index') }}"
      class="menu-item {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
        <i class="fa-solid fa-building"></i>
        <span>Departments</span>
    </a>

    <a href="{{ route('mobile-app-versions.index') }}" class="menu-item">
        <i class="fas fa-mobile"></i>
        <span>Mobile App</span>
    </a>
</div>

      <!-- Employee Management -->
      <div class="menu-section">
        <div class="menu-title">Employee</div>
        <a href="{{ route('addemp') }}" class="menu-item {{ request()->routeIs('addemp') ? 'active' : '' }}">
          <i class="fas fa-user-plus"></i>
          <span>Add Employee</span>
        </a>
        <a href="{{ route('dashboard.admin.showemp') }}" class="menu-item {{ request()->routeIs('dashboard.admin.showemp') ? 'active' : '' }}">
          <i class="fas fa-users"></i>
          <span>Employee List</span>
        </a>
      </div>

    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">

      <!-- Topbar -->
      <div class="topbar">
        <button type="button" class="mobile-menu-btn" id="mobileMenuBtn">
          <i class="fas fa-bars"></i>
        </button>

        <div class="page-title">
          <strong>@yield('page-title', 'Dashboard')</strong>
          <span>MGM ONE PLATFORM ADMIN</span>
        </div>

        <div class="topbar-actions">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
              <i class="fas fa-right-from-bracket"></i>
              <span>Logout</span>
            </button>
          </form>
        </div>
      </div>

      <!-- Content Area -->
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

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeSidebar();
    });
  </script>
</body>
</html>