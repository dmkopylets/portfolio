<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentGetRequest;
use App\Models\Courses\Group;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use Session;
use Redirect;

class GroupController extends Controller
{
    public function __construct(Group $model)
    {
        $this->model = $model;
    }
    public function index(Request $request)
    {
        $searchMyName = '%'.$request->input('searchMy_name').'%';
        $records = $this->model::
        where('name','like',$searchMyName)->
        get();
        return view( 'course.group.index', [
            'records' => $records,
            'add_th'=>array('name'),
            'add_td'=>array('name'),
            'th_width'=>array(100)
        ]);
    }

    public function create(): \Illuminate\Contracts\View\Factory|View
    {
        return view( 'course.group.create', [
            'add_th'=>array('name'),
            'add_td'=>array('name'),
            'th_width'=>array(100)
        ]);
    }

    public function store(OneGroupGetRequest $request): RedirectResponse
    {
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        //        $validated = $request->validated();
        //        return $this->model->getDetails($validated['id']);
        $record = new Group;
        $record->first_name = $request->input('first_name');
        $record->last_name = $request->input('last_name');
        $record->save();
        Session::flash('message', 'Record added successfully!');
        return Redirect::to('groups');
    }

    public function show(int $id): JsonResponse
    {
        $reportData = $this->model->getDetails($id);
        return response()->json($reportData, 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function edit(int $id): View
    {
        $record = $this->model::find($id);
        return view('course.group.edit',
            [
                'record' => $record,
                'dictName' => 'Group',
                'add_th' => array('name'),
                'add_td' => array('name')
            ]
        );
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $record = $this->model::find($id);
        $record->name = $request->input('name');
        $record->save();
        Session::flash('message', 'Record # '.$id.' changed successfully!');
        return Redirect::to('groups');
    }

    public function destroy(int $id): RedirectResponse
    {
        $record = Group::find($id);
        $name = $record->name;
        $record->delete();
        Session::flash('message', 'Successfully deleted group '.$name);
        return Redirect::to('groups');
    }
}
