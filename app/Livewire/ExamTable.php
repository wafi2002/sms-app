<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Result;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class ExamTable extends Component
{
    use WithPagination {
        WithPagination::resetPage as defaultResetPage;
    }

    public $columns = [];
    public $rows = [];
    public $hasAction = true;
    public $actions = [];
    public $course_id = '';
    public $courseOptions = [];
    public $subjectOptions = [];

    public $columnAlignments = [];
    protected $listeners = ['filter-changed' => 'onFilterChanged', 'refreshExamMarkTable' => '$refresh' ];
    public $filters = [];

    protected $paginationTheme = 'bootstrap';

    public function getPageName()
    {
        return 'exam_page'; // ini akan jadi query string: ?students_page=2
    }

    public function resetPage($pageName = 'page')
    {
        $this->defaultResetPage('exam_page');
    }


    #[On('filter-changed')]
    public function onFilterChanged($key, $value)
    {
        $this->filters[$key] = $value;
    }
    public function mount()
    {
        $this->courseOptions = [
            'Courses' => Course::select('id', 'course_name', 'course_code')->get()->toArray()
        ];
        $this->subjectOptions = [
            'Subjects' => Subject::select('id', 'subject_code', 'subject_name')->get()->toArray()
        ];
        $this->columns = [
            'matric_no' => 'Matric No',
            'name' => 'Name',
            'course' => 'Course',
            'subject' => 'Subject',
            'mark' => 'Marks',
            'grade' => 'Grade'
        ];

        $this->columnAlignments = [
            'matric_no' => 'left',
            'name' => 'left',
            'course' => 'center',
            'subject' => 'center',
            'mark' => 'center',
            'grade' => 'center',
        ];
    }
    public function render()
    {
        $results = Result::with(['student', 'subject.course'])
            ->whereNull('deleted_at')
            ->when(isset($this->filters['Courses']) && $this->filters['Courses'], function ($query) {
                $query->whereHas('student', function ($q) {
                    $q->where('course_id', $this->filters['Courses']);
                });
            })
            ->when(isset($this->filters['Subjects']) && $this->filters['Subjects'], function ($query) {
                $query->where('subject_id', $this->filters['Subjects']);
            })
            ->paginate(5, ['*'], 'exam_page')->withQueryString();

        $this->rows = $results->map(function ($result) {
            return [
                'id' => $result->id ?? '-',
                'matric_no' => $result->student->matric_no ?? '-',
                'name' => $result->student->name ?? '-',
                'course' => $result->subject->course->course_code ?? '-',
                'subject' => $result->subject->subject_name ?? '-',
                'mark' => $result->marks ?? '-',
                'grade' => $result->grade ?? '-',
            ];
        })->toArray();

        return view('livewire.exam-table' ,['exam' => $results,]);
    }
}
