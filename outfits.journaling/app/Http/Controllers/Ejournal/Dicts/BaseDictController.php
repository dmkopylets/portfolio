<?php

namespace App\Http\Controllers\Ejournal\Dicts;

use App\Http\Controllers\Ejournal\BaseController;
use App\Models\Ejournal\Dicts\Branch as Branches;
use App\Models\Ejournal\Dicts\Substation;
use App\Http\Controllers\Ejournal\Dicts\DictBranchesController;
use Illuminate\Http\Request;
use function Livewire\str;

class BaseDictController extends BaseController
{
    protected function getSubstationsList(int $branch_id, int $substation_type_id)
    {
        return Substation::
        select('id', 'body')
            ->where('branch_id', $branch_id)
            ->where('type_id', $substation_type_id)
            ->orderBy('type_id', 'asc')
            ->orderBy('body', 'asc')
            ->get();
    }

    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $repertory = substr(\Request::getRequestUri(),7);
        $searchMyBody = '%' . $request->input('searchMybody') . '%';
        $searchMyPrefix = '%' . $request->input('searchMyprefix') . '%';
//        DictBranchesController::class()->

        $records =  $repertory::
        where('body', 'like', $searchMyBody)->
        where('prefix', 'like', $searchMyPrefix)->
        orderBy('id')->get();
        $parameters = [
            'branchName' => $this->getBranch()->body,
            'records' => $records,
            'zagolovok' => 'філій',
            'dictName' => $repertory,
            'modelName' => 'App\Models\Ejournal\Dicts\Branch',
            'add_th' => array('філія', 'префікс'),
            'add_td' => array('body', 'prefix'),
            'th_width' => array(280, 70)
        ];
        return view('dicts.index', $parameters);
    }
}
