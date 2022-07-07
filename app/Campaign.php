<?php

namespace App;

use App\Observers\CampaignObserver;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;
use App\CampaignLead;
use App\CampaignAgent;

class Campaign extends Model
{
    protected $table = 'campaigns';
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CompanyScope);
        static::observe(CampaignObserver::class);
    }

    public function leads()
    {
        return $this->hasMany(CampaignLead::class, 'campaign_id');
    }
    public function agents()
    {
        return $this->hasMany(CampaignAgent::class, 'campaign_id');
    }
}
