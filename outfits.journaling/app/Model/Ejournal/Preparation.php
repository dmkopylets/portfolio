<?php

declare(strict_types=1);

namespace App\Model\Ejournal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preparation extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $fillable = ['id', 'target_obj', 'body'];
    protected $table = 'preparations';

    public static function get_data($naryadId)
    {
        return Preparation::select('id', 'target_obj', 'body')->where('order_id', $naryadId)->get();
    }

    public static function get_row($id)
    {
        return Preparation::select('id', 'target_obj', 'body')->where('id', $id)->get();
    }

    public static function get_maxId($naryadId)
    {
        return Preparation::select('id')->where('order_id', $naryadId)->get()->max('id');
    }
}
