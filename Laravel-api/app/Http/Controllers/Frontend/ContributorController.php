<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateContributorRequest;
use App\Models\Contributors\Contributor;
use Illuminate\Http\Request;
use Session;
use Redirect;

class ContributorController extends Controller
{
    public function __construct(Contributor $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $wantedUserName = $request->input('wanted_user_name');
        $wantedAmount = $request->input('wanted_amount');
        $records = $this->model::
        where('user_name', 'like', $wantedUserName)->
        where('amount', 'like', $wantedAmount)->
        get();
        return view( 'contributors.contributor.index', [
            'records' => $records,
            'add_th'=>array('#_Збору', 'користувач', 'внесок'),
            'add_td'=>array('collection_id', 'user_name', 'amount'),
            'th_width'=>array(70, 150, 150)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view( 'contributors.contributor.create', [
            'add_th'=>array('collect.id', 'user_name', 'amount'),
            'add_td'=>array('collection_id', 'user_name', 'amount'),
            'th_width'=>array(70, 100, 100)
        ]);
    }

    public function store(CreateContributorRequest $request)
    {
        // $collectionId = $request->input('collection_id');
        // $userName = $request->input('user_name');
        // $amount = $request->input('amount');
        //        $validated = $request->validated();
        //        return $this->model->getDetails($validated['id']);
        $record = new Contributor;
        $record->collection_id = $request->input('collection_id');
        $record->user_name = $request->input('user_name');
        $record->amount = $request->input('amount');
        $record->save();
        Session::flash('message', 'Record added successfully!');
        return Redirect::to('contributors');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contributors\Contributor  $contributor
     * @return \Illuminate\Http\Response
     */
    public function show(Contributor $contributor)
    {
        $reportData = $this->model->getDetails($id);
        return response()->json($reportData, 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contributors\Contributor  $contributor
     * @return \Illuminate\Http\Response
     */
    public function edit(Contributor $contributor)
    {
        return view('contributors.contributor.edit',
            [
                'record' => $contributor,
                'modelName' => 'Contributor',
                'add_th' => array('collection_id', 'user_name', 'amount'),
                'add_td' => array('collection_id', 'user_name', 'amount')
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contributors\Contributor  $contributor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contributor $contributor)
    {
        //$record = $this->model::find($id);
        $record = $contributor;
        $record->collection_id = $request->input('collection_id');
        $record->user_name = $request->input('user_name');
        $record->amount = $request->input('amount');
        $record->save();
        Session::flash('message', 'Record # '.$id.' changed successfully!');
        return Redirect::to('contributors');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contributors\Contributor  $contributor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contributor $contributor)
    {
        // $record = Group::find($id);
        // $name = $record->name;
        // $record->delete();
        $contributorId = $contributor->id;
        $contributor->delete;
        Session::flash('message', 'Successfully deleted contributor by id ' . $contributorId);
        return Redirect::to('groups');
    }
}
