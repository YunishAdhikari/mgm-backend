<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MobileAppVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MobileAppVersionController extends Controller
{
    public function index()
    {
        $versions = MobileAppVersion::latest()->get();

        return view('dashboard.admin.mobile-app-versions.index', compact('versions'));
    }

    public function create()
    {
        return view('dashboard.admin.mobile-app-versions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'version_name' => 'required|string|max:50',
            'version_code' => 'required|integer',
            'apk_file' => 'required|file|mimes:apk|max:204800',
            'release_notes' => 'nullable|string',
            'is_latest' => 'nullable|boolean',
        ]);

        $path = $request->file('apk_file')->store('mobile-apps', 'public');

        if ($request->boolean('is_latest')) {
            MobileAppVersion::where('platform', 'android')
                ->update(['is_latest' => false]);
        }

        MobileAppVersion::create([
            'platform' => 'android',
            'version_name' => $request->version_name,
            'version_code' => $request->version_code,
            'apk_path' => $path,
            'release_notes' => $request->release_notes,
            'is_latest' => $request->boolean('is_latest'),
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.mobile-app-versions.index')
            ->with('success', 'Mobile app version uploaded successfully.');
    }

    public function markLatest(MobileAppVersion $version)
    {
        MobileAppVersion::where('platform', $version->platform)
            ->update(['is_latest' => false]);

        $version->update(['is_latest' => true]);

        return back()->with('success', 'Version marked as latest.');
    }

    public function destroy(MobileAppVersion $version)
    {
        if ($version->is_latest) {
            return back()->with('error', 'You cannot delete the latest active version.');
        }

        if ($version->apk_path && Storage::disk('public')->exists($version->apk_path)) {
            Storage::disk('public')->delete($version->apk_path);
        }

        $version->delete();

        return back()->with('success', 'Version deleted successfully.');
    }
}