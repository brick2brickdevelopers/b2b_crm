<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Company;
use App\DidManagment;
use App\DidNumber;
use App\Helper\Reply;
use Illuminate\Http\Request;
use DataTables;
use Yajra\DataTables\Html\Builder;
//todo
class DidManagmentController extends SuperAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Did Managment';
        $this->pageIcon = 'icon-settings';
    }
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            return DataTables::of(DidManagment::query())
                ->editColumn('company_id', function ($data) {
                    return $data->company->company_name;
                })

                ->addColumn('action', function ($data) {
                    $btn = "<div class='btn-group dropdown m-r-10'>
                 <button onclick='editData(" . json_encode($data) . ")' data-toggle='modal' data-target='#editSIP' class='btn btn-default dropdown-toggle waves-effect waves-light'><i class='fa fa-pencil'></i></button>
                </div>";
                    $btn .= "<div class='btn-group dropdown m-r-10'>
                 <a href=" . route('super-admin.did-managment.destroy', $data->id) . " class='btn btn-danger dropdown-toggle waves-effect waves-light'><i class='fa fa-trash'></i></a>
                </div>";
                    return $btn;
                })
                ->editColumn('didnumber', function($data){
                $num = implode(', ', json_decode($data->didnumber));
                    return  $num;
                })
                ->rawColumns(['action'])
                ->toJson();
        }
        $this->html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'Id'],
            ['data' => 'company_id', 'name' => 'company_id', 'title' => 'Company'],
            ['data' => 'didnumber', 'name' => 'didnumber', 'title' => 'Number'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Action'],
        ]);
        $d =DidNumber::whereNotNull('company_id')->pluck('company_id');
        $this->company = Company::whereNotIn('id', array_unique($d->toArray()))->get();
        $this->didNumbers = DidNumber::whereNull('company_id')->get();
        return view('super-admin.did-managment.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //        = json_decode($this->didnumbe);


        $did_management = DidManagment::findOrNew($request->id);
        $did_management->company_id = $request->company_id;
        $did_management->didnumber = json_encode($request->didnumber);
        foreach ($request->didnumber as $value) {
            $didNumber = DidNumber::where('number', $value)->first();
            $didNumber->company_id = $request->company_id;
            $didNumber->save();
        }
        $did_management->save();


        return Reply::redirect(route('super-admin.did-managment.index'), 'Did updated successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
