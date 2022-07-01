<?php

namespace Modules\Asset\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\Admin\AdminBaseController;
use App\User;
use Carbon\Carbon as Carbon;
use Illuminate\Http\Response;
use Modules\Asset\Datatables\AssetsDataTable;
use Modules\Asset\Entities\Asset;
use Modules\Asset\Entities\AssetHistory;
use Modules\Asset\Entities\AssetType;
use Modules\Asset\Http\Requests\LendRequest;
use Modules\Asset\Http\Requests\ReturnRequest;
use Modules\Asset\Http\Requests\StoreRequest;
use Modules\Asset\Http\Requests\UpdateRequest;

class AssetHistoryController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            if (!in_array('asset', $this->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function create($id)
    {
        $this->asset = Asset::find($id);
        $this->employees  = User::allEmployees();
        return view('asset::asset.lend', $this->data);
    }

    public function store(LendRequest $request, $id)
    {
        $asset = Asset::findOrFail($id);

        $assetHistory = new AssetHistory();
        $assetHistory->asset_id = $id;
        $assetHistory->user_id = $request->employee_id;
        $assetHistory->lender_id = auth()->user()->id;
        $assetHistory->date_given = Carbon::createFromFormat($this->global->date_format, $request->date_given)->format('Y-m-d H:i:s');
        if($request->has('return_date') && $request->return_date != '') {
            $assetHistory->return_date = Carbon::createFromFormat($this->global->date_format, $request->return_date)->format('Y-m-d H:i:s');
        }
        if($request->has('notes')) {
            $assetHistory->notes = $request->notes;
        }
        $assetHistory->save();

        $asset->status = 'lent';
        $asset->save();

        $this->asset = Asset::with(['history' => function($query) {
            return $query->orderBy('id', 'desc');
        }, 'asset_type'])->findOrFail($id);
        $view = view('asset::asset.show', $this->data)->render();

        return Reply::successWithData(__('asset::app.lendAssetMessage'), ['view' => $view]);
    }

    public function edit($assetId, $historyId)
    {
        $this->history = AssetHistory::find($historyId);
        $this->employees  = User::allEmployees();
        return view('asset::asset.history-edit', $this->data);
    }

    public function returnAsset($assetId, $historyId)
    {
        $this->history = AssetHistory::find($historyId);
dd();
        return view('asset::asset.return', $this->data);
    }

    public function update(ReturnRequest $request, $assetId, $id)
    {
        $asset = Asset::findOrFail($assetId);;

        $assetHistory = AssetHistory::find($id);

        $assetHistory->asset_id = $asset->id;

        if($request->has('employee_id')) {
            $assetHistory->user_id = $request->employee_id;
        }

        if($request->has('date_given')) {
            $assetHistory->date_given = Carbon::createFromFormat($this->global->date_format, $request->date_given)->format('Y-m-d H:i:s');
        }

        if($request->has('return_date') && $request->return_date != '') {
            $assetHistory->return_date = Carbon::createFromFormat($this->global->date_format, $request->return_date)->format('Y-m-d H:i:s');
        }

        if($request->has('date_of_return') && $request->date_of_return != '' ) {
            $assetHistory->date_of_return = Carbon::createFromFormat($this->global->date_format, $request->date_of_return)->format('Y-m-d H:i:s');

            $asset->status = 'available';
            $asset->save();
        }

        if($request->has('notes')) {
            $assetHistory->notes = $request->notes;
        }

        $assetHistory->save();

        $this->asset = Asset::with(['history' => function($query) {
            return $query->orderBy('id', 'desc');
        }, 'asset_type'])->findOrFail($assetId);

        if($request->has('type') == 'return') {

            $view = view('asset::asset.show', $this->data)->render();
            return Reply::successWithData(__('asset::app.returnAssetMessage'), ['view' => $view]);
        }

        $view = view('asset::asset.history-list', $this->data)->render();

        return Reply::successWithData(__('asset::app.lendAssetMessage'), ['view' => $view]);
    }

    public function destroy($assetId, $id)
    {
        AssetHistory::destroy($id);

        $this->asset = Asset::with(['history' => function($query) {
            return $query->orderBy('id', 'desc');
        }, 'asset_type'])->findOrFail($assetId);

        $view = view('asset::asset.show', $this->data)->render();
        return Reply::successWithData(__('asset::app.historyDeleteSuccess'), ['view' => $view]);
    }
}
