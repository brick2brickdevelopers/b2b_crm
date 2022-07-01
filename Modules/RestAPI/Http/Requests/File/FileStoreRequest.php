<?php

namespace Modules\RestAPI\Http\Requests\File;

use Froiden\RestAPI\Exceptions\UnauthorizedException;
use Modules\RestAPI\Http\Requests\BaseRequest;

class FileStoreRequest extends BaseRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @throws UnauthorizedException
     */
    public function authorize()
    {
        $allowedTypes = ['expense-invoice', 'ticket-files'];
        if (!api_user() && !in_array(request()->get('type'), $allowedTypes)) {
            // Employee not logged in, check type of file
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|in:expense-invoice,logos,documents,photos,resumes,ticket-files',
            'file' => 'mimes:jpeg,png,bmp,doc,docx,pdf,xls,xlsx,ppt,pptx,txt,zip|max:8192'
        ];
    }
}
