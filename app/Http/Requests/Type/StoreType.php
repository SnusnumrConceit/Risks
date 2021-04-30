<?php

namespace App\Http\Requests\Type;

use Illuminate\Foundation\Http\FormRequest;

class StoreType extends FormRequest
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
            'name' => 'required|between:5,30|unique:types,name'
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
            'name' => __('types.name')
        ];
    }
}
