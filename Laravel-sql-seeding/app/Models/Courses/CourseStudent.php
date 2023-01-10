<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseStudent extends Model
{
    use HasFactory;

    protected $table='course_student';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'course_id',
        'student_id'
    ];
    protected $visible = [
        'id',
        'course_id',
        'student_id'
    ];
}
