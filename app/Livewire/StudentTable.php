<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Student;
use App\Models\Course;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class StudentTable extends Component
{
    use WithPagination {
        WithPagination::resetPage as defaultResetPage;
    }
    public $columns = [];
    public $rows = [];
    public $hasAction = true;
    public $actions = [];
    public $course_id = '';

    public $columnAlignments = [];
    public $filterOptions = [];
    protected $listeners = ['filter-changed' => 'onFilterChanged'];
    public $filters = [];

    protected $paginationTheme = 'bootstrap';

    public function getPageName()
    {
        return 'students_page'; // ini akan jadi query string: ?students_page=2
    }

    public function resetPage($pageName = 'page')
    {
        $this->defaultResetPage('students_page');
    }

    #[On('filter-changed')]
    public function onFilterChanged($key, $value)
    {
        $this->filters[$key] = $value;
    }
    public function mount()
    {
        $this->filterOptions = [
            'Course' => Course::select('id', 'course_name', 'course_code')->get()->toArray()
        ];
        $this->columns = [
            'name' => 'Name',
            'ic_no' => 'IC No',
            'phone_no' => 'Phone No',
            'matric_no' => 'Matric No',
            'email' => 'Email',
        ];

        $this->columnAlignments = [
            'name' => 'left',
            'ic_no' => 'left',
            'phone_no' => 'left',
            'matric_no' => 'left',
            'email' => 'left',
        ];

        $this->actions = [
            [
                'label' => 'View',
                'icon' => 'bx bx-show-alt',
                'route' => 'students.view',
                'paramKey' => 'student',
                'target' => '#viewStudentModal',
                'modal' => true,
                'jsTrigger' => 'view-student-btn',
            ],
            [
                'label' => 'Edit',
                'icon' => 'bx bx-edit',
                'route' => 'students.view',
                'paramKey' => 'student',
                'modal' => true,
                'target' => '#editStudentModal',
                'jsTrigger' => 'edit-student-btn',
            ],
            [
                'label' => 'Delete',
                'icon' => 'bx bx-trash',
                'route' => 'students.delete',
                'paramKey' => 'student',
                'modal' => false,
                'jsTrigger' => 'delete-student-btn',
            ],
        ];
    }
    public function render()
    {
        $students = Student::whereNull('deleted_at')
            ->when(isset($this->filters['Course']) && $this->filters['Course'], function ($query) {
                $query->where('course_id', $this->filters['Course']);
            })
            ->paginate(5, ['*'], 'students_page')->withQueryString();

        $this->rows = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->name,
                'matric_no' => $student->matric_no ?? '-',
                'phone_no' => $student->phone_no ?? '-',
                'ic_no' => $student->ic_no,
                'email' => $student->email,
            ];
        })->toArray();

        return view('livewire.student-table', ['students' => $students,]);
    }
}
