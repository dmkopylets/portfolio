<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SourceInitService;

class HomeController extends Controller
{
    public function index(SourceInitService $sources)
    {
        if (!$this ->sourceFilesExist($sources)) {
            return view('racingReport.errorSourceFiles', ['files'=>$this->absentsFilesList($sources)]);
        } else {
            return view('admin.home.index');
        }
    }

    private function sourceFilesExist(SourceInitService $sources): bool
    {
        return $sources->isExist();
    }

    private function absentsFilesList(SourceInitService $sources): array
    {
        return $sources->getAbsentsList();
    }
}
