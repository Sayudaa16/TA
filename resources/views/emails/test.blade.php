<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Notification</title>
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
            <h1>System Notification</h1>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>This is a notification regarding the system details of your server:</p>
            <table class="details-table">
                <tr>
                    <th>IP Address</th>
                    <td>103.253.212.117</td>
                </tr>
                <tr>
                    <th>Port</th>
                    <td>3306 / TCP</td>
                </tr>
                <tr>
                    <th>Domain</th>
                    <td>sthree.co.id</td>
                </tr>
                <tr>
                    <th>Asset Group</th>
                    <td>end_of_life</td>
                </tr>
                <tr>
                    <th>Trigger</th>
                    <td>MariaDB 10.2.44-MariaDB-cll-lve</td>
                </tr>
            </table>
            <p><strong>MariaDB Details:</strong></p>
            <table class="details-table">
                <tr>
                    <th>Protocol Version</th>
                    <td>10</td>
                </tr>
                <tr>
                    <th>Version</th>
                    <td>10.2.44-MariaDB-cll-lve</td>
                </tr>
                <tr>
                    <th>Capabilities</th>
                    <td>63486</td>
                </tr>
                <tr>
                    <th>Server Language</th>
                    <td>8</td>
                </tr>
                <tr>
                    <th>Server Status</th>
                    <td>2</td>
                </tr>
                <tr>
                    <th>Extended Capabilities</th>
                    <td>33215</td>
                </tr>
                <tr>
                    <th>Authentication Plugin</th>
                    <td>mysql_native_password</td>
                </tr>
            </table>
        </div>
        <div class="footer">
            <p>&copy; 2024 DASARATHA. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
