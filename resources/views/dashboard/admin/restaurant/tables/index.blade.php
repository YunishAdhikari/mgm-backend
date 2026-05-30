@extends('dashboard.admin.layout')

@section('content')
<style>
    :root {
        --bg-page: #09090b;
        --bg-card: #27272a;
        --bg-input: #1c1c1f;
        --text-main: #fafafa;
        --text-muted: #a1a1aa;
        --text-dim: #71717a;
        --border: #3f3f46;
        --primary: #8b5cf6;
        --primary-hover: #a78bfa;
    }

    .page-wrap { 
        max-width: 1300px; 
        margin: 0 auto; 
        padding: 20px; 
    }

    .page-title { 
        font-size: 28px; 
        font-weight: 800; 
        margin-bottom: 6px;
        color: var(--text-main);
    }

    .page-subtitle { 
        color: var(--text-muted); 
        margin-bottom: 20px; 
        font-size: 15px;
    }

    .card-box { 
        background: var(--bg-card); 
        border: 1px solid var(--border);
        border-radius: 16px; 
        padding: 20px; 
        box-shadow: 0 8px 25px rgba(0,0,0,0.3); 
        margin-bottom: 20px; 
    }

    .card-title { 
        font-size: 18px; 
        font-weight: 700; 
        margin-bottom: 16px;
        color: var(--text-main);
    }

    .form-grid { 
        display: grid; 
        grid-template-columns: repeat(4, 1fr); 
        gap: 14px; 
    }

    .form-row {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    label { 
        font-weight: 600; 
        font-size: 13px;
        color: var(--text-muted);
    }

    input[type="text"], 
    input[type="number"], 
    select { 
        width: 100%; 
        padding: 10px 12px; 
        border: 2px solid var(--border); 
        border-radius: 8px; 
        font-size: 14px;
        background: var(--bg-input);
        color: var(--text-main);
    }

    input:focus, select:focus {
        outline: none;
        border-color: var(--primary);
    }

    input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: var(--primary);
        margin-right: 6px;
    }

    .btn-main { 
        background: var(--primary); 
        color: white; 
        border: none; 
        border-radius: 8px; 
        padding: 10px 16px; 
        font-weight: 700; 
        font-size: 14px;
        cursor: pointer;
        text-decoration: none; 
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-main:hover {
        background: var(--primary-hover);
    }

    .btn-update { 
        background: rgba(16, 185, 129, 0.15); 
        color: #6ee7b7; 
        border: none; 
        border-radius: 6px; 
        padding: 6px 10px; 
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
    }

    .btn-delete { 
        background: rgba(239, 68, 68, 0.15); 
        color: #fca5a5; 
        border: none; 
        border-radius: 6px; 
        padding: 6px 10px; 
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
    }

    .success-box { 
        background: rgba(16, 185, 129, 0.15); 
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #6ee7b7; 
        padding: 10px 14px; 
        border-radius: 8px; 
        margin-bottom: 16px; 
        font-weight: 600; 
        font-size: 14px;
    }

    .error-box { 
        background: rgba(239, 68, 68, 0.15); 
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #fca5a5; 
        padding: 10px 14px; 
        border-radius: 8px; 
        margin-bottom: 16px; 
        font-weight: 600; 
        font-size: 14px;
    }

    .table-scroll { 
        overflow-x: auto; 
        -webkit-overflow-scrolling: touch;
    }

    table { 
        width: 100%; 
        border-collapse: collapse; 
        min-width: 700px;
    }

    thead { background: var(--bg-input); }

    th { 
        text-align: left; 
        padding: 10px 8px; 
        font-size: 12px; 
        color: var(--text-muted);
        font-weight: 700;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    td { 
        padding: 8px; 
        border-bottom: 1px solid var(--border);
        color: var(--text-main);
    }

    td input, td select {
        padding: 6px 8px;
        font-size: 13px;
        width: 80px;
    }

    td:first-child input {
        width: 60px;
    }

    .actions { 
        display: flex; 
        gap: 6px; 
        white-space: nowrap;
    }

    .top-actions { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 16px; 
        flex-wrap: wrap; 
        gap: 12px; 
    }

    .top-actions .card-title { margin-bottom: 0; }

    .empty-text {
        text-align: center;
        color: var(--text-muted);
        padding: 20px;
        font-size: 14px;
    }

    /* RESPONSIVE STYLES */
    @media (max-width: 900px) {
        .form-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .page-wrap {
            padding: 12px;
        }

        .page-title {
            font-size: 22px;
        }

        .card-box {
            padding: 14px;
            border-radius: 12px;
        }

        .card-title {
            font-size: 16px;
        }

        .form-grid {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .top-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .top-actions .btn-main {
            justify-content: center;
        }

        .actions {
            flex-direction: column;
            gap: 4px;
        }

        .btn-update, .btn-delete {
            width: 100%;
            justify-content: center;
            padding: 8px;
        }
    }

    @media (max-width: 400px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        td input, td select {
            width: 100%;
            min-width: 60px;
        }
    }
</style>

<div class="page-wrap">

    <h1 class="page-title">Restaurant Tables</h1>
    <p class="page-subtitle">Manage your restaurant floor layout & capacity.</p>

    @if(session('success'))
        <div class="success-box">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="error-box">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="card-box">
        <h3 class="card-title">Add New Table</h3>

        <form method="POST" action="{{ route('restaurant.tables.store') }}">
            @csrf

            <div class="form-grid">
                <div class="form-row">
                    <label>Table Name</label>
                    <input type="text" name="table_name" placeholder="T1" required>
                </div>

                <div class="form-row">
                    <label>Capacity</label>
                    <input type="number" name="capacity" min="1" placeholder="4" required>
                </div>

                <div class="form-row">
                    <label>Shape</label>
                    <select name="table_shape" required>
                        <option value="square">Square</option>
                        <option value="round">Round</option>
                        <option value="horizontal">Horizontal</option>
                        <option value="vertical">Vertical</option>
                        <option value="banquet">Banquet</option>
                    </select>
                </div>

                <div class="form-row">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="available">Available</option>
                        <option value="reserved">Reserved</option>
                        <option value="occupied">Occupied</option>
                        <option value="out_of_service">Out of Service</option>
                    </select>
                </div>

                <div class="form-row">
                    <label>Position X</label>
                    <input type="number" name="position_x" min="0" value="0" required>
                </div>

                <div class="form-row">
                    <label>Position Y</label>
                    <input type="number" name="position_y" min="0" value="0" required>
                </div>

                <div class="form-row" style="flex-direction: row; align-items: center; gap: 10px; padding-top: 24px;">
                    <input type="checkbox" name="is_active" value="1" checked>
                    <label style="margin: 0;">Active</label>
                    <button type="submit" class="btn-main">Add</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card-box">
        <div class="top-actions">
            <h3 class="card-title">Existing Tables</h3>
            <a href="{{ route('restaurant.tables.floor-plan') }}" class="btn-main">Floor Plan</a>
        </div>

        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Table</th>
                        <th>Cap</th>
                        <th>Shape</th>
                        <th>Status</th>
                        <th>X</th>
                        <th>Y</th>
                        <th>Act</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($tables as $table)
                        <tr>
                            <form method="POST" action="{{ route('restaurant.tables.update', $table) }}">
                                @csrf
                                @method('PUT')

                                <td><input type="text" name="table_name" value="{{ $table->table_name }}" required></td>
                                <td><input type="number" name="capacity" value="{{ $table->capacity }}" min="1" required></td>
                                <td>
                                    <select name="table_shape" required>
                                        @foreach(['square', 'round', 'horizontal', 'vertical', 'banquet'] as $shape)
                                            <option value="{{ $shape }}" {{ $table->table_shape === $shape ? 'selected' : '' }}>{{ ucfirst($shape) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="status" required>
                                        @foreach(['available', 'reserved', 'occupied', 'out_of_service'] as $status)
                                            <option value="{{ $status }}" {{ $table->status === $status ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $status)) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="position_x" value="{{ $table->position_x }}" min="0" required></td>
                                <td><input type="number" name="position_y" value="{{ $table->position_y }}" min="0" required></td>
                                <td><input type="checkbox" name="is_active" value="1" {{ $table->is_active ? 'checked' : '' }}></td>

                                <td>
                                    <div class="actions">
                                        <button type="submit" class="btn-update">Update</button>
                                    </form>
                                    <form method="POST" action="{{ route('restaurant.tables.destroy', $table) }}" onsubmit="return confirm('Delete?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-delete">Delete</button>
                                    </form>
                                </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-text">No tables added yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection