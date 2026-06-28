<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|regex:/^[\+\d\s\-\(\)]+$/',
            'message' => 'nullable|string|max:5000',
            'source' => 'required|string|in:catalog,primerka,callback,question,order',
            'article_ids' => 'nullable|array',
            'article_ids.*' => 'string',
            'facade_top_color' => 'nullable|string',
            'facade_bottom_color' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Укажите ваше имя',
            'phone.required' => 'Укажите номер телефона',
            'phone.regex' => 'Телефон указан в неверном формате',
            'source.in' => 'Неверный источник заказа',
        ];
    }
}
