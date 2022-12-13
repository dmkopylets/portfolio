<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\ReportController;

use App\Http\Requests\OneDriverGetRequest;
use App\Models\RacingData\Driver;
use Illuminate\Database\Eloquent\Model;

class DriversController extends ReportController
{
    private Model $model;

    public function __construct(Driver $model)
    {
        $this->model = $model;
    }

    public function index(OneDriverGetRequest $request)
    {
        $oneDriver = $request->validated();
        if (empty($oneDriver)) {
            $records = $this->model->getAllOrdered(parent::orderingIsDesc());
            return view('racingReport.drivers.list', ['reportDriversData' => $records]);
        } else {
            return view('racingReport.drivers.showOne', ['oneDriverInfo' => $this->model->getDetails($oneDriver['driverId'])]);
        }
    }
}
