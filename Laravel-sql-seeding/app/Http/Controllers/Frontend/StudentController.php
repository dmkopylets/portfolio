<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentGetRequest;
use App\Models\Courses\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use Session;
use Redirect;

class StudentController extends Controller
{
    public function __construct(Student $model)
    {
        $this->model = $model;
    }

    public function index(Request $request): View
    {
        $searchMyFirstName = '%'.$request->input('searchMy_first_name').'%';
        $searchMyLastName = '%'.$request->input('searchMy_last_name').'%';
        return view('course.student.index', [
            'records' => $this->model->getList($searchMyFirstName, $searchMyLastName),
            'add_th' => array('first_name', 'last_name'),
            'add_td' => array('first_name', 'last_name', 'course'),
            'th_width' => array(70, 100, 170)
        ]);
    }

    public function create(): View
    {
        return view('course.student.create', [
            'add_th' => array('first name', 'last name'),
            'add_td' => array('first_name', 'last_name'),
            'th_width' => array(100, 100, 70)
        ]);
    }

    public function store(StudentGetRequest $request): RedirectResponse
    {
        $record = new $this->model;
        $record->first_name = $request->input('first_name');
        $record->last_name = $request->input('last_name');
        $record->save();
        Session::flash('message', 'Record added successfully!');
        return Redirect::to('students');
    }

    public function edit(int $id): View
    {
        $record = $this->model::find($id);
        return view('course.student.edit',
            [
                'record' => $record,
                'modelName' => 'Student',
                'add_th' => array('firstName', 'LastName','courses'),
                'add_td' => array('first_name', 'last_name', $this->model->getCoursesList($id)),
            ]
        );
    }

    public function update(Request $request, int $id)
    {
        $record = $this->model::find($id);
        $record->first_name = $request->input('first_name');
        $record->last_name = $request->input('last_name');
        $record->save();
        Session::flash('message', 'Record # '.$id.' changed successfully!');
        return Redirect::to('students');
    }

    public function destroy(int $id): RedirectResponse
    {
        $record = $this->model::find($id);
        $name = $record->first_name . ' ' . $record->last_name;
        $record->delete();
        Session::flash('message', 'Successfully deleted student ' . $name);
        return Redirect::route('students.index');
    }
}
