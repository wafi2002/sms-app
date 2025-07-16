<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Performance Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
            text-align: center;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 2.5em;
            font-weight: 300;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .report-info {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            border-left: 4px solid #667eea;
        }

        .report-info h2 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.3em;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 15px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-weight: 600;
            color: #555;
            font-size: 0.9em;
            margin-bottom: 5px;
        }

        .info-value {
            color: #333;
            font-size: 1.1em;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .table-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 25px;
        }

        .table-header h2 {
            font-size: 1.4em;
            font-weight: 400;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95em;
        }

        th {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            padding: 18px 20px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        td {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f3f4;
            transition: background-color 0.2s ease;
        }

        tbody tr:hover {
            background-color: #f8f9fa;
        }

        tbody tr:nth-child(even) {
            background-color: #fdfdfd;
        }

        .matric-no {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: #667eea;
        }

        .student-name {
            font-weight: 500;
            color: #2d3748;
        }

        .average-mark {
            text-align: center;
            font-weight: 500;
        }

        .gpa {
            text-align: center;
            font-weight: 600;
            font-size: 1.05em;
        }

        .gpa-excellent {
            color: #38a169;
        }

        .gpa-good {
            color: #3182ce;
        }

        .gpa-average {
            color: #d69e2e;
        }

        .gpa-below {
            color: #e53e3e;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            color: #6c757d;
            font-size: 0.9em;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .statistics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            text-align: center;
            border-top: 4px solid #667eea;
        }

        .stat-value {
            font-size: 2.2em;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 8px;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9em;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .header h1 {
                font-size: 2em;
            }

            table {
                font-size: 0.85em;
            }

            th, td {
                padding: 12px 15px;
            }
        }

        @media print {
            body {
                background: white;
            }

            .container {
                max-width: none;
                padding: 0;
            }

            .header, .table-container, .report-info, .footer {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Academic Performance Report</h1>
            <p>Average Pointer Analysis</p>
        </div>

        <div class="report-info">
            <h2>Report Summary</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Report Generated</div>
                    <div class="info-value">{{ date('F j, Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Academic Period</div>
                    <div class="info-value">{{ $academic_period ?? 'Current Semester' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Total Students</div>
                    <div class="info-value">{{ count($rows) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Department</div>
                    <div class="info-value">{{ $department ?? 'All Departments' }}</div>
                </div>
            </div>
        </div>

        <div class="statistics">
            <div class="stat-card">
                <div class="stat-value">{{ number_format(collect($rows)->avg('average_pointer'), 2) }}</div>
                <div class="stat-label">Average GPA</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format(collect($rows)->max('average_pointer'), 2) }}</div>
                <div class="stat-label">Highest GPA</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format(collect($rows)->avg('average_mark'), 1) }}%</div>
                <div class="stat-label">Average Mark</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ collect($rows)->where('average_pointer', '>=', 3.5)->count() }}</div>
                <div class="stat-label">Dean's List</div>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h2>Student Performance Details</h2>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Matric Number</th>
                            <th>Student Name</th>
                            <th>Average Mark (%)</th>
                            <th>Grade Point Average</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $row)
                            <tr>
                                <td class="matric-no">{{ $row['matric_no'] }}</td>
                                <td class="student-name">{{ $row['name'] }}</td>
                                <td class="average-mark">{{ number_format($row['average_mark'], 1) }}%</td>
                                <td class="gpa
                                    @if($row['average_pointer'] >= 3.5) gpa-excellent
                                    @elseif($row['average_pointer'] >= 3.0) gpa-good
                                    @elseif($row['average_pointer'] >= 2.5) gpa-average
                                    @else gpa-below
                                    @endif">
                                    {{ number_format($row['average_pointer'], 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="footer">
            <p><strong>Note:</strong> This report is generated automatically and contains confidential academic information.</p>
            <p>Generated on {{ date('F j, Y \a\t g:i A') }} | Academic Affairs Office</p>
        </div>
    </div>
</body>
</html>
