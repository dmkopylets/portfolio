<?php

namespace App\Http\Controllers\eNaryad;

use App\Models\eNaryad\Dicts\Brigade_Member;
use Illuminate\Http\Request;
use App\Http\Controllers\eNaryad\BaseController;
use Session;
use Redirect; 

class DictBrigadeMembersController extends BaseController
{
    /**
     * Display a listing of the resource.
     * !!! INDEX
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $branch_id = $this->getBranch()->id;
        $searchMybody  =  '%'.$request->input('searchMybody').'%';
        $searchMygroup =  '%'.$request->input('searchMygroup').'%';
        $records = Brigade_Member::
            where('branch_id',$branch_id)->
            where('body','like',$searchMybody)->
            where('group','like',$searchMygroup)->
            orderBy('body')->get();
        return view('dicts.index', 
           ['branch_name'=>$this->getBranchName(),
            'records'=>$records,
            'zagolovok'=>'бригада',
            'modelName'=>'App\Models\eNaryad\Dicts\Brigade_Member',
            'dictName'=>'BrigadeMembers',
            'add_th'=>array('П.І.Б.','група'),
            'add_td'=>array('body','group'),
            'th_width'=>array(305,105)]);
    }

    /**
     * Show the form for creating a new resource.
     * !!! CREATE
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dicts.create', 
           ['zagolovok'=>'члені бригади',
            'modelName'=>'App\Models\eNaryad\Dicts\Brigade_Member',
            'dictName'=>'BrigadeMembers',
            'add_th'=>array('П.І.Б.','група'),
            'add_td'=>array('body','group')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $record = new Brigade_Member;
        $record->branch_id = $this->getBranchId();
        $record->id = Brigade_Member::max('id')+1;
        $record->body = $request->input('body');
        $record->group = $request->input('group');
        $record->save();
        // redirect
        Session::flash('message', 'Запис успішно додано у список можливих членів бригади '.$record->body);
        return Redirect::to('dicts/BrigadeMembers');  
    }

     /**
     * Show the form for editing the specified resource.
     * 
     * @param  \App\Models\Brigade_Member  $brigade_member
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $record = Brigade_Member::find($id);    
        return view('dicts.edit', 
           ['record'=>$record,
            'zagolovok'=>'машиністів-стропальщиків',
            'dictName'=>'BrigadeMembers',
            'modelName'=>'App\Models\eNaryad\Dicts\Brigade_Member',
            'add_th'=>array('П.І.Б.','група'),
            'add_td'=>array('body','group')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brigade_Member  $brigade_member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $record_id)
    {
    $record = Brigade_Member::find($record_id);
    $record->body           = $request->input('body');
    $record->group          = $request->input('group');
    $record->save();

    // redirect
    Session::flash('message', 'Запис № '.$record_id.' успішно змінено!');
    return Redirect::to('dicts/BrigadeMembers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brigade_Member  $brigade_member
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = Brigade_Member::find($id);
        $msgtxt = $record->body;
        $record->delete();

        // redirect
        Session::flash('message', 'Успішно видалено '.$msgtxt);
        return Redirect::to('dicts/BrigadeMembers');
    }
}
