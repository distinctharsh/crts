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
        $statusMap = \App\Models\Status::pluck('name', 'id')->toArray();
        $userMap = \App\Models\User::pluck('full_name', 'id')->toArray();
        $sectionMap = \App\Models\Section::pluck('name', 'id')->toArray();
        $roleMap = \App\Models\Role::pluck('name', 'id')->toArray();
        $networkTypeMap = \App\Models\NetworkType::pluck('name', 'id')->toArray();
        return view('audit-log.index', compact('logs', 'verticalMap', 'statusMap', 'userMap', 'sectionMap', 'roleMap', 'networkTypeMap'));
    }
} 