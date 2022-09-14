<?php

namespace App\Models\eNaryad\Dicts;

use Illuminate\Database\Eloquent\Model;


class Branch extends Model
{
    // use HasFactory;
    public $timestamps = false;
    protected $table = 'dict_branches';
    protected $fillable = ['body', 'prefix'];
    protected $branch = ['id'=>0,'body'=>''];

  public static function getTableName(): string
    {
        return 'dict_branches';
    }

    public function substations()
    {
      return $this->hasMany(Substation::class);
    }

    public static function dataFromLoginPrefix()
    {
      //*************************************************************************************************************
      // це буде потрібно при активні auth-ентифікації через AD
      // $user        = \Illuminate\Support\Facades\Auth::user();
      // $companyName = $user->ldap->getFirstAttribute('company');
      // $userlogin   = $user->name;
      //*************************************************************************************************************
      // для демо-режиму так:
      $userlogin = 'kl_demo_user';
      //*************************************************************************************************************
      // if ($companyName =='MAINЕНЕРГО') {   // якщо юзер з "головної бази"
      //        $branch = Branch::whereId(0)->get()->first();
      //       }
      // else {
      //        $branch = Branch::where('prefix','like','%'.substr($userlogin,0,3).'%')->get()->first();
      //      }

        return Branch::where('prefix','like','%'.substr($userlogin,0,3).'%')->get()->first();
    }

}
