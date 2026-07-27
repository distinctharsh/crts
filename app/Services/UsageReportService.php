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
        $completedStatusIds = Status::whereIn('name', ['completed', 'closed'])->pluck('id')->toArray();
        $complaintsQuery = Complaint::whereNotNull('vertical_id');
        
        if ($dateFrom) {
            $complaintsQuery->where('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $complaintsQuery->where('created_at', '<=', $dateTo);
        }
        $complaints = $complaintsQuery->get(['id', 'vertical_id', 'status_id']);
        $exactCounts = [];
        foreach ($complaints as $complaint) {
            $vId = $complaint->vertical_id;

            if (!isset($exactCounts[$vId])) {
                $exactCounts[$vId] = [
                    'pending' => 0,
                    'completed' => 0,
                    'total' => 0,
                ];
            }

            $exactCounts[$vId]['total']++;
            if (in_array($complaint->status_id, $completedStatusIds)) {
                $exactCounts[$vId]['completed']++;
            } else {
                $exactCounts[$vId]['pending']++;
            }
        }

        $activeVerticalIds = array_keys($exactCounts);
        if (empty($activeVerticalIds)) {
            return [];
        }

        $verticals = Vertical::whereIn('id', $activeVerticalIds)->get()->keyBy('id');
        $categoryData = [];
        foreach ($exactCounts as $verticalId => $counts) {
            $vertical = $verticals->get($verticalId);
            
            if (!$vertical) {
                continue;
            }

            $total = $counts['total'];
            $completed = $counts['completed'];
            $pending = $counts['pending'];

            $categoryData[] = [
                'id' => $vertical->id,
                'name' => $vertical->name,
                'full_path' => $vertical->full_path ?? $vertical->name,
                'pending' => $pending,
                'completed' => $completed,
                'total' => $total,
                'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 2) : 0,
                'level' => 0,
                'has_children' => false,
            ];
        }

        return $categoryData;
    }

    public function getParentCategoryWiseStatistics($dateFrom = null, $dateTo = null)
    {
        $completedStatusIds = Status::whereIn('name', ['completed', 'closed'])->pluck('id')->toArray();
        $complaintsQuery = Complaint::whereHas('vertical', function ($q) {
            $q->where('is_excluded', 0);
        });

        if ($dateFrom) {
            $complaintsQuery->where('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $complaintsQuery->where('created_at', '<=', $dateTo);
        }

        $complaints = $complaintsQuery->get(['id', 'vertical_id', 'status_id']);
        $vCounts = [];
        foreach ($complaints as $complaint) {
            $vId = $complaint->vertical_id;
            if (!isset($vCounts[$vId])) {
                $vCounts[$vId] = ['pending' => 0, 'completed' => 0, 'total' => 0];
            }
            $vCounts[$vId]['total']++;
            if (in_array($complaint->status_id, $completedStatusIds)) {
                $vCounts[$vId]['completed']++;
            } else {
                $vCounts[$vId]['pending']++;
            }
        }

        $allVerticals = Vertical::where('is_excluded', 0)->get()->keyBy('id');
        $getTopParentId = function ($vId) use ($allVerticals, &$getTopParentId) {
            if (!isset($allVerticals[$vId])) {
                return null;
            }
            $vertical = $allVerticals[$vId];
            if (empty($vertical->parent_id)) {
                return $vertical->id;
            }
            return $getTopParentId($vertical->parent_id);
        };

        $parentData = [];
        foreach ($vCounts as $vId => $counts) {
            $topParentId = $getTopParentId($vId);
            if (!$topParentId || !isset($allVerticals[$topParentId])) {
                continue;
            }

            if (!isset($parentData[$topParentId])) {
                $parentData[$topParentId] = [
                    'id' => $topParentId,
                    'name' => $allVerticals[$topParentId]->name,
                    'pending' => 0,
                    'completed' => 0,
                    'total' => 0,
                ];
            }

            $parentData[$topParentId]['pending'] += $counts['pending'];
            $parentData[$topParentId]['completed'] += $counts['completed'];
            $parentData[$topParentId]['total'] += $counts['total'];
        }

        return array_values($parentData);
    }
}