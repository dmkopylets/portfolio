<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ReportController;
use App\Models\RacingData\Flight;
use Illuminate\Http\JsonResponse;
use App\Services\XmlBuilderFlightsService;
use OpenApi\Annotations as OA;

class FlightsController extends ReportController
{
    /**
     * @OA\Get(
     *     path="/api/v1/report/",
     *     summary="Racing statistic",
     *     operationId="getFlightsList",
     *     tags={"Report"},
     *      @OA\Parameter(
     *          name="order",
     *          description="Ordering racing",
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
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *           )
     *       )
     *  )
     */
    public function index(): JsonResponse|string
    {
        $reportData = new ReportController;
        $ordering = $reportData->orderingIsDesc();
        $records = Flight::select(
            'flights.driverId',
            'drivers.name AS driver',
            'drivers.team',
            'flights.start',
            'flights.finish',
            'flights.duration',
            'flights.possition',
            'flights.top'
        )
            ->leftJoin('drivers', 'drivers.id', '=', 'flights.driverId')
            ->orderBy('duration',$ordering)
            ->get()
            ->toArray();
        return $this->getFormatedResponse($records, $this->formatIsXml());
    }

    private function getFormatedResponse(array $reportData, bool $formatIsXml): JsonResponse|string
    {
        if ($formatIsXml) {
            $xmlResponse = new XmlBuilderFlightsService();
            return  $this->sendResponse($xmlResponse->build($reportData), 'OK', 200);
        } else {
            return response()->json($reportData, 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    }
}
