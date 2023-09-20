<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\CreateCollectionRequest;
use App\Models\Contributors\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class CollectionController extends ApiController
{
    public function __construct(Collection $model)
    {
        $this->model = $model;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/collections",
     *     summary="Collections listing",
     *     operationId="getCollectionsList",
     *     tags={"Collections"},
     *     @OA\Parameter(
     *         description="collection completed (0 or 1)",
     *         name="wanted_Completed",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="part of the title of collection",
     *         name="wanted_Title",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="part of the description of collection",
     *         name="wanted_Description",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
           @OA\Parameter(
     *         description="part of the target amount of collection",
     *         name="wanted_TargetAmount",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="part of the link of collection",
     *         name="wanted_Link",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Collections not found",
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
    public function index(Request $request): JsonResponse
    {
        $wantedTitle = $request->input('wanted_Title');
        $wantedDescription = $request->input('wanted_Description');
        $wantedTargetAmount = $request->input('wanted_TargetAmount');
        $wantedLink = $request->input('wanted_Link');
        $wantedCompleted = $request->input('wanted_Completed');
        $collectionsList = $this->model->getList($wantedTitle, $wantedDescription, $wantedTargetAmount, $wantedLink, $wantedCompleted);
        return response()->json($collectionsList, 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/collections/create",
     *     summary="Collection creating",
     *     operationId="createCollection",
     *     tags={"Collections"},
     *     @OA\Parameter(
     *         description="collection title",
     *         name="title",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="collection description",
     *         name="description",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="collection target amountn",
     *         name="target_amount",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *              type="number",
                    format="currency",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="collection link",
     *         name="link",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *          response="default",
     *          response="201",
     *          description="collection created"
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="Validation failed"
     *     )
     * )
     */
    public function create(CreateCollectionRequest $request): JsonResponse
    {
        return parent::add($request);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/collections/{id}",
     *     summary="View collection info",
     *     operationId="getCollectionById",
     *     tags={"Collections"},
     *     @OA\Parameter(
     *          name="id",
     *          description="Collections id",
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
    public function show(int $collectionId): JsonResponse
    {
        return response()->json($this->model->getDetails($collectionId), 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/collections/{id}",
     *     summary="Delete a collection",
     *     operationId="deleteCollection",
     *     tags={"Collections"},
     *     @OA\Parameter(
     *          name="id",
     *          description="Collections id to delete",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Collection not found",
     *     ),
     *     @OA\Response(
     *          response="default",
     *          response="204",
     *          description="Delete collection"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->model::find($id)->delete();
        return $this->sendResponse(null, 'Deleted', 201);
    }
}
