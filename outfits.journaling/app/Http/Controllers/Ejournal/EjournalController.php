<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal;

use App\Http\Controllers\Ejournal\BaseController as BaseController;
use App\Model\Ejournal\Dicts\Adjuster;
use App\Model\Ejournal\Dicts\BrigadeEngineer;
use App\Model\Ejournal\Dicts\BrigadeMember;
use App\Model\Ejournal\Dicts\StationType;
use App\Model\Ejournal\Dicts\Substation;
use App\Model\Ejournal\Dicts\TypicalTask;
use App\Model\Ejournal\Dicts\Unit;
use App\Model\Ejournal\Dicts\Warden;
use App\Model\Ejournal\Measure;
use App\Model\Ejournal\Order;
use App\Model\Ejournal\Preparation;
use App\ReadModel\SubstationFetcher;
use Illuminate\Http\Request;
use Redirect;

class EjournalController extends BaseController
{
    public function welcome()
    {
        session()->forget('naryadRecord');
        session()->forget('preparations_rs');
        session()->forget('measures_rs');
        session()->forget('mode');

        return view('orders.welcome', [
            'userBranch' => $this->currentUser->userBranch,
            'userLogin' => $this->currentUser->userLogin,
            'userName' => $this->currentUser->userName
        ]);
    }


    /**  INDEX **
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searchWarden = '%' . $request->input('searchWarden') . '%';
        $searchTerm = '%' . $request->input('searchTerm') . '%';

        if ($this->currentUser->userBranch->id == 0) {
            $wardenlistid = Warden::where('body', 'like', $searchWarden)->pluck('id');
            $substationlistid = Substation::where('body', 'like', $searchTerm)->pluck('id');
        } else {
            $wardenlistid = Warden::where('body', 'like', $searchWarden)->where('branch_id', $this->currentUser->userBranch->id)->pluck('id');
            $substationlistid = Substation::where('body', 'like', $searchTerm)->where('branch_id', $this->currentUser->userBranch->id)->pluck('id');
        }

        $records = Order::whereIn('substation_id', $substationlistid)->whereIn('warden_id', $wardenlistid)->orderBy('id', 'desc')->paginate(5);

        // чистимо Session
        session()->forget('naryadRecord');
        session()->forget('preparations_rs');
        session()->forget('measures_rs');
        session()->forget('mode');

        return view('orders.index', ['records' => $records, 'mode' => 'index', 'branch' => $this->currentUser->userBranch]);
    }

    /**
     *   !! визначаємо напрямок робіт для  створюваного НОВОГО наряду
     */
    public function precreate()
    {
        return view('orders.precreate', [
            'workspecs' => \App\Model\Ejournal\Dicts\WorksSpec::worksSpecCollect(),
            'workspecs_id' => 0,
            // $this->__set('workspecs_id',0)  // не осилив покищо
        ]);
    }

    /**
     *   !! створюєно НОВИЙ наряд
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //$this->mode='create';
        $branch = $this->currentUser->userBranch;
        $tasks = TypicalTask::orderBy('id')->get();
        $units = Unit::where('branch_id', $branch->id)->orderBy('id')->get();
        $wardens = Warden::where('branch_id', $branch->id)->orderBy('id')->get();
        $adjusters = Adjuster::where('branch_id', $branch->id)->orderBy('id')->get();
        $brig_m_arr = BrigadeMember::where('branch_id', $branch->id)->orderBy('id')->get();
        $brig_e_arr = BrigadeEngineer::where('branch_id', $branch->id)->orderBy('id')->get();
        $workspecs_id = $request->input('direction'); // визначена позиція списка - де робитиметься :
        // тільки для "10-ток" буде зміна типу підстанцій (і тому й переліку в dict_substations), а так "завжди =0,4"
        if ($workspecs_id == 3) {
            $substation_type_id = 2;
        } else {
            $substation_type_id = 1;
        }
        $this->preparations_rs = array();
        $this->measures_rs = array();
        $this->naryadRecord = [
            'order_id' => 0,
            'branch_id' => $branch->id,
            'unit_id' => '',
            'unit_txt' => '',
            'warden_id' => '',
            'warden_txt' => '',
            'adjuster_id' => '',
            //'adjuster_txt'=>$adjuster->body.', '.$adjuster->group,
            'brigade_m' => '',
            'brigade_e' => '',
            'substation_id' => 1,
            'substation_type_id' => $substation_type_id,
            //'substation_type'=>$substation_type,
            'line_id' => 1,
            'sep_instrs' => '',
            'order_creator' => '',
            'order_longer' => '',
            'workspecs_id' => $request->input('direction'), // визначена позиція списка - де робитиметься :
            'objects' => '',
            'tasks' => '',
            'under_voltage' => '',
            // зараз це буде передано у editPart2.blade
        ];
        session()->forget('naryadRecord');
        session(['naryadRecord' => $this->naryadRecord]);


        return view('orders.edit', [
            'mode' => 'create',
            'title' => 'новий',
            'tasks' => $tasks,
            'units' => $units,
            'wardens' => $wardens,
            'adjusters' => $adjusters,
            'brig_m_arr' => $brig_m_arr,
            'brig_e_arr' => $brig_e_arr,
            'brigade_m' => '',
            'brigade_e' => '',
            'branch' => $branch,
            'substations' => $this->getSubstationsList($branch->id, $substation_type_id), // функція прописана в BaseController
            'workspecs' => \App\Model\Ejournal\Dicts\WorksSpec::worksSpecCollect(), // список - де робитиметься : на 10-ках, чи на 0.4, чи ...
            'workslist' => ' виконати ', // саме текст завдання
            'preparations_rs' => $this->preparations_rs,
            'measures_rs' => $this->measures_rs,
            'maxIdpreparation' => 0,
            'maxIdmeasure' => 0,
            'naryadRecord' => $this->naryadRecord,
        ]);
    }

    /**
     * Show the form for editing the specified resource - model "naryad".
     *
     * @param  $order_id - id конкретного наряду з таблиці  Naryads
     * @return \Illuminate\Http\Response
     */
    public function edit($order_id)
        /*
         !! більшість даних берем з бази
         !! передаєм їх у в'юшку
         !! Але зберігаємо цю частину новоствореного наряду в масив
         !! і передаємо у session для наступних форм введення */
    {
        $record = Order::find($order_id);  // !! по номеру наряду який клонуємо
        $branch = $this->currentUser->userBranch;
        $unit_id = $record->unit_id;
        $brig_m_arr = BrigadeMember::where('branch_id', $branch->id)->orderBy('id')->get();   // масив усіх можливих членів бригади
        $brig_e_arr = BrigadeEngineer::where('branch_id', $branch->id)->orderBy('id')->get(); // масив усіх можливих машиністів бригади
        $brigade_m = $record->brigade_m; // перелік id-шників членів бригади у наряді
        $brigade_e = $record->brigade_e; // перелік id-шників машиністів бригади у наряді
        $wardens = Warden::where('branch_id', $branch->id)->orderBy('id')->get();
        $warden = Warden::find($record->warden_id);
        $adjusters = Adjuster::where('branch_id', $branch->id)->orderBy('id')->get();
        $adjuster = Adjuster::find($record->adjuster_id);
        $brigade_txt = '';
        if (isset($record->brigade_m)) {
            $brigade_txt = BrigadeMember::find(explode(",", $record->brigade_m));
        }
        $engineers_txt = '';
        if (isset($record->brigade_e)) {
            $engineers_txt = BrigadeEngineer::find(explode(",", $record->brigade_e));
        }
        $countbrigade = count(explode(",", $record->brigade_m)) + count(explode(",", $record->brigade_e));
        $substation = Substation::find($record->substation_id);
        $substation_id = $substation->id;
        $substation_txt = $substation->body;
        $substation_type_id = $substation->type_id;
        $substation_type = StationType::find($substation_type_id)->body;
        $substations = Substation::   // однотипні підстанції підрозділу
        select('id', 'body')
            ->where('branch_id', $branch->id)
            ->where('type_id', $substation_type_id)
            ->orderBy('body', 'asc')
            ->get();

        $this->naryadRecord = [
            'order_id' => $order_id,
            'branch_id' => $branch->id,
            'unit_id' => $unit_id,
            'unit_txt' => Unit::find($record->unit_id)->body,
            'warden_id' => $record->warden_id,
            'warden_txt' => $warden->body . ', ' . $warden->group,
            'adjuster_id' => $record->adjuster_id,
            'adjuster_txt' => $adjuster->body . ', ' . $adjuster->group,
            'brigade_m' => $brigade_m,
            'brigade_e' => $brigade_e,
            'workspecs_id' => $record->works_spec_id,
            'substation_id' => $substation_id,
            'substation_type_id' => $substation_type_id,
            'substation_type' => $substation_type,
            'line_id' => $record->line_id,
            'objects' => $record->ojects,
            'tasks' => $record->tasks,
            'sep_instrs' => $record->sep_instrs,
            'order_creator' => $record->order_creator,
            'order_longer' => $record->order_longer,
            'under_voltage' => $record->under_voltage,
        ];
        session()->forget('naryadRecord');
        session(['naryadRecord' => $this->naryadRecord]);

        return view('orders.edit', [
            'mode' => 'clone',
            'title' => '№ ' . $order_id,
            'branch' => $branch,
            'brig_m_arr' => $brig_m_arr,
            'brig_e_arr' => $brig_e_arr,
            'units' => Unit::where('branch_id', $branch->id)->orderBy('id')->get(),
            'wardens' => $wardens,
            'adjusters' => $adjusters,
            'countbrigade' => $countbrigade,
            'substations' => $substations,
            'workspecs' => \App\Model\Ejournal\Dicts\WorksSpec::worksSpecCollect(),
            'workslist' => $record->ojects . ' виконати ' . $record->tasks,
            'brigade_txt' => $brigade_txt,
            'engineers_txt' => $engineers_txt,
            'substation_txt' => $substation_txt,
            'naryadRecord' => $this->naryadRecord,
        ]);
    }

    // !! *****************************************************
    public function editpart2($order_id, Request $request)
        /**********************************************************
         * !! спочатку з session('naryadRecord') витягуємо всі попередньо відомі дані по конкретному наряду, потім
         * !! за техлогією метода store(Request $request) зчитуємо те, що могло змінитися у попередній в'юшці
         * !! через $request-> витягуємо даніх попередньої в'юшки (orders.edit)
         * !! і цією частиною доповнюємо  новостворений наряд в масиві session
         * !! і передаємо у наступну форму введення
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         */
    {
        if ($order_id == 0) {
            $mode = 'create';
        } else {
            $mode = 'clone';
        }
        $sNaryadRecord = session('naryadRecord'); // !! спочатку з session('naryadRecord') витягуємо всі попередньо відомі дані по конкретному наряду,
        $branch_id = $sNaryadRecord['branch_id'];
        $brig_m_arr = BrigadeMember::where('branch_id', $branch_id)->orderBy('id')->get();   // масив усіх можливих членів бригади
        $brig_e_arr = BrigadeEngineer::where('branch_id', $branch_id)->orderBy('id')->get(); // масив усіх можливих машиністів бригади
        $brigade_m = $request->input('write_to_db_brigade');
        $brigade_e = $request->input('write_to_db_engineers');
        $substation_id = $request->input('choose_substation');
        $workspecs_id = $request->input('directions');
        if ($workspecs_id == 3) {
            $substation_type_id = 2;
        } else {
            $substation_type_id = 1;
        }
        //$substation_type_id = Substation::type_id($substation_id);

        // розділяємо текст на частини: об'єкти та робота з поля введення workslist по слову  ' виконати '
        $workslist = trim($request->get('workslist'));
        $pos = strpos($workslist, ' виконати ');
        $this->naryadRecord = [
            'order_id' => $order_id,
            'branch_id' => $branch_id,
            'unit_id' => $request->input('district'),
            'warden_id' => $request->input('warden'),
            'adjuster_id' => $request->input('adjuster'),
            'brigade_m' => $brigade_m,
            'brigade_e' => $brigade_e,
            'workspecs_id' => $workspecs_id,
            'substation_id' => $substation_id,
            'substation_type_id' => $substation_type_id,
            'line_id' => $request->input('sel_line_list'),
            'objects' => substr($workslist, 0, $pos),
            'tasks' => substr($workslist, $pos + 1),
            'w_begin' => date("Y-m-d H:i", strtotime($request->input('datetime_work_begin'))),
            'w_end' => date("Y-m-d H:i", strtotime($request->input('datetime_work_end'))),
            'sep_instrs' => $sNaryadRecord['sep_instrs'],
            'order_creator' => $sNaryadRecord['order_creator'],
            'order_longer' => $sNaryadRecord['order_longer'],
            'under_voltage' => $sNaryadRecord['under_voltage'],
        ];

        // "заганяємо" зчитані змінені значенні з полів введення в масив в session
        session(['naryadRecord' => $this->naryadRecord]);

        /*
        * займемося ровсетом preparations_rs
        * набором рядочків таблиці preparations (підготовчих заходів), що мають прив`язку до номеру клонованого наряду
        */

        $count_prepr_row = 0;
        $maxIdpreparation = 0;

        if ($mode == 'reedit') {  // якщо reedit, дані берем не з бази, а з session
            $this->preparations_rs = session('preparations_rs');
            if (!empty($this->preparations_rs)) {
                $maxIdpreparation = max(array_column($this->preparations_rs, 'id'));
                $count_prepr_row = count($this->preparations_rs);
            }
        }
        if ($mode == 'clone') {
            $max_id_pr = Preparation::get_maxId($order_id);
            //session(['max_id_pr' => $max_id_pr]); // for debug
            if ($max_id_pr > 0) {
                $pr_data = Preparation::get_data($order_id);
                $this->preparations_rs = json_decode($pr_data, true);
                $maxIdpreparation = max(array_column($this->preparations_rs, 'id'));
                $count_prepr_row = count($this->preparations_rs);
                session(['preparations_rs' => $this->preparations_rs]);
            }
        }
        if ($mode == 'create') {
            $this->preparations_rs = array();
        }
        session(['preparations_rs' => $this->preparations_rs]);
        session(['mode' => $mode]);

        // потім з цієї в'юшки буде через контролер livewire.preparation визвано "асинхроний" livewire фрейм edit.f6Preparation -->
        return view('orders.editPart2', [
            'title' => '№ ' . $order_id . ' препарації',
            'mode' => $mode,
            'substations' => $this->getSubstationsList($this->naryadRecord['branch_id'], $this->naryadRecord['substation_type_id']),
            'maxIdpreparation' => $maxIdpreparation,
            'count_prepr_row' => $count_prepr_row,
            'brig_m_arr' => $brig_m_arr,
            'brig_e_arr' => $brig_e_arr,
            'preparations_rs' => $this->preparations_rs,
            'naryadRecord' => $this->naryadRecord,
        ]);
    }

    public function editpart3($order_id, Request $request)
    {
        $this->naryadRecord = session('naryadRecord');
        if ($order_id == 0) {
            $mode = 'create';
        } else {
            $mode = 'clone';
        }

        return view('orders.editPart3', [
            'title' => 'клонуємо наряд № ' . $order_id,
            'mode' => $mode,
            'naryadRecord' => $this->naryadRecord,
        ]);
    }

    public function editpart4($order_id, Request $request)
    {
        if ($order_id == 0) {
            $mode = 'create';
        } else {
            $mode = 'clone';
        }
        $this->naryadRecord = session('naryadRecord');
        $this->naryadRecord['sep_instrs'] = trim($request->get('sep_instrs_txt'));
        $this->naryadRecord['order_date'] = date("Y-m-d H:i", strtotime(trim($request->datetime_order_created)));
        $this->naryadRecord['order_longto'] = date("Y-m-d H:i", strtotime(trim($request->datetime_order_longed)));
        $this->naryadRecord['order_creator'] = trim($request->inp_order_creator);
        $this->naryadRecord['order_longer'] = trim($request->inp_order_longer);
        session(['naryadRecord' => $this->naryadRecord]);


        /* займемося ровсетом measures_rs - набором рядочків таблиці measures (підготовчих заходів), що мають прив`язку до номеру клонованого наряду  */
        $count_meas_row = 0;
        $maxIdMeasure = 0;
        $this->measures_rs = array();
        if ($mode == 'reedit') {  // якщо reedit, дані берем не з бази, а з session
            $this->measures_rs = session('measures_rs');
            if (!empty($this->measures_rs)) {
                $maxIdMeasure = max(array_column($this->measures_rs, 'id'));
                $count_meas_row = count($this->measures_rs);
            }
        }
        if ($mode == 'clone') {
            $maxIdMeasureTmp = Measure::get_maxId($order_id);
            if ($maxIdMeasureTmp > 0) {
                $meas_data = Measure::get_data($order_id);
                $this->measures_rs = json_decode($meas_data, true);
                $maxIdMeasure = max(array_column($this->measures_rs, 'id'));
                $count_meas_row = count($this->measures_rs);
            }
        }

        session(['measures_rs' => $this->measures_rs]);


        return view('orders.editPart4', [
            'title' => '№ ' . $order_id . ' підготовка2',
            'mode' => session('mode'),
            'maxIdMeasure' => $maxIdMeasure,
            'count_meas_row' => $count_meas_row,
            'naryadRecord' => $this->naryadRecord,
            'measures_rs' => $this->measures_rs
        ]);
    }

    public function editpart5($order_id, Request $request)
    {
        $this->naryadRecord = session('naryadRecord');
        return view('orders.editPart5', [
            'title' => '№ ' . $order_id . ' завершення',
            'naryadRecord' => $this->naryadRecord,
            'mode' => session('mode'),
        ]);
    }


    public function store(Request $request)
    {
        $naryad = new Order;
        $naryadStored = $request->session()->get('naryadRecord'); //$naryadStored = session('naryadRecord'); // можна і так
        $naryad->id = Order::max('id') + 1;
        $naryad->branch_id = $naryadStored['branch_id'];
        $naryad->unit_id = $naryadStored['unit_id'];
        $naryad->warden_id = $naryadStored['warden_id'];
        $naryad->adjuster_id = $naryadStored['adjuster_id'];
        $naryad->brigade_m = $naryadStored['brigade_m'];
        $naryad->brigade_e = $naryadStored['brigade_e'];
        $naryad->substation_id = $naryadStored['substation_id'];
        $naryad->ojects = $naryadStored['objects'];
        $naryad->tasks = $naryadStored['tasks'];
        $naryad->w_begin = $naryadStored['w_begin'];
        $naryad->w_end = $naryadStored['w_end'];
        $naryad->order_date = $naryadStored['order_date'];
        $naryad->order_longto = $naryadStored['order_longto'];
        $naryad->sep_instrs = $naryadStored['sep_instrs'];
        $naryad->order_creator = $naryadStored['order_creator'];
        $naryad->order_longer = $naryadStored['order_longer'];
        $naryad->works_spec_id = $naryadStored['workspecs_id'];
        $naryad->line_id = $naryadStored['line_id'];
        $naryad->under_voltage = trim($request->get('under_voltage'));
        $request->flash();
        $naryad->save();
        $preparations_rs = session('preparations_rs');
        $count_prepr_row = count($preparations_rs);
        if ($count_prepr_row > 0) {
            foreach ($preparations_rs as $prRow) {
                $preparationsDBRecord = new \App\Model\Ejournal\Preparation;
                $preparationsDBRecord->naryad_id = $naryad->id;
                $preparationsDBRecord->target_obj = $prRow['target_obj'];
                $preparationsDBRecord->body = $prRow['body'];
                $preparationsDBRecord->save;
            }
        }
        $meashures_rs = session('meashures_rs');
        $count_meashures_row = count($meashures_rs);
        if ($count_meashures_row > 0) {
            foreach ($meashures_rs as $msRow) {
                $meashuresDBRecord = new \App\Model\Ejournal\Measure();
                $meashuresDBRecord->naryad_id = $naryad->id;
                $meashuresDBRecord->licensor = $msRow['licensor'];
                $meashuresDBRecord->lic_date = $msRow['lic_date'];
                $meashuresDBRecord->save;
            }
        }
        return Redirect::to('orders')->with('success', 'Наряд додано!');
    }


    // !!! повернення до редагування 1 частини наряду
    public function reedit($order_id)
    {
        session(['mode' => 'reedit']);
        $this->preparations_rs = session('preparations_rs');
        $this->naryadRecord = session('naryadRecord');
        $branch = $this->currentUser->userBranch;
        $wardens = Warden::where('branch_id', $branch->id)->orderBy('id')->get();
        $warden = Warden::find($this->naryadRecord['warden_id']);
        $adjusters = Adjuster::where('branch_id', $branch->id)->orderBy('id')->get();
        $adjuster = Adjuster::find($this->naryadRecord['adjuster_id']);

        $brig_m_arr = BrigadeMember::where('branch_id', $branch->id)->orderBy('id')->get();   // масив усіх можливих членів бригади
        $brig_e_arr = BrigadeEngineer::where('branch_id', $branch->id)->orderBy('id')->get(); // масив усіх можливих машиністів бригади
        $this->substation_type_id = Substation::find($this->naryadRecord['substation_id'])->type_id;
        $brigade_txt = '';
        if (isset($this->naryadRecord['brigade_m'])) {
            $brigade_txt = BrigadeMember::find(explode(",", $this->naryadRecord['brigade_m']));
        }
        $engineers_txt = '';
        if (isset($this->naryadRecord['brigade_e'])) {
            $engineers_txt = BrigadeEngineer::find(explode(",", $this->naryadRecord['brigade_e']));
        }
        return view('orders.edit', [
            'mode' => 'reedit',
            'order_id' => $order_id,
            'title' => ' клон № ' . $order_id,
            'branch' => $branch,
            'unit_id' => $this->naryadRecord['unit_id'],
            'unit_txt' => Unit::find($this->naryadRecord['unit_id'])->body,
            'units' => Unit::where('branch_id', $branch->id)->orderBy('id')->get(),
            'wardens' => $wardens,
            'warden_id' => $this->naryadRecord['warden_id'],
            'warden_txt' => $warden->body . ', ' . $warden->group,
            'adjusters' => $adjusters,
            'adjuster_id' => $this->naryadRecord['adjuster_id'],
            'adjuster_txt' => $adjuster->body . ', ' . $adjuster->group,
            'brigade_m' => $this->naryadRecord['brigade_m'], // id-шники через кому
            'brig_m_arr' => $brig_m_arr,
            'brigade_e' => $this->naryadRecord['brigade_e'], // id-шники через кому
            'brig_e_arr' => $brig_e_arr,
            'brigade_txt' => $brigade_txt,
            'engineers_txt' => $engineers_txt,
            'countbrigade' => count(explode(",", $this->naryadRecord['brigade_m'])) + count(explode(",", $this->naryadRecord['brigade_m'])),
            'substation_id' => $this->naryadRecord['substation_id'],
            'substation_txt' => Substation::find($this->naryadRecord['substation_id'])->body,
            'substation_type_id' => $this->substation_type_id,
            'substations' => Substation::select('id', 'body')->where('branch_id', $this->naryadRecord['branch_id'])->where('type_id', $this->substation_type_id)->orderBy('body')->get(),
            'line_id' => $this->naryadRecord['line_id'],
            'sep_instrs' => $this->naryadRecord['sep_instrs'],
            'order_creator' => $this->naryadRecord['order_creator'],
            'order_longer' => $this->naryadRecord['order_longer'],
            'under_voltage' => $this->naryadRecord['under_voltage'],
            'workspecs' => \App\Model\Ejournal\Dicts\WorksSpec::worksSpecCollect(),
            'workspecs_id' => $this->naryadRecord['workspecs_id'],
            'workslist' => $this->naryadRecord['objects'] . ' виконати ' . $this->naryadRecord['tasks'],
            'naryadRecord' => $this->naryadRecord,
            'preparations_rs' => $this->preparations_rs,
            'measures_rs' => $this->measures_rs
        ]);
    }

    // !!! повернення до редагування 2 частини наряду
    public function reedit2($order_id, Request $request)
    {
        session(['mode' => 'reedit']);
        session(['measures_rs' => $this->measures_rs]);
        $this->preparations_rs = session('preparations_rs');

        if (empty($this->preparations_rs)) {
            $count_prepr_row = 0;
            $maxIdpreparation = 0;
        } else {
            $maxIdpreparation = max(array_column($this->preparations_rs, 'id'));
            $count_prepr_row = count($this->preparations_rs);
        }

        $this->naryadRecord = session('naryadRecord');

        $this->naryadRecord['sep_instrs'] = trim($request->get('sep_instrs_txt'));
        $this->naryadRecord['order_date'] = date("Y-m-d H:i", strtotime(trim($request->datetime_order_created)));
        $this->naryadRecord['order_creator'] = trim($request->inp_order_creator);
        $this->naryadRecord['order_longto'] = date("Y-m-d H:i", strtotime(trim($request->datetime_order_longed)));
        $this->naryadRecord['order_longer'] = trim($request->inp_order_longer);
        $this->naryadRecord['under_voltage'] = trim($request->get('under_voltage'));

        session()->forget('naryadRecord');
        session(['naryadRecord' => $this->naryadRecord]);

        return view('orders.editPart2', [
            'mode' => 'reedit',
            'title' => ' клон № ' . $order_id,
            'branch_id' => $this->naryadRecord['branch_id'],
            'substation_id' => $this->naryadRecord['substation_id'],
            'substation_txt' => Substation::find($this->naryadRecord['substation_id'])->body,
            'substation_type_id' => $this->naryadRecord['substation_type_id'],
            'substations' => Substation::select('id', 'body')->where('branch_id', $this->naryadRecord['branch_id'])->where('type_id', $this->naryadRecord['substation_type_id'])->orderBy('body')->get(),
            'count_prepr_row' => $count_prepr_row,
            'maxIdpreparation' => $maxIdpreparation,
            'preparations_rs' => $this->preparations_rs,
            'naryadRecord' => $this->naryadRecord
        ]);
    }

    // !!! повернення до редагування 3 частини наряду
    public function reedit3($order_id, Request $request)
    {
        // session(['measures_rs'  => $this->measures_rs]);
        session(['mode' => 'reedit']);
        return view('orders.editPart3', [
            'mode' => 'reedit',
            'title' => ' клон № ' . $order_id,
            'naryadRecord' => session('naryadRecord')
        ]);
    }

// !!! повернення до редагування 4 частини наряду
    public function reedit4($order_id, Request $request)
    {
        // session(['measures_rs'  => $this->measures_rs]);
        session(['mode' => 'reedit']);
        $count_meas_row = 0;
        $maxIdMeasure = 0;
        $this->measures_rs = session('measures_rs');
        if (!empty($this->measures_rs)) {
            $maxIdMeasure = max(array_column($this->measures_rs, 'id'));
            $count_meas_row = count($this->measures_rs);
        }

        $this->naryadRecord = session('naryadRecord');
        $this->naryadRecord['under_voltage'] = trim($request->get('under_voltage'));
        session(['naryadRecord' => $this->naryadRecord]);

        return view('orders.editPart4', [
            'mode' => 'reedit',
            'title' => ' клон № ' . $order_id,
            'measures_rs' => $this->measures_rs,
            'maxIdMeasure' => $maxIdMeasure,
            'count_meas_row' => $count_meas_row,
            'naryadRecord' => session('naryadRecord')
        ]);
    }


    /**
     * !! Remove the specified resource from storage.
     *
     * @param \App\Model\Ejournal\Order $naryad
     * @return \Illuminate\Http\Response
     */
    public function destroy($order_id)
    {
        //        Обережно! бо працює харашо
//        $record = Order::find($order_id);
//        $record->delete();
    }

    public function pdf(Order $naryad)
    {
        $branch = $this->currentUser->userBranch;
        $nom_naryad = '№ ' . $naryad->id;
        $unit_txt = Unit::find($naryad->unit_id)->body;
        $warden_txt = Warden::find($naryad->warden_id)->body . ', ' . Warden::find($naryad->warden_id)->group;
        $adjuster_txt = Adjuster::find($naryad->adjuster_id)->body . ', ' . Adjuster::find($naryad->adjuster_id)->group;
        $brigade_txt = '';
        if (isset($this->naryadRecord['brig_m_ch'])) {
            $brigade_txt = BrigadeMember::find(explode(",", $this->naryadRecord['brig_m_ch']));
        }
        $engineers_txt = '';
        if (isset($this->naryadRecord['brig_e_ch'])) {
            $engineers_txt = BrigadeEngineer::find(explode(",", $this->naryadRecord['brig_e_ch']));
        }

        $brigade_txt = BrigadeMember::find(explode(",", $naryad->brigade_m));
        $engineers_txt = BrigadeEngineer::find(explode(",", $naryad->brigade_e));

        $substation_txt = Substation::find($naryad->substation_id)->body;
        $substation_type_id = Substation::find($naryad->substation_id)->type_id;
        $substation_type = StationType::find($substation_type_id)->body;
        $preparations = Preparation::get_data($naryad->id);
        $measures = Measure::get_data($naryad->id);
        /*PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);*/

        /* $pdf = Facade::loadView('orders.pdf', [ */
        $pdf = \Barryvdh\DomPDF\Facade::loadView('orders.pdf', [
            'naryad' => $naryad,
            'mode' => 'pdf',
            'nom_naryad' => $nom_naryad,
            'unit_txt' => $unit_txt,
            'warden_txt' => $warden_txt,
            'adjuster_txt' => $adjuster_txt,
            'brigade_txt' => $brigade_txt,
            'engineers_txt' => $engineers_txt,
            'substation_txt' => $substation_txt,
            'substation_type_id' => $substation_type_id,
            'substation_type' => $substation_type,
            'branch_name' => $branch->body,
            'preparations' => $preparations,
            'measures' => $measures
        ])->setPaper('a4', 'landscape')->setWarnings(false);

        // $responce = $pdf->download('Order.pdf');

        return $pdf->stream('Order.pdf');
        // return $pdf->download  ('Order.pdf');
        //$this->assertInstanceOf(Response::class, $response);
        //$this->assertNotEmpty($response->getContent());
        //$this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        //$this->assertEquals('attachment; filename="Order.pdf"', $response->headers->get('Content-Disposition'));

    }

    private function getSubstationsList(int $branchId, int $substationTypeId)
    {
        $substationFitcher = new SubstationFetcher($branchId, $substationTypeId);
        return $substationFitcher->getSubstationsList();
    }
}
