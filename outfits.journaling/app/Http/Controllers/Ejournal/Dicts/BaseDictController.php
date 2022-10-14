<?php

namespace App\Http\Controllers\Ejournal\Dicts;

use App\Http\Controllers\Ejournal\BaseController;

abstract class BaseDictController extends BaseController
{
    protected function getBranch22()
    {
        $branch = $this->getBranch();
        return $this->getBranch();
    }
}
