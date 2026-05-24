@extends('dashboard.admin.layout')

@section('content')

<div class="card">
    <div class="card-header">
        <h2><i class="fa-solid fa-screwdriver-wrench"></i> Add Maintenance Job</h2>
    </div>

    <form enctype="multipart/form-data" action="{{ route('admin.maintenance.store') }}" method="POST" enctype="multipart/form-data" class="maintenance-form">
        @csrf

        <div class="form-grid">
            <div class="form-group">
                <label>Job Title</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Example: Room heater not working">
                @error('title') <small>{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label>Department</label>
                <select name="department_id">
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
                @error('department_id') <small>{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label>Assign To</label>
                <select name="assigned_to">
                    <option value="">Not Assigned</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Priority</label>
                <select name="priority">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>

            <div class="form-group">
                <label>Location</label>
                <input type="text" name="location" value="{{ old('location') }}" placeholder="Example: 3rd Floor">
            </div>

            <div class="form-group">
                <label>Room Number</label>
                <input type="text" name="room_number" value="{{ old('room_number') }}" placeholder="Example: 305">
            </div>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="6" placeholder="Describe the maintenance issue...">{{ old('description') }}</textarea>
            @error('description') <small>{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Upload Image</label>
            <input type="file" name="image">
            @error('image') <small>{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="submit-btn">
            <i class="fa-solid fa-plus"></i>
            Add Job
        </button>
    </form>
</div>

<style>
.maintenance-form {
    display: grid;
    gap: 20px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-weight: 700;
    color: #222;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid #ddd;
    border-radius: 12px;
    outline: none;
    font-size: 15px;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    border-color: #1583ff;
    box-shadow: 0 0 0 4px rgba(21,131,255,0.12);
}

.form-group small {
    color: #dc2626;
    font-weight: 700;
}

.submit-btn {
    width: fit-content;
    border: none;
    cursor: pointer;
    padding: 14px 24px;
    border-radius: 14px;
    background: linear-gradient(135deg,#1583ff,#ff15c4);
    color: white;
    font-size: 15px;
    font-weight: 700;
}

@media(max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    .submit-btn {
        width: 100%;
    }
}
</style>

@endsection