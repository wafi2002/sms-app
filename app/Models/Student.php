<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = ['course_id', 'name', 'matric_no', 'ic_no', 'gender', 'phone_no', 'email', 'address'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
