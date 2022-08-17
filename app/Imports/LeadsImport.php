<?php

namespace App\Imports;

use App\Lead;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

// use Maatwebsite\Excel\Concerns\WithValidation;

class LeadsImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Lead([
            // 'column_priority'     => $row[0],
            // 'client_name' => $row[1],
            // 'client_email' => $row[2],
            // 'mobile' => $row[3],
            // 'note' => $row[4],
            // 'next_follow_up' => $row[5],

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

           
        
            // 'source_id'    => $row[1], 
            // 'source_id'    => $row[1], 
            // 'status_id'    => $row[1], 
            // 'column_priority'    => $row[1], 
            // 'agent_id'    => $row[1], 
            // 'company_name'    => $row[1], 
            // 'address'    => $row[1], 
            // 'client_surname'    => $row[1], 
            // 'office_phone'    => $row[1], 
            // 'city'    => $row[1], 
            // 'state'    => $row[1], 
            // 'country'    => $row[1], 
            // 'postal_code'    => $row[1], 
            // 'client_name'    => $row[1], 
            // 'mobile'    => $row[1], 
            // 'note'    => $row[1], 
            // 'next_follow_up'    => $row[1], 
            // 'value'    => $row[1], 
            
        ]);
    }
   
}
