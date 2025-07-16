<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            padding: 40px 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 300;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .header .subtitle {
            font-size: 16px;
            opacity: 0.9;
            font-weight: 300;
        }

        .content {
            padding: 30px;
        }

        .report-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #667eea;
        }

        .info-item {
            text-align: center;
        }

        .info-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        th {
            padding: 18px 15px;
            text-align: left;
            font-weight: 600;
            color: white;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        th:first-child {
            text-align: center;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        td:first-child {
            text-align: center;
            font-weight: 600;
            color: #667eea;
        }

        td:nth-child(3) {
            text-align: center;
            font-weight: 600;
        }

        td:last-child {
            text-align: center;
        }

        tbody tr:hover {
            background-color: #f8f9fa;
            transition: background-color 0.3s ease;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status.pass {
            background-color: #d4edda;
            color: #155724;
        }

        .status.fail {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status.pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #666;
            font-size: 12px;
        }

        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            border-left: 4px solid #667eea;
        }

        .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        @media (max-width: 768px) {
            body {
                padding: 20px 10px;
            }

            .header h1 {
                font-size: 24px;
            }

            .content {
                padding: 20px;
            }

            .report-info {
                flex-direction: column;
                gap: 15px;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 10px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $title }}</h1>
            <div class="subtitle">Academic Performance Report</div>
        </div>

        <div class="content">
            <div class="report-info">
                <div class="info-item">
                    <div class="info-label">Generated On</div>
                    <div class="info-value">{{ date('M d, Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Total Subjects</div>
                    <div class="info-value">{{ count($rows) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Report Type</div>
                    <div class="info-value">Subject Analysis</div>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Subject Name</th>
                        <th>Average Mark</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                        <tr>
                            <td>{{ $row['code'] }}</td>
                            <td>{{ $row['name'] }}</td>
                            <td>{{ number_format($row['average_mark'], 1) }}%</td>
                            <td>
                                <span class="status {{ strtolower($row['status']) }}">
                                    {{ $row['status'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="footer">
                <p>This report was automatically generated on {{ date('F j, Y \a\t g:i A') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
