<?php

namespace App;

use App\Observers\ManualLoggedCallObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CustomFieldsTrait;

class ManualLoggedCall extends Model
{
    use CustomFieldsTrait;
    protected static function boot()
    {

        parent::boot();
        static::addGlobalScope(new CompanyScope);
        static::observe(ManualLoggedCallObserver::class);
    }


    public function purpose()
    {
        return $this->belongsTo(CallPurpose::class, 'call_purpose');
    }
    public function calloutcome()
    {
        return $this->belongsTo(CallOutcome::class, 'call_outcome_id');
    }
    public function leadstatus()
    {
        return $this->belongsTo(CampaignLeadStatus::class, 'campaign_lead_status_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    


    protected $guarded = [];  

}
