<?php

namespace App\Http\Controllers\Ejournal;
use App\Http\Controllers\Controller;
use App\Model\Ejournal\Order;
use App\Model\User\Entity\UserInfo;
use App\Model\User\Entity\UserRepository;


class BaseController extends Controller
{
    protected UserInfo $currentUser;
    public string $mode;
    public array $preparations_rs;
    public array $measures_rs;
    public Order $orderRecord;

    public function __construct(public UserRepository $userRepository)
    {
        $this->currentUser = $this->userRepository->getUserInfo();
    }
}
