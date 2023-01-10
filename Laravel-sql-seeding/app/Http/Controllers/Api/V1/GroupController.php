<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\CreateGroupRequest;
use App\Models\Courses\Group;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class GroupController extends ApiController
{
    public function __construct(Group $model)
    {
        $this->model = $model;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/groups",
     *     summary="Groups listing",
     *     operationId="getGroupsList",
     *     tags={"Groups"},
     *     @OA\Response(
     *          response=404,
     *          description="Groups not found",
     *       ),
     *     @OA\Response(
     *          response="default",
     *          response=200,
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *           )
     *       )
     *  )
     */
    public function index(): JsonResponse
    {
        return response()->json($this->model->getList(), 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/groups/create",
     *     summary="Group creating",
     *     operationId="createGroup",
     *     tags={"Groups"},
     *     @OA\Parameter(
     *         description="Group name",
     *         name="name",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *          response="default",
     *          response="201",
     *          description="group created"
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="Validation failed"
     *     )
     * )
     */
    public function create(CreateGroupRequest $request): JsonResponse
    {
        return parent::add($request);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/groups/{id}",
     *     summary="View group info",
     *     operationId="getGroupById",
     *     tags={"Groups"},
     *     @OA\Parameter(
     *          name="id",
     *          description="Group id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="{ }")
     * )
     */
    public function show(int $groupId): JsonResponse
    {
        return response()->json($this->model->getDetails($groupId), 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/groups{id}",
     *     summary="Delete a group",
     *     operationId="deleteGroup",
     *     tags={"Groups"},
     *     @OA\Parameter(
     *          name="id",
     *          description="Group id to delete",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Group not found",
     *     ),
     *     @OA\Response(
     *          response="default",
     *          response="204",
     *          description="Delete group"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->model::find($id)->delete();
        return $this->sendResponse(null, 'Deleted', 201);
    }
}
