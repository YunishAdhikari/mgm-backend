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
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="news-form">
        @csrf

        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" value="{{ old('title') }}" placeholder="Enter news title">
            @error('title') <small>{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Full Description</label>
            <textarea name="description" rows="7" placeholder="Enter full news/blog content">{{ old('description') }}</textarea>
            @error('description') <small>{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Image</label>
            <input type="file" name="image" class="file-input">
            @error('image') <small>{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="submit-btn">
            <i class="fa-solid fa-plus"></i>
            Add News
        </button>
    </form>
</div>

<style>
    :root {
        --bg-card: #27272a;
        --bg-input: #1c1c1f;
        --text-main: #fafafa;
        --text-muted: #a1a1aa;
        --border: #3f3f46;
        --purple: #8b5cf6;
        --pink: #ec4899;
        --success: #10b981;
    }

    .news-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-weight: 700;
        color: var(--text-muted);
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 13px 14px;
        border: 1px solid var(--border);
        border-radius: 12px;
        outline: none;
        font-size: 15px;
        background: var(--bg-input);
        color: var(--text-main);
        transition: all 0.3s ease;
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder {
        color: var(--text-muted);
        opacity: 0.6;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        border-color: var(--purple);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.15);
    }

    .form-group small {
        color: #fca5a5;
        font-weight: 600;
    }

    .file-input {
        padding: 12px !important;
        cursor: pointer;
    }

    .submit-btn {
        width: fit-content;
        border: none;
        cursor: pointer;
        padding: 14px 26px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--purple), var(--pink));
        color: white;
        font-size: 15px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4);
    }

    .success-message {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #6ee7b7;
        padding: 14px 16px;
        border-radius: 12px;
        margin-bottom: 18px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    @media (max-width: 768px) {
        .submit-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

@endsection