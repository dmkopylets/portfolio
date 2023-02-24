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

     public static function getTableName()
    {
        return 'dict_substations';
    }
}
