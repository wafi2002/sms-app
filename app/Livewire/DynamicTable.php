<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Course;
use Livewire\Attributes\On;

class DynamicTable extends Component
{

    public $columns = [];
    public $rows = [];
    public $hasAction = true;
    public $actions = [];
    public $course_id = '';
    public $filterOptions = [];
    protected $listeners = ['filter-changed' => 'onFilterChanged'];
    public $filters = [];

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
            'matric_no' => 'Matric No',
            'email' => 'Email',
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
        $students = User::where('role', 'student')
            ->whereNull('deleted_at')
            ->when(isset($this->filters['Course']) && $this->filters['Course'], function ($query) {
                $query->whereHas('student', function ($q) {
                    $q->where('course_id', $this->filters['Course']);
                });
            })
            ->with('student')
            ->get();

        $this->rows = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->name,
                'matric_no' => $student->student->matric_no ?? '-',
                'ic_no' => $student->ic_no,
                'email' => $student->email,
            ];
        })->toArray();

        return view('livewire.dynamic-table');
    }
}
