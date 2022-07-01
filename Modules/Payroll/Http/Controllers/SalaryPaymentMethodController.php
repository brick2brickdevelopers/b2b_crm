<?php

namespace Modules\Payroll\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Payroll\Entities\SalaryGroup;
use Modules\Payroll\Entities\SalaryComponent;
use Modules\Payroll\Entities\SalaryPaymentMethod;
use Modules\Payroll\Http\Requests\StorePaymentMethod;
use Modules\Payroll\Http\Requests\StoreSalaryComponent;
use Yajra\DataTables\Facades\DataTables;

class SalaryPaymentMethodController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('app.menu.payroll') . ' ' . __('app.menu.settings');
        $this->pageIcon = 'icon-settings';
        $this->middleware(function ($request, $next) {
            if (!in_array('payroll', $this->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $this->paymentMethods = SalaryPaymentMethod::all();
        return view('payroll::payment-methods.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('payroll::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(StorePaymentMethod $request)
    {
        SalaryPaymentMethod::create($request->all());
        return Reply::success(__('messages.recordSaved'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('payroll::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $this->salaryComponent = SalaryPaymentMethod::find($id);
        return view('payroll::payment-methods.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(StorePaymentMethod $request, $id)
    {
        SalaryPaymentMethod::where('id', $id)->update([
            'payment_method' => $request->payment_method
        ]);

        return Reply::success(__('messages.updatedSuccessfully'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        SalaryPaymentMethod::destroy($id);
        return Reply::success(__('messages.deleteSuccess'));
    }
}
