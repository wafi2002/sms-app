<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;
    protected $fillable = ['course_id','lecturer_id','class_id','subject_code','subject_name','credit_hours', 'prereq_sub_id'];

    public function course()
    {
        return $this->belongsTo(Course::class,'course_id');
    }

    public function lecturer()
    {
        return $this->belongsToMany(Lecturer::class, 'lecture_subject', 'subject_id', 'lecturer_id');
    }

    public function exam()
    {
        return $this->hasMany(Exam::class);
    }

    public function class()
    {
        return $this->belongsTo(MyClass::class, 'class_id');
    }
    public function prerequisite()
    {
        return $this->belongsTo(Subject::class, 'prereq_sub_id');
    }

    public function dependentSubjects()
    {
        return $this->hasMany(Subject::class, 'prereq  _sub_id');
    }
}
