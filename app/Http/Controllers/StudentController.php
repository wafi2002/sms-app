<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class StudentController extends Controller
{
    public function index()
    {
        // Fetch course data
        $course = Course::select('id', 'course_name', 'course_code')->get();
        return view('modules.studentModule.index', compact('course'));
    }

    public function addStudent(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'ic_no' => 'required|string|max:20',
                'matric_no' => 'required|string|max:20|unique:students,matric_no',
                'email' => 'required|email|unique:users,email',
                'phone_no' => 'required|string|max:15',
                'address' => 'required|string|max:500',
                'gender' => 'required|in:male,female',
                'course_id' => 'required|exists:courses,id',
            ]);

            $student = Student::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'ic_no' => $validated['ic_no'],
                'phone_no' => $validated['phone_no'],
                'address' => $validated['address'],
                'gender' => $validated['gender'],
                'matric_no' => $validated['matric_no'],
                'course_id' => $validated['course_id'],
            ]);

            return redirect()->back()->with('success', 'New Student Successfully Added.');
        } catch (Exception $e) {
            Log::error('Error adding student: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add student.');
        }
    }

    public function viewStudent($studentId)
    {
        $student = Student::with(['course:id,course_name,course_code'])
            ->where('id', $studentId)
            ->select('id', 'course_id', 'matric_no', 'name', 'ic_no', 'gender', 'phone_no', 'email', 'address')
            ->first();

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        return response()->json([
            'id' => $student->id,
            'name' => $student->name ?? '-',
            'ic_no' => $student->ic_no ?? '-',
            'matric_no' => $student->matric_no ?? '-',
            'phone_no' => $student->phone_no ?? '-',
            'email' => $student->email ?? '-',
            'password' => $student->password ?? '-',
            'gender' => $student->gender ?? '-',
            'address' => $student->address ?? '-',
            'course_id' => $student->course->id ?? '-',
            'course_name' => $student->course->course_name ?? '-',
            'course_code' => $student->course->course_code ?? '-',
            'created_at' => $student->created_at ?? '-',
        ]);
    }

    public function updateStudent(Request $request, $studentId)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'ic_no' => 'required|string|max:20',
                'matric_no' => 'required|string|max:20|unique:students,matric_no,' . $studentId,
                'email' => 'required|email|unique:students,email,' . $studentId,
                'phone_no' => 'required|string|max:15',
                'address' => 'required|string|max:500',
                'gender' => 'required|in:male,female',
                'course_id' => 'required|exists:courses,id',
            ]);

            $student = Student::findOrFail($studentId);

            $student->name = $validated['name'];
            $student->email = $validated['email'];
            $student->ic_no = $validated['ic_no'];
            $student->phone_no = $validated['phone_no'];
            $student->address = $validated['address'];
            $student->gender = $validated['gender'];
            $student->matric_no = $validated['matric_no'];
            $student->course_id = $validated['course_id'];

            $student->save();

            return redirect()->back()->with('success', 'Updated Successfully.');
        } catch (Exception $e) {
            Log::error('Error updating student: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update student.');
        }
    }

    public function deleteStudent($studentId)
    {
        try {
            $student = Student::findOrFail($studentId);
            $user = $student->user;

            $student->delete();

            if ($user) {
                $user->delete();
            }
            return response()->json(['success' => 'Student deleted successfully.']);
        } catch (Exception $e) {
            Log::error('Error deleting student: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete student.'], 500);
        }
    }
}
