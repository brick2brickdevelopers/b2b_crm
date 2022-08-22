<?php

namespace App\Imports;

use App\Campaign;
use App\Lead;
use Exception;
use Maatwebsite\Excel\Concerns\ToModel;

class CampaignsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try {

            // dispatch(new JobsLead($row));
            // return 0;
            return new Lead([
                'client_id' => $row['client_id'],
                'source_id' => $row['source_id'],
                'status_id' => $row['status_id'],
                'column_priority' => $row['column_priority'],
                'agent_id' => $row['agent_id'],
                'company_name' => $row['company_name'],
                'website' => $row['website'],
                'currency_id' => 17,
                'address' => $row['address'],
                'client_surname' => $row['client_surname'],
                'office_phone' => $row['office_phone'],
                'city' => $row['city'],
                'state' => $row['state'],
                'country' => $row['country'],
                'postal_code' => $row['postal_code'],
                'client_name' => $row['client_name'],
                'address' => $row['address'],
                'client_email' => $row['client_email'],
                'mobile' => $row['mobile'],
                'note' => $row['note'],
                'next_follow_up' => $row['next_follow_up'],
                'value' => $row['value'],
                'category_id' => $row['category_id'],
                
            ]);
        } catch (Exception $exception) {
        }
    }
}
