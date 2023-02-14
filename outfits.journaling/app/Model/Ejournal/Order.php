<?php

namespace App\Model\Ejournal;

use App\Model\Ejournal\Dicts\Substation;
use App\Model\Ejournal\Dicts\Warden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function PHPUnit\Framework\isNull;

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
    public function getwardenAttribute(): string
    {
        if (isNull($this->warden_id)) {
            $result = '';
        } else {
            $result = Warden::find($this->warden_id)->body;
        }
       return $result;
    }
    public function getsubstationAttribute()
    {
       return Substation::find($this->substation_id)->body;
    }
}
