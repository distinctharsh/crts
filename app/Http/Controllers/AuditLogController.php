<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = Activity::with('causer')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        $verticalMap = \App\Models\Vertical::pluck('name', 'id')->toArray();
        return view('audit-log.index', compact('logs', 'verticalMap'));
    }
} 