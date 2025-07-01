<!-- resources/views/pdf/result.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Result - {{ $host->ip }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h1>Scan Result for IP: {{ $host->ip }}</h1>

<h3>Details:</h3>
<table>
    <tr>
        <th>IP Address</th>
        <td>{{ $host->ip }}</td>
    </tr>
    <tr>
        <th>Country</th>
        <td>{{ $host->country }}</td>
    </tr>
    <tr>
        <th>City</th>
        <td>{{ $host->city }}</td>
    </tr>
    <tr>
        <th>ISP</th>
        <td>{{ $host->isp }}</td>
    </tr>
    <tr>
        <th>ASN</th>
        <td>{{ $host->asn }}</td>
    </tr>
    <tr>
        <th>Open Ports</th>
        <td>
            @php
                $ports = json_decode($host->ports);
                echo is_array($ports) ? implode(', ', $ports) : 'N/A';
            @endphp
        </td>
    </tr>
</table>

<h3>Vulnerabilities (CVE):</h3>
@if ($host->vulns)
    <table>
        <thead>
            <tr>
                <th>CVE ID</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach (json_decode($host->vulns) as $vuln)
                <tr>
                    <td>{{ $vuln }}</td>
                    <td>{{ json_decode($host->cve_details)->{$vuln} ?? 'No details available' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No vulnerabilities detected.</p>
@endif

<h3>Service Banners:</h3>
@if ($host->service_banners)
    <table>
        <thead>
            <tr>
                <th>Port</th>
                <th>Service Banner</th>
            </tr>
        </thead>
        <tbody>
            @foreach (json_decode($host->service_banners) as $service)
                <tr>
                    <td>{{ $service->port }}</td>
                    <td>{{ $service->banner }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No service banners available.</p>
@endif

</body>
</html>
