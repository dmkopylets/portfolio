<?php

declare(strict_types=1);

namespace App\Http\Requests;

class EditOrderPart4Request  extends OrderEditRequest
{
    public function rules(): array
    {
        return [
            'licensor'=>'required',
            'lic_date'=>'required'
        ];
    }
    public function messages(): array
    {
        return [
            'licensor.required'=>'Не вказано хто дав дозвіл на підготовку',
            'lic_date.required'=>'Не вказано дати дозвілу'
        ];
    }
}
