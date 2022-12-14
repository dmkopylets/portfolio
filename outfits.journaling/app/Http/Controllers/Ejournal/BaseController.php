<?php

namespace App\Http\Controllers\Ejournal;
use App\Http\Controllers\Controller;
use App\Models\Ejournal\Dicts\Branch;
use App\Models\Ejournal\Dicts\Substation;


 class BaseController extends Controller
{
    private string $displayName;
    private string  $userLogin;
    public $branch, $mode;
    public $preparations_rs = array();
    public $measures_rs = array();
    public $naryadRecord = array();

    /**
     * @return string
     *    on full version
     *    $this->userLogin  = Auth::user()->name;
     */
    public function getUserLogin(): string
    {
        $this->userLogin = '09_demoUser';
        return $this->userLogin;
    }

    /**
     * @return string
     * ПІБ зареєстрованого юзера
     * on full version
     * $this->displayName = Auth::user()->ldap->getFirstAttribute('displayName');
     */
    public function getDisplayName(): string
    {
        $this->displayName = 'DemoUser';
        return $this->displayName;
    }

    /**
     * @return mixed
     */
    protected function getBranch()
    {
        return Branch::dataFromLoginPrefix();
    }
}
