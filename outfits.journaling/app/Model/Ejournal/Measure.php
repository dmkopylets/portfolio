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
}



