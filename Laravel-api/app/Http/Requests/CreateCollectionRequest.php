<?php

declare(strict_types=1);

namespace App\Http\Requests;

class CreateCollectionRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'title'  => 'required|string',
            'description'  => 'required|string',
            'target_amount'  => 'required|between:0.01,9999999.99',
            'link'  => 'required|string',
            'completed' => 'integer|between:0,1'
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
            'title.required' => 'Title is required!',
            'description.required' => 'Description is required!',
            'target_amount.required' => 'Target amount is required!',
            'link.required' => 'Link is required!',
        ];
    }
}
