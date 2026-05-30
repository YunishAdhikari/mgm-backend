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
        --danger: #ef4444;
    }

    .form-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 90vh;
        background: var(--bg-page);
        padding: 20px;
    }

    .form-card {
        background: var(--bg-card);
        padding: 40px;
        width: 100%;
        max-width: 700px;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.4);
        border: 1px solid var(--border);
    }

    .form-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
        color: var(--text-main);
    }

    .form-subtitle {
        color: var(--text-muted);
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
        margin-bottom: 8px;
        color: var(--text-muted);
        font-weight: 600;
    }

    .form-group input,
    .form-group select {
        padding: 12px 14px;
        border: 2px solid var(--border);
        border-radius: 10px;
        background: var(--bg-input);
        color: var(--text-main);
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-group input::placeholder {
        color: var(--text-dim);
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15);
    }

    .submit-btn {
        margin-top: 25px;
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, var(--primary), #ec4899);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4);
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
        border: 3px solid var(--primary);
    }

    .error-box {
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #fca5a5;
        padding: 12px;
        margin-bottom: 15px;
        border-radius: 8px;
        font-weight: 600;
    }

    @media (max-width: 600px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .form-card {
            padding: 24px;
        }
    }
</style>

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

            <div class="image-upload">
                <img id="previewImage" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2371717a'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E" class="avatar">
                <input type="file" name="image" onchange="preview(event)" accept="image/*">
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" placeholder="Enter full name" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Enter email address" required>
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" placeholder="Enter phone number">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter password" required>
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
                <i class="fa-solid fa-user-plus"></i> Add Employee
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