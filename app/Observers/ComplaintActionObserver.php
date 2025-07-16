<?php

namespace App\Observers;

use App\Models\ComplaintAction;

class ComplaintActionObserver
{
    public function created(ComplaintAction $action)
    {
        if (auth()->check()) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($action)
                ->withProperties($action->toArray())
                ->log('Complaint action created');
        }
    }

    public function updated(ComplaintAction $action)
    {
        if (auth()->check()) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($action)
                ->withProperties($action->toArray())
                ->log('Complaint action updated');
        }
    }
} 