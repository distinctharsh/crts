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

        // Get vertical IDs from complaint (multiple verticals)
        $verticalIds = $complaint->verticals->pluck('id');

        // Get VMs whose vertical matches any of the complaint's verticals
        $vms = User::whereHas('role', function ($query) {
            $query->where('slug', 'vm');
        })->whereHas('verticals', function ($query) use ($verticalIds) {
            $query->whereIn('vertical_id', $verticalIds);
        })->get();


        $toRecipients = $vms->pluck('email')->filter()->unique()->toArray();

        $ccRecipients = $managers->pluck('email')->filter()->unique()->toArray();

        if (empty($toRecipients) && empty($ccRecipients)) {
            // No recipients
            return;
        }

        try {
            $complaint->load([
                'verticals',
                'section',
                'networkType',
                'status',
                'assignedTo'
            ]);

            if (!empty($toRecipients)) {
                Mail::to($toRecipients)
                    ->cc($ccRecipients)
                    ->send(new ComplaintNotificationMail($managers->first(), $complaint, 'new'));

            } elseif (!empty($ccRecipients)) {
                Mail::to($ccRecipients)
                    ->send(new ComplaintNotificationMail($managers->first(), $complaint, 'new'));
            }

        } catch (\Exception $e) {
            \Log::error('Failed to send new complaint email: ' . $e->getMessage(), [
                'complaint_id' => $complaint->id,
                'error' => $e->getMessage()
            ]);
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

        $verticalIds = $complaint->verticals->pluck('id');

        // Get VMs whose vertical matches any of the complaint's verticals
        $vms = User::whereHas('role', function ($query) {
            $query->where('slug', 'vm');
        })
        ->whereHas('verticals', function ($query) use ($verticalIds) {
            $query->whereIn('vertical_id', $verticalIds);
        })
        ->get();

        // Get all Managers
        $managers = User::whereHas('role', function ($query) {
            $query->where('slug', 'manager');
        })->get();

        // Combine VMs and Managers for CC
        $ccRecipients = array_merge(
            $vms->pluck('email')->filter()->toArray(),
            $managers->pluck('email')->filter()->toArray()
        );

        // Remove duplicates and assigned user from CC (to avoid duplicate)
        $assignedUserEmail = $assignedUser->email ?? $assignedUser->username;
        $ccRecipients = array_unique($ccRecipients);
        $ccRecipients = array_filter($ccRecipients, function($email) use ($assignedUserEmail) {
            return $email !== $assignedUserEmail;
        });

        try {
            $complaint->load([
                'verticals',
                'section',
                'networkType',
                'status',
                'assignedTo'
            ]);

            Mail::to($assignedUserEmail)
                ->cc($ccRecipients)
                ->send(new ComplaintNotificationMail($assignedUser, $complaint, 'assigned'));

        } catch (\Exception $e) {
            \Log::error('Failed to send assigned complaint email: ' . $e->getMessage(), [
                'complaint_id' => $complaint->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
