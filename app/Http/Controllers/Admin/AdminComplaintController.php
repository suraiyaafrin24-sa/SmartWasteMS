<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminComplaintController extends Controller
{
    public function complaints()
    {
        $reports = \App\Models\Report::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.reports.index', compact('reports'));
    }

    public function resolveComplaint($id)
    {
        $report = \App\Models\Report::findOrFail($id);
        $report->update(['status' => 'Resolved']);
        return redirect()->back()->with('success', 'Compliant marked as resolved successfully.');
    }
}
