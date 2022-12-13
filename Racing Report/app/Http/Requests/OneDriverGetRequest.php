<?php

declare(strict_types=1);

namespace App\Http\Requests;

class OneDriverGetRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'driverId' => 'string|min:3|max:3|exists:flights,driverId',
        ];
    }

    public function messages(): array
    {
        return [
            'driverId.string' => 'Driver abbreviation must be a string!',
            'driverId.exists' => 'Race with this Driver Id is required!'
        ];
    }
}
