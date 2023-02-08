<?php

namespace App\Model\Ejournal\Dicts;

use Illuminate\Database\Eloquent\Model;


class Branch extends Model
{
    public $timestamps = false;
    protected $table = 'dict_branches';
    protected $fillable = ['body', 'prefix'];
    protected $branch = ['id' => 0, 'body' => ''];

    public static function getTableName()
    {
        return 'dict_branches';
    }

    public function substations()
    {
        return $this->hasMany(Substation::class);
    }

    public static function dataFromLoginPrefix()
    {
//      $user = \Illuminate\Support\Facades\Auth::user();
//      $companyName = $user->ldap->getFirstAttribute('company');
//      $userlogin   = $user->name;
        $userlogin = 'kl_DemoUser';
        $branch = Branch::where('prefix', 'like', '%' . substr($userlogin, 0, 3) . '%')->get()->first();
        return $branch;
    }

}
