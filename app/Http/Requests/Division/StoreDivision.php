<?php

namespace App\Http\Requests\Division;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Risk\ShouldVerifyDivision;

class StoreDivision extends FormRequest
{
    use ShouldVerifyDivision;

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
            'name'      => 'required|between:5,30|unique:divisions,name',
            'parent_id' => 'nullable|exists:divisions,id|accessable',
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
            'name'      => __('divisions.name'),
            'parent_id' => __('divisions.parent_id')
        ];
    }
}
