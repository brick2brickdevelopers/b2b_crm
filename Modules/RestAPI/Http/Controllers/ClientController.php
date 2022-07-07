<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Role;
use App\Scopes\CompanyScope;
use Froiden\RestAPI\ApiResponse;
use Froiden\RestAPI\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use Modules\RestAPI\Entities\Client;
use Modules\RestAPI\Entities\ClientDetails;
use Modules\RestAPI\Entities\User;
use Modules\RestAPI\Http\Requests\Client\IndexRequest;
use Modules\RestAPI\Http\Requests\Client\CreateRequest;
use Modules\RestAPI\Http\Requests\Client\UpdateRequest;
use Modules\RestAPI\Http\Requests\Client\ShowRequest;
use Modules\RestAPI\Http\Requests\Client\DeleteRequest;

class ClientController extends ApiBaseController
{
    protected $model = Client::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function modifyIndex($query)
    {
        return $query->visibility();
    }
    public function modifyShow($query)
    {
        return $query->visibility();
    }

    public function store()
    {
        // Check request
        app()->make($this->storeRequest);

        // Check requested email is not a super admin's email
        $isSuperadmin = User::withoutGlobalScopes(['active', CompanyScope::class])
            ->where('super_admin', '1')
            ->where('email', request()->input('email'))
            ->get()
            ->count();
        if ($isSuperadmin > 0) {
            $exception = new ApiException(__('messages.superAdminExistWithMail'), null, 403, 403, 2015);
            return ApiResponse::exception($exception);
        }

        // check if user is exist in user table
        $existing_user = Client::withoutGlobalScope(CompanyScope::class)
            ->select('id', 'email')
            ->where('email', request()->input('email'))
            ->first();
        if (!$existing_user) {
            $user = new client();
            $user->fill(request()->except('client_detail'));
            $user->save();

            $role = Role::where('name', 'client')->first();
            $user->attachRole($role->id);

            $clientDetail = new ClientDetails();
            $clientDetail->user_id = $user->id;
            $clientDetail->email = request()->email;
            $clientDetail->name = request()->name;
            $clientDetail->fill(request()->all('client_detail')['client_detail']);
            $clientDetail->save();
        }
        // check if user is already register as a client.
        $existing_client_count = ClientDetails::select('id', 'email', 'company_id')
            ->where(['email' => request()->input('email')])->count();
        if ($existing_client_count === 0) {
            $clientDetail = new ClientDetails();
            $clientDetail->user_id = $existing_user->id;
            $clientDetail->email = request()->email;
            $clientDetail->name = request()->name;
            $clientDetail->fill(request()->all('client_detail')['client_detail']);
            $clientDetail->save();
        }

        // if user already exist then add client role
        if ($existing_user) {
            $role = Role::where('name', 'client')->first();
            $existing_user->attachRole($role->id);
        }

        return ApiResponse::make(__('messages.clientAdded'), ['id' => $existing_user ? $existing_user->id : $user->id]);
    }

    public function update(...$args)
    {
        $id = last(func_get_args());
        app()->make($this->updateRequest);
        $client = Client::withoutGlobalScopes([CompanyScope::class, 'active'])
            ->where('id', $id)
            ->first();
        $data = request()->all('client_detail')['client_detail'];
        $data['category_id'] = $data['category']['id'];
        $data['sub_category_id'] = $data['sub_category']['id'];
        unset($data['category']);
        unset($data['sub_category']);
        $client->ClientDetail()->update($data);
        return ApiResponse::make(__('messages.clientUpdated'));
    }

    public function destroy(...$args)
    {
        $id = last(func_get_args());
        app()->make($this->deleteRequest);
        DB::beginTransaction();
        $clients_count = ClientDetails::withoutGlobalScope(CompanyScope::class)
            ->where('user_id', $id)
            ->count();

        if ($clients_count > 1) {
            $client_builder = ClientDetails::where('user_id', $id);
            $client = $client_builder->first();

            $user_builder = User::where('id', $id);
            $user = $user_builder->first();
            if ($user) {
                $other_client = $client_builder->withoutGlobalScope(CompanyScope::class)
                    ->where('company_id', '!=', $client->company_id)
                    ->first();

                request()->request->add(['company_id' => $other_client->company_id]);

                $user->save();
            }
            $role = Role::where('name', 'client')->first();
            $user_role = $user_builder
                ->withoutGlobalScope(CompanyScope::class)
                ->first();
            $user_role->detachRoles([$role->id]);
            $client->delete();
        } else {
            $userRoles = User::withoutGlobalScopes([CompanyScope::class, 'active'])
                ->where('id', $id)
                ->first()
                ->role
                ->count();
            if ($userRoles > 1) {
                $role = Role::where('name', 'client')->first();
                $client_role = User::withoutGlobalScopes([CompanyScope::class, 'active'])
                    ->where('id', $id)
                    ->first();
                $client_role->detachRoles([$role->id]);
                ClientDetails::withoutGlobalScope(CompanyScope::class)
                    ->where('user_id', $id)
                    ->delete();
            } else {
                User::withoutGlobalScopes([CompanyScope::class, 'active'])
                    ->where('id', $id)
                    ->delete($id);
            }
        }
        DB::commit();
        return ApiResponse::make(__('messages.clientDeleted'));
    }

    public function stored(Client $client)
    {
        // To add custom fields data
        if (request()->get('custom_fields_data')) {
            $client->clientDetail()->updateCustomFieldData(request()->get('custom_fields_data'));
        }

        return $client;
    }
}
