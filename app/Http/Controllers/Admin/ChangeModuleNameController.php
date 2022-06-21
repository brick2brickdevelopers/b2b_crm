<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModuleSetting;

class ChangeModuleNameController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.moduleSettings';
        $this->pageIcon = 'icon-settings';
    }

    public function getAllModule(Request $request)
    {

        $moduleInPackage = (array)json_decode(company()->package->module_in_package);
        if ($request->has('type')) {
            if ($request->get('type') == 'employee') {
                $this->modulesData = ModuleSetting::where('type', 'employee')->whereIn('module_name', $moduleInPackage)->get();
                $this->type = 'employee';
            } elseif ($request->get('type') == 'client') {
                $this->modulesData = ModuleSetting::where('type', 'client')->whereIn('module_name', $moduleInPackage)->get();
                $this->type = 'client';
            }
        } else {
            $this->modulesData = ModuleSetting::where('type', 'admin')->whereIn('module_name', $moduleInPackage)->get();
            $this->type = 'admin';
        }


        $this->type = "Change module names";
        return view('admin.module-settings.change-module-name.index', $this->data);
    }

    public function updateModule(Request $request, $id)
    {


        //find the module
        $module = ModuleSetting::where('type', 'admin')->where('id', $id)->first();


        //find all the module
        $modules = ModuleSetting::where('company_id', $module->company_id)->where('module_name', $module->module_name)->get();

        foreach ($modules as $item) {

            $item->custom_name = $request['custom_name'] == "" ? null : $request['custom_name'];

            $item->save();
        }

        return redirect()->back()->with('success', "Module name updated successfully");
    }
}
