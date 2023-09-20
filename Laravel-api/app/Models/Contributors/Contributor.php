<?php

namespace App\Models\Contributors;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contributor extends Model
{
    protected $table = 'contributors';
    protected $primaryKey = 'id';
    protected $appends = ['collection'];
    protected $fillable = [
        'id',
        'collection_id',
        'user_name',
        'amount'
    ];
    protected $visible = [
        'id',
        'collection_id',
        'user_name',
        'amount'
    ];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function getDetails(int $contributorId): mixed
    {
        return Contributor::select(
            'id',
            'collection_id',
            'user_name',
            'amount',)
            ->where('id', $contributorId)->first();
    }

    public function getList(
        ? string $collectionId = '',
        ? string $userName = '',
        ? string $amount = ''): mixed
    {
        return self::
        where('collection_id', 'like', '%' . $collectionId . '%')
        ->where('user_name', 'like', '%' . $userName . '%')
        ->where('amount', 'like', '%' . $amount . '%')
        ->get();
    }
}
