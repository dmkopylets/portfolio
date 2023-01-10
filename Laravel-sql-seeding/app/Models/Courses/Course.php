<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;

    protected $table='courses';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
        'description'
    ];
    protected $visible = [
        'id',
        'name',
        'description'
    ];

    public function students():  BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }
}
