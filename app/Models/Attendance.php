<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $table = 'attendances';
    protected $fillable = ['price_id','day_id','course_time','date', 'teacher_id', 'activity', 'text_book', 'excercise_book'];
}
