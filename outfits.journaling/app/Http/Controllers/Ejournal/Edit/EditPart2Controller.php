<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal\Edit;

use App\Http\Controllers\Ejournal\BaseController;
use App\Model\Ejournal\Dicts\BrigadeEngineer;
use App\Model\Ejournal\Dicts\BrigadeMember;
use App\Model\Ejournal\OrderRecordDTO;
use App\Model\Ejournal\Preparation;
use App\Model\User\Entity\BranchInfo;
use App\Model\User\Entity\UserRepository;
use Illuminate\Http\Request;

class EditPart2Controller extends BaseController
{

    private OrderRecordDTO $orderRecord;
    protected BranchInfo $branch;

    public function __construct(public UserRepository $userRepository, private EditRepository $repo)
    {
        parent::__construct($userRepository);
        $this->branch = $this->currentUser->userBranch;
        $this->repo = $repo;
    }

    public function editpart2(int $orderId, Request $request)
        /**********************************************************
         * !! через $request-> витягуємо даніх попередньої в'юшки (orders.editPar1)
         * !! і цією частиною доповнюємо  новостворений наряд в масиві session
         * !! і передаємо у наступну форму введення
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         */
    {
        if ($orderId == 0) {
            $mode = 'create';
        } else {
            $mode = 'clone';
        }

        $orderRecord = $this->orderRecord;
        var_dump($orderRecord);
        die();
        //->getOrderRecord();
        $branch = $this->branch;
        $brig_m_arr = BrigadeMember::where('branch_id', $branch->id)->orderBy('id')->get();   // масив усіх можливих членів бригади
        $brig_e_arr = BrigadeEngineer::where('branch_id', $branch->id)->orderBy('id')->get(); // масив усіх можливих машиністів бригади
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

            $orderRecord->unit_id = $request->input('district');
            $orderRecord->wardenId = $request->input('warden');
            $orderRecord->adjusterId = $request->input('adjuster');
//            'brigade_m' => $brigade_m,
//            'brigade_e' => $brigade_e,
//            'workspecs_id' => $workspecs_id,
//            'substation_id' => $substation_id,
//            'substation_type_id' => $substation_type_id,
//            'line_id' => $request->input('sel_line_list'),
//            'objects' => substr($workslist, 0, $pos),
//            'tasks' => substr($workslist, $pos + 1),
//            'w_begin' => date("Y-m-d H:i", strtotime($request->input('datetime_work_begin'))),
//            'w_end' => date("Y-m-d H:i", strtotime($request->input('datetime_work_end'))),
//            'sep_instrs' => $this->orderRecordsep_instrs'],
//            'order_creator' => $this->orderRecordorder_creator'],
//            'order_longer' => $this->orderRecordorder_longer'],
//            'under_voltage' => $this->orderRecordunder_voltage'],
//        ];

        // "заганяємо" зчитані змінені значенні з полів введення в масив в session
        //      session(['orderRecord' => $this->orderRecord]);

        /*
        * займемося ровсетом preparations_rs
        * набором рядочків таблиці preparations (підготовчих заходів), що мають прив`язку до номеру клонованого наряду
        */

        $count_prepr_row = 0;
        $maxIdpreparation = 0;

        if ($mode == 'reedit') {  // якщо reedit, дані берем не з бази, а з session
            $this->setPreparationsRs(session('preparations_rs'));
            if (!empty($this->getPreparationsRs())) {
                $maxIdpreparation = max(array_column($this->getPreparationsRs(), 'id'));
                $count_prepr_row = count($this->getPreparationsRs());
            }
        }
        if ($mode == 'clone') {
            $max_id_pr = Preparation::get_maxId($orderId);
            //session(['max_id_pr' => $max_id_pr]); // for debug
            if ($max_id_pr > 0) {
                $pr_data = Preparation::get_data($orderId);
                $this->setPreparationsRs(json_decode($pr_data, true));
                $maxIdpreparation = max(array_column($this->getPreparationsRs(), 'id'));
                $count_prepr_row = count($this->getPreparationsRs());
                session(['preparations_rs' => $this->getPreparationsRs()]);
            }
        }
        if ($mode == 'create') {
            $this->setPreparationsRs([]);
        }

        session(['mode' => $mode]);

        // потім з цієї в'юшки буде через контролер livewire.preparation визвано "асинхроний" livewire фрейм edit.f6Preparation -->
        return view('orders.editPart2', [
            'title' => '№ ' . $orderId . ' препарації',
            'mode' => $mode,
            'substations' => $this->getSubstationsList($branch->id, $this->getOrderRecord()['substation_type_id']),
            'maxIdpreparation' => $maxIdpreparation,
            'count_prepr_row' => $count_prepr_row,
            'brig_m_arr' => $brig_m_arr,
            'brig_e_arr' => $brig_e_arr,
            'preparations_rs' => $this->getPreparationsRs(),
            'orderRecord' => $this->getOrderRecord(),
        ]);
    }
}