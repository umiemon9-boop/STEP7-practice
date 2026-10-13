<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'product_name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'comment' => ['nullable', 'string'],
            'image' => ['nullable', 'image'],
        ];
    }

    public function messages()
    {
        return [
            'company_id.required' => 'メーカーを選択してください。',
            'company_id.integer' => 'メーカーの指定が正しくありません。',
            'company_id.exists' => '選択したメーカーが見つかりません。',
            'product_name.required' => '商品名を入力してください。',
            'product_name.max' => '商品名は255文字以内で入力してください。',
            'price.required' => '価格を入力してください。',
            'price.integer' => '価格は整数で入力してください。',
            'price.min' => '価格は0以上で入力してください。',
            'stock.required' => '在庫数を入力してください。',
            'stock.integer' => '在庫数は整数で入力してください。',
            'stock.min' => '在庫数は0以上で入力してください。',
            'comment.string' => 'コメントは文字で入力してください。',
            'image.image' => '商品画像には画像ファイルを選択してください。',
        ];
    }
}
