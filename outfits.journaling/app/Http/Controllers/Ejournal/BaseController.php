<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal;

use App\Http\Controllers\Controller;
use App\Model\Ejournal\Dicts\Branch;
use App\Model\Ejournal\Order;
use App\Model\Ejournal\OrderRecordDTO;
use App\Model\User\Entity\BranchInfo;
use App\Model\User\Entity\UserInfo;
use App\Model\User\Entity\UserRepository;


class BaseController extends Controller
{
    private OrderRecordDTO $orderRecord;
    public UserInfo $currentUser;
    protected BranchInfo $branch;
    public UserRepository $userRepository;
    protected string $mode;
    protected array $preparations_rs;
    protected array $measures_rs;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->currentUser = $this->userRepository->getUserInfo();
        $this->branch = $this->currentUser->userBranch;
    }

    public function getBranch(): BranchInfo
    {
        return $this->branch;
    }

    public function getOrderRecord(): OrderRecordDTO
    {
        $this->orderRecord = session('orderRecord');
        return $this->orderRecord;
    }

    public function setOrderRecord(OrderRecordDTO $orderRecord): void
    {
        session(['orderRecord' => $orderRecord]);
        $this->orderRecord = $orderRecord;
    }

    public function getMode(): string
    {
        $this->mode = session('mode');
        return $this->mode;
    }
    public function setMode(string $mode): void
    {
        session(['mode' => $mode]);
        $this->mode = $mode;
    }

    public function getPreparationsRs(): array
    {
        return $this->preparations_rs;
    }

    public function setPreparationsRs(array $preparations_rs): void
    {
        $this->preparations_rs = $preparations_rs;
    }

    /**
     * @return array
     */
    public function getMeasuresRs(): array
    {
        return $this->measures_rs;
    }

    /**
     * @param array $measures_rs
     */
    public function setMeasuresRs(array $measures_rs): void
    {
        $this->measures_rs = $measures_rs;
    }
}
