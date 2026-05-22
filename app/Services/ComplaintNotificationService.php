<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\User;
use App\Mail\ComplaintNotificationMail;
use Illuminate\Support\Facades\Mail;

class ComplaintNotificationService
{
    /**
     * Send email notifications when a new complaint is created.
     * Sends ONE email to Managers with VMs in CC.
     *
     * @param Complaint $complaint
     * @return void
     */
    public function sendNewComplaintNotifications(Complaint $complaint)
    {
        // Get all Managers
        $managers = User::whereHas('role', function ($query) {
            $query->where('slug', 'manager');
        })->get();

        // Get VMs whose vertical matches the complaint's vertical
        $vms = User::whereHas('role', function ($query) {
            $query->where('slug', 'vm');
        })->whereHas('verticals', function ($query) use ($complaint) {
            $query->where('vertical_id', $complaint->vertical_id);
        })->get();

        // If no managers, send to first VM as primary recipient
        if ($managers->isEmpty() && !$vms->isEmpty()) {
            $primaryRecipient = $vms->first();
            $ccRecipients = $vms->slice(1)->pluck('email')->filter()->toArray();
        } elseif (!$managers->isEmpty()) {
            $primaryRecipient = $managers->first();
            $ccRecipients = $vms->pluck('email')->filter()->toArray();
        } else {
            // No recipients
            return;
        }

        try {
            $recipient = $primaryRecipient->email ?? $primaryRecipient->username;
            Mail::to($recipient)
                ->cc($ccRecipients)
                ->send(new ComplaintNotificationMail($primaryRecipient, $complaint, 'new'));
        } catch (\Exception $e) {
            \Log::error('Failed to send new complaint email: ' . $e->getMessage());
        }
    }

    /**
     * Send email notifications when a complaint is assigned.
     * Sends ONE email to assigned user with VMs in CC.
     *
     * @param Complaint $complaint
     * @return void
     */
    public function sendAssignedComplaintNotifications(Complaint $complaint)
    {
        if (!$complaint->assigned_to) {
            return;
        }

        // Get assigned user
        $assignedUser = User::find($complaint->assigned_to);
        if (!$assignedUser) {
            return;
        }

        // Get VMs whose vertical matches the complaint's vertical
        $vms = User::whereHas('role', function ($query) {
            $query->where('slug', 'vm');
        })->whereHas('verticals', function ($query) use ($complaint) {
            $query->where('vertical_id', $complaint->vertical_id);
        })->get();

        $ccRecipients = $vms->pluck('email')->filter()->toArray();

        try {
            $recipient = $assignedUser->email ?? $assignedUser->username;
            Mail::to($recipient)
                ->cc($ccRecipients)
                ->send(new ComplaintNotificationMail($assignedUser, $complaint, 'assigned'));
        } catch (\Exception $e) {
            \Log::error('Failed to send assigned complaint email: ' . $e->getMessage());
        }
    }
}
