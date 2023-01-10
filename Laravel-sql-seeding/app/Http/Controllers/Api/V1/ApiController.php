<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiRequest;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

abstract class ApiController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Info(
     *     title="Laravel Swagger API documentation for task 9",
     *     version="1.0.0",
     *     @OA\Contact(
     *         name="foxstudent102564",
     *         email="dm.kopylets@gmail.com"
     *         )
     * )
     *
     */

    protected Model $model;

    protected function add(ApiRequest $request) : mixed
    {
        $data = $request->validated();
        $this->model->fill($data)->push();
        return $this->sendResponse(null, 'Created', 201);
    }
}
