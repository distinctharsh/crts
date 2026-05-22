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
        <div style="padding: 30px;">
            <p style="margin: 0 0 20px 0;">Hello <strong>{{ $user->full_name }}</strong>,</p>
            
            @if($notificationType === 'assigned')
                <p style="margin: 0 0 20px 0;">A complaint has been assigned to you. Please review and take appropriate action.</p>
            @else
                <p style="margin: 0 0 20px 0;">A new complaint has been created in the system. Please review the details below.</p>
            @endif

            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #667eea; width: 40%;">Reference Number:</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $complaint->reference_number }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #667eea;">User Name:</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $complaint->user_name }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #667eea;">Room Number:</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $complaint->room_number }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #667eea;">Intercom:</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $complaint->intercom }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #667eea;">Vertical:</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $complaint->vertical }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #667eea;">Section:</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $complaint->section }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #667eea;">Issue Type:</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $complaint->network_type }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #667eea;">Priority:</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">
                        @if($complaint->priority === 'high')
                            <span style="background-color: #ff6b6b; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: bold;">HIGH</span>
                        @else
                            <span style="background-color: #ffd43b; color: #333; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: bold;">MEDIUM</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #667eea;">Status:</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $complaint->status->display_name ?? 'Unassigned' }}</td>
                </tr>
                @if($complaint->assigned_to)
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #667eea;">Assigned To:</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $complaint->assignedTo->full_name ?? '-' }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #667eea;">Created At:</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $complaint->created_at->format('M d, Y H:i') }}</td>
                </tr>
            </table>

            <div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #667eea; border-radius: 4px; margin-bottom: 20px;">
                <p style="margin: 0; font-weight: bold; color: #667eea; margin-bottom: 8px;">Description:</p>
                <p style="margin: 0; color: #555;">{{ $complaint->description }}</p>
            </div>

            <p style="margin: 0 0 20px 0;">Please review this complaint and take appropriate action.</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('complaints.show', $complaint) }}" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; font-weight: bold; box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);">View Complaint Details</a>
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
