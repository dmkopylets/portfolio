<?php

namespace App\Model\Ejournal\Dicts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    use HasFactory;

    protected $table = 'dict_lines';

    public static function getTableName()
    {
        return 'dict_lines';
    }

    public function substation()
    {
        return $this->belongsTo('Substation','substation_id','id');
    }
    public static function getListArray(int $branchId, int $substationId): array
    {
        return Line::
        select('line_id')
            ->where('branch_id', $branchId)
            ->where('substation_id', $substationId)
            ->orderBy('line_id', 'asc')
            ->get()
            ->toArray();
    }

}
