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
                'password' => 'required|string|min:8',
                'address' => 'nullable|string|max:500',
                'gender' => 'required|in:0,1',
                'course_id' => 'required|exists:courses,id',
            ]);

            $validated['password'] = Hash::make($validated['password']);

            DB::transaction(function () use ($validated) {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'ic_no' => $validated['ic_no'],
                    'password' => $validated['password'],
                    'address' => $validated['address'],
                    'gender' => $validated['gender'],
                    'role' => 'student'
                ]);

                Student::create([
                    'user_id' => $user->id,
                    'matric_no' => $validated['matric_no'],
                    'course_id' => $validated['course_id'],
                ]);
            });

            return redirect()->back()->with('success', 'New Student Successfully Added.');
        } catch (Exception $e) {
            Log::error('Error adding student: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add student.');
        }
    }

    public function viewStudent($studentId)
    {
        $student = Student::with(['user:id,name,email,ic_no,gender,password,address,created_at', 'course:id,course_name,course_code'])
            ->where('user_id', $studentId)
            ->select('id', 'user_id', 'course_id', 'matric_no')
            ->first();

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        return response()->json([
            'id' => $student->id,
            'name' => $student->user->name ?? '-',
            'ic_no' => $student->user->ic_no ?? '-',
            'matric_no' => $student->matric_no ?? '-',
            'email' => $student->user->email ?? '-',
            'password' => $student->user->password ?? '-',
            'gender' => $student->user->gender ?? '-',
            'address' => $student->user->address ?? '-',
            'course_id' => $student->course->id ?? '-',
            'course_name' => $student->course->course_name ?? '-',
            'course_code' => $student->course->course_code ?? '-',
            'created_at' => $student->user->created_at ?? '-',
        ]);
    }

    public function updateStudent(Request $request, $studentId)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'ic_no' => 'required|string|max:20',
                'matric_no' => 'required|string|max:20|unique:students,matric_no,' . $studentId,
                'email' => 'required|email|unique:users,email,' . $studentId . ',id',
                'password' => 'required|string|min:8',
                'address' => 'nullable|string|max:500',
                'gender' => 'required|in:0,1',
                'course_id' => 'required|exists:courses,id',
            ]);

            $validated['password'] = Hash::make($validated['password']);

            DB::transaction(function () use ($validated, $studentId) {
                $student = Student::findOrFail($studentId);
                $user = $student->user;

                // Update user
                $user->name = $validated['name'];
                $user->email = $validated['email'];
                $user->ic_no = $validated['ic_no'];
                $user->address = $validated['address'];
                $user->gender = $validated['gender'];

                if (!empty($validated['password'])) {
                    $user->password = Hash::make($validated['password']);
                }

                $user->save();

                // Update student
                $student->matric_no = $validated['matric_no'];
                $student->course_id = $validated['course_id'];
                $student->save();
            });

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
