<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;


use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Auth;
use LdapRecord\Laravel\Auth\BindFailureListener;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     *
     */
     
     protected $policies = [
         'App\Model' => 'App\Policies\ModelPolicy',
       ];

    
     /* Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
       $this->registerPolicies();

//       BindFailureListener::setErrorHandler(function ($message, $code = null) {
//        if ($code == '773') {
//           The users password has expired. Redirect them.
//            abort(redirect('/password-reset'));
//        }
//       });

      Fortify::authenticateUsing(function ($request) {
        $validated = Auth::validate([
            'samaccountname' => $request->username,
            'password' => $request->password,
        ]);

        return $validated ? Auth::getLastAttempted() : null;
    }); 

   
    }
}
