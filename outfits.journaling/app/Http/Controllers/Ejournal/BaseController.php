<?php

namespace App\Http\Controllers\Ejournal;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Exception;

abstract class BaseController extends Controller
{
       protected $displayName, $userlogin;
       public $branch, $mode;
       public $preparations_rs = array();
       public $measures_rs = array();
       public $naryadRecord = array();

 public function getUserLogin()
 {
//  !!!!!! used on full version
//    $this->userlogin   = Auth::user()->name;    //  -логін
    $this->userlogin  = 'kl_demoUser';
    return $this->userlogin;
 }

 public function getDisplayName()  // ПІБ зареєстрованого юзера
 {
// !!!!!!!  used on full version
//    $this->displayName = Auth::user()->ldap->getFirstAttribute('displayName');
     $this->displayName = 'DemoUser';
    return $this->displayName;
 }

 public function getBranch()
 {
    return \App\Models\Ejournal\Dicts\Branch::dataFromLoginPrefix();
 }

 public function getSubstationsList($branch_id,$substation_type_id)
 {
   return \App\Models\Ejournal\Dicts\Substation::
      select('id','body')
         ->where('branch_id',$branch_id)
         ->where('type_id',$substation_type_id)
         ->orderBy('type_id','asc')
         ->orderBy('body','asc')
      ->get();
 }
}
