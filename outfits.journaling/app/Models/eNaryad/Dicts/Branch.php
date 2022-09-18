<?php

namespace App\Models\eNaryad\Dicts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Branch extends Model
{
    // use HasFactory;
    public $timestamps = false;
    protected $table = 'dict_branches';
    protected $fillable = ['body', 'prefix'];
    protected $branch = ['id'=>0,'body'=>''];

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
      $user        = \Illuminate\Support\Facades\Auth::user();
//      $companyName = $user->ldap->getFirstAttribute('company');

//      $userlogin   = $user->name;
        $userlogin   = 'kl_dit01';

// !!!!!
    $companyName ='АТ ANYОБЛЕНЕРГО';
      if ($companyName =='АТ ANYОБЛЕНЕРГО') {
             $branch = Branch::whereId(0)->get()->first();
             /*  элегантный способ превратить это:
                 $users = User::where('approved', 1)->get();
                 В это:
                 $users = User::whereApproved(1)->get();
             */
            }
      else {
             $branch = Branch::where('prefix','like','%'.substr($userlogin,0,3).'%')->get()->first();
           }
      return $branch;
    }

}
