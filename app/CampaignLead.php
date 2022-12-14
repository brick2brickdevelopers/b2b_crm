<?php

namespace App;

use App\Observers\CampaignLeadsObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class CampaignLead extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CompanyScope);
        static::observe(CampaignLeadsObserver::class);
    } 

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function calloutcome()
    {
        return $this->belongsTo(CallOutcome::class, 'leadcallstatus');
    }

   protected $guarded = [];
}
