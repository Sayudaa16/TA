<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domain Report</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            padding: 0;
            color: #333;
        }

        h1 {
            color: #2c3e50;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 0.9rem; /* Smaller font size to fit the content */
        }

        table th, table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            word-wrap: break-word;
            vertical-align: top;
        }

        table th {
            background-color: #3498db;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        ul {
            margin: 0;
            padding-left: 20px;
        }

        /* Adjustments for page size in PDF */
        @page {
            size: A4;
            margin: 20mm;
        }

        /* Ensure content fits within page */
        body, table {
            page-break-inside: avoid;
        }

        /* Allow for the content to break across multiple pages without cutting off */
        table {
            page-break-after: auto;
        }

    </style>
</head>
<body>
    <h1>Domain Report</h1>

    <table>
        <thead>
            <tr>
                <th>Nama Domain</th>
                <th>Expiry Date</th>
                <th>Registrar</th>
                <th>Created Date</th>
                <th>Updated Date</th>
                <th>Name Servers</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($domains as $domain)
                <tr>
                    <td>{{ $domain->domain_name }}</td>
                    <td>{{ $domain->expiry_date }}</td>
                    <td>{{ $domain->registrar_name }}</td>
                    <td>{{ $domain->created_date }}</td>
                    <td>{{ $domain->updated_date }}</td>
                    <td>
                        <ul>
                            @foreach (json_decode($domain->name_servers, true) as $nameServer)
                                <li>{{ $nameServer }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ $domain->domain_status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
