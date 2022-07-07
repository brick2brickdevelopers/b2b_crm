<?php

namespace Modules\RestAPI\Entities;

use App\Scopes\CompanyScope;

class Client extends \App\User
{
    protected $table = 'users';

    protected $default = [
        'id',
        'name',
        'email',
        'status',
        'client_details'
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $filterable = [
        'id',
        'users.name',
        'email',
        'status',
        'client_details.category_id',
        'client_details.sub_category_id'
    ];

    public function clientDetail()
    {
        return $this->hasOne(ClientDetails::class, 'user_id');
    }

    public function scopeVisibility($query)
    {

        if (api_user()) {
            // If employee or client show projects assigned
            $query->withoutGlobalScope(CompanyScope::class);
            $query->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->leftJoin('client_details', 'users.id', '=', 'client_details.user_id')
                ->where('client_details.company_id', '=', company()->id)
                ->select(
                    'users.id',
                    'users.name as name',
                    'users.email',
                    'users.created_at',
                    'client_details.company_name',
                    'users.image'
                )
                ->where('roles.name', 'client')
                ->groupBy('users.id');
            return $query;
        }
        return $query;
    }
}
