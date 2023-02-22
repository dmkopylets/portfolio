<?php

namespace App\Model\Ejournal\Dicts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Substation extends Model
{
    use HasFactory;
    protected $table = 'dict_substations';


    public function branch()
    {
        return $this->belongsTo('App\Model\Ejournal\Dicts\Branch','branch_id','id');
    }


    public function stationType()
    {
        return $this->belongsTo('App\Models\Ejournal\Dicts\StationType','type_id','id');
    }

    public static function getTypeId($substation_id)
    {
        return Substation::find($substation_id)->type_id;
    }

     public static function getTableName()
    {
        return 'dict_substations';
    }

    public static function getListArray(int $branchId, int $substationTypeId): array
    {
        return Substation::
        select('id', 'body', 'type_id')
            ->where('branch_id', $branchId)
            ->where('type_id', $substationTypeId)
            ->orderBy('type_id', 'asc')
            ->orderBy('body', 'asc')
            ->get()
            ->toArray();
    }
}
