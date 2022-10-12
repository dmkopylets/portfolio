<?php

namespace App\Models\Ejournal\Dicts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adjuster extends Model
{
    use HasFactory;
    protected $table = 'dict_adjusters';
    public function branch()
    {
        return $this->belongsTo('App\Models\Ejournal\Dicts\Branch','branch_id','id');
    }

    public static function getTableName()
    {
        return 'dict_adjusters';
    }

}
