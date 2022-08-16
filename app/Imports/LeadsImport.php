<?php

namespace App\Imports;

use App\Lead;
use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Maatwebsite\Excel\Concerns\WithValidation;

class LeadsImport implements ToModel
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

            // 'client_name' => $row['client_name'],
            // 'client_email' => $row['client_email'],
            // 'mobile' => $row['mobile'],
            // 'note' => $row['note'],
            // 'next_follow_up' => $row['next_follow_up'],
            // 'value' => $row['value'],
            // 'currency_id' => 17,
            // 'column_priority' => $row['column_priority'],

            'client_name' => $row[0],
            'client_email' => $row[1],
            'mobile' => $row[2],
            'note' => $row[3],
            'next_follow_up' => $row[4],
            'value' => $row[5],
            'currency_id' => 17,
            'column_priority' => $row[7],
            'company_id' =>company()->id,
        
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
