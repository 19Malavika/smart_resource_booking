<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $logs = $query->latest()->paginate(20)->withQueryString();

        $actionTypes = AuditLog::select('action')->distinct()->orderBy('action')->pluck('action');

        return view('admin.audit-logs.index', compact('logs', 'actionTypes'));
    }

    public function show(AuditLog $auditLog)
    {
        $auditLog->load('user');
        return view('admin.audit-logs.show', compact('auditLog'));
    }

    // Audit logs are read-only — no create/store/edit/update/destroy
    public function create() { return redirect()->route('admin.audit-logs.index'); }
    public function store(Request $request) { return redirect()->route('admin.audit-logs.index'); }
    public function edit($id) { return redirect()->route('admin.audit-logs.index'); }
    public function update(Request $request, $id) { return redirect()->route('admin.audit-logs.index'); }
    public function destroy($id) { return redirect()->route('admin.audit-logs.index'); }
}
