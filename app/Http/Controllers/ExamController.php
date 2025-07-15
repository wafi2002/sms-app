<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;

class ExamController extends Controller
{
    public function updateMark(Request $request, $student)
    {
        $request->validate([
            'marks' => 'required|numeric|min:0|max:100',
        ]);

        $result = Result::where('student_id', $student)->firstOrFail();
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
