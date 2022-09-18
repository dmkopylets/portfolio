<?php

namespace App\Models\eNaryad\Dicts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Works_Spec extends Model
{
    use HasFactory;
    protected $table = 'dict_works_specs';
    public static function getTableName()
    {
        return 'dict_works_spec';
    }
    
    public static function worksSpecCollect()  
        {
          return Works_Spec::select('id','body')->orderBy('id')->get();
        }


}
