<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\OneDriverGetRequest;
use App\Models\RacingData\Driver;
use App\Services\XmlBuilderDriversService;
use App\Services\XmlBuilderOneDriverService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class DriversController extends ApiController
{
    public function __construct(Driver $model)
    {
        $this->model = $model;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/report/drivers",
     *     summary="Drivers listing",
     *     operationId="getDriversList",
     *     tags={"Drivers"},
     *     @OA\Parameter(
     *          name="order",
     *          description="Ordering drivers",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="format",
     *          description="output fotmat: json or xml",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="driverId",
     *          description="DriverTxt id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              minLength=3,
     *              maxLength=3
     *          )
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="Driver not found",
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
    public function index(OneDriverGetRequest $request): JsonResponse
    {
        $validated = $request->validated();
        if (empty($validated)) {
            $reportData = $this->model->getAllOrdered(parent::orderingIsDesc());
            $xmlResponse = new XmlBuilderDriversService();
        } else {
            $reportData = $this->model->getDetails($validated['driverId']);
            $xmlResponse = new XmlBuilderOneDriverService();
        }
        $result = $this->getFormatedResponse($reportData, parent::formatIsXml(), $xmlResponse);
        return $this->sendResponse($result, 'OK', 200);
    }

    private function getFormatedResponse(array $reportData, bool $formatIsXml, XmlBuilderDriversService|XmlBuilderOneDriverService $xmlResponse): JsonResponse|string
    {
        if ($formatIsXml) {
            return $xmlResponse->build($reportData);
        } else {
            return response()->json($reportData, 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    }
}
