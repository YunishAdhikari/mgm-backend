@extends('dashboard.admin.layout')

@section('content')

<div class="upload-page">

    <div class="upload-header">
        <div>
            <div class="eyebrow">Android Release</div>
            <h1>Upload Android App Link</h1>
            <p>Add a new MGM Ops Android build using a Google Drive APK download link.</p>
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
            <div class="card-title">
                <div class="icon-box">
                    <i class="fas fa-android"></i>
                </div>
                <div>
                    <h2>New Android Version</h2>
                    <p>Paste the latest APK link and publish it for staff.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('mobile-app-versions.store') }}">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label>Version Name</label>
                        <input type="text"
                               name="version_name"
                               placeholder="Example: 1.0.1"
                               value="{{ old('version_name') }}"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Version Code</label>
                        <input type="number"
                               name="version_code"
                               placeholder="Example: 14"
                               value="{{ old('version_code') }}"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Google Drive APK Direct Download Link</label>

                    <div class="link-input-wrap">
                        <i class="fas fa-link"></i>
                        <input type="url"
                               name="apk_url"
                               placeholder="https://drive.google.com/uc?export=download&id=FILE_ID"
                               value="{{ old('apk_url') }}"
                               required>
                    </div>

                    <small>
                        Use the direct download format, not the normal Drive preview link.
                    </small>
                </div>

                <div class="form-group">
                    <label>Release Notes</label>
                    <textarea name="release_notes"
                              rows="5"
                              placeholder="Example: Added housekeeping inspection, rota view, dashboard improvements and bug fixes.">{{ old('release_notes') }}</textarea>
                </div>

                <label class="latest-box">
                    <input type="checkbox"
                           name="is_latest"
                           value="1"
                           checked>

                    <div>
                        <strong>Mark as latest version</strong>
                        <span>This version will appear on the public staff download page.</span>
                    </div>
                </label>

                <button type="submit" class="upload-btn">
                    <i class="fas fa-cloud-upload-alt"></i>
                    Save App Version
                </button>
            </form>
        </div>

        <div class="info-card">
            <h3>Before Saving</h3>

            <div class="check-item">
                <span>1</span>
                <div>
                    <strong>Build release APK</strong>
                    <p>Generate the Android release APK from Flutter.</p>
                    <div class="command-box">flutter build apk --release</div>
                </div>
            </div>

            <div class="check-item">
                <span>2</span>
                <div>
                    <strong>Update version number</strong>
                    <p>Increase the version in your Flutter project before building.</p>
                    <div class="command-box">version: 1.0.1+14</div>
                </div>
            </div>

            <div class="check-item">
                <span>3</span>
                <div>
                    <strong>Use production API</strong>
                    <p>Make sure the app connects to the live MGM Ops API.</p>
                    <div class="command-box">https://mgmglasgow.com/api</div>
                </div>
            </div>

            <div class="check-item">
                <span>4</span>
                <div>
                    <strong>Convert Google Drive link</strong>
                    <p>Use the file ID from your Drive share link.</p>
                    <div class="drive-example">
                        <small>Normal:</small>
                        <div>https://drive.google.com/file/d/FILE_ID/view</div>

                        <small>Direct:</small>
                        <div>https://drive.google.com/uc?export=download&id=FILE_ID</div>
                    </div>
                </div>
            </div>

            <div class="note">
                Staff will always download the latest active APK from the staff app page.
            </div>
        </div>

    </div>

</div>

<style>
* {
    box-sizing: border-box;
}

.upload-page {
    padding: 26px;
    min-height: calc(100vh - 70px);
    color: #fafafa;
    background:
        radial-gradient(circle at top left, rgba(239, 68, 68, 0.14), transparent 36%),
        radial-gradient(circle at bottom right, rgba(20, 184, 166, 0.10), transparent 34%),
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
    line-height: 1.1;
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
    white-space: nowrap;
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
    grid-template-columns: minmax(0, 1.35fr) minmax(340px, .9fr);
    gap: 18px;
    align-items: start;
}

.upload-card,
.info-card {
    background: rgba(24,24,27,.92);
    border: 1px solid #3f3f46;
    border-radius: 24px;
    padding: 24px;
    box-shadow: 0 24px 70px rgba(0,0,0,.28);
}

.card-title {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 22px;
    padding-bottom: 18px;
    border-bottom: 1px solid #3f3f46;
}

.icon-box {
    width: 56px;
    height: 56px;
    border-radius: 18px;
    background: linear-gradient(135deg, #ef4444, #7f1d1d);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    flex-shrink: 0;
}

.card-title h2,
.info-card h3 {
    margin: 0;
    color: #fafafa;
    font-size: 22px;
    font-weight: 900;
}

.card-title p {
    margin: 5px 0 0;
    color: #a1a1aa;
    font-size: 14px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
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

.form-group input,
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

.form-group small {
    display: block;
    color: #a1a1aa;
    margin-top: 8px;
    line-height: 1.4;
}

.link-input-wrap {
    position: relative;
}

.link-input-wrap i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #71717a;
}

.link-input-wrap input {
    padding-left: 44px;
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
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
}

.upload-btn:hover {
    transform: translateY(-1px);
}

.info-card h3 {
    margin-bottom: 18px;
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

.command-box {
    margin-top: 10px;
    background: #18181b;
    border: 1px solid #3f3f46;
    color: #4ade80;
    padding: 11px 12px;
    border-radius: 12px;
    font-family: monospace;
    font-size: 13px;
    overflow-x: auto;
    white-space: nowrap;
}

.drive-example {
    margin-top: 10px;
    background: #18181b;
    border: 1px solid #3f3f46;
    border-radius: 12px;
    padding: 12px;
}

.drive-example small {
    color: #71717a;
    display: block;
    font-weight: 900;
    text-transform: uppercase;
    font-size: 10px;
    margin-bottom: 4px;
}

.drive-example div {
    color: #d4d4d8;
    font-family: monospace;
    font-size: 11px;
    line-height: 1.45;
    word-break: break-all;
    margin-bottom: 9px;
}

.drive-example div:last-child {
    margin-bottom: 0;
    color: #4ade80;
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

@media(max-width: 1000px) {
    .upload-grid {
        grid-template-columns: 1fr;
    }

    .upload-header {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media(max-width: 600px) {
    .upload-page {
        padding: 16px;
    }

    .upload-header h1 {
        font-size: 28px;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .back-btn,
    .upload-btn {
        width: 100%;
        text-align: center;
    }

    .card-title {
        align-items: flex-start;
    }
}
</style>

@endsection