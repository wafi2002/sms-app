<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = ['student_id', 'exam_id','subject_id', 'marks', 'grade', 'lecturer_id'];

    public function student() {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function subject() {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function lecturer() {
        return $this->belongsTo(Lecturer::class, 'lecturer_id');
    }
}
