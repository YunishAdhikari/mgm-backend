@extends('dashboard.supervisor.layout')

@section('content')

<div class="cards">

    <a href="{{ route('supervisor.holidays.calendar') }}" class="card">
        <i class="fa-solid fa-calendar-days"></i>
        <h3>Holiday Calendar</h3>
        <p>View approved and pending holidays.</p>
    </a>

    <a href="{{ route('supervisor.rota.index') }}" class="card">
        <i class="fa-solid fa-clipboard-list"></i>
        <h3>Rota Maker</h3>
        <p>Create rota drafts for staff.</p>
    </a>

</div>

<style>
.cards {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 22px;
}

.card {
    background: white;
    padding: 30px;
    border-radius: 24px;
    box-shadow: 0 10px 28px rgba(0,0,0,0.06);
    text-decoration: none;
    color: #111827;
}

.card i {
    font-size: 38px;
    color: #1583ff;
    margin-bottom: 18px;
}

.card h3 {
    margin: 0 0 8px;
    font-size: 24px;
}

.card p {
    margin: 0;
    color: #6b7280;
}

@media(max-width: 800px) {
    .cards {
        grid-template-columns: 1fr;
    }
}
</style>

@endsection