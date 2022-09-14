<?php

namespace App\Models\eNaryad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measure extends Model
{
    use HasFactory;
    protected $table='measures';
    protected $fillable = [
        'naryad_id', 'licensor','lic_date'
    ];
    public static function get_data($naryadId)
	{	
        return Measure::select('id','licensor','lic_date')->where('naryad_id',$naryadId)->get(); 
	}
          
    public static function get_row($id)
	{	
        return Measure::select('id','licensor','lic_date')->where('id',$id)->get(); 
	}

    public static function get_maxId($naryadId)
	{	
        return Measure::select('id')->where('naryad_id',$naryadId)->get()->max('id'); 
	}
}



