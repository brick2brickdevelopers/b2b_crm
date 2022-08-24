<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Company;
use App\DidNumber;
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
                 <a href=" . route('super-admin.sip-gateway.edit', $data->id) . " class='btn btn-default dropdown-toggle waves-effect waves-light'><i class='fa fa-pencil'></i></a>
                </div>";
                    $btn .= "<div class='btn-group dropdown m-r-10'>
                 <a href=" . route('super-admin.sip-gateway.destroy', $data->id) . " class='btn btn-danger dropdown-toggle waves-effect waves-light'><i class='fa fa-trash'></i></a>
                </div>";
                    return $btn;
                })
                // ' . $data->id . '
                ->addColumn('status', function ($data) {
                    $checked  = $data->status == true ? 'checked' : "";
                    $btn = '<input class="switch-event1" ' . $checked . '   onchange="changeStatus(' . $data->id . ')" type="checkbox" > ';
                    return $btn;
                })
                ->editColumn('caller_id', function ($data) {
                    $num = implode('<br> ', json_decode($data->caller_id));
                    return  $num;
                })

                ->rawColumns(['action', 'status', 'caller_id'])
                ->toJson();
        }
        $this->html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'Id'],
            ['data' => 'company_id', 'name' => 'company_id', 'title' => 'Company'],
            ['data' => 'type', 'name' => 'type', 'title' => 'Server Type'],
            ['data' => 'caller_id', 'name' => 'caller_id', 'title' => 'Caller ID'],
            ['data' => 'key', 'name' => 'key', 'title' => 'Key'],
            ['data' => 'token', 'name' => 'token', 'title' => 'Token'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Action'],
        ]);


        // $this->company = Company::all();
        $d = DidNumber::whereNotNull('company_id')->pluck('company_id');
        $this->company = Company::whereNotIn('id', array_unique($d->toArray()))->get();
        $this->didNumbers = DidNumber::whereNull('company_id')->get();
        // $this->caller_id = DidNumber::where('company_id', )->get();
        return view('super-admin.sipgateway.index', $this->data);
    }

    public function store(Request $request)
    {
        $sip = new SipGateway();
        $sip->company_id = $request->company_id;
        $sip->type = $request->type;
        $sip->caller_id = json_encode($request->caller_id);
        foreach ($request->caller_id as $value) {
            $didNumber = DidNumber::where('number', $value)->first();
            $didNumber->company_id = $request->company_id;
            $didNumber->save();
        }
        $sip->endpoint = $request->endpoint;
        $sip->key = $request->user;
        $sip->token = $request->token;
        $sip->save();
        return Reply::redirect(route('super-admin.sip-gateway.index'), 'Package updated successfully.');
    }

    public function edit($id)
    {
        // $employee1 = CampaignAgent::where('campaign_id', $id)->pluck('employee_id');
        // $campaigns = Campaign::pluck('id');
        // $employeex = CampaignAgent::whereIn('campaign_id', $campaigns)->pluck('employee_id');
        // $employee2 = EmployeeDetails::whereNotIn('user_id', $employeex)->pluck('user_id');
        // $employee = $employee1->merge($employee2);
        // $this->employee =  EmployeeDetails::whereIn('user_id', $employee)->get();



        $this->sip_gateway = SipGateway::find($id);
        $d = DidNumber::whereNotNull('company_id')->pluck('company_id');
        $this->didFree = DidNumber::whereNull('company_id')->pluck('number');

        $this->didUsing = DidNumber::where('company_id',$this->sip_gateway->company_id)->pluck('number');

        $this->allDid = $this->didFree->merge($this->didUsing);

        $this->didNumbers = DidNumber::whereIn('number',$this->allDid)->get();

        

        $this->company = Company::where('id',$this->sip_gateway->company_id)->first();
        return view('super-admin.sipgateway.edit', $this->data);
    }

    public function update(Request $request,$id)
    {
        
        
        $sip = SipGateway::find($id);
       
        $sip->company_id = $request->company_id;
        $sip->type = $request->type;
        $sip->caller_id = json_encode($request->caller_id);
        $didUsing = DidNumber::where('company_id',$sip->company_id)->get();
        foreach( $didUsing as $didNumber){
            DidNumber::where('number',$didNumber->number)->update(['company_id'=>null]);
        }
        
        foreach ($request->caller_id as $value) {
            $didNumber = DidNumber::where('number', $value)->first();
            $didNumber->company_id = $request->company_id;
            $didNumber->save();
        }

        $sip->endpoint = $request->endpoint;
        $sip->key = $request->user;
        $sip->token = $request->token;
        $sip->save();
        return redirect()->route('super-admin.sip-gateway.index');
    }


    public function changeStatus(Request $request)
    {
        $sip = SipGateway::find($request->id);
        $sip->status = $sip->status ? false : true;
        $sip->save();
        return $sip;
    }

    public function destroy($id)
    {
        SipGateway::find($id)->delete();
        return redirect()->back();
    }

    public function leadwebcall()
    {
        return view('webcall');
    }
}
