<?php

namespace App\Http\Controllers\Ejournal;

use App\Model\Ejournal\Dicts\Direction;
use App\Model\Ejournal\Dicts\Substation;
use App\Model\Ejournal\Order;
use Livewire\Component;

class DirectionWorkController extends Component
{

    public $searchTerm;
    public $records;
    public function render(Direction $direction)
    {
    	$searchTerm = '%'.$this->searchTerm.'%';
        $substationlist = Substation::where('body','like',$searchTerm)->pluck('id');
        $this->records = Order::whereIn('substation_id',$substationlist)->orderBy('id','desc')->paginate(10);
        return view('livewire.search', compact('direction'));

    }
}
