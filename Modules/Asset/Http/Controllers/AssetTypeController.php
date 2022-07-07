<?php

namespace Modules\Asset\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Http\Response;
use Modules\Asset\Entities\Asset;
use Modules\Asset\Entities\AssetType;
use Modules\Asset\Http\Requests\AssetType\StoreRequest;

class AssetTypeController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('asset::app.menu.asset');
        $this->pageIcon = 'icon-desktop';
        $this->middleware(function ($request, $next) {
            if (!in_array('asset', $this->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function create()
    {
        $this->assetTypes = AssetType::all();
        return view('asset::asset-type.create', $this->data);
    }

    public function store(StoreRequest $request)
    {
        $asset = new AssetType();
        $this->storeUpdate($asset, $request);
        $assetTypeData = AssetType::all();
        return Reply::successWithData(__('asset::app.typeStoreSuccess'),['data' => $assetTypeData]);
    }

    private function storeUpdate($asset, $request)
    {
        $asset->name = $request->name;

        $asset->save();
    }

    public function destroy($id)
    {
        AssetType::destroy($id);
        $assetTypeData = AssetType::all();
        return Reply::successWithData(__('asset::app.typeDeleteSuccess'),['data' => $assetTypeData]);
    }
}
