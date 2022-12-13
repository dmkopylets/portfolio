<?php

namespace App\Models\RacingData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    protected $table = 'flights';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'driverId';

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    protected $fillable = [
        'driverId',
        'start',
        'finish',
        'duration',
        'possition',
        'top'
    ];

    protected $visible = [
        'driverId',
        'driver',
        'team',
        'start',
        'finish',
        'duration',
        'possition',
        'top'
    ];
}
