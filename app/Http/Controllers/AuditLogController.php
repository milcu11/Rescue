<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('user')) {
            $query->where('user_name', 'like', '%'.$request->user.'%');
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $logs = $query->paginate(30)->withQueryString();

        $modules = AuditLog::distinct()->pluck('module')->sort();
        $actions = AuditLog::distinct()->pluck('action')->sort();

        return view('audit.index', compact('logs', 'modules', 'actions'));
    }

    public function show(AuditLog $auditLog)
    {
        return view('audit.show', compact('auditLog'));
    }
}
