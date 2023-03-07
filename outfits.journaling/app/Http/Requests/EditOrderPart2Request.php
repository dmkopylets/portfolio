<?php

declare(strict_types=1);

namespace App\Http\Requests;

class EditOrderPart2Request  extends OrderEditRequest
{
    public function rules()
    {
        return [
            'preparationTargetObj' => 'required',
            'preparationBody' => ['required', 'string'],
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
            'preparationTargetObj' => 'Не вибрано станції',
            'preparationBody.required' => 'Не вказано що робити',
        ];
    }
}
