@extends('dashboard.admin.layout')

@section('page-title', 'Create Hotel')

@section('content')
<div class="animate-fade-in">

    <div class="d-flex justify-between items-center gap-4 mb-6" style="flex-wrap: wrap;">
        <div>
            <p style="color: var(--primary); font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; font-size: 12px;">
                MGM One / Hotels
            </p>
            <h1 style="font-size: 32px; font-weight: 900; margin-top: 6px;">Create Hotel</h1>
            <p style="color: var(--text-muted); margin-top: 8px;">
                Register a new hotel into the multi-hotel platform.
            </p>
        </div>

        <a href="{{ route('admin.hotels.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>

    <form action="{{ route('admin.hotels.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="hotel-form-layout">

            <div class="card hotel-form-card">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-hotel"></i>
                        Hotel Details
                    </h2>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Hotel Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Example: MGM Glasgow Riverside Hotel">
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Hotel Code</label>
                        <input type="text" name="code" value="{{ old('code') }}" placeholder="Example: MGM-GLA">
                        @error('code')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="hotel@example.com">
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+44 ...">
                        @error('phone')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group full">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" placeholder="Hotel full address">
                        @error('address')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" name="city" value="{{ old('city') }}" placeholder="Glasgow">
                        @error('city')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Postcode</label>
                        <input type="text" name="postcode" value="{{ old('postcode') }}" placeholder="PA8 ...">
                        @error('postcode')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Country</label>
                        <input type="text" name="country" value="{{ old('country', 'United Kingdom') }}">
                        @error('country')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Hotel Logo</label>
                        <input type="file" name="logo" accept="image/*">
                        @error('logo')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="side-panel">
                <div class="card">
                    <div class="hotel-preview-icon">
                        <i class="fas fa-hotel"></i>
                    </div>

                    <h3>Create New Property</h3>
                    <p>
                        This hotel will become part of the MGM One platform. Later, users, rooms,
                        departments and modules will be linked to this hotel.
                    </p>

                    <label class="status-toggle">
                        <input type="checkbox" checked name="is_active" value="1">
                        <span></span>
                        <strong>Hotel Active</strong>
                    </label>
                </div>
            </div>

        </div>

        <div class="form-actions">
            <a href="{{ route('admin.hotels.index') }}" class="btn btn-secondary">
                Cancel
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i>
                Create Hotel
            </button>
        </div>
    </form>
</div>

<style>
    .hotel-form-layout {
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

    .form-group.full {
        grid-column: 1 / -1;
    }

    .form-error {
        margin-top: 8px;
        color: #f87171;
        font-size: 13px;
        font-weight: 700;
    }

    .side-panel {
        position: sticky;
        top: 100px;
    }

    .hotel-preview-icon {
        width: 82px;
        height: 82px;
        border-radius: 24px;
        background: rgba(232,45,45,.12);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
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
        margin-bottom: 24px;
    }

    .status-toggle {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        border-radius: 14px;
        background: #0a0a0a;
        border: 1px solid var(--border);
        cursor: pointer;
    }

    .status-toggle input {
        display: none;
    }

    .status-toggle span {
        width: 46px;
        height: 24px;
        border-radius: 999px;
        background: #2a2a2a;
        position: relative;
        transition: all .2s ease;
        flex-shrink: 0;
    }

    .status-toggle span::after {
        content: '';
        position: absolute;
        width: 18px;
        height: 18px;
        top: 3px;
        left: 3px;
        border-radius: 50%;
        background: #777;
        transition: all .2s ease;
    }

    .status-toggle input:checked + span {
        background: rgba(34,197,94,.25);
        border: 1px solid rgba(34,197,94,.4);
    }

    .status-toggle input:checked + span::after {
        left: 23px;
        background: #4ade80;
        box-shadow: 0 0 12px rgba(74,222,128,.6);
    }

    .status-toggle strong {
        font-size: 13px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
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

    input[type="file"] {
        padding: 11px;
    }

    input[type="file"]::file-selector-button {
        border: 0;
        padding: 10px 14px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        font-weight: 800;
        margin-right: 12px;
        cursor: pointer;
    }

    @media (max-width: 980px) {
        .hotel-form-layout {
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