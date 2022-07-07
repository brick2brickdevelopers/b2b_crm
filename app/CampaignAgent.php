<?php

namespace App;

use App\Observers\CampaignAgentObserver;
use App\Scopes\CompanyScope;
use App\LeadAgent;
use Illuminate\Database\Eloquent\Model;

class CampaignAgent extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CompanyScope);
        static::observe(CampaignAgentObserver::class);
    }
    protected $fillable = ['campaign_id', 'company_id', 'employee_id'];

    public function agent()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function cAgent()
    {
        return $this->belongsTo(LeadAgent::class, 'employee_id', 'user_id');
    }
}
