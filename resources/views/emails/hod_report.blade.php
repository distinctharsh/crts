<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Complaint Report - HOD</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4;">
    <div style="max-width: 700px; margin: 0 auto; background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white; padding: 35px; text-align: center;">
            <h1 style="margin: 0; font-size: 32px;">📊 Daily Complaint Report</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9; font-size: 18px;">{{ $reportData['date'] ?? now()->format('M d, Y') }}</p>
        </div>

        <!-- Content -->
        <div style="padding: 35px;">
            <p style="margin: 0 0 25px 0; font-size: 16px;">Dear <strong>HOD</strong>,</p>
            
            <p style="margin: 0 0 25px 0; font-size: 16px;">Please find below the daily complaint report summary for your review.</p>

            <!-- Summary Cards -->
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 30px;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Total Complaints</div>
                    <div style="font-size: 36px; font-weight: bold;">{{ $reportData['total_complaints'] ?? 0 }}</div>
                </div>
                <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Pending</div>
                    <div style="font-size: 36px; font-weight: bold;">{{ $reportData['pending_complaints'] ?? 0 }}</div>
                </div>
                <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 20px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Assigned</div>
                    <div style="font-size: 36px; font-weight: bold;">{{ $reportData['assigned_complaints'] ?? 0 }}</div>
                </div>
                <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 20px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Resolved</div>
                    <div style="font-size: 36px; font-weight: bold;">{{ $reportData['resolved_complaints'] ?? 0 }}</div>
                </div>
            </div>

            <!-- High Priority Section -->
            @if(isset($reportData['high_priority_complaints']) && $reportData['high_priority_complaints'] > 0)
            <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 4px; margin-bottom: 25px;">
                <p style="margin: 0; font-weight: bold; color: #856404; margin-bottom: 8px; font-size: 16px;">⚠️ High Priority Complaints: {{ $reportData['high_priority_complaints'] }}</p>
                <p style="margin: 0; color: #856404; font-size: 14px;">Please review these complaints as they require immediate attention.</p>
            </div>
            @endif

            <!-- Vertical-wise Breakdown -->
            @if(isset($reportData['vertical_breakdown']) && count($reportData['vertical_breakdown']) > 0)
            <div style="margin-bottom: 25px;">
                <h3 style="margin: 0 0 15px 0; color: #1e3a8a; font-size: 18px;">Vertical-wise Breakdown</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f8f9fa;">
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6; color: #1e3a8a;">Vertical</th>
                            <th style="padding: 12px; text-align: center; border-bottom: 2px solid #dee2e6; color: #1e3a8a;">Total</th>
                            <th style="padding: 12px; text-align: center; border-bottom: 2px solid #dee2e6; color: #1e3a8a;">Pending</th>
                            <th style="padding: 12px; text-align: center; border-bottom: 2px solid #dee2e6; color: #1e3a8a;">Resolved</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['vertical_breakdown'] as $vertical => $stats)
                        <tr>
                            <td style="padding: 12px; border-bottom: 1px solid #dee2e6;">{{ $vertical }}</td>
                            <td style="padding: 12px; text-align: center; border-bottom: 1px solid #dee2e6;">{{ $stats['total'] ?? 0 }}</td>
                            <td style="padding: 12px; text-align: center; border-bottom: 1px solid #dee2e6;">{{ $stats['pending'] ?? 0 }}</td>
                            <td style="padding: 12px; text-align: center; border-bottom: 1px solid #dee2e6;">{{ $stats['resolved'] ?? 0 }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Recent Complaints -->
            @if(isset($reportData['recent_complaints']) && count($reportData['recent_complaints']) > 0)
            <div style="margin-bottom: 25px;">
                <h3 style="margin: 0 0 15px 0; color: #1e3a8a; font-size: 18px;">Recent Complaints (Last 5)</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f8f9fa;">
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #dee2e6; color: #1e3a8a; font-size: 14px;">Ref No.</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #dee2e6; color: #1e3a8a; font-size: 14px;">Description</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #dee2e6; color: #1e3a8a; font-size: 14px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['recent_complaints'] as $complaint)
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #dee2e6; font-size: 13px;">{{ $complaint['reference_number'] ?? '-' }}</td>
                            <td style="padding: 10px; border-bottom: 1px solid #dee2e6; font-size: 13px;">{{ Str::limit($complaint['description'] ?? '', 50) }}</td>
                            <td style="padding: 10px; border-bottom: 1px solid #dee2e6; font-size: 13px;">
                                @if($complaint['status'] === 'resolved')
                                    <span style="background-color: #d4edda; color: #155724; padding: 2px 8px; border-radius: 4px; font-size: 11px;">Resolved</span>
                                @elseif($complaint['status'] === 'assigned')
                                    <span style="background-color: #cce5ff; color: #004085; padding: 2px 8px; border-radius: 4px; font-size: 11px;">Assigned</span>
                                @else
                                    <span style="background-color: #f8d7da; color: #721c24; padding: 2px 8px; border-radius: 4px; font-size: 11px;">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <p style="margin: 25px 0; font-size: 16px;">For detailed information, please login to the complaint management system.</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/') }}" style="display: inline-block; background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white; padding: 14px 35px; text-decoration: none; border-radius: 25px; font-weight: bold; box-shadow: 0 4px 6px rgba(30, 58, 138, 0.3); font-size: 16px;">View Dashboard</a>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8f9fa; padding: 25px; text-align: center; border-top: 1px solid #eee;">
            <p style="margin: 0; color: #666; font-size: 14px;">This is an automated daily report. Please do not reply to this message.</p>
            <p style="margin: 10px 0 0 0; color: #999; font-size: 12px;">&copy; {{ date('Y') }} Complaint Management System</p>
        </div>
    </div>
</body>
</html>
