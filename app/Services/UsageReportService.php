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
}
