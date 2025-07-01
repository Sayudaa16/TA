<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Open Port Detected</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #ddd;
        }
        .header {
            background: #4CAF50;
            color: #ffffff;
            padding: 15px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .content p {
            margin: 10px 0;
            line-height: 1.5;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .details-table th,
        .details-table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .details-table th {
            background: #f8f8f8;
        }
        .footer {
            background: #f4f4f9;
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>New Open Port Detected</h1>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>This is a notification regarding the system details of your server:</p>
            <table class="details-table">
                <tr>
                    <th>Port</th>
                    <td>{{ $port }}</td>
                </tr>
                <tr>
                    <th>Asset Group</th>
                    <td>{{ $asset_group }}</td>
                </tr>
                <tr>
                    <th>Trigger</th>
                    <td>{{ $trigger }}</td>
                </tr>
                <tr>
                    <th>Version</th>
                    <td>{{ $version }}</td>
                </tr>
                <tr>
                    <th>Details</th>
                    <td>{{ $details }}</td>
                </tr>
            </table>
        </div>
        <div class="footer">
            <p>&copy; 2024 DASARATHA. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
