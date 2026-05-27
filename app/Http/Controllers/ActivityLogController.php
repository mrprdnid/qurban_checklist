<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $sort    = $request->query('sort', 'created_at');
        $dir     = $request->query('direction', 'desc');
        $allowed = ['created_at', 'action'];
        if (!in_array($sort, $allowed)) { $sort = 'created_at'; }
        if (!in_array($dir, ['asc', 'desc'])) { $dir = 'desc'; }

        $query = ActivityLog::with('user');

        if ($request->filled('model')) {
            $query->where('loggable_type', $request->model);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs  = $query->orderBy($sort, $dir)->paginate(50)->withQueryString();
        $users = User::orderBy('name')->get();

        return view('logs.index', compact('logs', 'users', 'sort', 'dir'));
    }
}
