<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $appends = ['course'];
    protected $fillable = [
        'id',
        'first_name',
        'last_name'
    ];
    protected $visible = [
        'id',
        'first_name',
        'last_name'
    ];

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }

    public function getCoursesList(int $studentId): string
    {
        $student = self::find($studentId);
        return implode(', ', $student->courses()->pluck('name')->toArray());
    }

    public function getDetails(int $studentId): array
    {
        $studentData = self::select(
            'id',
            'first_name',
            'last_name')
            ->where('id', $studentId)
            ->first()
            ->toArray();
        $studentData['course'] = $this->getCoursesList($studentId);
        return $studentData;
    }

    public function getList(? string $firstName = '', ? string $lastName = ''): array
    {
        $list = self::
        where('first_name', 'like', '%' . $firstName . '%')
            ->where('last_name', 'like', '%' . $lastName. '%')
            ->get()
            ->toArray();
        foreach ($list as $key => $value) {
            $list[$key]['course'] = $this->getCoursesList($value['id']);
        }
        return $list;
    }
    public function getcourseAttribute()
    {
        return implode(', ', self::find($this->id)->courses()->pluck('name')->toArray());
    }
}
