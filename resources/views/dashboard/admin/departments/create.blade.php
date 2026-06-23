@extends('dashboard.admin.layout')

@section('title', 'Create Department')
@section('page-title', 'Create Department')

@section('content')
<div class="animate-fade-in">

    <div class="department-create-top">
        <div>
            <p>MGM One / Departments</p>
            <h1>Create Department</h1>
            <span>Create a department and assign it to a hotel.</span>
        </div>

        <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>

    @if($errors->any())
        <div class="error-message">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.departments.store') }}" method="POST">
        @csrf

        <div class="department-form-layout">

            <div class="card department-form-card">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-building"></i>
                        Department Details
                    </h2>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Hotel *</label>
                        <select name="hotel_id" required>
                            <option value="">Select Hotel</option>
                            @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}" {{ old('hotel_id') == $hotel->id ? 'selected' : '' }}>
                                    {{ $hotel->name }} {{ $hotel->code ? '('.$hotel->code.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Department Name *</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Example: Reception"
                               required>
                    </div>
                </div>
            </div>

            <div class="side-panel">
                <div class="card">
                    <div class="department-preview-icon">
                        <i class="fas fa-building"></i>
                    </div>

                    <h3>Hotel Department</h3>
                    <p>
                        Departments are linked to hotels. Employees, maintenance tasks,
                        attendance and reports will use this department structure.
                    </p>

                    <div class="info-list">
                        <div>
                            <i class="fas fa-check-circle"></i>
                            Unique per hotel
                        </div>
                        <div>
                            <i class="fas fa-users"></i>
                            Used for employees
                        </div>
                        <div>
                            <i class="fas fa-screwdriver-wrench"></i>
                            Used for operations
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="form-actions">
            <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">
                Cancel
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i>
                Create Department
            </button>
        </div>
    </form>
</div>

<style>
    .department-create-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .department-create-top p {
        color: var(--primary);
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-size: 12px;
    }

    .department-create-top h1 {
        font-size: 34px;
        font-weight: 900;
        margin-top: 6px;
    }

    .department-create-top span {
        color: var(--text-muted);
        display: block;
        margin-top: 8px;
    }

    .error-message {
        background: rgba(239,68,68,.12);
        border: 1px solid rgba(239,68,68,.35);
        color: #f87171;
        padding: 16px 18px;
        border-radius: 14px;
        margin-bottom: 20px;
        font-weight: 800;
    }

    .department-form-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 340px;
        gap: 24px;
        align-items: start;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
    }

    .side-panel {
        position: sticky;
        top: 100px;
    }

    .department-preview-icon {
        width: 86px;
        height: 86px;
        border-radius: 24px;
        background: rgba(232,45,45,.12);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 38px;
        margin-bottom: 22px;
        box-shadow: 0 0 35px rgba(232,45,45,.22);
    }

    .side-panel h3 {
        font-size: 22px;
        font-weight: 900;
        margin-bottom: 10px;
    }

    .side-panel p {
        color: var(--text-muted);
        line-height: 1.6;
        font-size: 14px;
        margin-bottom: 22px;
    }

    .info-list {
        display: grid;
        gap: 10px;
    }

    .info-list div {
        padding: 13px 14px;
        border-radius: 12px;
        background: #0a0a0a;
        border: 1px solid var(--border);
        color: var(--text-muted);
        font-weight: 800;
        font-size: 13px;
    }

    .info-list i {
        color: var(--primary);
        margin-right: 8px;
    }

    .form-actions {
        margin-top: 24px;
        padding: 22px;
        border: 2px solid var(--border);
        border-radius: 18px;
        background: var(--bg-card);
        display: flex;
        justify-content: flex-end;
        gap: 14px;
        flex-wrap: wrap;
    }

    @media (max-width: 980px) {
        .department-form-layout {
            grid-template-columns: 1fr;
        }

        .side-panel {
            position: static;
        }
    }

    @media (max-width: 640px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .form-actions .btn {
            width: 100%;
        }
    }
</style>
@endsection