<?php

namespace App\Models\eNaryad\Dicts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Substation extends Model
{
    use HasFactory;
    protected $table = 'dict_substations';
   

    public function branch()
    {
        return $this->belongsTo('App\Models\eNaryad\Dicts\Branch','branch_id','id');
    }
   

    public function station_type()
    {
        return $this->belongsTo('App\Models\eNaryad\Dicts\Station_Type','type_id','id');
    }
   
    public static function type_id($substation_id)
    {
        return Substation::find($substation_id)->type_id;
    }

     public static function getTableName()
    {
        return 'dict_substations';
    }
}
