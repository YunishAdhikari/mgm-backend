@extends('dashboard.admin.layout')

@section('page-title', 'Add Employee')

@section('content')
<div class="animate-fade-in">

    <div class="d-flex justify-between items-center gap-4 mb-6" style="flex-wrap: wrap;">
        <div>
            <p style="color: var(--primary); font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; font-size: 12px;">
                MGM One / Employees
            </p>
            <h1 style="font-size: 32px; font-weight: 900; margin-top: 6px;">Add Employee</h1>
            <p style="color: var(--text-muted); margin-top: 8px;">
                Create a staff member and assign them to a hotel, role and department.
            </p>
        </div>

        <a href="{{ route('dashboard.admin.showemp') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="card" style="border-color: rgba(239,68,68,.35); background: rgba(239,68,68,.08); color:#fca5a5;">
            @foreach ($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('addemp.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="employee-form-layout">

            <div class="card employee-form-card">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-user-plus"></i>
                        Employee Details
                    </h2>
                </div>

                <div class="form-grid">

                    <div class="form-group full">
                        <label class="form-label">Hotel</label>
                        <select name="hotel_id" id="hotel_id">
                            <option value="">🌐 Platform / Global Employee</option>
                            @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}" {{ old('hotel_id') == $hotel->id ? 'selected' : '' }}>
                                    🏨 {{ $hotel->name }} {{ $hotel->code ? '(' . $hotel->code . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <p class="helper-text">
                            Leave empty only for platform-level employees such as Admin, DOPS, HR or Project Engineers.
                        </p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter full name" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter email address" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Enter phone number">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" placeholder="Enter password" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Employee Code</label>
                        <input type="text" name="employee_code" value="{{ old('employee_code') }}" placeholder="Example: MGM001">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Job Title</label>
                        <input type="text" name="job_title" value="{{ old('job_title') }}" placeholder="Example: Receptionist">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Role *</label>
                        <select name="role_id" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Department</label>
                        <select name="department_id" id="department_id">
                            <option value="">Select Department</option>

                            @foreach($departments as $department)
                                <option
                                    value="{{ $department->id }}"
                                    data-hotel-id="{{ $department->hotel_id ?? '' }}"
                                    {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->hotel_id ? '🏨' : '🌐' }}
                                    {{ $department->name }}
                                    {{ $department->hotel?->name ? ' - ' . $department->hotel->name : ' - Global' }}
                                </option>
                            @endforeach
                        </select>
                        <p class="helper-text">
                            Global departments are available to platform employees. Hotel departments are filtered by selected hotel.
                        </p>
                    </div>

                </div>
            </div>

            <div class="side-panel">
                <div class="card">
                    <div class="image-upload">
                        <img id="previewImage"
                             src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23e82d2d'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E"
                             class="avatar">

                        <label class="upload-btn">
                            <i class="fas fa-camera"></i>
                            Upload Image
                            <input type="file" name="image" onchange="preview(event)" accept="image/*">
                        </label>
                    </div>

                    <h3>Employee Profile</h3>
                    <p>
                        Hotel employees will be linked to a property. Platform employees can be left without a hotel.
                    </p>

                    <div class="profile-hints">
                        <div><i class="fas fa-hotel"></i> Hotel staff: select hotel</div>
                        <div><i class="fas fa-globe"></i> Platform staff: leave hotel empty</div>
                        <div><i class="fas fa-building"></i> Department follows hotel scope</div>
                    </div>
                </div>
            </div>

        </div>

        <div class="form-actions">
            <a href="{{ route('dashboard.admin.showemp') }}" class="btn btn-secondary">
                Cancel
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-plus"></i>
                Add Employee
            </button>
        </div>
    </form>
</div>

<style>
    .employee-form-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 320px;
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

    .helper-text {
        margin-top: 8px;
        color: var(--text-dim);
        font-size: 12px;
        font-weight: 700;
        line-height: 1.5;
    }

    .side-panel {
        position: sticky;
        top: 100px;
    }

    .image-upload {
        text-align: center;
        margin-bottom: 24px;
    }

    .avatar {
        width: 130px;
        height: 130px;
        border-radius: 30px;
        object-fit: cover;
        background: #0a0a0a;
        border: 3px solid var(--primary);
        box-shadow: 0 0 35px rgba(232,45,45,.25);
        margin-bottom: 16px;
    }

    .upload-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 18px;
        border-radius: 12px;
        background: rgba(232,45,45,.12);
        border: 1px solid rgba(232,45,45,.35);
        color: var(--primary);
        font-weight: 900;
        cursor: pointer;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: .8px;
    }

    .upload-btn input {
        display: none;
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
    }

    .profile-hints {
        margin-top: 20px;
        display: grid;
        gap: 10px;
    }

    .profile-hints div {
        padding: 12px 14px;
        border-radius: 12px;
        background: #0a0a0a;
        border: 1px solid var(--border);
        color: var(--text-muted);
        font-weight: 800;
        font-size: 13px;
    }

    .profile-hints i {
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
        .employee-form-layout {
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

<script>
function preview(event) {
    const file = event.target.files[0];

    if (!file) {
        return;
    }

    const reader = new FileReader();

    reader.onload = function() {
        document.getElementById('previewImage').src = reader.result;
    }

    reader.readAsDataURL(file);
}

document.addEventListener('DOMContentLoaded', function () {
    const hotelSelect = document.getElementById('hotel_id');
    const departmentSelect = document.getElementById('department_id');

    function filterDepartments() {
        const selectedHotelId = hotelSelect.value;

        Array.from(departmentSelect.options).forEach(option => {
            if (!option.value) {
                option.hidden = false;
                return;
            }

            const departmentHotelId = option.dataset.hotelId || '';

            if (!selectedHotelId) {
                option.hidden = departmentHotelId !== '';
            } else {
                option.hidden = departmentHotelId !== '' && departmentHotelId !== selectedHotelId;
            }
        });

        const selectedOption = departmentSelect.options[departmentSelect.selectedIndex];

        if (selectedOption && selectedOption.hidden) {
            departmentSelect.value = '';
        }
    }

    hotelSelect.addEventListener('change', filterDepartments);
    filterDepartments();
});
</script>
@endsection