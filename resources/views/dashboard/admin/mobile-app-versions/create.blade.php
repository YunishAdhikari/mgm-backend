@extends('dashboard.admin.layout')

@section('content')

<div class="upload-page">

    <div class="upload-header">
        <div>
            <div class="eyebrow">Android Release</div>
            <h1>Upload Android APK</h1>
            <p>Upload a new MGM Ops build and make it available for staff download.</p>
        </div>

        <a href="{{ route('mobile-app-versions.index') }}" class="back-btn">
            Back
        </a>
    </div>

    @if($errors->any())
        <div class="error-box">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="upload-grid">

        <div class="upload-card">
            <form method="POST"
                  action="{{ route('mobile-app-versions.store') }}"
                  enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>Version Name</label>
                    <input type="text"
                           name="version_name"
                           placeholder="Example: 1.0.1"
                           required>
                </div>

                <div class="form-group">
                    <label>Version Code</label>
                    <input type="number"
                           name="version_code"
                           placeholder="Example: 14"
                           required>
                </div>

                <div class="form-group">
                    <label>APK File</label>

                    <label class="file-box">
                        <input type="file"
                               name="apk_file"
                               accept=".apk"
                               required>

                        <div class="file-icon">☁</div>
                        <strong>Choose APK file</strong>
                        <span>Upload the release APK generated from Flutter</span>
                    </label>
                </div>

                <div class="form-group">
                    <label>Release Notes</label>
                    <textarea name="release_notes"
                              rows="5"
                              placeholder="Example: Added housekeeping inspection, rota view, and dashboard improvements."></textarea>
                </div>

                <label class="latest-box">
                    <input type="checkbox"
                           name="is_latest"
                           value="1"
                           checked>

                    <div>
                        <strong>Mark as latest version</strong>
                        <span>This APK will be shown on the staff download page.</span>
                    </div>
                </label>

                <button type="submit" class="upload-btn">
                    Upload APK
                </button>
            </form>
        </div>

        <div class="info-card">
            <h3>Before Uploading</h3>

            <div class="check-item">
                <span>1</span>
                <div>
                    <strong>Build release APK</strong>
                    <p>Use <code>flutter build apk --release</code>.</p>
                </div>
            </div>

            <div class="check-item">
                <span>2</span>
                <div>
                    <strong>Update version number</strong>
                    <p>Example: <code>version: 1.0.1+14</code>.</p>
                </div>
            </div>

            <div class="check-item">
                <span>3</span>
                <div>
                    <strong>Use production API</strong>
                    <p>Confirm app points to <code>https://mgmglasgow.com/api</code>.</p>
                </div>
            </div>

            <div class="note">
                Staff will download the latest active APK from the public staff app page.
            </div>
        </div>

    </div>

</div>

<style>
.upload-page {
    padding: 26px;
    min-height: calc(100vh - 70px);
    color: #fafafa;
    background:
        radial-gradient(circle at top left, rgba(239, 68, 68, 0.12), transparent 34%),
        radial-gradient(circle at bottom right, rgba(139, 92, 246, 0.10), transparent 34%),
        #09090b;
}

.upload-header {
    background: linear-gradient(135deg, rgba(24,24,27,.96), rgba(39,39,42,.82));
    border: 1px solid #3f3f46;
    border-radius: 24px;
    padding: 24px;
    margin-bottom: 22px;
    display: flex;
    justify-content: space-between;
    gap: 18px;
    align-items: center;
    box-shadow: 0 24px 70px rgba(0,0,0,.35);
}

.eyebrow {
    color: #ef4444;
    font-size: 12px;
    font-weight: 900;
    letter-spacing: .12em;
    text-transform: uppercase;
    margin-bottom: 8px;
}

.upload-header h1 {
    margin: 0;
    font-size: 34px;
    font-weight: 900;
    color: #fafafa;
}

.upload-header p {
    margin: 8px 0 0;
    color: #a1a1aa;
}

.back-btn {
    background: #27272a;
    color: #fafafa;
    border: 1px solid #3f3f46;
    padding: 12px 16px;
    border-radius: 14px;
    text-decoration: none;
    font-weight: 900;
}

.back-btn:hover {
    color: #fff;
    background: #3f3f46;
}

.error-box {
    background: rgba(239,68,68,.12);
    border: 1px solid rgba(239,68,68,.35);
    color: #f87171;
    padding: 14px 16px;
    border-radius: 16px;
    margin-bottom: 18px;
}

.upload-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(320px, .8fr);
    gap: 18px;
}

.upload-card,
.info-card {
    background: rgba(24,24,27,.92);
    border: 1px solid #3f3f46;
    border-radius: 24px;
    padding: 24px;
    box-shadow: 0 24px 70px rgba(0,0,0,.28);
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    color: #d4d4d8;
    font-size: 13px;
    font-weight: 900;
    margin-bottom: 8px;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group textarea {
    width: 100%;
    background: #27272a;
    color: #fafafa;
    border: 1px solid #3f3f46;
    border-radius: 16px;
    padding: 14px 15px;
    outline: none;
}

.form-group textarea {
    resize: vertical;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239,68,68,.12);
}

.file-box {
    border: 1.5px dashed #52525b;
    background: #18181b;
    border-radius: 20px;
    padding: 30px 20px;
    text-align: center;
    cursor: pointer;
    transition: .2s;
}

.file-box:hover {
    border-color: #ef4444;
    background: rgba(239,68,68,.06);
}

.file-box input {
    display: none;
}

.file-icon {
    width: 64px;
    height: 64px;
    border-radius: 22px;
    background: rgba(239,68,68,.14);
    color: #f87171;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 14px;
    font-size: 32px;
    font-weight: 900;
}

.file-box strong {
    display: block;
    color: #fafafa;
    font-size: 18px;
}

.file-box span {
    display: block;
    color: #a1a1aa;
    margin-top: 6px;
}

.latest-box {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    background: rgba(34,197,94,.10);
    border: 1px solid rgba(34,197,94,.35);
    border-radius: 18px;
    padding: 15px;
    margin-bottom: 20px;
    cursor: pointer;
}

.latest-box input {
    margin-top: 4px;
}

.latest-box strong {
    display: block;
    color: #4ade80;
    font-size: 14px;
}

.latest-box span {
    display: block;
    color: #a7f3d0;
    font-size: 13px;
    margin-top: 4px;
}

.upload-btn {
    width: 100%;
    border: none;
    background: linear-gradient(135deg, #ef4444, #b91c1c);
    color: white;
    padding: 15px 18px;
    border-radius: 16px;
    font-weight: 900;
    cursor: pointer;
    box-shadow: 0 16px 35px rgba(239,68,68,.22);
}

.upload-btn:hover {
    transform: translateY(-1px);
}

.info-card h3 {
    color: #fafafa;
    font-size: 22px;
    font-weight: 900;
    margin: 0 0 18px;
}

.check-item {
    display: flex;
    gap: 12px;
    background: #27272a;
    border: 1px solid #3f3f46;
    border-radius: 18px;
    padding: 15px;
    margin-bottom: 12px;
}

.check-item > span {
    width: 32px;
    height: 32px;
    border-radius: 11px;
    background: rgba(239,68,68,.14);
    color: #f87171;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    flex-shrink: 0;
}

.check-item strong {
    color: #fafafa;
    display: block;
}

.check-item p {
    color: #a1a1aa;
    margin: 5px 0 0;
    line-height: 1.5;
}

code {
    display: inline-block;
    color: #fca5a5;
    background: rgba(239,68,68,.10);
    border: 1px solid rgba(239,68,68,.15);
    padding: 4px 8px;
    border-radius: 8px;
    white-space: nowrap;
    font-size: 13px;
}

.note {
    margin-top: 18px;
    background: rgba(20,184,166,.10);
    border: 1px solid rgba(20,184,166,.32);
    color: #5eead4;
    border-radius: 18px;
    padding: 15px;
    line-height: 1.5;
}

@media(max-width: 950px) {
    .upload-grid {
        grid-template-columns: 1fr;
    }

    .upload-header {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media(max-width: 560px) {
    .upload-page {
        padding: 16px;
    }

    .upload-header h1 {
        font-size: 28px;
    }

    .back-btn,
    .upload-btn {
        width: 100%;
        text-align: center;
        justify-content: center;
    }
}
</style>

@endsection