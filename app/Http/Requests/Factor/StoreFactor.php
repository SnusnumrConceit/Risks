<?php

namespace App\Http\Requests\Factor;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFactor extends FormRequest
{
    /**
     * Наличие доступа
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'      => 'required|between:5,100|unique:factors,name',
            'parent_id' => [
                'nullable',
                Rule::exists('factors', 'id')->whereNull('parent_id'),
            ],
        ];
    }

    /**
     * Переводы атрибутов
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name'      => __('factors.name'),
            'parent_id' => __('factors.parent_id')
        ];
    }
}
