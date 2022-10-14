<?php
namespace App\Http\Livewire;
use Livewire\Component;
use App\Models\Ejournal\Dicts\TypicalTask;
use App\Models\Ejournal\Dicts\Substation;
use App\Models\Ejournal\Dicts\Line;

class DirectionTask extends Component
{
    public $naryadRecord = array();
    public $lines = array();
    public $substations = array();
    public $taskslist = array();
    public $workspecs,  $choosedSubstation, $workslist,   $branch_id, $works_specs_id, $substation_type_id, $line_id, $mode;

    public function mount($workspecs, $substations, $mode, $naryadRecord)
    {
       $this->reset();
       $this->mode = $mode;
       $this->naryadRecord = $naryadRecord;
       $this->works_specs_id = $naryadRecord['workspecs_id'];
       $this->workspecs = $workspecs;
       $this->choosedSubstation = $naryadRecord['substation_id'];
       $this->substation_type_id = $naryadRecord['substation_type_id'];
       $this->branch_id = $naryadRecord['branch_id'];
       $this->workslist = $naryadRecord['objects'].' виконати '.$naryadRecord['tasks'];
       $this->substations = $substations;
       $this->line_id = $naryadRecord['line_id'];
       $this->lines = Line::
       select('line_id')
       ->where('branch_id',$this->branch_id)
       ->where('substation_id',$naryadRecord['substation_id'])
       ->orderBy('line_id','asc')
       ->get()->toArray();

       $this->taskslist = TypicalTask:: // список робіт визначених, в функціях mount або choose_direction 
       select('id','body')
       ->where('works_specs_id',$this->works_specs_id)
       ->orderBy('body')->get()->toArray();
    }

    public function choose_direction($choice)
    {
        $this->works_specs_id = $choice;
        // тільки при works_specs_id==3 , що означає = 
        // тільки для 10-ток 
        // буде зміна типу підстанцій (і тому й переліку в dict_substations), а так "завжди =0,4"
        if ($this->works_specs_id==3) {$this->substation_type_id=2;} else {$this->substation_type_id=1;}

        $this->taskslist = TypicalTask:: // список робіт визначених, в функціях mount або choose_direction 
            select('id','body')
            ->where('works_specs_id',$this->works_specs_id)
            ->orderBy('body')->get()->toArray();
        
        // список підстанцій, визначених в функціях mount або choose_direction 
              $this->substations = Substation::
              select('id','body')
                 ->where('branch_id',$this->branch_id)
                 ->where('type_id',$this->substation_type_id)
                 ->orderBy('type_id','asc')
                 ->orderBy('body','asc')
                 ->get()->toArray();
              
              // $this->getSubstationsList($this->branch_id,$this->substation_type_id);
        }

    public function choose_substation($choice)
    {
        $this->reset('choosedSubstation');
        $this->choosedSubstation = $choice;
        $this->reset('lines');
        $this->lines = Line::
        select('line_id')
        ->where('branch_id',$this->branch_id)
        ->where('substation_id',$choice)
        ->orderBy('line_id','asc')
        ->get()->toArray();
    }

    public function render()
    {   $this->taskslist = TypicalTask:: // список робіт визначених, в функціях mount або choose_direction 
        select('id','body')
        ->where('works_specs_id',$this->works_specs_id)
        ->orderBy('body')->get()->toArray();
        $this->substations = Substation::  // список підстанцій, визначених в функціях mount або choose_direction 
         select('id','body')
        ->where('branch_id',$this->branch_id)
        ->where('type_id',$this->substation_type_id)
        ->orderBy('body','asc')
        ->get()->toArray();
        $this->lines = Line::
        select('line_id')
        ->where('branch_id',$this->branch_id)
       ->where('substation_id',$this->choosedSubstation)
       ->orderBy('line_id','asc')
       ->get()->toArray();


        $newNaryadRecord = [
        'order_id' => $this->naryadRecord['order_id'],
        'branch_id' => $this->naryadRecord['branch_id'],
        'unit_id' => $this->naryadRecord['unit_id'],
        'warden_id' => $this->naryadRecord['warden_id'],
        'adjuster_id' => $this->naryadRecord['adjuster_id'],
        'brigade_m' => $this->naryadRecord['brigade_m'],
        'brigade_e' => $this->naryadRecord['brigade_e'],
        'workspecs_id' =>  $this->works_specs_id,
        'substation_id' =>  $this->choosedSubstation,
        'substation_type_id'=> $this->substation_type_id,
        'line_id'=> $this->line_id,
        'objects' => $this->naryadRecord['objects'],
        'tasks' => $this->naryadRecord['tasks'],
   //     'w_begin' => date ("Y-m-d H:i",strtotime($request->input('datetime_work_begin'))),
   //     'w_end' => date ("Y-m-d H:i",strtotime($request->input('datetime_work_end'))),   
        'sep_instrs'=>$this->naryadRecord['sep_instrs'],
        'order_creator'=>$this->naryadRecord['order_creator'],
        'order_longer'=>$this->naryadRecord['order_longer'],
        'under_voltage'=>$this->naryadRecord['under_voltage'],
        ];
        // "заганяємо" зчитані змінені значенні з полів введення в масив в session
        session(['naryadRecord' => $newNaryadRecord]); 

        return view('naryads.edit.f4_direction-task', [
            'lines'=>$this->lines,
            'line_id'=> $this->line_id,
            'workspecs'=>$this->workspecs,
            'works_specs_id'=>$this->works_specs_id,
            'substations'=>$this->substations,
            'taskslist'=>$this->taskslist,
            'substation_id'=>$this->choosedSubstation,
            'substation_type_id'=>$this->substation_type_id,
            'mode'=> $this->mode,
            'workslist'=>$this->workslist,
            'naryadRecord' => $newNaryadRecord,
            ]);
    }
}
