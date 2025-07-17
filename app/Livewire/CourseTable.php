<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Course;
use App\Models\Subject;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class CourseTable extends Component
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

    public $columnAlignments = [];
    protected $listeners = ['filter-changed' => 'onFilterChanged', 'refreshExamMarkTable' => '$refresh'];
    public $filters = [];

    protected $paginationTheme = 'bootstrap';

    public function getPageName()
    {
        return 'course_page'; // ini akan jadi query string: ?students_page=2
    }

    public function resetPage($pageName = 'page')
    {
        $this->defaultResetPage('course_page');
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

        $this->columns = [
            'subject_code' => 'Subject Code',
            'subject_name' => 'Subject Name',
            'credit_hours' => 'Credit Hours',
            'prereq_sub' => 'Pre-Requisite',
        ];

        $this->columnAlignments = [
            'subject_code' => 'left',
            'subject_name' => 'left',
            'credit_hours' => 'center',
            'prereq_sub' => 'center',
        ];

        $this->actions = [
            [
                'label' => 'View',
                'icon' => 'bx bx-show-alt',
                'route' => 'course.view',
                'paramKey' => 'subject',
                'target' => '#viewCourseModal',
                'modal' => true,
                'jsTrigger' => 'view-course-btn',
            ],
            [
                'label' => 'Edit',
                'icon' => 'bx bx-edit',
                'route' => 'course.view',
                'paramKey' => 'subject',
                'modal' => true,
                'target' => '#editCourseModal',
                'jsTrigger' => 'edit-course-btn',
            ],
            [
                'label' => 'Delete',
                'icon' => 'bx bx-trash',
                'route' => 'course.delete',
                'paramKey' => 'subject',
                'modal' => false,
                'jsTrigger' => 'delete-course-btn',
            ],
        ];
    }
    public function render()
    {
        $subject = Subject::with(['course', 'prerequisite'])
            ->whereNull('deleted_at')
            ->when(isset($this->filters['Courses']) && $this->filters['Courses'], function ($query) {
                $query->where('course_id', $this->filters['Courses']);
            })
            ->paginate(5, ['*'], 'course_page')->withQueryString();

        $this->rows = $subject->map(function ($subject) {
            return [
                'id' => $subject->id ?? '-',
                'subject_code' => $subject->subject_code ?? '-',
                'subject_name' => $subject->subject_name ?? '-',
                'credit_hours' => $subject->credit_hours ?? '-',
                'prereq_sub' => $subject->prerequisite->subject_name ?? '-',
            ];
        })->toArray();

        return view('livewire.course-table', ['subject' => $subject,]);
    }
}
