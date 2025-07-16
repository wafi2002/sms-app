<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lecturer extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','lecturer_no','department','expertise','ic_no', 'gender', 'phone_no', 'email', 'address'];
}
