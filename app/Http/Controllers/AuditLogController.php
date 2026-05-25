<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('causer');

        // Filter by user if provided
        if ($request->has('user_id') && $request->user_id) {
            $query->where('causer_id', $request->user_id);
        }

        // Filter by event type if provided
        if ($request->has('event') && $request->event) {
            $query->where('event', $request->event);
        }

        // Filter by date range if provided
        if ($request->has('date_from') && $request->date_from) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->where('created_at', '<=', $request->date_to);
        }

        // Filter by model type if provided
        if ($request->has('model_type') && $request->model_type) {
            $query->where('subject_type', 'like', '%' . $request->model_type . '%');
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(50);

        // Field maps for converting IDs to readable names
        $verticalMap = \App\Models\Vertical::pluck('name', 'id')->toArray();
        $statusMap = \App\Models\Status::pluck('name', 'id')->toArray();
        $userMap = \App\Models\User::pluck('full_name', 'id')->toArray();
        $sectionMap = \App\Models\Section::pluck('name', 'id')->toArray();
        $roleMap = \App\Models\Role::pluck('name', 'id')->toArray();
        $networkTypeMap = \App\Models\NetworkType::pluck('name', 'id')->toArray();

        // Combined field maps for old/new values
        $fieldMaps = [
            'vertical_id' => $verticalMap,
            'status_id' => $statusMap,
            'user_id' => $userMap,
            'section_id' => $sectionMap,
            'role_id' => $roleMap,
            'network_type_id' => $networkTypeMap,
            'assigned_to' => $userMap,
            'assigned_by' => $userMap,
            'created_by' => $userMap,
        ];

        // Get all users for filter dropdown
        $allUsers = \App\Models\User::orderBy('full_name')->pluck('full_name', 'id')->toArray();

        // Get unique event types for filter dropdown
        $eventTypes = Activity::select('event')->distinct()->whereNotNull('event')->pluck('event')->toArray();

        // Get unique model types for filter dropdown
        $modelTypes = Activity::select('subject_type')->distinct()->whereNotNull('subject_type')->pluck('subject_type')->map(function($type) {
            return class_basename($type);
        })->unique()->toArray();

        return view('audit-log.index', compact(
            'logs',
            'verticalMap',
            'statusMap',
            'userMap',
            'sectionMap',
            'roleMap',
            'networkTypeMap',
            'fieldMaps',
            'allUsers',
            'eventTypes',
            'modelTypes'
        ));
    }
} 