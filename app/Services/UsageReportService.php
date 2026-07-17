<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\Status;
use App\Models\Vertical;
use Carbon\Carbon;

class UsageReportService
{
    /**
     * Get category-wise statistics for complaints
     * 
     * @param Carbon|null $dateFrom
     * @param Carbon|null $dateTo
     * @return array
     */
    public function getCategoryWiseStatistics($dateFrom = null, $dateTo = null)
    {
        $allVerticals = Vertical::with('children')->get();
        $categoryData = [];
        
        $complaintsQuery = Complaint::with('verticals');
        if ($dateFrom) {
            $complaintsQuery->where('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $complaintsQuery->where('created_at', '<=', $dateTo);
        }
        $complaints = $complaintsQuery->get();

        $completedStatusIds = Status::whereIn('name', ['completed', 'closed'])->pluck('id');

        $getDepth = function($verticalId) use ($allVerticals) {
            $depth = 0;
            $current = $allVerticals->where('id', $verticalId)->first();
            while ($current && $current->parent_id) {
                $depth++;
                $current = $allVerticals->where('id', $current->parent_id)->first();
            }
            return $depth;
        };

        $getLevel = function($verticalId) use ($allVerticals) {
            $level = 0;
            $current = $allVerticals->where('id', $verticalId)->first();
            while ($current && $current->parent_id) {
                $level++;
                $current = $allVerticals->where('id', $current->parent_id)->first();
            }
            return $level;
        };

        $verticalCounts = [];
        foreach ($allVerticals as $vertical) {
            $verticalCounts[$vertical->id] = [
                'pending' => 0,
                'completed' => 0,
                'total' => 0,
                'name' => $vertical->name,
                'level' => $getLevel($vertical->id),
                'has_children' => $vertical->children->count() > 0,
            ];
        }

        foreach ($complaints as $complaint) {
            if ($complaint->verticals->isEmpty()) {
                continue;
            }

            $deepestVertical = null;
            $maxDepth = -1;

            foreach ($complaint->verticals as $vertical) {
                $depth = $getDepth($vertical->id);
                if ($depth > $maxDepth) {
                    $maxDepth = $depth;
                    $deepestVertical = $vertical;
                }
            }

            if ($deepestVertical && isset($verticalCounts[$deepestVertical->id])) {
                $verticalCounts[$deepestVertical->id]['total']++;
                if (in_array($complaint->status_id, $completedStatusIds->toArray())) {
                    $verticalCounts[$deepestVertical->id]['completed']++;
                } else {
                    $verticalCounts[$deepestVertical->id]['pending']++;
                }
            }
        }

        $processVertical = function($vertical, $level = 0) use (&$processVertical, &$verticalCounts, &$categoryData) {
            $data = $verticalCounts[$vertical->id];
            $total = $data['total'];
            $completed = $data['completed'];
            $pending = $data['pending'];

            if ($total > 0 || $vertical->children->count() == 0) {
                $categoryData[] = [
                    'id' => $vertical->id,
                    'name' => $data['name'],
                    'pending' => $pending,
                    'completed' => $completed,
                    'total' => $total,
                    'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 2) : 0,
                    'level' => $level,
                    'has_children' => $data['has_children'],
                ];
            }

            foreach ($vertical->children as $child) {
                $processVertical($child, $level + 1);
            }
        };

        foreach ($allVerticals->where('parent_id', null) as $rootCategory) {
            $processVertical($rootCategory, 0);
        }

        return $categoryData;
    }
}
