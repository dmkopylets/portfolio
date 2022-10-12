<?php

namespace App\Models\Ejournal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ejournal\Dicts\Warden;
use App\Models\Ejournal\Dicts\Substation;

class Naryad extends Model
{
    use HasFactory;
	protected $table='naryads';

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
