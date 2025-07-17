<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\MyClass;
use App\Models\Subject;
use App\Models\Lecturer;
use Illuminate\Support\Facades\Log;
use Exception;

class CourseController extends Controller
{
    public function index()
    {
        $subject = Subject::select('id', 'subject_code', 'subject_name', 'credit_hours')->get();
        $course = Course::select('id', 'course_name', 'course_code', 'department')->get();
        $class = MyClass::select('id', 'class_code', 'class_name', 'class_location', 'class_department', 'class_type')->get();
        $lecturer = Lecturer::select('id', 'lecturer_no', 'name')->get();

        return view('modules.courseModule.index', compact('subject', 'course', 'class', 'lecturer'));
    }

    public function viewCourse($subjectId)
    {
        $subject = Subject::with(['course', 'prerequisite', 'lecturer', 'class'])
            ->where('id', $subjectId)
            ->select('id', 'course_id', 'class_id', 'lecturer_id', 'subject_code', 'subject_name', 'credit_hours', 'prereq_sub_id')
            ->first();


        if (!$subject) {
            return response()->json(['error' => 'Subject Not Found'], 404);
        }

        return response()->json([
            'id' => $subject->id,
            'subject_code' => $subject->subject_code ?? '-',
            'subject_name' => $subject->subject_name ?? '-',
            'credit_hours' => $subject->credit_hours ?? '-',
            'prereq_sub_id' => $subject->prereq_sub_id ?? '-',
            'prereq_sub_name' => $subject->prerequisite->subject_name ?? '-',
            'prereq_sub_code' => $subject->prerequisite->subject_code ?? '-',
            'lecturer_id' => $subject->lecturer_id ?? '-',
            'lecturers' => $subject->lecturer->map(function ($lecturer) {
                return [
                    'lecturer_no' => $lecturer->lecturer_no,
                    'lecturer_name' => $lecturer->name,
                    'expertise' => $lecturer->expertise,
                ];
            }),
            'course_id' => $subject->course_id ?? '-',
            'course_code' => $subject->course->course_name ?? '-',
            'course_name' => $subject->course->course_name ?? '-',
            'class_id' => $subject->class_id ?? '-',
            'class_code' => $subject->class->class_code ?? '-',
            'class_name' => $subject->class->class_name ?? '-',
            'class_location' => $subject->class->class_location ?? '-',
            'class_department' => $subject->class->class_department ?? '-'
        ]);
    }

    public function addCourse(Request $request)
    {
        try {
            $validated = $request->validate([
                'subject_name' => 'required|string|max:128',
                'subject_code' => 'required|string|max:16',
                'credit_hours' => 'required|integer|min:1|max:10',
                'prereq_sub_id' => 'nullable|exists:subjects,id',
                'class_id' => 'required|exists:classes,id',
                'lecturer_id' => 'required|exists:lecturers,id',
                'course_id' => 'required|exists:courses,id',
            ]);

            Subject::create([
                'subject_name' => $validated['subject_name'],
                'subject_code' => $validated['subject_code'],
                'credit_hours' => $validated['credit_hours'],
                'prereq_sub_id' => $validated['prereq_sub_id'],
                'class_id' => $validated['class_id'],
                'lecturer_id' => $validated['lecturer_id'],
                'course_id' => $validated['course_id']
            ]);

            return redirect()->back()->with('success', 'New Subject Successfully Added.');
        } catch (Exception $e) {
            Log::error('Error adding subject: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add subject.');
        }
    }

    public function updateCourse(Request $request, $subjectId)
    {
        try {
            if ($request->input('null_prereq') == 1) {
                $request->merge(['prereq_sub_id' => null]);
            }

            $validated = $request->validate([
                'subject_name' => 'required|string|max:128',
                'subject_code' => 'required|string|max:16',
                'credit_hours' => 'required|integer|min:1|max:10',
                'prereq_sub_id' => 'nullable|exists:subjects,id',
                'class_id' => 'required|exists:classes,id',
                'lecturer_id' => 'required|exists:lecturers,id',
                'course_id' => 'required|exists:courses,id',
            ]);

            $subject = Subject::findOrFail($subjectId);

            $subject->subject_name = $validated['subject_name'];
            $subject->subject_code = $validated['subject_code'];
            $subject->credit_hours = $validated['credit_hours'];
            $subject->prereq_sub_id = $validated['prereq_sub_id'];
            $subject->course_id = $validated['course_id'];
            $subject->class_id = $validated['class_id'];
            $subject->lecturer_id = $validated['lecturer_id'];

            $subject->save();

            return redirect()->back()->with('success', 'Updated Successfully.');
        } catch (Exception $e) {
            Log::error('Error updating subject: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update subject.');
        }
    }

    public function deleteCourse($subjectId)
    {
        try {
            $subject = Subject::findOrFail($subjectId);

            $subject->delete();

            return response()->json(['success' => 'Subject deleted successfully.']);
        } catch (Exception $e) {
            Log::error('Error deleting subject: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete subject.'], 500);
        }
    }
}
