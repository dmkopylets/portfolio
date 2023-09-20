<?php

declare(strict_types=1);

namespace App\Http\Requests;

class CreateContributorRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'collection_id' => 'required|integer',
            'user_name' => 'required|string',
            'amount' => 'required|between:0.01,9999999.99',
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
            'collection_id.required' => 'Collection id is required!',
            'user_name.required' => 'User name id is required!',
            'amount.required' => 'Amount id is required!',
        ];
    }
}
