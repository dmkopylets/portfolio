<?php

declare(strict_types=1);

namespace App\Model\User\Entity;

use App\Model\Ejournal\Dicts\Branch;

class UserInfo
{
    public Branch $userBranch;
    public string $userLogin;
    public string $userName;
}
