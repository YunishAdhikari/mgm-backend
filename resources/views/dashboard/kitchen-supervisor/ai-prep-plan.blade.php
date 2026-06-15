@extends('dashboard.kitchen-supervisor.layout')

@section('content')

<style>
    body {
        background: #09090b !important;
    }

    .ai-page {
        min-height: 100vh;
        padding: 32px;
        background:
            radial-gradient(circle at top left, rgba(139,92,246,.18), transparent 30%),
            radial-gradient(circle at top right, rgba(236,72,153,.14), transparent 30%),
            #09090b;
        color: #fafafa;
        font-family: 'Inter', sans-serif;
    }

    .ai-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 28px;
    }

    .ai-title {
        font-size: 38px;
        font-weight: 900;
        letter-spacing: -1px;
        background: linear-gradient(90deg, #8b5cf6, #ec4899, #22d3ee);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .ai-subtitle {
        color: #a1a1aa;
        font-size: 15px;
        margin-top: 6px;
    }

    .ai-btn {
        background: linear-gradient(135deg, #8b5cf6, #ec4899);
        color: #fff;
        border: none;
        border-radius: 14px;
        padding: 12px 20px;
        font-weight: 800;
        text-decoration: none;
        box-shadow: 0 12px 30px rgba(139,92,246,.25);
    }

    .ai-btn:hover {
        color: #fff;
        transform: translateY(-2px);
    }

    .panel {
        background: rgba(24,24,27,.92);
        border: 1px solid #27272a;
        border-radius: 22px;
        padding: 24px;
        box-shadow: 0 20px 60px rgba(0,0,0,.35);
        margin-bottom: 24px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 18px;
        align-items: end;
    }

    .form-label-ai {
        color: #a78bfa;
        font-size: 12px;
        font-weight: 900;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .ai-input {
        width: 100%;
        background: #1c1c1f !important;
        border: 1px solid #3f3f46 !important;
        color: #fafafa !important;
        border-radius: 14px;
        padding: 12px 14px;
        height: 48px;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }

    .kpi-card {
        background: linear-gradient(180deg, #18181b, #111113);
        border: 1px solid #27272a;
        border-radius: 22px;
        padding: 24px;
        position: relative;
        overflow: hidden;
    }

    .kpi-card::after {
        content: "";
        position: absolute;
        right: -30px;
        top: -30px;
        width: 110px;
        height: 110px;
        background: radial-gradient(circle, rgba(139,92,246,.28), transparent 70%);
    }

    .kpi-card.alert-card::after {
        background: radial-gradient(circle, rgba(239,68,68,.28), transparent 70%);
    }

    .kpi-icon {
        font-size: 28px;
        margin-bottom: 14px;
    }

    .kpi-value {
        font-size: 44px;
        font-weight: 900;
        line-height: 1;
        color: #fafafa;
    }

    .kpi-label {
        margin-top: 10px;
        color: #a1a1aa;
        font-weight: 700;
    }

    .section-title {
        font-size: 21px;
        font-weight: 900;
        margin-bottom: 6px;
    }

    .section-subtitle {
        color: #a1a1aa;
        margin-bottom: 18px;
    }

    .recommend-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .recommend-list li {
        background: #27272a;
        border: 1px solid #3f3f46;
        border-radius: 14px;
        padding: 14px 16px;
        margin-bottom: 10px;
        color: #fafafa;
        font-weight: 600;
    }

    .ai-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .ai-table th {
        color: #a78bfa;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 10px 14px;
    }

    .ai-table td {
        background: #1c1c1f;
        color: #fafafa;
        padding: 16px 14px;
        border-top: 1px solid #27272a;
        border-bottom: 1px solid #27272a;
    }

    .ai-table td:first-child {
        border-left: 1px solid #27272a;
        border-radius: 14px 0 0 14px;
    }

    .ai-table td:last-child {
        border-right: 1px solid #27272a;
        border-radius: 0 14px 14px 0;
    }

    .badge-ai {
        background: rgba(139,92,246,.18);
        color: #c4b5fd;
        border: 1px solid rgba(139,92,246,.35);
        border-radius: 999px;
        padding: 7px 12px;
        font-size: 12px;
        font-weight: 800;
    }

    .warning-panel {
        background: rgba(127,29,29,.35);
        border: 1px solid rgba(248,113,113,.45);
        border-radius: 22px;
        padding: 22px;
        margin-bottom: 24px;
        color: #fff;
    }

    .empty-row {
        text-align: center;
        color: #a1a1aa !important;
        padding: 25px !important;
    }

    @media (max-width: 992px) {
        .kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .ai-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (max-width: 576px) {
        .kpi-grid {
            grid-template-columns: 1fr;
        }

        .ai-page {
            padding: 18px;
        }

        .ai-title {
            font-size: 28px;
        }
    }
</style>

<div class="ai-page">

    <div class="ai-header">
        <div>
            <div class="ai-title">🤖 AI Kitchen Command Centre</div>
            <div class="ai-subtitle">
                Enterprise kitchen forecasting using buffet sales, recipes, ingredients and dietary notes.
            </div>
        </div>

        <a href="{{ route('kitchen.supervisor.dashboard') }}" class="ai-btn">
            ← Dashboard
        </a>
    </div>

    <div class="panel">
        <form method="GET" action="{{ route('kitchen.ai.prep') }}">
            <div class="filter-grid">
                <div>
                    <div class="form-label-ai">Service Date</div>
                    <input type="date" name="date" value="{{ $date }}" class="ai-input">
                </div>

                <div>
                    <div class="form-label-ai">AI Engine</div>
                    <input type="text" value="Buffet + Recipe Forecast Model" class="ai-input" readonly>
                </div>

                <button class="ai-btn" type="submit">
                    ⚡ Generate Forecast
                </button>
            </div>
        </form>
    </div>

    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon">👥</div>
            <div class="kpi-value">{{ $totalPax }}</div>
            <div class="kpi-label">Expected Covers</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon">🍽️</div>
            <div class="kpi-value">{{ $sales->count() }}</div>
            <div class="kpi-label">Buffet Events</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon">📦</div>
            <div class="kpi-value">{{ $ingredients->count() }}</div>
            <div class="kpi-label">Forecast Ingredients</div>
        </div>

        <div class="kpi-card alert-card">
            <div class="kpi-icon">⚠️</div>
            <div class="kpi-value">{{ $allergyWarnings->count() }}</div>
            <div class="kpi-label">Diet Alerts</div>
        </div>
    </div>

    <div class="panel">
        <div class="section-title">🧠 AI Recommendations</div>
        <div class="section-subtitle">Operational suggestions based on today's kitchen data.</div>

        <ul class="recommend-list">
            @if($totalPax > 100)
                <li>⚠ High volume detected. Prepare additional backup stock and assign extra kitchen support.</li>
            @elseif($totalPax > 0)
                <li>✅ Normal volume detected. Standard prep level should be sufficient.</li>
            @else
                <li>ℹ No buffet sales found for this date. Forecast is waiting for sales data.</li>
            @endif

            @if($allergyWarnings->count())
                <li>🚨 Dietary or allergy notes detected. Head Chef should review all notes before service.</li>
            @else
                <li>✅ No allergy or dietary risk detected from buffet notes.</li>
            @endif

            @if($ingredients->count())
                <li>📦 Ingredient forecast generated from linked buffet menus and recipe ingredients.</li>
            @else
                <li>⚠ No ingredients found. Check that buffet menu items and recipes are configured.</li>
            @endif

            <li>📊 Forecast source: buffet sales × recipe ingredient quantity × pax.</li>
        </ul>
    </div>

    @if($allergyWarnings->count())
        <div class="warning-panel">
            <div class="section-title">🚨 Dietary / Allergy Intelligence Alert</div>
            <div class="section-subtitle">These notes require kitchen attention.</div>

            @foreach($allergyWarnings as $warning)
                <p class="mb-2">
                    <strong>{{ $warning->buffet_name }}</strong> —
                    {{ $warning->note }}
                </p>
            @endforeach
        </div>
    @endif

    <div class="panel">
        <div class="section-title">🛰 Buffet Service Scan</div>
        <div class="section-subtitle">Buffet sales detected for {{ $date }}.</div>

        <div class="table-responsive">
            <table class="ai-table">
                <thead>
                    <tr>
                        <th>Buffet</th>
                        <th>Service Type</th>
                        <th>Pax</th>
                        <th>Operational Note</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td>{{ $sale->buffet_name }}</td>
                            <td>{{ ucfirst($sale->service_type ?? '-') }}</td>
                            <td><strong>{{ $sale->pax }}</strong></td>
                            <td>{{ $sale->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-row">
                                No buffet sales found for this date.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel">
        <div class="section-title">🧪 Ingredient Forecast</div>
        <div class="section-subtitle">
            Required stock calculated from buffet menus, menu items and recipe ingredients.
        </div>

        <div class="table-responsive">
            <table class="ai-table">
                <thead>
                    <tr>
                        <th>Ingredient</th>
                        <th>Total Required</th>
                        <th>AI Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ingredients as $item)
                        <tr>
                            <td>{{ $item->ingredient_name }}</td>
                            <td><strong>{{ number_format($item->total_required, 2) }}</strong></td>
                            <td><span class="badge-ai">Forecasted</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty-row">
                                No recipe ingredients found. Add recipes first.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection