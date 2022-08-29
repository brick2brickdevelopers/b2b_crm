<?php

namespace App\Imports;

use App\Jobs\AdminCampaignLeadImportJob;

use App\Campaign;
use App\Lead;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;

class CampaignsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {

        $company_id = company()->id;
        $currency_id = company()->currency_id;
        // $campaign_id = $_GET['id'];
        // Log::alert($campaign_id);
        dispatch(new AdminCampaignLeadImportJob($rows, $company_id, $currency_id));
    }
}
