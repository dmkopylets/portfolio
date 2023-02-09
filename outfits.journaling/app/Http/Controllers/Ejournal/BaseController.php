<?php

namespace App\Http\Controllers\Ejournal;
use App\Http\Controllers\Controller;
use App\Model\User\Entity\UserInfo;
use App\Model\User\Entity\UserRepository;


class BaseController extends Controller
{
    protected UserInfo $currentUser;
    public $branch, $mode;
    public $preparations_rs = array();
    public $measures_rs = array();
    public $naryadRecord = array();

    public function __construct(public UserRepository $userRepository)
    {
        $this->currentUser = $this->userRepository->getUserInfo();
    }
}
