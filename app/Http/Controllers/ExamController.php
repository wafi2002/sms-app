<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Lecturer;

class ExamController extends Controller
{

    public function marks()
    {
        $course = Course::select('id', 'course_name', 'course_code')->get();
        $subject = Subject::select('id', 'subject_code', 'subject_name')->get();
        $lecturer = Lecturer::select('id', 'name', 'lecturer_no')->get();

        return view('modules.examModule.mark', compact('course', 'subject', 'lecturer'));
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
}
