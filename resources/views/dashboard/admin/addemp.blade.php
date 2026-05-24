@extends('dashboard.admin.layout')

@section('content')
<style>
.form-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 90vh;
    background: #f4f6f9;
}

.form-card {
    background: #ffffff;
    padding: 40px;
    width: 100%;
    max-width: 700px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.form-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 5px;
}

.form-subtitle {
    color: #666;
    margin-bottom: 20px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-size: 14px;
    margin-bottom: 5px;
    color: #444;
}

.form-group input,
.form-group select {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    transition: 0.2s;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #3b82f6;
    outline: none;
}

.submit-btn {
    margin-top: 25px;
    width: 100%;
    padding: 12px;
    background: #3b82f6;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}

.submit-btn:hover {
    background: #2563eb;
}

.image-upload {
    text-align: center;
    margin-bottom: 20px;
}

.avatar {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 10px;
}

.error-box {
    background: #ffe5e5;
    color: #c0392b;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 6px;
} </style>

<div class="form-wrapper">

    <div class="form-card">

        <h2 class="form-title">Add Employee</h2>
        <p class="form-subtitle">Create a new staff member</p>

        @if ($errors->any())
            <div class="error-box">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('addemp.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Image Upload -->
            <div class="image-upload">
                {{-- <img id="previewImage" src="https://via.placeholder.com/100" class="avatar"> --}}
                <input type="file" name="image" onchange="preview(event)">
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select name="role_id" required>
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Department</label>
                    <select name="department_id">
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <button type="submit" class="submit-btn">
                Add Employee
            </button>

        </form>

    </div>

</div>

<script>
function preview(event) {
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('previewImage').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

@endsection