<?php

namespace Modules\Asset\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique('assets')->where(function ($query) {
                    return $query->where('company_id', company()->id);
                })->ignore($this->route('asset'))
            ],
            'asset_type_id' => 'required|exists:asset_types,id',
            'serial_number' => 'required',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'asset_type_id.required' => 'The asset type field is required.',
            'asset_type_id.exists' => 'The asset type field is not exists.'
        ];
    }
}
