<?php

namespace App\Http\Controllers\Ejournal;

use Livewire\Component;
use App\Models\Ejournal\Naryad;
use App\Models\Ejournal\Dicts\Direction;
use App\Models\Ejournal\Dicts\Substation;

class DirectionWorkController extends Component
{

    public $searchTerm;
    public $records;
    public function render(Direction $direction)
    {
    	$searchTerm = '%'.$this->searchTerm.'%';
        $substationlist = Substation::where('body','like',$searchTerm)->pluck('id');
        //$this->records = Naryad::whereIn('substation_id',$substationlist)->orderBy('id','desc')->get();
        $this->records = Naryad::whereIn('substation_id',$substationlist)->orderBy('id','desc')->paginate(10);
        return view('livewire.search', compact('direction'));
     //   return view('naryads.grid');
    }
}
