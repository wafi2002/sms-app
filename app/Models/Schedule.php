<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{

    use SoftDeletes;
    protected $fillable = ['lecturer_id','subject_id','class_id','title','description','start_date', 'start_time', 'end_date', 'end_time'];

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function class()
    {
        return $this->belongsTo(MyClass::class, 'class_id');
    }
}
