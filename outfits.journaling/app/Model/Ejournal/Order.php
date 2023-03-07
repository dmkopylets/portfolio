<?php

namespace App\Model\Ejournal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
	protected $table='orders';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'branch_id',
        'unit_id',
        'warden_id',
        'adjuster_id',
        'brigade_m',
        'brigade_e',
        'substation_id',
        'works_spec_id',
        'line_id',
        'objects',
        'tasks',
        'w_begin',
        'w_end',
        'sep_instrs',
        'order_date',
        'order_creator',
        'order_longto',
        'order_longer',
        'under_voltage',
     ];

    public function preparations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Preparation::class,'order_id','id');
    }

    public function measures(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Measure::class,'order_id','id');
    }
}
