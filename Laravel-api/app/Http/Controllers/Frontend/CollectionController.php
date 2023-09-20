<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contributors\Collection;
use App\Http\Requests\CreateCollectionRequest as CollectionGetRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Session;
use Redirect;

class CollectionController extends Controller
{
    public function __construct(Collection $model)
    {
        $this->model = $model;
    }

    public function index(Request $request): View
    {
        $wantedTitle = $request->input('wanted_title');
        $wantedDescription = $request->input('wanted_description');
        $wantedTargetAmount = $request->input('wanted_target_amount');
        $wantedLink = $request->input('wanted_link');
        $wantedCompleted = $request->input('wanted_completed');
        return view('contributors.collection.index', [
            'records' => $this->model->getList($wantedTitle, $wantedDescription, $wantedTargetAmount, $wantedLink, $wantedCompleted),
            'add_th' => array('completed', 'title', 'description', 'target_amount', 'link'),
            'add_td' => array('completed', 'title', 'description', 'target_amount', 'link'),
            'th_width' => array(120, 120, 200, 100, 250)
        ]);
    }

    public function create(): View
    {
        return view('contributors.collection.create', [
            'method' => ['POST'],
            'add_th' => array('title', 'description', 'target_amount', 'link'),
            'add_td' => array('title', 'description', 'target_amount', 'link'),
            'th_width' => array(70, 170, 70, 170)
        ]);
    }

    public function store(CollectionGetRequest $request): RedirectResponse
    {
        $record = new $this->model;
        $validated = $request->validated();
        $record->title = $validated['title'];
        $record->description = $validated['description'];
        $record->target_amount = $validated['target_amount'];
        $record->link = $validated['link'];
        $record->save();
        Session::flash('message', 'Record added successfully!');
        return Redirect::to('collections');
    }

    public function edit(int $id): View
    {
        $record = $this->model::find($id);
        return view('contributors.collection.edit',
            [
                'record' => $record,
                'modelName' => 'Collection',
                'add_th' => array('title', 'description', 'target_amount', 'link'),
                'add_td' => array('title', 'description', 'target_amount', 'link'),
            ]
        );
    }

    public function update(CollectionGetRequest $request, int $id)
    {
        $record = $this->model::find($id);
        $validated = $request->validated();
        $record->title = $validated['title'];
        $record->description = $validated['description'];
        $record->target_amount = $validated['target_amount'];
        $record->link = $validated['link'];
        $record->save();
        Session::flash('message', 'Record # '.$id.' changed successfully!');
        return Redirect::to('collections');
    }

    public function destroy(int $id): RedirectResponse
    {
        $record = $this->model::find($id);
        $name = $record->first_name . ' ' . $record->last_name;
        $record->delete();
        Session::flash('message', 'Successfully deleted student ' . $name);
        return Redirect::route('collections.index');
    }
}
