<?php

namespace App\Http\Controllers\eNaryad;

use App\Models\eNaryad\Dicts\Brigade_Engineer;
use Illuminate\Http\Request;
use App\Http\Controllers\eNaryad\BaseController;
use Session;
use Redirect; 

class DictBrigadeEngineersController extends BaseController
{
    /**
     * Display a listing of the resource.
     *   !!! INDEX
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $branch_id = $this->getBranch()->id;
        $searchMybody  =  '%'.$request->input('searchMybody').'%';
        $searchMygroup =  '%'.$request->input('searchMygroup').'%';
        $searchMyspecialization =  '%'.$request->input('searchMyspecialization').'%';

        $records = Brigade_Engineer::
           where('branch_id',$branch_id)->
           where('body','like',$searchMybody)->
           where('group','like',$searchMygroup)->
           where('specialization','like',$searchMyspecialization)->
           orderBy('body')->get();
        return view('dicts.index', 
           ['branch_name'=>$this->getBranchName(),
            'records'=>$records,
            'zagolovok'=>'механіки-стропальщики',
            'modelName'=>'App\Models\eNaryad\Dicts\Brigade_Engineer',
            'dictName'=>'BrigadeEngineers',
            'add_th'=>array('спеціалізація','П.І.Б.','група'),
            'add_td'=>array('specialization','body','group'),
            'th_width'=>array(100,100,50)]);
    }


    /**
     * Show the form for creating a new resource.
     * !!! CREATE
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dicts.create', 
           ['zagolovok'=>'машиністів-стропальщиків',
            'modelName'=>'App\Models\eNaryad\Dicts\Brigade_Engineer',
            'dictName'=>'BrigadeEngineers',
            'add_th'=>array('спеціалізація','П.І.Б.','група'),
            'add_td'=>array('specialization','body','group')]);
    }

    /**
     * !! Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $record = new Brigade_Engineer;
        $record->branch_id = $this->getBranchId();
        $record->id = Brigade_Engineer::max('id')+1;
        $record->specialization = $request->input('specialization');
        $record->body = $request->input('body');
        $record->group = $request->input('group');
        $record->save();
        // redirect
        Session::flash('message', 'Запис успішно додано машиніста-стропальщика '.$record->body);
        return Redirect::to('dicts/BrigadeEngineers');  
    }

    

    /**
     * Show the form for editing the specified resource.
     * !! EDIT
     * @param  \App\Models\eNaryad\Dicts\Brigade_Engineer  $brigade_engineer
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $record = Brigade_Engineer::find($id);    
        return view('dicts.edit', 
           ['record'=>$record,
            'zagolovok'=>'машиністів-стропальщиків',
            'dictName'=>'BrigadeEngineers',
            'modelName'=>'App\Models\eNaryad\Dicts\Brigade_Engineer',
            'add_th'=>array('спеціалізація','П.І.Б.','група'),
            'add_td'=>array('specialization','body','group')]);
    }

    /**
     * !! Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\eNaryad\Dicts\Brigade_Engineer  $brigade_engineer
     * @return \Illuminate\Http\Response
     * originalstring -  public function update(Request $request, Brigade_Engineer $brigade_engineer)
     */
    public function update(Request $request, $record_id)
    {
    $record = Brigade_Engineer::find($record_id);
    $record->specialization = $request->input('specialization');
    $record->body           = $request->input('body');
    $record->group          = $request->input('group');
    $record->save();

    // redirect
    Session::flash('message', 'Запис № '.$record_id.' успішно змінено!');
    return Redirect::to('dicts/BrigadeEngineers');
    }

    /**
     * !! Remove the specified resource from storage.
     *
     * @param  \App\Models\eNaryad\Dicts\Brigade_Engineer  $brigade_engineer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brigade_Engineer $brigade_engineer)
    {
        $record = Brigade_Engineer::find($id);
        $msgtxt = $record->body;
        $record->delete();

        // redirect
        Session::flash('message', 'Успішно видалено машиніста-стропальщика '.$msgtxt);
        return Redirect::to('dicts/BrigadeEngineers');
    }
}
