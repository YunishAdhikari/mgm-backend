@extends('dashboard.admin.layout')

@section('content')

<div class="mobile-app-page">

    <div class="page-header">
        <div>
            <div class="eyebrow">Android Distribution</div>
            <h1>Mobile App Management</h1>
            <p>Upload, manage, and deploy MGM Ops Android APK builds for staff.</p>
        </div>

        <a href="{{ route('mobile-app-versions.create') }}" class="primary-btn">
            <i class="fas fa-cloud-upload-alt"></i>
            Upload New APK
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @php
        $latest = $versions->firstWhere('is_latest', true);
    @endphp

    <div class="top-grid">

        <div class="live-card">
            <div class="card-top">
                <div class="app-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>

                <div>
                    <span class="live-badge">
                        <i class="fas fa-circle"></i>
                        {{ $latest ? 'Live Version' : 'No Live Build' }}
                    </span>

                    <h2>
                        {{ $latest ? 'MGM Ops ' . $latest->version_name : 'Upload First APK' }}
                    </h2>

                    <p>
                        {{ $latest ? 'Current Android build available for staff download.' : 'No APK is currently available for staff.' }}
                    </p>
                </div>
            </div>

            <div class="live-stats">
                <div>
                    <span>Version</span>
                    <strong>{{ $latest->version_name ?? '-' }}</strong>
                </div>

                <div>
                    <span>Build</span>
                    <strong>{{ $latest->version_code ?? '-' }}</strong>
                </div>

                <div>
                    <span>Uploaded</span>
                    <strong>{{ $latest ? $latest->created_at->format('d M Y') : '-' }}</strong>
                </div>
            </div>

            <div class="live-actions">
                @if($latest)
                    <a href="{{ asset('storage/' . $latest->apk_path) }}" target="_blank" class="download-btn">
                        <i class="fas fa-download"></i>
                        Download Live APK
                    </a>
                @endif

                <a href="{{ route('mobile-app-versions.create') }}" class="outline-btn">
                    <i class="fas fa-plus"></i>
                    Upload Version
                </a>
            </div>
        </div>

        <div class="quick-card">
            <h3>Release Checklist</h3>

            <ul>
                <li>
                    <i class="fas fa-check"></i>
                    Build APK from Flutter release mode
                </li>
                <li>
                    <i class="fas fa-check"></i>
                    Upload APK from admin dashboard
                </li>
                <li>
                    <i class="fas fa-check"></i>
                    Mark latest version as live
                </li>
                <li>
                    <i class="fas fa-check"></i>
                    Staff download from /staff-app
                </li>
            </ul>

            <a href="{{ url('/staff-app') }}" target="_blank" class="staff-link">
                Open Staff Download Page
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

    </div>

    <div class="history-card">

        <div class="history-header">
            <div>
                <h3>Version History</h3>
                <p>{{ $versions->count() }} APK version{{ $versions->count() === 1 ? '' : 's' }} uploaded</p>
            </div>
        </div>

        @if($versions->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-cloud-upload-alt"></i>
                </div>

                <h3>No APK uploaded yet</h3>
                <p>Upload your first Android APK to make MGM Ops available for staff download.</p>

                <a href="{{ route('mobile-app-versions.create') }}" class="primary-btn">
                    Upload First APK
                </a>
            </div>
        @else
            <div class="version-list">
                @foreach($versions as $version)
                    <div class="version-row">

                        <div class="version-main">
                            <div class="version-icon">
                                <i class="fas fa-robot"></i>
                            </div>

                            <div>
                                <div class="version-title">
                                    <strong>{{ $version->version_name }}</strong>

                                    @if($version->is_latest)
                                        <span class="status-badge live">Live</span>
                                    @else
                                        <span class="status-badge archived">Archived</span>
                                    @endif
                                </div>

                                <p>
                                    Build {{ $version->version_code }}
                                    • Uploaded {{ $version->created_at->format('d M Y H:i') }}
                                    • By {{ $version->uploadedBy->name ?? 'System' }}
                                </p>

                                @if($version->release_notes)
                                    <div class="release-note">
                                        {{ $version->release_notes }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="version-actions">
                            <a href="{{ asset('storage/' . $version->apk_path) }}"
                               target="_blank"
                               class="small-btn">
                                <i class="fas fa-download"></i>
                                Download
                            </a>

                            @if(!$version->is_latest)
                                <form method="POST"
                                      action="{{ route('mobile-app-versions.mark-latest', $version->id) }}">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" class="small-btn success">
                                        <i class="fas fa-check"></i>
                                        Make Live
                                    </button>
                                </form>

                                <form method="POST"
                                      action="{{ route('mobile-app-versions.destroy', $version->id) }}"
                                      onsubmit="return confirm('Delete this APK version?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="small-btn danger">
                                        <i class="fas fa-trash"></i>
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>

</div>

<style>
.mobile-app-page {
    padding: 26px;
    color: #fafafa;
    background:
        radial-gradient(circle at top left, rgba(239, 68, 68, 0.12), transparent 36%),
        radial-gradient(circle at bottom right, rgba(139, 92, 246, 0.10), transparent 34%),
        #09090b;
    min-height: calc(100vh - 70px);
}

.page-header {
    background: linear-gradient(135deg, rgba(24,24,27,0.96), rgba(39,39,42,0.8));
    border: 1px solid #3f3f46;
    border-radius: 24px;
    padding: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 22px;
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

.page-header h1 {
    margin: 0;
    font-size: 34px;
    font-weight: 900;
    color: #fafafa;
    line-height: 1.1;
}

.page-header p {
    color: #a1a1aa;
    margin: 8px 0 0;
}

.primary-btn,
.outline-btn,
.download-btn,
.small-btn,
.staff-link {
    text-decoration: none;
    border: none;
    cursor: pointer;
    font-weight: 900;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    border-radius: 14px;
    white-space: nowrap;
}

.primary-btn {
    background: linear-gradient(135deg, #ef4444, #b91c1c);
    color: #fff;
    padding: 14px 18px;
    box-shadow: 0 14px 30px rgba(239,68,68,.24);
}

.primary-btn:hover,
.download-btn:hover {
    color: #fff;
    transform: translateY(-1px);
}

.alert-success {
    background: rgba(34,197,94,.12);
    border: 1px solid rgba(34,197,94,.35);
    color: #4ade80;
    padding: 14px 16px;
    border-radius: 16px;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.top-grid {
    display: grid;
    grid-template-columns: minmax(0, 2fr) minmax(320px, 1fr);
    gap: 18px;
    margin-bottom: 22px;
}

.live-card,
.quick-card,
.history-card {
    background: rgba(24,24,27,.92);
    border: 1px solid #3f3f46;
    border-radius: 24px;
    box-shadow: 0 24px 70px rgba(0,0,0,.28);
}

.live-card {
    padding: 24px;
    position: relative;
    overflow: hidden;
}

.live-card::before {
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 90% 20%, rgba(239,68,68,.16), transparent 30%);
    pointer-events: none;
}

.card-top {
    position: relative;
    display: flex;
    gap: 18px;
    align-items: flex-start;
}

.app-icon {
    width: 72px;
    height: 72px;
    border-radius: 22px;
    background: linear-gradient(135deg, #ef4444, #7f1d1d);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 28px;
    box-shadow: 0 16px 35px rgba(239,68,68,.26);
    flex-shrink: 0;
}

.live-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(34,197,94,.12);
    color: #4ade80;
    border: 1px solid rgba(34,197,94,.35);
    padding: 7px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 900;
    margin-bottom: 12px;
}

.live-badge i {
    font-size: 7px;
}

.live-card h2 {
    color: #fafafa;
    font-size: 32px;
    font-weight: 900;
    margin: 0;
}

.live-card p {
    color: #a1a1aa;
    margin: 8px 0 0;
}

.live-stats {
    position: relative;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin: 24px 0;
}

.live-stats div {
    background: #27272a;
    border: 1px solid #3f3f46;
    border-radius: 18px;
    padding: 16px;
}

.live-stats span {
    display: block;
    color: #71717a;
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
    margin-bottom: 6px;
}

.live-stats strong {
    color: #fafafa;
    font-size: 18px;
}

.live-actions {
    position: relative;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.download-btn {
    background: #22c55e;
    color: white;
    padding: 13px 16px;
}

.outline-btn {
    background: transparent;
    color: #fafafa;
    border: 1px solid #3f3f46;
    padding: 13px 16px;
}

.outline-btn:hover {
    color: #fafafa;
    background: #27272a;
}

.quick-card {
    padding: 24px;
}

.quick-card h3,
.history-header h3,
.empty-state h3 {
    color: #fafafa;
    font-size: 21px;
    font-weight: 900;
    margin: 0;
}

.quick-card ul {
    list-style: none;
    padding: 0;
    margin: 18px 0;
}

.quick-card li {
    color: #d4d4d8;
    display: flex;
    gap: 10px;
    align-items: center;
    padding: 11px 0;
    border-bottom: 1px solid rgba(63,63,70,.55);
}

.quick-card li i {
    color: #22c55e;
}

.staff-link {
    width: 100%;
    background: rgba(239,68,68,.12);
    color: #fca5a5;
    border: 1px solid rgba(239,68,68,.35);
    padding: 13px;
}

.staff-link:hover {
    color: #fff;
    background: rgba(239,68,68,.2);
}

.history-card {
    overflow: hidden;
}

.history-header {
    padding: 22px 24px;
    border-bottom: 1px solid #3f3f46;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.history-header p {
    color: #a1a1aa;
    margin: 6px 0 0;
}

.empty-state {
    text-align: center;
    padding: 70px 24px;
}

.empty-icon {
    width: 86px;
    height: 86px;
    border-radius: 28px;
    background: #27272a;
    border: 1px solid #3f3f46;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 18px;
    color: #ef4444;
    font-size: 34px;
}

.empty-state p {
    color: #a1a1aa;
    margin: 10px 0 22px;
}

.version-list {
    display: flex;
    flex-direction: column;
}

.version-row {
    padding: 18px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 18px;
    border-bottom: 1px solid #3f3f46;
}

.version-row:last-child {
    border-bottom: none;
}

.version-main {
    display: flex;
    gap: 14px;
    align-items: flex-start;
    min-width: 0;
}

.version-icon {
    width: 48px;
    height: 48px;
    border-radius: 16px;
    background: #27272a;
    border: 1px solid #3f3f46;
    color: #ef4444;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.version-title {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.version-title strong {
    font-size: 18px;
    color: #fafafa;
}

.status-badge {
    padding: 5px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 900;
}

.status-badge.live {
    background: rgba(34,197,94,.12);
    color: #4ade80;
    border: 1px solid rgba(34,197,94,.35);
}

.status-badge.archived {
    background: rgba(113,113,122,.14);
    color: #a1a1aa;
    border: 1px solid rgba(113,113,122,.32);
}

.version-main p {
    color: #a1a1aa;
    margin: 6px 0 0;
}

.release-note {
    margin-top: 10px;
    color: #d4d4d8;
    background: #27272a;
    border: 1px solid #3f3f46;
    border-radius: 14px;
    padding: 10px 12px;
    max-width: 720px;
}

.version-actions {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-shrink: 0;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.small-btn {
    background: #27272a;
    border: 1px solid #3f3f46;
    color: #fafafa;
    padding: 9px 11px;
    font-size: 12px;
}

.small-btn:hover {
    color: #fafafa;
    background: #3f3f46;
}

.small-btn.success {
    color: #4ade80;
    border-color: rgba(34,197,94,.4);
}

.small-btn.danger {
    color: #f87171;
    border-color: rgba(239,68,68,.4);
}

@media(max-width: 1000px) {
    .top-grid {
        grid-template-columns: 1fr;
    }

    .page-header,
    .version-row {
        flex-direction: column;
        align-items: flex-start;
    }

    .version-actions {
        width: 100%;
        justify-content: flex-start;
    }
}

@media(max-width: 650px) {
    .mobile-app-page {
        padding: 16px;
    }

    .page-header h1,
    .live-card h2 {
        font-size: 26px;
    }

    .card-top {
        flex-direction: column;
    }

    .live-stats {
        grid-template-columns: 1fr;
    }

    .primary-btn,
    .download-btn,
    .outline-btn {
        width: 100%;
    }
}
</style>

@endsection