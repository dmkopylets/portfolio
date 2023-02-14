<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal;

use App\Http\Controllers\Controller;
use App\Model\Ejournal\Dicts\Branch;
use App\Model\Ejournal\Order;
use App\Model\User\Entity\UserInfo;
use App\Model\User\Entity\UserRepository;


class BaseController extends Controller
{
    private Order $orderRecord;
    public UserInfo $currentUser;
    protected Branch $branch;
    protected string $mode;
    protected array $preparations_rs;
    protected array $measures_rs;

    public function __construct(public UserRepository $userRepository)
    {
        $this->currentUser = $this->userRepository->getUserInfo();
        $this->branch = $this->currentUser->userBranch;
    }

    public function getBranch(): Branch
    {
        return $this->currentUser->userBranch;
    }

    public function getOrderRecord(): Order
    {
        return $this->orderRecord;
    }

    public function getPreparationsRs(): array
    {
        return $this->preparations_rs;
    }

    public function setPreparationsRs(array $preparations_rs): void
    {
        $this->preparations_rs = $preparations_rs;
    }
}
