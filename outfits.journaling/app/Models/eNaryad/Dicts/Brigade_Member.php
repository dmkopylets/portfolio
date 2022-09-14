<?php

namespace App\Models\eNaryad\Dicts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brigade_Member extends Model
{
    use HasFactory;
    protected $table = 'dict_brigade_members';
    public function branch()
    {
        return $this->belongsTo('App\Models\eNaryad\Dicts\Branch','branch_id','id');
    }
     public static function getTableName()
    {
        return 'dict_brigade_members';
    }
}
