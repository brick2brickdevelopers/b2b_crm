<?php

namespace Modules\Payroll\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Payroll\Entities\SalaryComponent;
use Modules\Payroll\Entities\SalaryGroup;
use Modules\Payroll\Entities\SalaryGroupComponent;
use Modules\Payroll\Http\Requests\StoreSalaryGroup;

class SalaryGroupController extends AdminBaseController
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
        $this->salaryGroups = SalaryGroup::with('components', 'components.component')->withCount('employees')->get();
        $this->salaryComponents = SalaryComponent::all();
        return view('payroll::salary-group.index', $this->data);
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
    public function store(StoreSalaryGroup $request)
    {
        $salaryGroup = SalaryGroup::create(
            [
                'group_name' => $request->group_name
            ]
        );

        foreach ($request->salary_components as $component) {
            SalaryGroupComponent::create(
                [
                    'salary_group_id' => $salaryGroup->id,
                    'salary_component_id' => $component
                ]
            );
        }

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
        $this->salaryGroup = SalaryGroup::find($id);
        $this->salaryComponents = SalaryComponent::all();
        return view('payroll::salary-group.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(StoreSalaryGroup $request, $id)
    {
        SalaryGroup::where('id', $id)->update([
            'group_name' => $request->group_name
        ]);

        SalaryGroupComponent::where('salary_group_id', $id)->delete();
        foreach ($request->salary_components as $component) {
            SalaryGroupComponent::create(
                [
                    'salary_group_id' => $id,
                    'salary_component_id' => $component
                ]
            );
        }

        return Reply::success(__('messages.updatedSuccessfully'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        SalaryGroup::destroy($id);
        return Reply::success(__('messages.deleteSuccess'));
    }
}
