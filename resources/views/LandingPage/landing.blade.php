<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MGM Ops | Hotel Internal Management Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--dark);
            color: var(--text);
            line-height: 1.6;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Header */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(15, 15, 15, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .nav-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 72px;
        }

        .logo {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .logo span {
            color: var(--primary);
        }

        .nav-links {
            display: flex;
            gap: 40px;
        }

        .nav-links a {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-muted);
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--text);
        }

        .nav-actions {
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
        }

        .btn-outline {
            border: 1px solid var(--gray-light);
            color: var(--text);
        }

        .btn-outline:hover {
            border-color: var(--text);
            background: rgba(255, 255, 255, 0.05);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(220, 38, 38, 0.3);
        }

        .btn-secondary {
            background: var(--gray);
            color: var(--text);
            border: none;
        }

        .btn-secondary:hover {
            background: var(--gray-light);
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 72px;
            background: 
                radial-gradient(ellipse at 20% 50%, rgba(220, 38, 38, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(220, 38, 38, 0.1) 0%, transparent 40%),
                var(--dark);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 80px;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .hero-text .tag {
            display: inline-block;
            padding: 8px 16px;
            background: rgba(220, 38, 38, 0.15);
            border: 1px solid rgba(220, 38, 38, 0.3);
            border-radius: 100px;
            color: var(--primary-light);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 24px;
        }

        .hero-text h1 {
            font-size: 56px;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -1px;
            margin-bottom: 20px;
        }

        .hero-text h1 span {
            color: var(--primary);
        }

        .hero-text p {
            font-size: 18px;
            color: var(--text-muted);
            margin-bottom: 32px;
            max-width: 500px;
        }

        .hero-buttons {
            display: flex;
            gap: 16px;
            margin-bottom: 56px;
        }

        .hero-stats {
            display: flex;
            gap: 40px;
        }

        .stat-card h3 {
            font-size: 36px;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 4px;
        }

        .stat-card p {
            font-size: 14px;
            color: var(--text-dim);
            margin-bottom: 0;
        }

        .hero-panel {
            position: relative;
        }

        .dashboard-card {
            background: var(--dark-secondary);
            border: 1px solid var(--gray);
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }

        .dashboard-card h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--text-muted);
        }

        .dashboard-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid var(--gray);
        }

        .dashboard-item:last-child {
            border-bottom: none;
        }

        .dashboard-item span {
            font-size: 14px;
            color: var(--text-muted);
        }

        .dashboard-item strong {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary);
        }

        /* Section Styles */
        .section {
            padding: 120px 0;
        }

        .section-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 64px;
        }

        .section-tag {
            display: inline-block;
            padding: 6px 14px;
            background: var(--gray);
            border-radius: 100px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .section-header h2 {
            font-size: 40px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .section-header p {
            font-size: 18px;
            color: var(--text-muted);
        }

        .section-soft {
            background: var(--dark-secondary);
        }

        /* Features */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .feature-card {
            background: var(--dark);
            border: 1px solid var(--gray);
            border-radius: 16px;
            padding: 32px;
            transition: all 0.3s;
        }

        .feature-card:hover {
            border-color: var(--primary);
            transform: translateY(-4px);
        }

        .feature-card .icon {
            width: 56px;
            height: 56px;
            background: rgba(220, 38, 38, 0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: var(--primary);
        }

        .feature-card h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .feature-card p {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.7;
        }

        /* News */
        .news-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .news-card {
            background: var(--dark);
            border: 1px solid var(--gray);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s;
        }

        .news-card:hover {
            transform: translateY(-4px);
            border-color: var(--primary);
        }

        .news-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .news-body {
            padding: 24px;
        }

        .news-date {
            font-size: 12px;
            color: var(--primary);
            font-weight: 600;
        }

        .news-body h3 {
            font-size: 18px;
            font-weight: 600;
            margin: 8px 0 12px;
        }

        .news-body p {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .no-news {
            grid-column: 1 / -1;
            text-align: center;
            color: var(--text-muted);
            padding: 40px;
        }

        /* Departments */
        .department-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }

        .department-card {
            background: var(--dark);
            border: 1px solid var(--gray);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s;
        }

        .department-card:hover {
            transform: translateY(-4px);
            border-color: var(--primary);
        }

        .department-card img {
            width: 100%;
            height: 140px;
            object-fit: cover;
        }

        .department-body {
            padding: 20px;
        }

        .department-body h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .department-body p {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* About */
        .about-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
            background: var(--dark-secondary);
            border: 1px solid var(--gray);
            border-radius: 24px;
            padding: 64px;
        }

        .about-text h2 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .about-text > p {
            font-size: 16px;
            color: var(--text-muted);
            margin-bottom: 32px;
        }

        .about-list {
            list-style: none;
        }

        .about-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            font-size: 15px;
            color: var(--text-muted);
        }

        .about-list li i {
            color: var(--primary);
        }

        /* CTA */
        .cta-section {
            padding: 100px 0;
        }

        .cta-box {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 24px;
            padding: 64px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cta-box h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .cta-box p {
            font-size: 16px;
            opacity: 0.9;
        }

        .cta-box .btn {
            background: white;
            color: var(--primary);
        }

        .cta-box .btn:hover {
            background: var(--text);
            transform: translateY(-2px);
        }

        /* Footer */
                /* Footer */
        .footer {
            padding: 40px 0;
            border-top: 1px solid var(--gray);
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .footer h3 span {
            color: var(--primary);
        }

        .footer p {
            font-size: 14px;
            color: var(--text-dim);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .hero-grid {
                grid-template-columns: 1fr;
                gap: 60px;
                text-align: center;
            }

            .hero-text p {
                margin: 0 auto 32px;
            }

            .hero-buttons {
                justify-content: center;
            }

            .hero-stats {
                justify-content: center;
            }

            .feature-grid, .news-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .department-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .nav-links, .nav-actions {
                display: none;
            }

            .menu-toggle {
                display: block;
            }

            .hero-text h1 {
                font-size: 36px;
            }

            .feature-grid, .news-grid, .department-grid {
                grid-template-columns: 1fr;
            }

            .about-box, .cta-box {
                grid-template-columns: 1fr;
                flex-direction: column;
                text-align: center;
                gap: 32px;
            }

            .footer-content {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container nav-content">
            <a href="/" class="logo">MGM<span>Ops</span></a>

            <nav class="nav-links">
                <a href="#home">Home</a>
                <a href="#features">Features</a>
                <a href="#news">News</a>
                <a href="#departments">Departments</a>
                <a href="#about">About</a>
            </nav>

            <div class="nav-actions">
                <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
                <a href="#departments" class="btn btn-primary">Explore</a>
            </div>

            <button class="menu-toggle"><i class="fas fa-bars"></i></button>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container hero-grid">
            <div class="hero-text">
                <span class="tag">Internal Hotel Management Platform</span>
                <h1>Run hotel operations <span>smarter</span> with one modern system</h1>
                <p>
                    Manage complaints, maintenance logs, department handovers, SOP approvals,
                    employee points, and daily task flow for managers, supervisors, and staff.
                </p>

                <div class="hero-buttons">
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Login to System
                    </a>
                    <a href="#features" class="btn btn-secondary">View Features</a>
                </div>

                <div class="hero-stats">
                    <div class="stat-card">
                        <h3>{{ $totalDepartments }}</h3>
                        <p>Core Departments</p>
                    </div>
                    <div class="stat-card">
                        <h3>6+</h3>
                        <p>Operational Modules</p>
                    </div>
                    <div class="stat-card">
                        <h3>24/7</h3>
                        <p>Internal Access</p>
                    </div>
                </div>
            </div>

            <div class="hero-panel">
                <div class="dashboard-card">
                    <h3><i class="fas fa-chart-line"></i> Today's Overview</h3>
                    <div class="dashboard-item">
                        <span>Open Complaints</span>
                        <strong>0</strong>
                    </div>
                    <div class="dashboard-item">
                        <span>Maintenance Jobs</span>
                        <strong>{{ $activemaintanance }}</strong>
                    </div>
                    <div class="dashboard-item">
                        <span>Pending Handovers</span>
                        <strong>4</strong>
                    </div>
                    <div class="dashboard-item">
                        <span>SOP Approvals</span>
                        <strong>3</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section" id="features">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Platform Features</span>
                <h2>Everything your hotel team needs in one place</h2>
                <p>Built for real hotel workflows across operations, supervision, and management.</p>
            </div>

            <div class="feature-grid">
                <div class="feature-card">
                    <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                    <h3>Guest Complaints</h3>
                    <p>Guests can raise complaints visible to managers and supervisors for quick action.</p>
                </div>

                <div class="feature-card">
                    <div class="icon"><i class="fas fa-tools"></i></div>
                    <h3>Maintenance Tracker</h3>
                    <p>Track issues, assign work, monitor progress, and maintain history of all technical jobs.</p>
                </div>

                <div class="feature-card">
                    <div class="icon"><i class="fas fa-newspaper"></i></div>
                    <h3>Internal News & Blogs</h3>
                    <p>Share hotel updates, staff announcements, policies, and internal communication.</p>
                </div>

                <div class="feature-card">
                    <div class="icon"><i class="fas fa-trophy"></i></div>
                    <h3>Employee Point System</h3>
                    <p>Reward strong performance and support fair employee-of-the-month selection.</p>
                </div>

                <div class="feature-card">
                    <div class="icon"><i class="fas fa-random"></i></div>
                    <h3>Department Handover</h3>
                    <p>Record pending tasks, updates, and shift notes clearly for the next team.</p>
                </div>

                <div class="feature-card">
                    <div class="icon"><i class="fas fa-tasks"></i></div>
                    <h3>Task Management</h3>
                    <p>Assign and track daily tasks with status updates and completion tracking.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section class="section section-soft" id="news">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Latest Updates</span>
                <h2>News & Blogs</h2>
                <p>Stay updated with latest hotel announcements and internal news.</p>
            </div>

            <div class="news-grid">
                @forelse($activeNews as $item)
                    <div class="news-card">
                        @if($item->image)
                            <img src="{{ asset('uploads/news/' . $item->image) }}" alt="{{ $item->title }}">
                        @else
                            <img src="{{ asset('assets/landing/news-placeholder.jpg') }}" alt="News">
                        @endif

                        <div class="news-body">
                            <span class="news-date">
                                <i class="far fa-calendar"></i> {{ $item->created_at->format('d M Y') }}
                            </span>
                            <h3>{{ $item->title }}</h3>
                            <p>{{ Str::limit($item->description, 100) }}</p>
                        </div>
                    </div>
                @empty
                    <div class="no-news">
                        <i class="fas fa-newspaper"></i>
                        <p>No active news available.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Departments Section - YOUR LOCAL IMAGES -->
    <section class="section" id="departments">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Departments</span>
                <h2>Designed for every major hotel team</h2>
                <p>Each department can operate inside the same system with its own workflow and access level.</p>
            </div>

            <div class="department-grid">
                <div class="department-card">
                    <img src="{{ asset('assets/landing/Reception.jpeg') }}" alt="Reception">
                    <div class="department-body">
                        <h3><i class="fas fa-concierge-bell"></i> Reception</h3>
                        <p>Manage guest-facing issues and cross-department communication.</p>
                    </div>
                </div>

                <div class="department-card">
                    <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=400&q=80" alt="F&B">
                    <div class="department-body">
                        <h3><i class="fas fa-utensils"></i> F&B</h3>
                        <p>Track service tasks and responsibilities for food and beverage teams.</p>
                    </div>
                </div>

                <div class="department-card">
                    <img src="{{ asset('assets/landing/Housekeeping.jpg') }}" alt="Housekeeping">
                    <div class="department-body">
                        <h3><i class="fas fa-bed"></i> Housekeeping</h3>
                        <p>Room readiness, cleaning updates, and shift handovers.</p>
                    </div>
                </div>

                <div class="department-card">
                    <img src="{{ asset('assets/landing/Kitchen.jpg') }}" alt="Kitchen">
                    <div class="department-body">
                        <h3><i class="fas fa-hat-cook"></i> Kitchen</h3>
                        <p>Kitchen operations, order tracking, and coordination with service.</p>
                    </div>
                </div>

                <div class="department-card">
                    <img src="{{ asset('assets/landing/maintanance.jpg') }}" alt="Maintenance">
                    <div class="department-body">
                        <h3><i class="fas fa-wrench"></i> Maintenance</h3>
                        <p>Log faults, schedule repairs, and monitor work completion.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section" id="about">
        <div class="container about-box">
            <div class="about-text">
                <span class="section-tag">Why This Platform</span>
                <h2>Built around your real hotel workflow</h2>
                <p>This platform is designed for managers, supervisors, and operational users. It supports accountability, speed, and smoother coordination between departments.</p>
                
                <ul class="about-list">
                    <li><i class="fas fa-check-circle"></i> Role-based access for managers, supervisors, and staff</li>
                    <li><i class="fas fa-check-circle"></i> Department-specific visibility and actions</li>
                    <li><i class="fas fa-check-circle"></i> Faster issue resolution and cleaner communication</li>
                    <li><i class="fas fa-check-circle"></i> Professional foundation for web and mobile expansion</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container cta-box">
            <div>
                <h2>Let's begin our journey with new hotel software?</h2>
                <p>Start with the web platform now and expand to mobile later.</p>
            </div>
            <a href="{{ route('login') }}" class="btn btn-primary">
                <i class="fas fa-arrow-right"></i> Go to Login
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container footer-content">
            <div>
                <h3>MGM<span>Ops</span></h3>
                <p>Modern hotel operations platform for internal team management.</p>
            </div>
            <div>
                <p>&copy; {{ date('Y') }} MGM Hotel System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Mobile Menu Toggle
        document.querySelector('.menu-toggle')?.addEventListener('click', () => {
            document.querySelector('.nav-links')?.classList.toggle('active');
            document.querySelector('.nav-actions')?.classList.toggle('active');
        });

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href'))?.scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Header Scroll Effect
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 50) {
                header.style.background = 'rgba(15, 15, 15, 0.95)';
            } else {
                header.style.background = 'rgba(15, 15, 15, 0.9)';
            }
        });
    </script>
</body>
</html>