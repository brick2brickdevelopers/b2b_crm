<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Company;
use App\SipGateway;
use Illuminate\Http\Request;
use App\Helper\Reply;
use DataTables;
use Yajra\DataTables\Html\Builder;

class SipController extends SuperAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'SIP Gateway';
        $this->pageIcon = 'icon-settings';
    }
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            return DataTables::of(SipGateway::query())
                ->editColumn('company_id', function ($data) {
                    return $data->company->company_name;
                })
                ->editColumn('type', function ($data) {
                    return $data->type == 1 ? "SIP" : "SARV";
                })
                ->addColumn('action', function ($data) {
                    $btn = "<div class='btn-group dropdown m-r-10'>
                 <button onclick='editData(". json_encode($data).")' data-toggle='modal' data-target='#editSIP' class='btn btn-default dropdown-toggle waves-effect waves-light'><i class='fa fa-pencil'></i></button>
                </div>";
                    return $btn;
                })

                ->rawColumns(['action'])
                ->toJson();
        }
        $this->html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'Id'],
            ['data' => 'company_id', 'name' => 'company_id', 'title' => 'Company'],
            ['data' => 'type', 'name' => 'type', 'title' => 'Server Type'],
            ['data' => 'caller_id', 'name' => 'caller_id', 'title' => 'Caller ID'],
            ['data' => 'key', 'name' => 'key', 'title' => 'Key'],
            ['data' => 'token', 'name' => 'token', 'title' => 'Token'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Action'],
        ]);


        $this->company = Company::all();
        return view('super-admin.sipgateway.index', $this->data);
    }

    public function store(Request $request)
    {
        $sip = SipGateway::findOrNew($request->id);
        $sip->company_id = $request->company_id;
        $sip->type = $request->type;
        $sip->caller_id = $request->caller_id;
        $sip->endpoint = $request->endpoint;
        $sip->key = $request->user;
        $sip->token = $request->token;
        $sip->save();
        return Reply::redirect(route('super-admin.sip-gateway.index'), 'Package updated successfully.');
    }

    public function leadwebcall()
    {
        return view('webcall');
    }
}
