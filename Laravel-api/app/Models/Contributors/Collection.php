<?php

namespace App\Models\Contributors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Collection extends Model
{
    use HasFactory;


    protected $table = 'collections';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'title',
        'description',
        'target_amount',
        'link'
    ];
    protected $visible = [
        'id',
        'title',
        'description',
        'target_amount',
        'link'
    ];
    private array $list = [];


    public function contributors(): HasMany
    {
        return $this->hasMany(Contributor::class);
    }

    public function getDetails(int $collectionId): array
    {
        $collectionData = self::select(
            'id',
            'title',
            'description',
            'target_amount',
            'link')
            ->where('id', $collectionId)
            ->first()
            ->toArray();
        return $collectionData;
    }

    public function getList(
        ? string $title = '',
        ? string $description = '',
        ? string $target_amount = '',
        ? string $link = '',
        ? string $completed = ''): array
    {
        $this->list = self::
              where('title', 'like', '%' . $title . '%')
            ->where('description', 'like', '%' . $description . '%')
            ->where('target_amount', 'like', '%' . $target_amount . '%')
            ->where('link', 'like', '%' . $link . '%')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();

        foreach ($this->list as $key =>$row) {
            $contributors = self::find($row['id'])->contributors;
            $this->list[$key]['completed'] = $row['target_amount'] > array_sum($contributors->pluck('amount')->toArray()) ? 0 : 1;

            $filter = trim($completed);

            if( in_array($filter, array('0', '1')) ){
                $this->unsetRow($key, (int) $filter);
            }
        }

        return $this->list;
    }

    private function unsetRow(int $key, int $filter)
    {
        if ($this->list[$key]['completed'] !== $filter) { unset($this->list[$key]); }
    }
}
