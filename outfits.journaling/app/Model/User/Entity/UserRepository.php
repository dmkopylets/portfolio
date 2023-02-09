<?php

declare(strict_types=1);

namespace App\Model\User\Entity;

use App\Model\Ejournal\Dicts\Branch;

class UserRepository
{
    private UserInfo $userInfo;

    public function getUserInfo(): UserInfo
    {
//      On full version:
//      $user = \Illuminate\Support\Facades\Auth::user();
//      $companyName = $user->ldap->getFirstAttribute('company');
//      $this->userInfo->userLogin  = $user->name;

        $this->userInfo = new UserInfo();
        $this->userInfo->userLogin = $this->getUserLogin();
        $this->userInfo->userName = $this->getUserName();
        $this->userInfo->userBranch = $this->dataFromLoginPrefix($this->userInfo->userLogin);
        return $this->userInfo;
    }

    public function getUserLogin(): string
    {
        /**
         *    on full version
         *    $this->userLogin  = Auth::user()->name;
         *    return Auth::user()->name;
         */
        return 'kl_demouser';
    }

    public function getUserName(): string
    {
        /**
         * ПІБ зареєстрованого юзера
         * on full version
         * return Auth::user()->ldap->getFirstAttribute('displayName');
         */
        return 'Demo User';
    }

    public static function dataFromLoginPrefix(string $userlogin): Branch
    {
        return Branch::
        where('prefix', 'like', '%' . substr($userlogin, 0, 3) . '%')
            ->get()
            ->first();
    }
}
