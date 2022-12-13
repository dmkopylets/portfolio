<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ReportController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

abstract class ApiController extends ReportController
{
    /**
     * @OA\Info(
     *     title="Laravel Swagger API documentation for task 7",
     *     version="1.0.0",
     *     @OA\Contact(
     *         name="foxstudent102564",
     *         email="dm.kopylets@gmail.com"
     *         )
     * )
     * */

    protected Model $model;
    protected Request $request;
}
