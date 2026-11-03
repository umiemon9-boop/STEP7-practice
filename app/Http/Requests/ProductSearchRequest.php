<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ProductSearchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_name' => ['nullable', 'string', 'max:255'],
            'company_id' => ['nullable', 'integer', 'exists:companies,id'],
            'price_min' => ['nullable', 'integer', 'min:0'],
            'price_max' => ['nullable', 'integer', 'min:0'],
            'stock_min' => ['nullable', 'integer', 'min:0'],
            'stock_max' => ['nullable', 'integer', 'min:0'],
            'sort' => ['nullable', 'in:id,product_name,company_name,price,stock'],
            'direction' => ['nullable', 'in:asc,desc'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            foreach (['price', 'stock'] as $field) {
                $minimum = $this->input($field . '_min');
                $maximum = $this->input($field . '_max');

                if ($minimum !== null && $minimum !== '' && $maximum !== null && $maximum !== '' && $minimum > $maximum) {
                    $validator->errors()->add($field . '_min', '下限は上限以下にしてください。');
                }
            }
        });
    }
}
