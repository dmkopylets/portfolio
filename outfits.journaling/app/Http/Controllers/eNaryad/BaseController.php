<?php

namespace App\Http\Controllers\eNaryad;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Exception;

abstract class BaseController extends Controller
{   
    // тут можна завести якусь загальну змінну на весь проект, конструктор ...
       protected $displayName, $userlogin;
       public $branch, $mode;
       public $preparations_rs = array();
       public $measures_rs = array();
       public $naryadRecord = array();
      
      
       // private $order = array();
       
      //  public function __set($key, $value) {
      //    //put validation here
      //    $this->order[$key]    = $value;
      //    }
 
      //  public function __get($var) {
      //       //$key = array_search($var, array_column($this->order, 'id'));

      //       if (!array_key_exists($var,$this->order )) {
      //            throw new Exception("Помилкова змінна $var = ".$this->order['order_creator']);
      //       } 
      //       else {
      //            return $this->order[$var];
      //          }
      //    }




 public function getUserLogin() 
 {
//  !!!!!!!!!!!!!!!
//    $this->userlogin   = Auth::user()->name;    //  -логін                 
    $this->userlogin  = 'kl_dit01';
    return $this->userlogin;
 }

 public function getDisplayName()  // ПІБ зареєстрованого юзера 
 {
// !!!!!!!
//    $this->displayName = Auth::user()->ldap->getFirstAttribute('displayName'); 
     $this->displayName = 'DemoUser';
    return $this->displayName;
 }

 public function getBranch()
 {
    return \App\Models\eNaryad\Dicts\Branch::dataFromLoginPrefix();
 }
 
 public function getSubstationsList($branch_id,$substation_type_id)
 {
   return \App\Models\eNaryad\Dicts\Substation::
      select('id','body')
         ->where('branch_id',$branch_id)
         ->where('type_id',$substation_type_id)
         ->orderBy('type_id','asc')
         ->orderBy('body','asc')
      ->get();
 }
}


// class order { 
//    protected $values = array(); 
//    public function __get( $key ) { 
//       return $this->values[ $key ]; 
//    } 
//    public function __set( $key, $value ) { 
//       $this->values[ $key ] = $value; 
//    } 
// } 