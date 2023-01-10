<?php

namespace App\Models\Courses;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
    ];
    protected $visible = [
        'id',
        'name',
    ];

    public function getDetails(int $groupId): mixed
    {
        return Group::select(
            'id',
            'name',)
            ->where('id', $groupId)->first();
    }

    public function getList(): mixed
    {
        return self::select('id', 'name')->get();
    }
}
