<?php

declare(strict_types=1);

namespace App\Http\Requests;

class CreateStudentRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
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
            'first_name.required' => 'Firstname is required!',
            'last_name.required' => 'Lastname is required!',
        ];
    }
}
