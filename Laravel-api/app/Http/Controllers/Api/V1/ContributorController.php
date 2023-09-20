<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\CreateContributorRequest;
use App\Models\Contributors\Contributor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ContributorController extends ApiController
{
    public function __construct(Contributor $model)
    {
        $this->model = $model;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/contributors",
     *     summary="Contributors listing",
     *     operationId="getContributorsList",
     *     tags={"Contributors"},
     *     @OA\Parameter(
     *         description="part of the title of collection id",
     *         name="wanted_CollectionId",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="part of the description of user name",
     *         name="wanted_UserName",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="part of the description of user amount",
     *         name="wanted_Amount",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Contributors not found",
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
        $wantedCollectionIid = $request->input('wanted_CollectionIid');
        $wantedUserName = $request->input('wanted_UserName');
        $wantedAmount = $request->input('wanted_amount');
        $contributorsList = $this->model->getList($wantedCollectionIid, $wantedUserName, $wantedAmount);
        return response()->json($contributorsList, 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/contributors/create",
     *     summary="Contributor creating",
     *     operationId="createContributor",
     *     tags={"Contributors"},
     *     @OA\Parameter(
     *         description="contributor collection id",
     *         name="collection_id",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="contributor user name",
     *         name="user_name",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="contributor amountn",
     *         name="amount",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *              type="number",
                    format="currency",
     *         )
     *     ),
     *     @OA\Response(
     *          response="default",
     *          response="201",
     *          description="contributor created"
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="Validation failed"
     *     )
     * )
     */
    public function create(CreateContributorRequest $request): JsonResponse
    {
        return parent::add($request);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/contributors/{id}",
     *     summary="View contributor info",
     *     operationId="getContributorById",
     *     tags={"Contributors"},
     *     @OA\Parameter(
     *          name="id",
     *          description="Contributors id",
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
    public function show(int $contributorId): JsonResponse
    {
        return response()->json($this->model->getDetails($contributorId), 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/contributors/{id}",
     *     summary="Delete a contributor",
     *     operationId="deleteContributor",
     *     tags={"Contributors"},
     *     @OA\Parameter(
     *          name="id",
     *          description="Contributors id to delete",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Contributor not found",
     *     ),
     *     @OA\Response(
     *          response="default",
     *          response="204",
     *          description="Delete contributor"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->model::find($id)->delete();
        return $this->sendResponse(null, 'Deleted', 201);
    }
}
