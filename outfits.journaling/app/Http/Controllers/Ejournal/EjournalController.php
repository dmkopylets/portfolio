<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal;

use App\Http\Controllers\Ejournal\BaseController as BaseController;
use App\Http\Controllers\Ejournal\Edit\EditRepository;
use App\Model\Ejournal\Dicts\Adjuster;
use App\Model\Ejournal\Dicts\BrigadeEngineer;
use App\Model\Ejournal\Dicts\BrigadeMember;
use App\Model\Ejournal\Dicts\Substation;
use App\Model\Ejournal\Dicts\TypicalTask;
use App\Model\Ejournal\Dicts\Unit;
use App\Model\Ejournal\Dicts\Warden;
use App\Model\Ejournal\Dicts\WorksSpec;
use App\Model\Ejournal\Measure;
use App\Model\Ejournal\Order;
use App\Model\User\Entity\BranchInfo;
use App\Model\User\Entity\UserRepository;
use Illuminate\Http\Request;
use PDF;
use Redirect;

class EjournalController extends BaseController
{

    protected BranchInfo $branch;
    //private Edit\EditPart1Controller $editPart1Controller;
    //   private Edit\EditPart2Controller $editPart2Controller;

    public function __construct(public UserRepository $userRepository, public EditRepository $myRepo)
    {
        parent::__construct($userRepository);
        $this->branch = $this->currentUser->userBranch;
        // $this->editPart1Controller = new Edit\EditPart1Controller($this->myRepo, $this->branch);
        // $this->editPart2Controller = new EditPart2Controller($this);
    }

    public function welcome()
    {
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

        if ($this->branch->id == 0) {   // if user from main office (nobranch)
            $wardenListId = Warden::where('body', 'like', $searchWarden)->pluck('id');
            $substationListId = Substation::where('body', 'like', $searchTerm)->pluck('id');
        } else {
            $wardenListId = Warden::where('body', 'like', $searchWarden)->where('branch_id', $this->branch->id)->pluck('id');
            $substationListId = Substation::where('body', 'like', $searchTerm)->where('branch_id', $this->branch->id)->pluck('id');
        }
        $records = $this->myRepo->fetchOrdersList($substationListId, $wardenListId);

        // чистимо Session
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
            'workspecs' => WorksSpec::getWorksSpecs(),
            'workspecs_id' => 0,
        ]);
    }

    /**
     *   !! створюєно НОВИЙ наряд
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->mode = 'create';
        $branch = $this->currentUser->userBranch;
        $this->orderRecord = new Order();
        $tasks = TypicalTask::orderBy('id')->get();
        $units = Unit::where('branch_id', $branch->id)->orderBy('id')->get();
        $wardens = Warden::where('branch_id', $branch->id)->orderBy('id')->get();
        $adjusters = Adjuster::where('branch_id', $branch->id)->orderBy('id')->get();
        $brig_m_arr = BrigadeMember::where('branch_id', $branch->id)->orderBy('id')->get();
        $brig_e_arr = BrigadeEngineer::where('branch_id', $branch->id)->orderBy('id')->get();
        $worksSpecsId = $request->input('direction'); // визначена позиція списка - де робитиметься :
        // тільки для "10-ток" буде зміна типу підстанцій (і тому й переліку в dict_substations), а так "завжди =0,4"
        if ($worksSpecsId == 3) {
            $substation_type_id = 2;
        } else {
            $substation_type_id = 1;
        }

        $substantions = $this->getSubstationsList($branch->id, $substation_type_id);

        $this->preparations_rs = array();
        $this->measures_rs = array();

        $this->orderRecord->branch_id = $branch->id;
        $this->orderRecord->unit_id = 1;
        $this->orderRecord->warden_id = 0;
        $this->orderRecord->adjuster_id = 0;
        $this->orderRecord->brigade_m = '';
        $this->orderRecord->brigade_e = '';
        $this->orderRecord->substation_id = 1;
        $this->orderRecord->ojects = '';
        $this->orderRecord->w_begin = '';
        $this->orderRecord->w_end = '';
        $this->orderRecord->sep_instrs = '';
        $this->orderRecord->order_date = '';
        $this->orderRecord->order_creator = '';
        $this->orderRecord->order_longto = '';
        $this->orderRecord->order_longer = '';
        $this->orderRecord->works_spec_id = $worksSpecsId;
        $this->orderRecord->line_id = 0;
        $this->orderRecord->under_voltage = '';


        return view('orders.edit.editPart1', [
            'orderRecord' => $this->orderRecord,
            'mode' => $this->mode,
            'title' => 'новий',
            'tasks' => $tasks,
            'units' => $units,
            'wardens' => $wardens,
            'adjusters' => $adjusters,
            'brig_m_arr' => $brig_m_arr,
            'brig_e_arr' => $brig_e_arr,
            'branch' => $branch,
            'substations' => $substantions,
            'workspecs' => WorksSpec::getWorksSpecs(), // список - де робитиметься : на 10-ках, чи на 0.4, чи ...
            'workslist' => ' виконати ', // саме текст завдання
            'preparations_rs' => $this->preparations_rs,
            'measures_rs' => $this->measures_rs,
            'maxIdpreparation' => 0,
            'maxIdmeasure' => 0,
        ]);
    }

    public function editpart1(int $orderId)
    {
        return $this->editPart1Controller->editpart1($orderId, $this->branch);
    }
//
//    public function editpart2($orderId, Request $request)
//        /**********************************************************
//         * !! через $request-> витягуємо даніх попередньої в'юшки (orders.editPar1)
//         * !! і цією частиною доповнюємо  новостворений наряд в масиві session
//         * !! і передаємо у наступну форму введення
//         * @param \Illuminate\Http\Request $request
//         * @return \Illuminate\Http\Response
//         */
//    {
//        return $this->editPart2Controller->editpart2($orderId, $request);
//    }

    public function editpart3($orderId, Request $request)
    {
        $this->orderRecord = session('orderRecord');
        if ($orderId == 0) {
            $mode = 'create';
        } else {
            $mode = 'clone';
        }

        return view('orders.editPart3', [
            'title' => 'клонуємо наряд № ' . $orderId,
            'mode' => $mode,
            'orderRecord' => $this->orderRecord,
        ]);
    }

    public function editpart4($orderId, Request $request)
    {
        if ($orderId == 0) {
            $mode = 'create';
        } else {
            $mode = 'clone';
        }
        $this->orderRecord = session('orderRecord');
        $this->orderRecord['sep_instrs'] = trim($request->get('sep_instrs_txt'));
        $this->orderRecord['order_date'] = date("Y-m-d H:i", strtotime(trim($request->datetime_order_created)));
        $this->orderRecord['order_longto'] = date("Y-m-d H:i", strtotime(trim($request->datetime_order_longed)));
        $this->orderRecord['order_creator'] = trim($request->inp_order_creator);
        $this->orderRecord['order_longer'] = trim($request->inp_order_longer);
        session(['orderRecord' => $this->orderRecord]);


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
            $maxIdMeasureTmp = Measure::get_maxId($orderId);
            if ($maxIdMeasureTmp > 0) {
                $meas_data = Measure::get_data($orderId);
                $this->measures_rs = json_decode($meas_data, true);
                $maxIdMeasure = max(array_column($this->measures_rs, 'id'));
                $count_meas_row = count($this->measures_rs);
            }
        }

        session(['measures_rs' => $this->measures_rs]);

        return view('orders.editPart4', [
            'title' => '№ ' . $orderId . ' підготовка2',
            'mode' => session('mode'),
            'maxIdMeasure' => $maxIdMeasure,
            'count_meas_row' => $count_meas_row,
            'orderRecord' => $this->orderRecord,
            'measures_rs' => $this->measures_rs
        ]);
    }

    public function editpart5($orderId, Request $request)
    {
        $this->orderRecord = session('orderRecord');
        return view('orders.editPart5', [
            'title' => '№ ' . $orderId . ' завершення',
            'orderRecord' => $this->orderRecord,
            'mode' => session('mode'),
        ]);
    }


    public function store(Request $request)
    {
        //$orderStored = $request->session()->get('orderRecord'); //$orderStored = session('orderRecord'); // можна і так
        $this->orderRecord->id = Order::max('id') + 1;
//        $this->order->branch_id = $orderStored['branch_id'];
//        $this->order->unit_id = $orderStored['unit_id'];
//        $this->order->warden_id = $orderStored['warden_id'];
//        $this->order->adjuster_id = $orderStored['adjuster_id'];
//        $this->order->brigade_m = $orderStored['brigade_m'];
//        $this->order->brigade_e = $orderStored['brigade_e'];
//        $this->order->substation_id = $orderStored['substation_id'];
//        $this->order->ojects = $orderStored['objects'];
//        $this->order->tasks = $orderStored['tasks'];
//        $this->order->w_begin = $orderStored['w_begin'];
//        $this->order->w_end = $orderStored['w_end'];
//        $this->order->order_date = $orderStored['order_date'];
//        $this->order->order_longto = $orderStored['order_longto'];
//        $this->order->sep_instrs = $orderStored['sep_instrs'];
//        $this->order->order_creator = $orderStored['order_creator'];
//        $this->order->order_longer = $orderStored['order_longer'];
//        $this->order->works_spec_id = $orderStored['workspecs_id'];
//        $this->order->line_id = $orderStored['line_id'];
        $this->orderRecord->under_voltage = trim($request->get('under_voltage_txt'));
        $request->flash();
        $this->orderRecord->save();
        $preparations_rs = session('preparations_rs');
        $count_prepr_row = count($preparations_rs);
        if ($count_prepr_row > 0) {
            foreach ($preparations_rs as $prRow) {
                $preparationsDBRecord = new \App\Model\Ejournal\Preparation;
                $preparationsDBRecord->naryad_id = $this->order->id;
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
                $meashuresDBRecord->naryad_id = $this->order->id;
                $meashuresDBRecord->licensor = $msRow['licensor'];
                $meashuresDBRecord->lic_date = $msRow['lic_date'];
                $meashuresDBRecord->save;
            }
        }
        return Redirect::to('orders')->with('success', 'Наряд додано!');
    }


    // !!! повернення до редагування 1 частини наряду
    public function reedit($orderId)
    {
        session(['mode' => 'reedit']);
        $this->preparations_rs = session('preparations_rs');
        $this->orderRecord = session('orderRecord');
        $branch = $this->currentUser->userBranch;
        $wardens = Warden::where('branch_id', $branch->id)->orderBy('id')->get();
        $warden = Warden::find($this->orderRecord['warden_id']);
        $adjusters = Adjuster::where('branch_id', $branch->id)->orderBy('id')->get();
        $adjuster = Adjuster::find($this->orderRecord['adjuster_id']);

        $brig_m_arr = BrigadeMember::where('branch_id', $branch->id)->orderBy('id')->get();   // масив усіх можливих членів бригади
        $brig_e_arr = BrigadeEngineer::where('branch_id', $branch->id)->orderBy('id')->get(); // масив усіх можливих машиністів бригади
        $this->substation_type_id = Substation::find($this->orderRecord['substation_id'])->type_id;
        $brigadeText = '';
        if (isset($this->orderRecord['brigade_m'])) {
            $brigadeText = BrigadeMember::find(explode(",", $this->orderRecord['brigade_m']));
        }
        $engineersText = '';
        if (isset($this->orderRecord['brigade_e'])) {
            $engineersText = BrigadeEngineer::find(explode(",", $this->orderRecord['brigade_e']));
        }
        return view('orders.edit', [
            'mode' => 'reedit',
            'order_id' => $orderId,
            'title' => ' клон № ' . $orderId,
            'branch' => $branch,
            'unit_id' => $this->orderRecord['unit_id'],
            'unit_txt' => Unit::find($this->orderRecord['unit_id'])->body,
            'units' => Unit::where('branch_id', $branch->id)->orderBy('id')->get(),
            'wardens' => $wardens,
            'warden_id' => $this->orderRecord['warden_id'],
            'warden_txt' => $warden->body . ', ' . $warden->group,
            'adjusters' => $adjusters,
            'adjuster_id' => $this->orderRecord['adjuster_id'],
            'adjuster_txt' => $adjuster->body . ', ' . $adjuster->group,
            'brigade_m' => $this->orderRecord['brigade_m'], // id-шники через кому
            'brig_m_arr' => $brig_m_arr,
            'brigade_e' => $this->orderRecord['brigade_e'], // id-шники через кому
            'brig_e_arr' => $brig_e_arr,
            'brigade_txt' => $brigadeText,
            'engineers_txt' => $engineersText,
            'countbrigade' => count(explode(",", $this->orderRecord['brigade_m'])) + count(explode(",", $this->orderRecord['brigade_m'])),
            'substation_id' => $this->orderRecord['substation_id'],
            'substation_txt' => Substation::find($this->orderRecord['substation_id'])->body,
            'substation_type_id' => $this->substation_type_id,
            'substations' => Substation::select('id', 'body')->where('branch_id', $this->orderRecord['branch_id'])->where('type_id', $this->substation_type_id)->orderBy('body')->get(),
            'line_id' => $this->orderRecord['line_id'],
            'sep_instrs' => $this->orderRecord['sep_instrs'],
            'order_creator' => $this->orderRecord['order_creator'],
            'order_longer' => $this->orderRecord['order_longer'],
            'under_voltage' => $this->orderRecord['under_voltage'],
            'workspecs' => \App\Model\Ejournal\Dicts\WorksSpec::worksSpecCollect(),
            'workspecs_id' => $this->orderRecord['workspecs_id'],
            'workslist' => $this->orderRecord['objects'] . ' виконати ' . $this->orderRecord['tasks'],
            'orderRecord' => $this->orderRecord,
            'preparations_rs' => $this->preparations_rs,
            'measures_rs' => $this->measures_rs
        ]);
    }

    // !!! повернення до редагування 2 частини наряду
    public function reedit2($orderId, Request $request)
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

        $this->orderRecord = session('orderRecord');

        $this->orderRecord['sep_instrs'] = trim($request->get('sep_instrs_txt'));
        $this->orderRecord['order_date'] = date("Y-m-d H:i", strtotime(trim($request->datetime_order_created)));
        $this->orderRecord['order_creator'] = trim($request->inp_order_creator);
        $this->orderRecord['order_longto'] = date("Y-m-d H:i", strtotime(trim($request->datetime_order_longed)));
        $this->orderRecord['order_longer'] = trim($request->inp_order_longer);
        $this->orderRecord['under_voltage'] = trim($request->get('under_voltage'));

        session()->forget('orderRecord');
        session(['orderRecord' => $this->orderRecord]);

        return view('orders.editPart2', [
            'mode' => 'reedit',
            'title' => ' клон № ' . $orderId,
            'branch_id' => $this->orderRecord['branch_id'],
            'substation_id' => $this->orderRecord['substation_id'],
            'substation_txt' => Substation::find($this->orderRecord['substation_id'])->body,
            'substation_type_id' => $this->orderRecord['substation_type_id'],
            'substations' => Substation::select('id', 'body')->where('branch_id', $this->orderRecord['branch_id'])->where('type_id', $this->orderRecord['substation_type_id'])->orderBy('body')->get(),
            'count_prepr_row' => $count_prepr_row,
            'maxIdpreparation' => $maxIdpreparation,
            'preparations_rs' => $this->preparations_rs,
            'orderRecord' => $this->orderRecord
        ]);
    }

    // !!! повернення до редагування 3 частини наряду
    public function reedit3($orderId, Request $request)
    {
        // session(['measures_rs'  => $this->measures_rs]);
        session(['mode' => 'reedit']);
        return view('orders.editPart3', [
            'mode' => 'reedit',
            'title' => ' клон № ' . $orderId,
            'orderRecord' => session('orderRecord')
        ]);
    }

// !!! повернення до редагування 4 частини наряду
    public function reedit4($orderId, Request $request)
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

        $this->orderRecord = session('orderRecord');
        $this->orderRecord['under_voltage'] = trim($request->get('under_voltage'));
        session(['orderRecord' => $this->orderRecord]);

        return view('orders.editPart4', [
            'mode' => 'reedit',
            'title' => ' клон № ' . $orderId,
            'measures_rs' => $this->measures_rs,
            'maxIdMeasure' => $maxIdMeasure,
            'count_meas_row' => $count_meas_row,
            'orderRecord' => session('orderRecord')
        ]);
    }


    /**
     * !! Remove the specified resource from storage.
     *
     * @param \App\Model\Ejournal\Order $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($orderId)
    {
//        Обережно! бо працює харашо
//        Але ми не видаляємо нічого з журналу
//        $record = Order::find($orderId);
//        $record->delete();
    }
}
