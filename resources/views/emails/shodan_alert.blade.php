<!DOCTYPE html>
<html>
<head>
    <title>Shodan Alert Notification</title>
</head>
<body>
    <h2>Shodan Alert Notification</h2>
    <p><strong>IP Address:</strong> {{ $alert['filters']['ip'] ?? 'Unknown' }}</p>
    <p><strong>Alert Name:</strong> {{ $alert['name'] ?? 'Unknown' }}</p>
    <p><strong>Created At:</strong> {{ $alert['created'] ?? 'Unknown' }}</p>

    <p>Segera periksa sistem Anda!</p>

    <p>Terima kasih,<br>Dasaratha System</p>
</body>
</html>
