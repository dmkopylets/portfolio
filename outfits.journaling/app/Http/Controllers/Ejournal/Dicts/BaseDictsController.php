<?php

namespace App\Http\Controllers\Ejournal;

use Illuminate\Http\Request;
use App\Http\Controllers\Ejournal\BaseController;
use Session;
use Redirect;

class DictBranchesController extends BaseController
{
    public function index(Request $request)
    {
        $searchMybody = '%' . $request->input('searchMybody') . '%';
        $searchMyprefix = '%' . $request->input('searchMyprefix') . '%';
        $records = Branch::
        where('body', 'like', $searchMybody)->
        where('prefix', 'like', $searchMyprefix)->
        orderBy('id')->get();
        return view('dicts.index',
            ['branch_name' => $this->getBranch()->body,
                'records' => $records,
                'zagolovok' => 'філій',
                'dictName' => 'Branches',
                'modelName' => 'App\Models\Ejournal\Dicts\Branch',
                'add_th' => array('філія', 'префікс'),
                'add_td' => array('body', 'prefix'),
                'th_width' => array(280, 70)]);
    }


    /**
     * Show the form for creating a new resource.
     *  !!! CREATE
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dicts.create', [
            'zagolovok' => 'філій',
            'dictName' => 'Branches',
            'add_th' => array('філія', 'prefix'),
            'add_td' => array('body', 'prefix')
        ]);
    }

    /**
     * !!! STORE a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $record = new Branch;
        $record->id = Branch::max('id') + 1;
        $record->body = $request->input('body');
        $record->prefix = $request->input('prefix');
        $record->save();
        // redirect
        Session::flash('message', 'Запис успішно додано!');
        return Redirect::to('dicts/Branches');
    }


    /**
     * Show the form for editing the specified resource.
     *  !! EDIT
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $record = Branch::find($id);
        return view('dicts.edit', [
            'record' => $record, 'zagolovok' => 'філій',
            'dictName' => 'Branches',
            'add_th' => array('філія', 'prefix'),
            'add_td' => array('body', 'prefix')
        ]);
    }

    /**
     * !!! Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update($record_id, Request $request)
    {
        $rules = array(
            'body' => 'required',
            'prefix' => 'required'
        );
        // далі має бути якась валідація
        // store
        $record = Branch::find($record_id);
        $record->body = $request->input('body');
        $record->prefix = $request->input('prefix');
        $record->save();

        // redirect
        Session::flash('message', 'Запис успішно змінено!');
        return Redirect::to('dicts/Branches');
    }

    /**
     * !!! Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = Branch::find($id);
        $msgtxt = $record->body;
        $record->delete();

        // redirect
        Session::flash('message', 'Успішно видалено філію ' . $msgtxt);
        return redirect::to('dicts/Branches');
    }
}
