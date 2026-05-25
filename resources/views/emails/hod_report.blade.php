<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daily Complaint Summary Report</title>
</head>

<body style="margin:0;padding:20px;background-color:#f4f6f9;font-family:Arial,sans-serif;line-height:1.6;">

<div style="max-width:700px;margin:auto;background:#ffffff;border-radius:10px;overflow:hidden;border:1px solid #e5e7eb;">

    <!-- Header -->
    <div style="background:#1e3a8a;padding:30px;text-align:center;color:white;">
        <h1 style="margin:0;font-size:28px;">
            Daily Complaint Summary Report
        </h1>

        <p style="margin-top:8px;font-size:15px;opacity:0.9;">
            {{ $reportData['date'] ?? 0  }}
        </p>
    </div>


    <!-- Content -->
    <div style="padding:35px;">

        <p style="margin:0;font-size:16px;">
            Dear <strong>HOD</strong>,
        </p>

        <p style="margin-top:20px;color:#555;font-size:15px;">
            Please find below the daily complaint status summary for your review and monitoring. 
            This report provides a quick overview of complaints received and their current status within the Complaint Redressal Ticketing (CRT) System.
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


        <!-- Notes section -->

        <div style="margin-top:30px;padding:18px;background:#f8fafc;border-left:4px solid #1e3a8a;border-radius:5px;">

            <strong>Remarks:</strong>

            <p style="margin-top:8px;color:#555;font-size:14px;">
                Kindly review complaints that remain unassigned or pending action to ensure timely resolution and effective service delivery.
            </p>

        </div>


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