<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointHistory extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $table = 'point_histories';
    protected $fillable = ['student_id', 'date', 'total_point', 'type', 'keterangan'];
}
