<?php

namespace App\Models\eNaryad\Dicts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypicalTask extends Model
{
    use HasFactory;
protected $table = 'dict_typicaltasks';
    public static function getTableName()
    {
        return 'dict_typicaltasks';
    }
    public function works_spec()
    {
        return $this->belongsTo('App\Models\eNaryad\Dicts\Works_Spec','works_specs_id','id');
    }
    public static function getMyColumnsHead()
    {
        return array('виконати:');
    }
    public static function getMyColumnsData1()
    {
        return array('body');
    }

}
