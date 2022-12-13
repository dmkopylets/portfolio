<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Traits\ApiResponse;

class ReportController extends BaseController
{
    use ValidatesRequests, ApiResponse;
    protected function orderingIsDesc(): string
    {
        if (request()->query('order') === 'desc') {
            return 'DESC';
        } else {
            return 'ASC';
        }
    }

    protected function formatIsXml(): bool
    {
        return request()->query('format') === 'xml';
    }
}
