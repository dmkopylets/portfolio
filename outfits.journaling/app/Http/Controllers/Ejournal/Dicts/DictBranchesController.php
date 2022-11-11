<?php

namespace App\Http\Controllers\Ejournal\Dicts;

use App\Http\Controllers\Ejournal\BaseController;
use App\Models\Ejournal\Dicts\Branch;
use Illuminate\Http\Request;
use Redirect;
use Session;

class DictBranchesController extends BaseController
{
    public function index(): array
    {
        $records = Branch::
        where('body', 'like', $searchMyBody)->
        where('prefix', 'like', $searchMyPrefix)->
        orderBy('id')->get();
        return ($records);
    }

    public function create()
    {
        return view('dicts.create', [
            'zagolovok' => 'філій',
            'dictName' => 'Branches',
            'add_th' => array('філія', 'prefix'),
            'add_td' => array('body', 'prefix'),
        ]);
    }

    public function store(Request $request): \Illuminate\Http\Response
    {
        $record = new Branch;
        $record->id = Branch::max('id') + 1;
        $record->body = $request->input('body');
        $record->prefix = $request->input('prefix');
        $record->save();
        Session::flash('message', 'Запис успішно додано!');
        return Redirect::to('dicts/Branches');
    }

    public function edit(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $record = Branch::find($id);
        return view('dicts.edit', [
            'record' => $record, 'zagolovok' => 'філій',
            'dictName' => 'Branches',
            'add_th' => array('філія', 'prefix'),
            'add_td' => array('body', 'prefix')
        ]);
    }

    public function update(int $record_id, Request $request): \Illuminate\Http\Response
    {
        $rules = array(
            'body' => 'required',
            'prefix' => 'required'
        );
        $record = Branch::find($record_id);
        $record->body = $request->input('body');
        $record->prefix = $request->input('prefix');
        $record->save();
        Session::flash('message', 'Запис успішно змінено!');
        return Redirect::to('dicts/Branches');
    }

    public function destroy(int $id): \Illuminate\Http\Response
    {
        $record = Branch::find($id);
        $msgtxt = $record->body;
        $record->delete();
        Session::flash('message', 'Успішно видалено філію ' . $msgtxt);
        return redirect::to('dicts/Branches');
    }
}
