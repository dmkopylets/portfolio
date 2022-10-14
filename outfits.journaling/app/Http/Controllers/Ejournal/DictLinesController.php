<?php

namespace App\Http\Controllers\Ejournal;

use App\Models\Ejournal\Dicts\Line;
use App\Http\Controllers\Ejournal\BaseController;
use App\Models\Ejournal\Dicts\Substation;
use Illuminate\Http\Request;

class DictLinesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

         $searchMystation =  '%'.$request->input('searchMystation').'%';
         $searchMyline_id =  '%'.$request->input('searchMyline_id').'%';

         $branch_id = $this->getBranch()->id;
         //$branch = Branch::find($branch_id);
         //$records = $branch->Lines()
         $stationList = Substation::
         // $Linelist = Line::
         where('branch_id',$branch_id)->
         where('body','like',$searchMystation)->
         pluck('id');
        
         $records = Line::select(Line::raw('dict_lines.id*1000+dict_lines.line_id AS id, dict_station_types.body AS station_type, dict_substations.body AS station, dict_lines.line_id AS line_id'))
         ->leftJoin('dict_substations',  'dict_substations.id', '=', 'dict_lines.substation_id')  
         ->leftJoin('dict_station_types','dict_station_types.id','=','dict_substations.type_id')         
         //->where('dict_lines.branch_id',$branch_id)
         ->whereIn('substation_id',$stationList)
         ->where('line_id','like',$searchMyline_id)
         ->get();

          return view('dicts.index', [
              'branch_name'=>$this->getBranch()->body,
              'records'=>$records,
              'zagolovok'=>'бригада',
              'dictName'=>'Lines',
              'add_th'=>array('рівень','підстанція','лінія'),
              'add_td'=>array('station_type','station','line_id'),
              'th_width'=>array(100,220,80)
              ]);
            

//              @if ($record->Line->type_id==1)
//                 <td width="105px">ПЛ-0,4 кВ</td>
//              @else
//                 <td width="105px">ПЛ-10 кВ</td>
//              @endif
//          <td width="295px">{{$record->Line->body}}</td>

         
    }

    /**
     * Show the form for creating a new resource.
     *  !!! CREATE
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dicts.create', [
            'zagolovok'=>'ліній',
            'dictName'=>'Lines',
            'add_th'=>array('рівень','підстанція','лінія'),
            'add_td'=>array('Line_type','station','line_id')
            ]);
    }

    /**
     * !!! Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $record = new Line;
        $record->id = Line::max('id')+1;
        $record->body = $request->input('Line_type');
        $record->type = $request->input('station');
        $record->type = $request->input('Line_id');
        $record->save();
        // redirect
        Session::flash('message', 'Запис успішно додано!');
        return Redirect::to('dicts/Lines');     
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ejournal\Dicts\Line $line
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $record = Line::find($id);    
        return view('dicts.edit', [
            'record'=>$record,'zagolovok'=>'ліній',
            'dictName'=>'Lines',
            'add_th'=>array('рівень 0.4-10','підстанція','лінія'),
            'add_td'=>array('Line_type','station','line_id')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ejournal\Dicts\Line  $line
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $record_id)
    {
        $rules = array(
            'Line_type'  => 'required',
            'station'    => 'required',
            'line_id'    => 'required'
        );
    // далі має бути якась валідація        
    // store
    $record = Line::find($record_id);
        $record->body = $request->input('Line_type');
        $record->type = $request->input('station');
        $record->type = $request->input('Line_id');
    $record->save();

    // redirect
    Session::flash('message', 'Запис успішно змінено!');
    return Redirect::to('dicts/Line');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ejournal\Dicts\Line  $line
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = Line::find($id);
        $msgtxt = $record->body;
        $record->delete();

        // redirect
        Session::flash('message', 'Успішно видалено лінію '.$msgtxt);
        return Redirect::to('dicts/Lines');
    }
}
