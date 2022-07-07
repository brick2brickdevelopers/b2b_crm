<?php

namespace Modules\Asset\Http\Controllers;

use App\Helper\Files;
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

class AssetController extends AdminBaseController
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
    public function index(AssetsDataTable $dataTable)
    {
        $this->assetType = AssetType::all();
        $this->employees = User::allEmployees();
        $this->totalAssets = Asset::count();
        $this->status = ['lent', 'available', 'non-functional'];
        return $dataTable->render('asset::asset.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $this->assetType = AssetType::all();
        return view('asset::asset.create', $this->data);
    }

    public function store(StoreRequest $request)
    {
        $asset = new Asset();
        $this->storeUpdate($asset, $request);

        return Reply::redirect(route('admin.assets.index'), 'asset::app.storeSuccess');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function edit($id)
    {
        $this->asset = Asset::findorFail($id);
        $this->assetType = AssetType::all();
        return view('asset::asset.edit', $this->data);
    }


    public function update(UpdateRequest $request, $id)
    {
        $asset = Asset::find($id);
        $this->storeUpdate($asset, $request);

        return Reply::redirect(route('admin.assets.index'), 'asset::app.updateSuccess');
    }

    private function storeUpdate($asset, $request)
    {
        $asset->name = $request->name;
        $asset->serial_number = $request->serial_number;
        $asset->asset_type_id = $request->asset_type_id;

        if($request->has('description')) {
            $asset->description = $request->description;
        }

        if($asset->status != 'lent') {
            $asset->status = $request->has('status') ? 'non-functional': 'available';
        }

        if ($request->hasFile('image')) {

            Files::deleteFile($asset->image, 'avatar');
            $asset->image = Files::upload($request->image, 'assets');
        }

        $asset->save();
    }

    public function show($id)
    {
        $this->asset = Asset::with(['history' => function($query) {
            return $query->orderBy('id', 'desc');
        }, 'asset_type'])->findOrFail($id);
        $view = view('asset::asset.show', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function destroy($id)
    {
        Asset::destroy($id);
        return Reply::success(__('asset::app.deleteSuccess'));
    }
}
