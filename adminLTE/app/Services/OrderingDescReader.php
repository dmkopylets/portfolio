<?php

declare(strict_types=1);

namespace App\Services;

class OrderingDescReader
{
    private bool $descending = false;

    public function __construct()
    {
        if (request()->query('order') === 'desc') {
           $this->descending = true;
        }
    }
    public function getIsDesc(): bool
    {
        return $this->descending;
    }
}
