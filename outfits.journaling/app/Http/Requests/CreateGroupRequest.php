<?php

declare(strict_types=1);

namespace App\Http\Requests;

class CreateGroupRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Name is required!',
        ];
    }
}
