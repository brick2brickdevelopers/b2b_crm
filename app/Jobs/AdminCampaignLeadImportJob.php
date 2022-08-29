<?php

namespace App\Jobs;

use App\Campaign;
use App\CampaignAgent;
use App\CampaignLead;
use App\Imports\LeadsImport;
use App\Lead;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class AdminCampaignLeadImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $rows;
    protected $company_id;
    protected $currency_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($rows, $company_id, $currency_id)
    {
        //
        $this->rows = $rows;
        $this->company_id = $company_id;
        $this->currency_id = $currency_id;
       // $this->campaign_id = $campaign_id;
        // $request->id
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::alert('start');
        foreach ($this->rows as $row) {
            
            try {
               
                $leadFind = Lead::where('mobile',$row["mobile"])->first();
                $leadCount = Lead::where('mobile',$row["mobile"])->count();
               if($leadCount > 0){
                $campaignAgent = CampaignAgent::where('campaign_id',1)->first();

                $campaignLead = New CampaignLead();
                $campaignLead->company_id = $this->company_id;
                $campaignLead->campaign_id = 1;
                $campaignLead->agent_id = $campaignAgent->employee_id;
                $campaignLead->lead_id = $leadFind->id;
                $campaignLead->status = 0;
                $campaignLead->leadcallstatus = 0;
                $campaignLead->save();

              }else{
                $lead = new Lead();
                $lead->company_id = $this->company_id;
                $lead->client_id = $row["client_id"];
                $lead->client_name = $row["client_name"];
                $lead->source_id = $row["source_id"];
                $lead->status_id = $row["status_id"];
                $lead->column_priority = 0;
                $lead->agent_id = $row["client_id"];
                $lead->company_name = $row["company_name"];
                $lead->website = $row["website"];
                $lead->currency_id = $this->currency_id;
                $lead->address = $row["address"];
                $lead->client_surname = $row["client_surname"];
                $lead->office_phone = $row["office_phone"];
                $lead->city = $row["city"];
                $lead->state = $row["state"];
                $lead->country = $row["country"];
                $lead->postal_code = $row["postal_code"];
                $lead->address = $row["address"];
                $lead->client_email = $row["client_email"];
                $lead->mobile = $row["mobile"];
                $lead->note = $row["note"];
                $lead->next_follow_up = $row["next_follow_up"];
                $lead->value = $row["value"];
                $lead->category_id = $row["category_id"];
                $lead->save();

                $campaignAgent = CampaignAgent::where('campaign_id',1)->first();
                $campaignLead = New CampaignLead();
                $campaignLead->company_id = $this->company_id;
                $campaignLead->campaign_id = 1;
                $campaignLead->agent_id = $campaignAgent->employee_id;
                $campaignLead->lead_id = 1;
                $campaignLead->status = 0;
                $campaignLead->leadcallstatus = 0;
                $campaignLead->save();

              }
               
                // Log::alert($lead->id);
            } catch (Exception $exception) {
                Log::alert($exception->getMessage());
            }
        }
    }
}
