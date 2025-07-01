<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pulse Threat Advisory</title>
    <style>
        @page {
            margin: 0;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }
        .page {
            page-break-after: always;
            padding: 20px;
        }

        /* Cover Page Styles */
        .cover-page {
            height: 100vh;
            background: linear-gradient(135deg, #1a365d 0%, #2a4365 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding-right: 80px;
        }
        .cover-title {
            font-size: 60px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 40px;
        }
        .cover-card {
            background: white;
            color: #333;
            padding: 80px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            width: 85%;
            max-width: 750px;
        }
        .cover-logo {
            width: 250px;
            height: 250px;
            margin-bottom: 40px;
        }
        .cover-footer {
            position: absolute;
            bottom: 5%;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Content Page Styles */
        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #eaeaea;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header-logo {
            height: 30px;
            margin-right: 10px;
        }
        .header-title {
            font-size: 16px;
            font-weight: bold;
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f5f7fa;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-top: 20px;
            border-top: 1px solid #eaeaea;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="page cover-page">
        <h1 class="cover-title">Threat Intelligence Report</h1>
        <div class="cover-card">
            <h2 style="font-size: 36px;">THREAT INTELLIGENCE REPORT</h2>
            <img class="cover-logo" src="THREAT.png" alt="Logo">
            <h2 style="font-size: 36px;">Threat Intelligence Advisory</h2>
            <h3 style="font-size: 28px;">{{ $pulse['name'] ?? 'Unknown Pulse' }}</h3>
        </div>
        <div class="cover-footer">CONFIDENTIAL - FOR INTERNAL USE ONLY</div>
    </div>

    <div class="page content-page">
        <div class="header">
            <img class="header-logo" src="THREAT.png" alt="Logo">
            <h1 class="header-title">Threat Intelligence Advisory</h1>
        </div>

        <h2 class="pulse-title">{{ $pulse['name'] ?? 'Unknown Pulse' }}</h2>
        <p class="pulse-title">{{ $pulse['description'] ?? 'Unknown Pulse' }}</p>
        <table>
            <tr><td><strong>ID</strong></td><td>{{ $pulse['id'] ?? 'N/A' }}</td></tr>
            <tr><td><strong>Revision</strong></td><td>{{ $pulse['revision'] ?? 'N/A' }}</td></tr>
            <tr><td><strong>Public</strong></td><td>{{ isset($pulse['public']) && $pulse['public'] ? 'Yes' : 'No' }}</td></tr>
            <tr><td><strong>Adversary</strong></td><td>{{ $pulse['adversary'] ?? 'Unknown' }}</td></tr>
        </table>

        <h3>Indicators</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Indicator</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pulse['indicators'] ?? [] as $indicator)
                    <tr>
                        <td>{{ $indicator['id'] ?? '-' }}</td>
                        <td>{{ $indicator['type'] ?? '-' }}</td>
                        <td>{{ $indicator['indicator'] ?? '-' }}</td>
                        <td>{{ isset($indicator['created']) ? date('d M Y', strtotime($indicator['created'])) : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">No indicators available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="footer">
            This report contains sensitive information and should be handled according to your organization's security policies.
        </div>
    </div>
</body>
</html>
