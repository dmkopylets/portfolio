<?php

namespace App\Models\RacingData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $table='drivers';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'name',
        'team'
    ];
    protected $visible = [
        'id',
        'name',
        'team'
    ];

    public function flights()
    {
        return $this->hasMany(Flight::class);
    }

    public function get(): mixed
    {
        return Driver::select('id', 'name', 'team')->get();
    }

    public function getAllOrdered(string $ordering): array
    {
        return  Driver::select('id', 'name', 'team')->orderBy('name', $ordering)->get()->toArray();
    }

    public function getDetails(string $driverId): array
    {
        return Flight::select(
            'flights.driverId',
            'drivers.name AS driver',
            'drivers.team',
            'flights.start',
            'flights.finish',
            'flights.duration',
            'flights.possition',
            'flights.top'
        )
            ->leftJoin('drivers', 'drivers.id', 'flights.driverId')
            ->where('driverId', $driverId)
            ->first()->toArray();
    }
}
