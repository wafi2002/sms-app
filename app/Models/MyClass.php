<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MyClass extends Model
{
    use SoftDeletes;

    protected $table = 'classes';
    protected $fillable = ['class_code', 'class_name', 'class_location', 'class_department', 'class_type'];

}
