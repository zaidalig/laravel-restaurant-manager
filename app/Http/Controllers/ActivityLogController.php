<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');
        if ($request->filled('search')) {
            $query->where('description', 'like', '%'.$request->input('search').'%');
        }
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }
        [$sort, $direction] = $this->tableSort($request, ['created_at', 'action']);
        $logs = $query->orderBy($sort, $direction)->paginate($this->tablePerPage($request))->withQueryString();

        return view('activity.index', compact('logs'));
    }
}
