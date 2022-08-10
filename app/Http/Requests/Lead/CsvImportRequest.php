<?php

namespace App\Http\Requests\Lead;

use App\Http\Requests\CoreRequest;

class CsvImportRequest extends CoreRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'csv_file' => 'required|file'
        ];
    }
}
