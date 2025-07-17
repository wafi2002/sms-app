<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;

    protected $fillable = ['exam_code', 'exam_name', 'exam_location', 'duration'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
