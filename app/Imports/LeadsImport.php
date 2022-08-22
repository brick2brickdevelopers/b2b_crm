<?php

namespace App\Imports;

use App\Jobs\AdminLeadImportJob;
use App\Lead;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Exception;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithChunkReading;
// use Maatwebsite\Excel\Concerns\WithValidation;

class LeadsImport implements ToCollection, WithHeadingRow
// class LeadsImport implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Log::alert($row);
            try {
                $lead = Lead::firstOrNew(['mobile' => $row['mobile']]);
                $lead->client_id = $row["client_id"];
                $lead->client_name = $row["client_name"];
                $lead->source_id = $row["source_id"];
                $lead->status_id = $row["status_id"];
                $lead->column_priority = 0;
                $lead->agent_id = $row["client_id"];
                $lead->company_name = $row["company_name"];
                $lead->website = $row["website"];
                $lead->currency_id = 17;
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
            } catch (Exception $exception) {
                Log::alert($exception->getMessage());
            }
        }
        // dispatch(new AdminLeadImportJob($rows));
    }

    // public function chunkSize(): int
    // {
    //     return 1000;
    // }
}
