<?php

namespace App\Model\Ejournal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measure extends Model
{
    use HasFactory;
    protected $table='measures';
    protected $fillable = [
        'order_id', 'licensor','lic_date'
    ];
    public static function getData($orderId)
	{
        return Measure::select('id','licensor','lic_date')->where('order_id',$orderId)->get();
	}

    public static function getMaxId($orderId)
	{
        return Measure::select('id')->where('order_id',$orderId)->get()->max('id');
	}
}



