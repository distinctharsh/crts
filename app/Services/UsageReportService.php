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
        $complaintsQuery = Complaint::whereHas('vertical');

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

        if (empty($vCounts)) {
            return [];
        }

        $allVerticals = Vertical::get()->keyBy('id');
        $getTopParent = function ($vId) use ($allVerticals, &$getTopParent) {
            if (!isset($allVerticals[$vId])) {
                return null;
            }
            $vertical = $allVerticals[$vId];
            if (empty($vertical->parent_id)) {
                return $vertical;
            }
            return $getTopParent($vertical->parent_id);
        };

        $groupedData = [];
        foreach ($vCounts as $vId => $counts) {
            if (!isset($allVerticals[$vId])) continue;

            $currentVertical = $allVerticals[$vId];
            $topParent = $getTopParent($vId);

            if (!$topParent) continue;
            $isExcluded = ($currentVertical->is_excluded == 1) || ($topParent->is_excluded == 1);

            if ($isExcluded) {
                $disabledKey = 'disabled_v_' . $vId;
                $groupedData[$disabledKey] = [
                    'id' => $currentVertical->id,
                    'name' => $currentVertical->name,
                    'pending' => $counts['pending'],
                    'completed' => $counts['completed'],
                    'total' => $counts['total'],
                    'is_parent' => false,
                    'is_disabled' => true
                ];
            } else {
                $topParentId = $topParent->id;

                if (!isset($groupedData[$topParentId])) {
                    $groupedData[$topParentId] = [
                        'id' => $topParentId,
                        'name' => $topParent->name,
                        'pending' => 0,
                        'completed' => 0,
                        'total' => 0,
                        'is_parent' => true,
                        'is_disabled' => false,
                        'children' => []
                    ];
                }

                $groupedData[$topParentId]['pending'] += $counts['pending'];
                $groupedData[$topParentId]['completed'] += $counts['completed'];
                $groupedData[$topParentId]['total'] += $counts['total'];

                if ($vId != $topParentId) {
                    $groupedData[$topParentId]['children'][] = [
                        'id' => $currentVertical->id,
                        'name' => $currentVertical->full_path ?? $currentVertical->name,
                        'pending' => $counts['pending'],
                        'completed' => $counts['completed'],
                        'total' => $counts['total'],
                        'is_parent' => false
                    ];
                }
            }
        }

        return array_values($groupedData);
    }
}