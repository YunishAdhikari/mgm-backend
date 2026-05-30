@extends('dashboard.reception.layout')

@section('content')
<style>
    :root {
        --primary: #8b5cf6;
        --primary-hover: #a78bfa;
        --secondary: #ec4899;
        
        --bg-card: #27272a;
        --bg-input: #1c1c1f;
        
        --text-main: #fafafa;
        --text-muted: #a1a1aa;
        --text-dim: #71717a;
        
        --border: #3f3f46;
        
        --glow: 0 0 20px rgba(139, 92, 246, 0.3);
        
        --radius-lg: 1.5rem;
        --radius-md: 1rem;
    }

    .page-wrap {
        padding: 30px;
    }

    .page-title {
        font-size: 32px;
        font-weight: 900;
        background: linear-gradient(135deg, #fff 0%, #a1a1aa 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 6px;
    }

    .page-subtitle {
        color: var(--text-muted);
        margin-bottom: 28px;
    }

    .booking-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 22px;
    }

    .booking-card {
        background: var(--bg-card);
        padding: 34px;
        border-radius: var(--radius-lg);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        text-decoration: none;
        display: block;
        transition: all 0.25s ease;
        border: 2px solid var(--border);
        position: relative;
        overflow: hidden;
    }

    .booking-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.25s ease;
    }

    .booking-card:hover {
        transform: translateY(-6px);
        border-color: var(--primary);
        box-shadow: var(--glow);
    }

    .booking-card:hover::before {
        transform: scaleX(1);
    }

    .booking-card h3 {
        color: var(--text-main);
        font-size: 26px;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .booking-card p {
        color: var(--text-muted);
        font-weight: 500;
    }

    .booking-icon {
        font-size: 48px;
        margin-bottom: 16px;
        display: block;
    }

    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .booking-card {
        animation: fadeInUp 0.5s ease-out forwards;
        opacity: 0;
    }

    .booking-card:nth-child(1) { animation-delay: 0.1s; }
    .booking-card:nth-child(2) { animation-delay: 0.2s; }

    @media (max-width: 640px) {
        .page-wrap {
            padding: 20px;
        }

        .booking-grid {
            grid-template-columns: 1fr;
        }

        .booking-card {
            padding: 28px;
        }

        .booking-card h3 {
            font-size: 22px;
        }
    }
</style>

<div class="page-wrap">
    <h1 class="page-title">Restaurant Bookings</h1>
    <p class="page-subtitle">Choose booking type to start a new reservation.</p>

    <div class="booking-grid">

        <a href="{{ route('reception.restaurant.bookings.slots', 'afternoon_tea') }}" class="booking-card">
            <span class="booking-icon">☕</span>
            <h3>Afternoon Tea</h3>
            <p>Create afternoon tea booking and select available slot.</p>
        </a>

        <a href="{{ route('reception.restaurant.bookings.slots', 'dinner') }}" class="booking-card">
            <span class="booking-icon">🍽️</span>
            <h3>Dinner</h3>
            <p>Create dinner booking and select available slot.</p>
        </a>

    </div>
</div>
@endsection