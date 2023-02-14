<?php

declare(strict_types=1);

namespace App\Model\Ejournal\Dicts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypicalTask extends Model
{
    use HasFactory;

    protected $table = 'dict_typicaltasks';

    public static function getTableName()
    {
        return 'dict_typicaltasks';
    }

    public function works_spec()
    {
        return $this->belongsTo('App\Models\Ejournal\Dicts\Works_Spec', 'works_specs_id', 'id');
    }

    public static function getMyColumnsHead()
    {
        return array('виконати:');
    }

    public static function getMyColumnsData1()
    {
        return array('body');
    }

    public static function getListArray(int $worksSpecsId): array
    {
        return TypicalTask::
        select('id', 'body')
            ->where('works_specs_id', $worksSpecsId)
            ->orderBy('body')->get()->toArray();
    }
}
