<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{

    private function calculateStudentAverages()
    {
        $results = Result::with('student', 'subject')
            ->whereNull('deleted_at')
            ->select('id', 'student_id', 'subject_id', 'marks', 'grade')
            ->get();

        $gradePointerMap = [
            'A'  => 4.00,
            'A-' => 3.67,
            'B+' => 3.33,
            'B'  => 3.00,
            'B-' => 2.67,
            'C+' => 2.33,
            'C'  => 2.00,
            'D'  => 1.67,
            'F' => 1.33,
        ];

        $students = [];

        foreach ($results as $result) {
            $studentId = $result->student_id;
            $subject = $result->subject;
            $creditHour = $subject->credit_hours ?? 0;
            $pointer = $gradePointerMap[$result->grade] ?? 0;

            if (!isset($students[$studentId])) {
                $students[$studentId] = [
                    'matric_no' => $result->student->matric_no ?? '-',
                    'name' => $result->student->name ?? '-',
                    'total_weighted_pointer' => 0,
                    'total_credit_hours' => 0,
                    'total_marks' => 0,
                    'subject_count' => 0,
                ];
            }

            $students[$studentId]['total_weighted_pointer'] += $pointer * $creditHour;
            $students[$studentId]['total_credit_hours'] += $creditHour;
            $students[$studentId]['total_marks'] += $result->marks;
            $students[$studentId]['subject_count'] += 1;
        }

        $final = [];

        foreach ($students as $student) {
            $avgPointer = $student['total_credit_hours'] > 0
                ? number_format($student['total_weighted_pointer'] / $student['total_credit_hours'], 2)
                : number_format(0, 2);

            $avgMark = $student['subject_count'] > 0
                ? number_format($student['total_marks'] / $student['subject_count'], 2)
                : number_format(0, 2);

            $status = $avgPointer >= 3.50 ? "Dean's List" : '-';

            $final[] = [
                'matric_no' => $student['matric_no'],
                'name' => $student['name'],
                'average_mark' => $avgMark,
                'average_pointer' => $avgPointer,
                'status' => $status,
            ];
        }

        return $final;
    }

    public function averageStudent()
    {

        $rows = $this->calculateStudentAverages();

        $columns = [
            'matric_no' => 'Matric No',
            'name' => 'Student Name',
            'average_mark' => 'Average Mark',
            'average_pointer' => 'Average Pointer (GPA)',
            'status' => 'Status',
        ];

        $columnAlignments = [
            'matric_no' => 'left',
            'name' => 'left',
            'average_mark' => 'center',
            'average_pointer' => 'center',
            'status' => 'left',
        ];

        $exportActions = [
            [
                'route' => 'reports.exportStdAvgExcel',
                'icon' => 'bx bxs-file-export',
                'exportLabel' => 'Export to Excel',
            ],
            [
                'route' => 'reports.exportStdAvgPdf',
                'icon' => 'bx bxs-file-pdf',
                'exportLabel' => 'Export to PDF',
            ]
        ];

        return view('modules.reportModule.averageStudent', [
            'columns' => $columns,
            'rows' => $rows,
            'title' => 'Average Mark by Student',
            'showAddButton' => false,
            'showExportButton' => true,
            'hasAction' => false,
            'exportActions' => $exportActions,
            'columnAlignments' => $columnAlignments
        ]);
    }

    public function exportStudentAvgExcel()
    {
        $rows = $this->calculateStudentAverages();

        $csvData = [
            ['Matric No', 'Student Name', 'Average Mark by Student', 'Average Pointer (GPA)', 'Status']
        ];

        foreach ($rows as $student) {
            $csvData[] = [
                'matric_no' => $student['matric_no'],
                'name' => $student['name'],
                'average_mark' => $student['average_mark'],
                'average_pointer' => $student['average_pointer'],
                'status' => $student['status'],
            ];
        }

        $filename = 'student_average_mark.csv';
        $handle = fopen('php://temp', 'r+');

        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        // Return response download
        return Response::make($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportStudentAvgPDF()
    {
        $rows = $this->calculateStudentAverages();

        // Generate PDF guna view
        $pdf = Pdf::loadView('modules.reportModule.pdfAverageStudent', [
            'rows' => $rows,
        ]);

        return $pdf->download('average-students.pdf');
    }

    private function calculateSubjectAverages()
    {
        $results = Result::with('subject')
            ->whereNull('deleted_at')
            ->select('subject_id', 'marks')
            ->get();

        $subjects = [];

        foreach ($results as $result) {
            $subjectId = $result->subject_id;

            if (!isset($subjects[$subjectId])) {
                $subjects[$subjectId] = [
                    'code' => $result->subject->subject_code ?? '-',
                    'name' => $result->subject->subject_name ?? '-',
                    'total_marks' => 0,
                    'student_count' => 0,
                ];
            }

            $subjects[$subjectId]['total_marks'] += $result->marks;
            $subjects[$subjectId]['student_count'] += 1;
        }

        $rows = [];

        foreach ($subjects as $subject) {
            $avg = $subject['student_count'] > 0
                ? $subject['total_marks'] / $subject['student_count']
                : 0;

            $avgMark = number_format($avg, 2);

            if ($avg >= 90) {
                $status = 'Excellent';
            } elseif ($avg >= 80) {
                $status = 'Very Good';
            } elseif ($avg >= 70) {
                $status = 'Good';
            } elseif ($avg >= 60) {
                $status = 'Fair';
            } else {
                $status = 'Poor';
            }

            $rows[] = [
                'code' => $subject['code'],
                'name' => $subject['name'],
                'average_mark' => $avgMark,
                'status' => $status,
            ];
        }

        return $rows;
    }


    public function averageSubject()
    {
        $rows = $this->calculateSubjectAverages();

        $columns = [
            'code' => 'Subject Code',
            'name' => 'Subject Name',
            'average_mark' => 'Average Mark',
            'status' => 'Status',
        ];

        $columnAlignments = [
            'code' => 'left',
            'name' => 'left',
            'average_mark' => 'center',
            'status' => 'left',
        ];

        return view('modules.reportModule.averageSubject', [
            'columns' => $columns,
            'rows' => $rows,
            'title' => 'Average Mark by Subject',
            'showAddButton' => false,
            'showExportButton' => true,
            'columnAlignments' => $columnAlignments,
            'hasAction' => false,
            'exportActions' => [
                [
                    'route' => 'reports.exportSbjAvgExcel',
                    'icon' => 'bx bxs-file-export',
                    'exportLabel' => 'Export to Excel',
                ],
                [
                    'route' => 'reports.exportSbjAvgPdf',
                    'icon' => 'bx bxs-file-pdf',
                    'exportLabel' => 'Export to PDF',
                ],
            ],
        ]);
    }

    public function exportSubjectAvgExcel()
    {
        $rows = $this->calculateSubjectAverages();

        $csvData = [
            ['Subject Code', 'Subject Name', 'Average Mark', 'Status']
        ];

        foreach ($rows as $subject) {
            $csvData[] = [
                $subject['code'],
                $subject['name'],
                $subject['average_mark'],
                $subject['status'],
            ];
        }

        $filename = 'subject_average_mark.csv';
        $handle = fopen('php://temp', 'r+');

        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return Response::make($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportSubjectAvgPDF()
    {
        $rows = $this->calculateSubjectAverages();

        $pdf = Pdf::loadView('modules.reportModule.pdfAverageSubject', [
            'title' => 'Average Mark by Subject',
            'rows' => $rows,
        ]);

        return $pdf->download('subject_average_mark.pdf');
    }
}
