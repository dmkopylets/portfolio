<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal;

use App\Http\Controllers\Ejournal\BaseController as BaseController;
use App\Http\Controllers\Ejournal\Edit\EditRepository;
use App\Model\Ejournal\Dicts\Substation;
use App\Model\Ejournal\Dicts\Warden;
use App\Model\Ejournal\Dicts\WorksSpec;
use App\Model\Ejournal\Order;
use App\Model\Ejournal\OrderRecordDTO;
use App\Model\User\Entity\BranchInfo;
use App\Model\User\Entity\UserRepository;
use Illuminate\Http\Request;
use PDF;
use Redirect;

class EjournalController extends BaseController
{
    public UserRepository $userRepository;
    public EditRepository $editRepository;
    protected BranchInfo $branch;
    protected string $mode;
    protected array $preparations = [];
    protected array $measures = [];
    private OrderRecordDTO $orderRecord;
    private CreateOrder $createOrder;
    private Edit\EditPart1Controller $editPart1;
    private Edit\EditPart2Controller $editPart2;
    private Edit\EditPart3Controller $editPart3;
    private Edit\EditPart4Controller $editPart4;
    private Edit\EditPart5Controller $editPart5;
    private Edit\StoreOrder $storeOrder;
    private Edit\ReeditPart1 $reeditPart1;

    public function __construct()
    {
        parent::__construct();
        $this->branch = $this->currentUser->userBranch;
        $this->editRepository = new EditRepository();
        $this->createOrder = new CreateOrder($this->editRepository, $this->branch, $this);
        $this->editPart1 = new Edit\EditPart1Controller($this->editRepository, $this->branch);
        $this->editPart2 = new Edit\EditPart2Controller($this->editRepository, $this->branch);
        $this->editPart3 = new Edit\EditPart3Controller($this->editRepository, $this->branch, $this);
        $this->editPart4 = new Edit\EditPart4Controller($this->editRepository, $this->branch, $this);
        $this->editPart5 = new Edit\EditPart5Controller($this->editRepository, $this->branch, $this);
        $this->storeOrder = new Edit\StoreOrder($this->editRepository, $this->branch, $this);
        $this->reeditPart1 = new Edit\ReeditPart1($this->editRepository, $this->branch, $this);
    }

    public function welcome()
    {
        session()->forget('orderRecord');
        session()->forget('preparations');
        session()->forget('measures');
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
        $records = $this->editRepository->fetchOrdersList($substationListId, $wardenListId);

        // чистимо Session
        session()->forget('orderRecord');
        session()->forget('preparations');
        session()->forget('measures');
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
     *    створюєно НОВИЙ наряд
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request): \Illuminate\Http\Response
    {
        return $this->createOrder->create($request);
    }

    public function clone(int $orderId): \Illuminate\View\View
    {
        $mode = 'clone';
        $orderFinded = Order::find($orderId);
        $orderRecord = $this->editRepository->readOrderFromDB($orderFinded);
        $this->editRepository->setOrderRecord($orderRecord);
        $this->editRepository->setMode('clone');
        return $this->editPart1->editpart1($orderRecord, $mode);
    }

    public function editpart1(OrderRecordDTO $orderRecord, string $mode)
    {
       return $this->editPart1->editpart1($orderRecord, $mode);
    }

    public function editpart2(Request $request)
    {
        $mode = $this->editRepository->getMode();
        return $this->editPart2->editpart2($this->editRepository->getOrderRecord(), $mode, $request);
    }

    public function editpart3(Request $request)
    {
        return $this->editPart3->editpart3($this->editRepository->getOrderRecord(), $request);
    }

    public function editpart4(Request $request)
    {
        return $this->editPart4->editpart4($this->editRepository->getOrderRecord(), $request);
    }

    public function editpart5(Request $request)
    {
        return $this->editPart5->editpart5($this->editRepository->getOrderRecord(), $request);
    }


    public function store(Request $request)
    {
        return $this->storeOrder->store($this->editRepository->getOrderRecord(), $request);
    }


    // !!! повернення до редагування 1 частини наряду
    public function reedit($orderId)
    {
        return $this->reeditPart1->reeditPart1($this->editRepository->getOrderRecord());
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
