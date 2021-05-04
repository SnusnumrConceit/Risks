<?php

namespace App\Http\Requests\Risk;

use App\Risk;
use Illuminate\Foundation\Http\FormRequest;

class StoreRisk extends FormRequest
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
            'name'        => 'required|max:255',
            'description' => 'required|max:2000',
            'level'       => 'required|in:' . implode(',', Risk::getLevels()),
            'likelihood'  => 'required|integer',
            'impact'      => 'required|integer',
            'expired_at'  => 'required|date|after:today',
            'factors'     => 'required|array|min:1',
            'factors.*'   => 'required|exists:factors,id',
            'types'       => 'required|array|min:1',
            'types.*'     => 'required|exists:types,id',
            'divisions'   => 'required|array|min:1',
            'divisions.*' => 'required|exists:divisions,id',
        ];
    }

    /**
     * Атрибуты
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name'        => __('risks.name'),
            'description' => __('risks.description'),
            'level'       => __('risks.level'),
            'status'      => __('risks.status'),
            'likelihood'  => __('risks.likelihood'),
            'impact'      => __('risks.impact'),
            'expired_at'  => __('risks.expired_at'),
            'factors'     => __('factors.factors'),
            'types'       => __('types.types'),
            'divisions'   => __('divisions.division'),
        ];
    }
}
