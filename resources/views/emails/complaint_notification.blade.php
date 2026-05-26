<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Notification</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center;">
            @if($notificationType === 'assigned')
                <h1 style="margin: 0; font-size: 28px;">📋 Complaint Assigned to You</h1>
                <p style="margin: 10px 0 0 0; opacity: 0.9;">Action Required - Please Review</p>
            @else
                <h1 style="margin: 0; font-size: 28px;">📋 New Complaint Created</h1>
                <p style="margin: 10px 0 0 0; opacity: 0.9;">New Complaint Requires Attention</p>
            @endif
        </div>

        <!-- Content -->
<!-- Content -->
<div style="padding:25px;">

    <p style="margin-top:0;">
        Hello <strong>{{ $user->full_name }}</strong>,
    </p>

    @if($notificationType === 'assigned')
        <p style="margin-bottom:20px;color:#555;">
            A complaint has been assigned to you for review and necessary action.
        </p>
    @else
        <p style="margin-bottom:20px;color:#555;">
            A new complaint has been registered in the Complaint Redressal Ticketing (CRT) System.
        </p>
    @endif

    <!-- Compact Details Table -->

    <table width="100%" cellpadding="8" cellspacing="0"
        style="border-collapse:collapse;
               border:1px solid #ddd;
               font-size:13px;
               margin-bottom:20px;">

        <tr style="background:#f8f9fa;">
            <th colspan="4"
                style="padding:10px;
                       text-align:left;
                       color:#667eea;
                       font-size:15px;">
                Complaint Details
            </th>
        </tr>

        <tr>
            <td style="font-weight:bold;width:20%;">Ref No.</td>
            <td>{{ $complaint->reference_number }}</td>

            <td style="font-weight:bold;width:20%;">Status</td>
            <td>{{ $complaint->status->display_name ?? 'Unassigned' }}</td>
        </tr>

        <tr style="background:#fafafa;">
            <td style="font-weight:bold;">User</td>
            <td>{{ $complaint->user_name }}</td>

            <td style="font-weight:bold;">Room</td>
            <td>{{ $complaint->room_number }}</td>
        </tr>

        <tr>
            <td style="font-weight:bold;">Intercom</td>
            <td>{{ $complaint->intercom }}</td>

            <td style="font-weight:bold;">Priority</td>
            <td>

                @if($complaint->priority === 'high')
                    <span style="color:#dc3545;font-weight:bold;">
                        HIGH
                    </span>
                @else
                    <span style="color:#ff9800;font-weight:bold;">
                        MEDIUM
                    </span>
                @endif

            </td>
        </tr>

        <tr style="background:#fafafa;">
            <td style="font-weight:bold;">Verticals</td>
            <td>{{ $complaint->verticals->pluck('name')->map(fn($name) => ucfirst($name))->implode(', ') ?? '-' }}</td>

            <td style="font-weight:bold;">Section</td>
            <td>{{ $complaint->section->name ?? '-' }}</td>
        </tr>

        <tr>
            <td style="font-weight:bold;">Issue Type</td>
            <td>{{ $complaint->networkType->name ?? '-' }}</td>

            <td style="font-weight:bold;">Assigned To</td>
            <td>
                {{ $complaint->assignedTo->full_name ?? '-' }}
            </td>
        </tr>

        <tr style="background:#fafafa;">
            <td style="font-weight:bold;">Created</td>
            <td colspan="3">
                {{ $complaint->created_at->format('M d, Y H:i') }}
            </td>
        </tr>

        <tr>
            <td style="font-weight:bold;">Description</td>
            <td colspan="3">
                {{ $complaint->description }}
            </td>
        </tr>

    </table>


    <div style="text-align:center;">

        <a href="#"
           style="display:inline-block;
                  background:#667eea;
                  color:white;
                  padding:10px 25px;
                  border-radius:20px;
                  text-decoration:none;
                  font-weight:bold;">

            View Complaint Details

        </a>

    </div>

</div>

        <!-- Footer -->
        <div style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #eee;">
            <p style="margin: 0; color: #666; font-size: 14px;">This is an automated email. Please do not reply to this message.</p>
            <p style="margin: 10px 0 0 0; color: #999; font-size: 12px;">&copy; {{ date('Y') }} Complaint Management System</p>
        </div>
    </div>
</body>
</html>
