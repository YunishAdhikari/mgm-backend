<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\MockData;

class SopController extends Controller
{
    public function index()
    {
        return view('admin.sops.index', ['sops' => MockData::sopApprovals()]);
    }

    public function approve($id)
    {
        return redirect()
            ->route('admin.sops.index')
            ->with('status', "SOP{$id} approved.");
    }
}
