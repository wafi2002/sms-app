<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = ['course_name', 'course_code', 'department'];

    public function student()
    {
        return $this->hasMany(Student::class);
    }
}
