<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Lecturer;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Exam;
use App\Models\MyClass;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class ExamController extends Controller
{

    public function schedule()
    {
        $class = MyClass::select('id', 'class_code', 'class_name')->get();
        $subject = Subject::select('id', 'subject_code', 'subject_name')->get();
        $lecturer = lecturer::select('id', 'lecturer_no', 'name')->get();

        $events = Schedule::with(['lecturer', 'subject', 'class'])
            ->select('id', 'lecturer_id', 'subject_id', 'class_id', 'title', 'description', 'start_date', 'start_time', 'end_date', 'end_time')
            ->get();

        $formattedEvents = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description
                    . ' | ' . ($event->lecturer->name ?? '-')
                    . ' | ' . ($event->subject->subject_name ?? '-')
                    . ' | ' . ($event->class->class_name ?? '-'),
                'start' => $event->start_date . 'T' . $event->start_time,
                'end' => $event->end_date . 'T' . $event->end_time,
            ];
        });

        return view('modules.examModule.schedule', compact('subject', 'lecturer', 'class', 'formattedEvents'));
    }

    public function addSchedule(Request $request)
    {
        try {
            $validated = $request->validate([
                'schedule_title' => 'required|string|max:128',
                'schedule_description' => 'required|string|max:1000',
                'start_date' => 'required|date|before_or_equal:end_date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'lecturer_id' => 'required|exists:lecturers,id',
                'class_id' => 'required|exists:classes,id',
                'subject_id' => 'required|exists:subjects,id',
            ]);

            $startDateTime = Carbon::parse($validated['start_date']);
            $endDateTime = Carbon::parse($validated['end_date']);

            Schedule::create([
                'title' => $validated['schedule_title'],
                'description' => $validated['schedule_description'],
                'start_date' => $startDateTime->toDateString(),
                'start_time' => $startDateTime->toTimeString(),
                'end_date' => $endDateTime->toDateString(),
                'end_time' => $endDateTime->toTimeString(),
                'lecturer_id' => $validated['lecturer_id'],
                'class_id' => $validated['class_id'],
                'subject_id' => $validated['subject_id'],
            ]);

            return redirect()->back()->with('success', 'Schedule added successfully!');
        } catch (Exception $e) {
            Log::error('Error add schedule: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add schedule.');
        }
    }
    public function marks()
    {
        $student = Student::with('course')->select('id', 'course_id', 'name', 'matric_no')->get();
        $course = Course::select('id', 'course_name', 'course_code')->get();
        $subject = Subject::select('id', 'subject_code', 'subject_name')->get();
        $lecturer = Lecturer::select('id', 'name', 'lecturer_no')->get();
        $exam = Exam::select('id', 'exam_code', 'exam_name')->get();

        return view('modules.examModule.mark', compact('course', 'subject', 'lecturer', 'student', 'exam'));
    }
    public function updateMark(Request $request, $student)
    {
        $request->validate([
            'marks' => 'required|numeric|min:0|max:100',
        ]);

        $result = Result::where('id', $student)->firstOrFail();
        $result->marks = $request->marks;

        if ($result->marks >= 85) {
            $result->grade = 'A';
        } elseif ($result->marks >= 80) {
            $result->grade = 'A-';
        } elseif ($result->marks >= 75) {
            $result->grade = 'B+';
        } elseif ($result->marks >= 70) {
            $result->grade = 'B';
        } elseif ($result->marks >= 65) {
            $result->grade = 'B-';
        } elseif ($result->marks >= 60) {
            $result->grade = 'C+';
        } elseif ($result->marks >= 55) {
            $result->grade = 'C';
        } elseif ($result->marks >= 50) {
            $result->grade = 'D';
        } else {
            $result->grade = 'F';
        }

        $result->save();

        return response()->json(['success' => 'Mark updated successfully']);
    }

    public function storeMark(Request $request)
    {
        try {
            $validated = $request->validate([
                'student_id' => 'required|exists:students,id',
                'subject_id' => 'required|exists:subjects,id',
                'exam_id' => 'required|exists:exams,id',
                'lecturer_id' => 'required|exists:lecturers,id',
                'marks' => 'required|integer|min:0|max:100',
                'grade' => 'required|string|max:2',
            ]);

            Result::create([
                'student_id' => $validated['student_id'],
                'subject_id' => $validated['subject_id'],
                'exam_id' => $validated['exam_id'],
                'lecturer_id' => $validated['lecturer_id'],
                'marks' => $validated['marks'],
                'grade' => $validated['grade'],
            ]);

            return redirect()->back()->with('success', 'Mark added successfully!');
        } catch (Exception $e) {
            Log::error('Error add mark: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add mark.');
        }
    }
}
