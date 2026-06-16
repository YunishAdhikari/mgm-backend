<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MGM Ops | Hotel Operations Reimagined</title>
  <link rel="icon" type="image/png" href="{{ asset('myapp.png') }}">


    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg: #050505;
            --card: rgba(24,24,27,.72);
            --border: rgba(255,255,255,.10);
            --text: #f5f5f7;
            --muted: #a1a1aa;
            --dim: #71717a;
            --red: #ef4444;
            --red-dark: #b91c1c;
            --green: #22c55e;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .container {
            max-width: 1180px;
            margin: auto;
            padding: 0 24px;
        }

        header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 999;
            backdrop-filter: blur(24px);
            background: rgba(5,5,5,.72);
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .nav {
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            font-size: 22px;
            font-weight: 900;
            letter-spacing: -.6px;
        }

        .logo span {
            color: var(--red);
        }

        .nav-links {
            display: flex;
            gap: 34px;
        }

        .nav-links a {
            font-size: 13px;
            color: var(--muted);
            font-weight: 600;
        }

        .nav-links a:hover {
            color: white;
        }

        .nav-actions {
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 11px 18px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: .25s;
        }

        .btn-red {
            background: linear-gradient(135deg, var(--red), var(--red-dark));
            color: white;
            box-shadow: 0 14px 35px rgba(239,68,68,.26);
        }

        .btn-red:hover {
            transform: translateY(-2px);
            color: white;
        }

        .btn-dark {
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.12);
            color: white;
        }

        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 120px 0 70px;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 50% 5%, rgba(239,68,68,.24), transparent 30%),
                radial-gradient(circle at 15% 50%, rgba(255,255,255,.08), transparent 28%),
                radial-gradient(circle at 85% 70%, rgba(239,68,68,.12), transparent 34%);
            pointer-events: none;
        }

        .hero-inner {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(239,68,68,.12);
            border: 1px solid rgba(239,68,68,.28);
            color: #fca5a5;
            padding: 9px 15px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 28px;
        }

        .hero h1 {
            font-size: clamp(48px, 8vw, 94px);
            line-height: .95;
            letter-spacing: -4px;
            font-weight: 900;
            max-width: 1000px;
            margin: auto;
        }

        .hero h1 span {
            background: linear-gradient(135deg, #fff, #fca5a5, #ef4444);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            max-width: 720px;
            margin: 26px auto 36px;
            color: var(--muted);
            font-size: 20px;
            line-height: 1.7;
        }

        .hero-buttons {
            display: flex;
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 54px;
        }

        .mockup {
            max-width: 980px;
            margin: auto;
            background: linear-gradient(180deg, rgba(255,255,255,.12), rgba(255,255,255,.04));
            border: 1px solid rgba(255,255,255,.16);
            border-radius: 34px;
            padding: 14px;
            box-shadow: 0 40px 120px rgba(0,0,0,.65);
            transform: perspective(1200px) rotateX(5deg);
        }

        .mockup-window {
            background: #0b0b0d;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,.08);
        }

        .mockup-top {
            height: 42px;
            background: #18181b;
            display: flex;
            align-items: center;
            padding: 0 16px;
            gap: 8px;
        }

        .dot {
            width: 11px;
            height: 11px;
            border-radius: 50%;
            background: #ef4444;
        }

        .dot:nth-child(2) {
            background: #f59e0b;
        }

        .dot:nth-child(3) {
            background: #22c55e;
        }

        .dashboard-preview {
            padding: 28px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 18px;
        }

        .preview-card {
            background: #18181b;
            border: 1px solid #27272a;
            border-radius: 22px;
            padding: 22px;
            text-align: left;
        }

        .preview-card span {
            color: var(--dim);
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .preview-card strong {
            display: block;
            font-size: 34px;
            margin-top: 8px;
        }

        .preview-card.red strong {
            color: var(--red);
        }

        .preview-card.green strong {
            color: var(--green);
        }

        .section {
            padding: 110px 0;
        }

        .section-soft {
            background: #0b0b0d;
        }

        .section-head {
            text-align: center;
            max-width: 760px;
            margin: 0 auto 60px;
        }

        .section-head small {
            color: var(--red);
            font-weight: 900;
            letter-spacing: .12em;
            text-transform: uppercase;
            font-size: 12px;
        }

        .section-head h2 {
            font-size: clamp(34px, 5vw, 62px);
            line-height: 1.05;
            letter-spacing: -2px;
            margin: 14px 0;
        }

        .section-head p {
            color: var(--muted);
            font-size: 18px;
            line-height: 1.7;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
        }

        .glass-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 28px;
            padding: 30px;
            backdrop-filter: blur(20px);
            transition: .3s;
        }

        .glass-card:hover {
            transform: translateY(-8px);
            border-color: rgba(239,68,68,.45);
            box-shadow: 0 24px 70px rgba(0,0,0,.45);
        }

        .icon {
            width: 58px;
            height: 58px;
            border-radius: 20px;
            background: rgba(239,68,68,.14);
            color: #f87171;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 22px;
        }

        .glass-card h3 {
            font-size: 21px;
            margin-bottom: 12px;
        }

        .glass-card p {
            color: var(--muted);
            line-height: 1.7;
            font-size: 14px;
        }

        .department-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 18px;
        }

        .dept-card {
            background: #111113;
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 26px;
            overflow: hidden;
            transition: .3s;
        }

        .dept-card:hover {
            transform: translateY(-8px);
            border-color: rgba(239,68,68,.45);
        }

        .dept-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            filter: saturate(.9);
        }

        .dept-body {
            padding: 20px;
        }

        .dept-body h3 {
            font-size: 17px;
            margin-bottom: 8px;
        }

        .dept-body p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.6;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
        }

        .news-card {
            background: #111113;
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 26px;
            overflow: hidden;
            transition: .3s;
        }

        .news-card:hover {
            transform: translateY(-8px);
            border-color: rgba(239,68,68,.45);
        }

        .news-card img {
            width: 100%;
            height: 210px;
            object-fit: cover;
        }

        .news-body {
            padding: 24px;
        }

        .news-date {
            color: #f87171;
            font-size: 12px;
            font-weight: 800;
        }

        .news-body h3 {
            font-size: 20px;
            margin: 10px 0;
        }

        .news-body p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
        }

        .about-panel {
            background:
                radial-gradient(circle at top right, rgba(239,68,68,.18), transparent 30%),
                rgba(24,24,27,.78);
            border: 1px solid rgba(255,255,255,.10);
            border-radius: 34px;
            padding: 54px;
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            gap: 48px;
            align-items: center;
        }

        .about-panel h2 {
            font-size: clamp(34px, 4vw, 54px);
            line-height: 1.05;
            letter-spacing: -2px;
            margin-bottom: 18px;
        }

        .about-panel p {
            color: var(--muted);
            line-height: 1.8;
            font-size: 16px;
        }

        .about-list {
            margin-top: 28px;
            display: grid;
            gap: 14px;
        }

        .about-list div {
            display: flex;
            gap: 12px;
            color: #d4d4d8;
        }

        .about-list i {
            color: var(--green);
            margin-top: 4px;
        }

        .about-metrics {
            display: grid;
            gap: 14px;
        }

        .metric {
            background: #111113;
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 24px;
            padding: 24px;
        }

        .metric strong {
            display: block;
            font-size: 34px;
        }

        .metric span {
            color: var(--muted);
        }

        .cta {
            padding: 100px 0;
        }

        .cta-box {
            text-align: center;
            background:
                radial-gradient(circle at 50% 0%, rgba(255,255,255,.18), transparent 26%),
                linear-gradient(135deg, #ef4444, #7f1d1d);
            border-radius: 38px;
            padding: 74px 28px;
            box-shadow: 0 34px 100px rgba(239,68,68,.25);
        }

        .cta-box h2 {
            font-size: clamp(34px, 5vw, 64px);
            line-height: 1;
            letter-spacing: -2px;
            margin-bottom: 18px;
        }

        .cta-box p {
            opacity: .9;
            font-size: 18px;
            margin-bottom: 28px;
        }

        .cta-box .btn {
            background: white;
            color: #991b1b;
        }

        footer {
            padding: 42px 0;
            border-top: 1px solid rgba(255,255,255,.08);
            color: var(--dim);
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .reveal {
            opacity: 0;
            transform: translateY(38px);
            transition: all .9s cubic-bezier(.2,.8,.2,1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .mobile-menu {
            display: none;
        }

        @media(max-width: 1050px) {
            .feature-grid,
            .news-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .department-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .about-panel {
                grid-template-columns: 1fr;
            }
        }

        @media(max-width: 760px) {
            .nav-links,
            .nav-actions {
                display: none;
            }

            .mobile-menu {
                display: block;
                background: none;
                border: none;
                color: white;
                font-size: 22px;
            }

            .hero {
                padding-top: 105px;
            }

            .hero h1 {
                letter-spacing: -2px;
            }

            .dashboard-preview {
                grid-template-columns: 1fr;
            }

            .mockup {
                transform: none;
            }

            .feature-grid,
            .news-grid,
            .department-grid {
                grid-template-columns: 1fr;
            }

            .about-panel {
                padding: 32px;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>

<body>

<header>
    <div class="container nav">
        <a href="/" class="logo">MGM<span>Ops</span></a>

        <nav class="nav-links">
            <a href="#features">Features</a>
            <a href="#departments">Departments</a>
            <a href="#news">News</a>
            <a href="#about">About</a>
        </nav>

        <div class="nav-actions">
            <a href="{{ route('login') }}" class="btn btn-dark">Login</a>
            <a href="#features" class="btn btn-red">Explore</a>
        </div>

        <button class="mobile-menu">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</header>

<section class="hero" id="home">
    <div class="container hero-inner reveal">
        <div class="pill">
            <i class="fas fa-hotel"></i>
            Internal hotel operations platform
        </div>

        <h1>Hotel operations.<br><span>Reimagined.</span></h1>

        <p>
            MGM Ops connects reception, housekeeping, maintenance, F&B, kitchen,
            and management into one smooth internal platform.
        </p>

        <div class="hero-buttons">
            <a href="{{ route('login') }}" class="btn btn-red">
                <i class="fas fa-sign-in-alt"></i>
                Login to System
            </a>

            <a href="#departments" class="btn btn-dark">
                View Departments
            </a>
        </div>

        <div class="mockup">
            <div class="mockup-window">
                <div class="mockup-top">
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>

                <div class="dashboard-preview">
                    <div class="preview-card red">
                        <span>Departments</span>
                        <strong>{{ $totalDepartments }}</strong>
                    </div>

                    <div class="preview-card">
                        <span>Active Maintenance</span>
                        <strong>{{ $activemaintanance }}</strong>
                    </div>

                    <div class="preview-card green">
                        <span>System Access</span>
                        <strong>24/7</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="features">
    <div class="container">
        <div class="section-head reveal">
            <small>Platform Features</small>
            <h2>One platform for daily hotel operations.</h2>
            <p>Built for real staff workflows, not just dashboards.</p>
        </div>

        <div class="feature-grid">
            <div class="glass-card reveal">
                <div class="icon"><i class="fas fa-bed"></i></div>
                <h3>Housekeeping</h3>
                <p>Room allocation, cleaning progress, inspection queue, rota, holiday calendar, and productivity reports.</p>
            </div>

            <div class="glass-card reveal">
                <div class="icon"><i class="fas fa-tools"></i></div>
                <h3>Maintenance</h3>
                <p>Report faults, assign jobs, track progress, and keep a full record of completed repairs.</p>
            </div>

            <div class="glass-card reveal">
                <div class="icon"><i class="fas fa-qrcode"></i></div>
                <h3>Attendance QR</h3>
                <p>Staff clock in and out using secure QR codes directly from the mobile app.</p>
            </div>

            <div class="glass-card reveal">
                <div class="icon"><i class="fas fa-utensils"></i></div>
                <h3>Restaurant Booking</h3>
                <p>Manage slots, floor plans, tables, guests, pax, vouchers, and overbooking workflows.</p>
            </div>

            <div class="glass-card reveal">
                <div class="icon"><i class="fas fa-users-gear"></i></div>
                <h3>Role Based Access</h3>
                <p>Admin, manager, supervisor, and staff access levels with department-specific permissions.</p>
            </div>

            <div class="glass-card reveal">
                <div class="icon"><i class="fas fa-newspaper"></i></div>
                <h3>Internal Updates</h3>
                <p>Share hotel news, announcements, internal communication, and operational updates.</p>
            </div>
        </div>
    </div>
</section>

<section class="section section-soft" id="departments">
    <div class="container">
        <div class="section-head reveal">
            <small>Departments</small>
            <h2>Designed for every hotel team.</h2>
            <p>Each department works inside the same connected system.</p>
        </div>

        <div class="department-grid">
            <div class="dept-card reveal">
                <img src="{{ asset('assets/landing/Reception.jpeg') }}" alt="Reception">
                <div class="dept-body">
                    <h3>Reception</h3>
                    <p>Guest operations, bookings, requests, and internal coordination.</p>
                </div>
            </div>

            <div class="dept-card reveal">
                <img src="{{ asset('assets/landing/Housekeeping.jpg') }}" alt="Housekeeping">
                <div class="dept-body">
                    <h3>Housekeeping</h3>
                    <p>Room readiness, allocation, cleaning, and inspection workflows.</p>
                </div>
            </div>

            <div class="dept-card reveal">
                <img src="{{ asset('assets/landing/maintanance.jpg') }}" alt="Maintenance">
                <div class="dept-body">
                    <h3>Maintenance</h3>
                    <p>Fault reporting, repair tracking, and job completion history.</p>
                </div>
            </div>

            <div class="dept-card reveal">
                <img src="{{ asset('assets/landing/kitchen.jpg') }}" alt="Kitchen">
                <div class="dept-body">
                    <h3>Kitchen</h3>
                    <p>Kitchen workflows and coordination with service departments.</p>
                </div>
            </div>

            <div class="dept-card reveal">
                <img src="{{ asset('assets/landing/fb.png') }}" alt="F&B">
                <div class="dept-body">
                    <h3>F&B</h3>
                    <p>Restaurant bookings, floor plans, service tasks, and operations.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="news">
    <div class="container">
        <div class="section-head reveal">
            <small>Latest Updates</small>
            <h2>News & internal updates.</h2>
            <p>Keep teams informed with hotel announcements and updates.</p>
        </div>

        <div class="news-grid">
            @forelse($activeNews as $item)
                <div class="news-card reveal">
                    @if($item->image)
                        <img src="{{ asset('uploads/news/' . $item->image) }}" alt="{{ $item->title }}">
                    @else
                        <img src="{{ asset('assets/landing/news-placeholder.jpg') }}" alt="News">
                    @endif

                    <div class="news-body">
                        <span class="news-date">
                            <i class="far fa-calendar"></i>
                            {{ $item->created_at->format('d M Y') }}
                        </span>

                        <h3>{{ $item->title }}</h3>
                        <p>{{ Str::limit($item->description, 110) }}</p>
                    </div>
                </div>
            @empty
                <div class="glass-card reveal" style="grid-column: 1 / -1; text-align:center;">
                    <div class="icon" style="margin:auto auto 18px;">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h3>No active news available</h3>
                    <p>Updates will appear here once published by the admin team.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="section section-soft" id="about">
    <div class="container">
        <div class="about-panel reveal">
            <div>
                <small style="color:#ef4444;font-weight:900;text-transform:uppercase;letter-spacing:.12em;">Why MGM Ops</small>
                <h2>Built around real hotel operations.</h2>

                <p>
                    MGM Ops helps departments work together with better visibility,
                    faster communication, and cleaner accountability.
                </p>

                <div class="about-list">
                    <div><i class="fas fa-check-circle"></i> Role-based access for staff, supervisors, managers, and admin</div>
                    <div><i class="fas fa-check-circle"></i> Web dashboard and mobile app working together</div>
                    <div><i class="fas fa-check-circle"></i> Built for daily hotel workflows and live operations</div>
                    <div><i class="fas fa-check-circle"></i> Scalable foundation for multiple hotel departments</div>
                </div>
            </div>

            <div class="about-metrics">
                <div class="metric">
                    <strong>{{ $totalDepartments }}</strong>
                    <span>Departments connected</span>
                </div>

                <div class="metric">
                    <strong>6+</strong>
                    <span>Operational modules</span>
                </div>

                <div class="metric">
                    <strong>24/7</strong>
                    <span>Internal access</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta">
    <div class="container">
        <div class="cta-box reveal">
            <h2>Ready to simplify hotel operations?</h2>
            <p>Access MGM Ops and continue managing your hotel team smarter.</p>

            <a href="{{ route('login') }}" class="btn">
                Go to Login
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<footer>
    <div class="container footer-content">
        <div>
            <strong>MGM<span style="color:#ef4444;">Ops</span></strong>
            <p>Modern internal hotel operations platform.</p>
        </div>

        <p>&copy; {{ date('Y') }} MGM Hotel System. All rights reserved.</p>
    </div>
</footer>

<script>
    const reveals = document.querySelectorAll('.reveal');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, {
        threshold: 0.12
    });

    reveals.forEach(item => observer.observe(item));

    window.addEventListener('scroll', () => {
        const header = document.querySelector('header');

        if (window.scrollY > 50) {
            header.style.background = 'rgba(5,5,5,.9)';
        } else {
            header.style.background = 'rgba(5,5,5,.72)';
        }
    });
</script>

</body>
</html>