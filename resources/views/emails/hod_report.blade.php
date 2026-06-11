<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="format-detection" content="telephone=no, date=no, address=no, email=no, url=no">
        <title>Daily Complaint Summary Report</title>
    </head>

    <body style="margin:0;padding:20px;background-color:#f4f6f9;font-family:Arial,sans-serif;line-height:1.6;">
        <div style="max-width:700px;margin:auto;background:#ffffff;border-radius:10px;overflow:hidden;border:1px solid #e5e7eb;">

            <!-- Header -->
            <div style="background:#1e3a8a;padding:30px;text-align:center;color:white;">
                <h1 style="margin:0;font-size:28px;">
                    Daily Complaint Summary Report
                </h1>

                <p style="margin-top:8px;font-size:15px;opacity:0.9;color:#ffffff !important;">
                    <span style="color:#ffffff !important;text-decoration:none !important;">
                        {{ $reportData['date'] ?? 0 }}
                    </span>
                </p>
            </div>

            <!-- Content -->
            <div style="padding:35px;">
                <p style="margin:0;font-size:16px;">
                    Dear <strong>Sir/Mam</strong>,
                </p>

                <p style="margin-top:20px;color:#555;font-size:15px;">
                    Please find below the daily complaint status summary for your review and monitoring.
                </p>

                <!-- Cards -->
                <table width="100%" cellspacing="10" cellpadding="0" style="margin-top:25px;">
                    <tr>
                        <td width="50%">
                            <div style="background:#4F46E5;padding:20px;border-radius:8px;text-align:center;color:white;">
                                <div style="font-size:14px;">
                                    Today's Complaints
                                </div>

                                <div style="font-size:34px;font-weight:bold;margin-top:5px;">
                                    {{ $reportData['total_complaints'] ?? 0  }}
                                </div>
                            </div>
                        </td>

                        <td width="50%">
                            <div style="background:#DC2626;padding:20px;border-radius:8px;text-align:center;color:white;">
                                <div style="font-size:14px;">
                                    Unassigned
                                </div>

                                <div style="font-size:34px;font-weight:bold;margin-top:5px;">
                                    {{ $reportData['unassigned'] ?? 0  }}
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%">
                            <div style="background:#059669;padding:20px;border-radius:8px;text-align:center;color:white;">
                                <div style="font-size:14px;">
                                    Completed
                                </div>

                                <div style="font-size:34px;font-weight:bold;margin-top:5px;">
                                    {{ $reportData['completed'] ?? 0  }}
                                </div>
                            </div>
                        </td>

                        <td width="50%">
                            <div style="background:#D97706;padding:20px;border-radius:8px;text-align:center;color:white;">
                                <div style="font-size:14px;">
                                    Action Pending
                                </div>

                                <div style="font-size:34px;font-weight:bold;margin-top:5px;">
                                    {{ $reportData['action_pending'] ?? 0  }}
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>

                <!-- Usage Report Section -->
                @if(isset($reportData['usage_data']) && count($reportData['usage_data']) > 0)
                <div style="margin-top:40px;">
                    <h3 style="margin:0 0 20px 0;font-size:18px;color:#1e3a8a;border-bottom:2px solid #1e3a8a;padding-bottom:10px;">
                        User Performance Report
                    </h3>
                    <table width="100%" cellspacing="0" cellpadding="10" style="border-collapse:collapse;background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
                        <thead>
                            <tr style="background:#1e3a8a;color:white;">
                                <th style="text-align:left;padding:12px;font-size:14px;">User Name</th>
                                <th style="text-align:center;padding:12px;font-size:14px;">Pending</th>
                                <th style="text-align:center;padding:12px;font-size:14px;">Completed</th>
                                <th style="text-align:center;padding:12px;font-size:14px;">Total</th>
                                <th style="text-align:center;padding:12px;font-size:14px;">Completion Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportData['usage_data'] as $user)
                            <tr style="border-bottom:1px solid #e5e7eb;">
                                <td style="padding:12px;font-size:14px;color:#333;">{{ $user['name'] }}</td>
                                <td style="text-align:center;padding:12px;font-size:14px;color:#D97706;font-weight:bold;">{{ $user['pending'] }}</td>
                                <td style="text-align:center;padding:12px;font-size:14px;color:#059669;font-weight:bold;">{{ $user['completed'] }}</td>
                                <td style="text-align:center;padding:12px;font-size:14px;color:#1e3a8a;font-weight:bold;">{{ $user['total'] }}</td>
                                <td style="text-align:center;padding:12px;font-size:14px;color:#333;">{{ $user['completion_rate'] }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <!-- Button -->
                <div style="text-align:center;margin-top:35px;">
                    <a href="https://crts.gov.in"
                    style="display:inline-block;background:#1e3a8a;color:white;padding:14px 30px;text-decoration:none;border-radius:25px;font-weight:bold;">
                        View CRTS Dashboard
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div style="background:#f8f9fa;padding:25px;text-align:center;border-top:1px solid #e5e7eb;">
                <p style="margin:0;font-size:13px;color:#666;">
                    This is an automated system-generated report. Please do not reply to this email.
                </p>

                <p style="margin-top:10px;font-size:12px;color:#999;">
                    © {{ date('Y') }} Complaint Redressal Ticketing (CRT) System
                </p>
            </div>
        </div>
    </body>
</html>