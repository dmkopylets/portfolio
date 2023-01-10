<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\CreateStudentRequest;
use App\Models\Courses\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class StudentController extends ApiController
{
    public function __construct(Student $model)
    {
        $this->model = $model;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/students",
     *     summary="Students listing",
     *     operationId="getStudentsList",
     *     tags={"Students"},
     *     @OA\Parameter(
     *         description="part of the first name of student",
     *         name="wantedFirstName",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="part of the last name of student",
     *         name="wantedLastName",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Students not found",
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
        $wantedFirstName = $request->input('wantedFirstName');
        $wantedLastName = $request->input('wantedLastName');
        $studentsList = $this->model->getList($wantedFirstName, $wantedLastName);
        return response()->json($studentsList, 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/students/create",
     *     summary="Student creating",
     *     operationId="createStudent",
     *     tags={"Students"},
     *     @OA\Parameter(
     *         description="student first name",
     *         name="first_name",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="student last name",
     *         name="last_name",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *          response="default",
     *          response="201",
     *          description="student created"
     *     ),
     *     @OA\Response(
     *          response="422",
     *          description="Validation failed"
     *     )
     * )
     */
    public function create(CreateStudentRequest $request): JsonResponse
    {
        return parent::add($request);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/students/{id}",
     *     summary="View student info",
     *     operationId="getStudentById",
     *     tags={"Students"},
     *     @OA\Parameter(
     *          name="id",
     *          description="Students id",
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
    public function show(int $studentId): JsonResponse
    {
        return response()->json($this->model->getDetails($studentId), 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/students/{id}",
     *     summary="Delete a student",
     *     operationId="deleteStudent",
     *     tags={"Students"},
     *     @OA\Parameter(
     *          name="id",
     *          description="Students id to delete",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Student not found",
     *     ),
     *     @OA\Response(
     *          response="default",
     *          response="204",
     *          description="Delete student"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->model::find($id)->delete();
        return $this->sendResponse(null, 'Deleted', 201);
    }
}
