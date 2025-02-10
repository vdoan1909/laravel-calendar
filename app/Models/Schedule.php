<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'lecturer_id',
        'start',
        'day_of_week',
        'start_time',
        'end_time',
        'description',
    ];

    public function lecturer()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'schedule_id', 'student_id');
    }
}
