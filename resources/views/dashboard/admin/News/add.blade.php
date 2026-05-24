@extends('dashboard.admin.layout')

@section('content')

<div class="card">
    <div class="card-header">
        <h2>
            <i class="fa-solid fa-newspaper"></i>
            Add News / Blog
        </h2>
    </div>

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" value="{{ old('title') }}" placeholder="Enter news title">
            @error('title') <small>{{ $message }}</small> @enderror
        </div>

        {{-- <div class="form-group">
            <label>Short Description</label>
            <textarea name="short_description" rows="3" placeholder="Enter short description">{{ old('short_description') }}</textarea>
        </div> --}}

        <div class="form-group">
            <label>Full Description</label>
            <textarea name="description" rows="7" placeholder="Enter full news/blog content">{{ old('description') }}</textarea>
            @error('description') <small>{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Image</label>
            <input type="file" name="image">
            @error('image') <small>{{ $message }}</small> @enderror
        </div>

        {{-- <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div> --}}

        <button type="submit" class="submit-btn">
            <i class="fa-solid fa-plus"></i>
            Add News
        </button>
    </form>
</div>

<style>
.news-form {
    display: grid;
    gap: 18px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-weight: 700;
    color: #333;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 13px 14px;
    border: 1px solid #ddd;
    border-radius: 10px;
    outline: none;
    font-size: 15px;
    background: #fff;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    border-color: #1583ff;
    box-shadow: 0 0 0 3px rgba(21, 131, 255, 0.12);
}

.form-group small {
    color: #dc2626;
    font-weight: 600;
}

.submit-btn {
    width: fit-content;
    border: none;
    cursor: pointer;
    padding: 13px 22px;
    border-radius: 12px;
    background: linear-gradient(135deg, #1583ff, #ff15c4);
    color: white;
    font-size: 15px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: 0.3s;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(21, 131, 255, 0.25);
}

.success-message {
    background: #dcfce7;
    color: #166534;
    padding: 14px 16px;
    border-radius: 10px;
    margin-bottom: 18px;
    font-weight: 700;
}

@media (max-width: 768px) {
    .submit-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

@endsection