<?php

namespace App\Model\Ejournal;

use App\Model\Ejournal\Dicts\Substation;
use App\Model\Ejournal\Dicts\Warden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
	protected $table='orders';

    public function substation()
    {
        return $this->belongsTo('Substation','substation_id','id');
    }




    protected $appends = ['warden','substation'];
    /** підтягує ім'я керівника як колонку warden **/
    public function getwardenAttribute()
    {
       return Warden::find($this->warden_id)->body;
    }
    public function getsubstationAttribute()
    {
       return Substation::find($this->substation_id)->body;
    }


}
