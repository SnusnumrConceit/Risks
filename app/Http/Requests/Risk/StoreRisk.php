<?php

namespace App\Http\Requests\Risk;

use App\Risk;
use Illuminate\Foundation\Http\FormRequest;

class StoreRisk extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->authUser = auth()->user();

        if (! $this->authUser->is_responsible && ! $this->authUser->hasPermission('divisions_view')) {
            return $this->merge(['division_id' => $this->authUser->division_id]);
        }
    }

    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required|between:5,255',
            'description' => 'required|max:2000',
            'summa'       => 'nullable|between:0,99999999.00',
            'damage'      => 'nullable|between:0,99999999.00',
            'level'       => 'required|in:' . implode(',', Risk::getLevels()),
            'likelihood'  => 'required|integer|between:1,5',
            'impact'      => 'required|integer|between:1,5',
            'expired_at'  => 'required|date|after:today',
            'factors'     => 'required|array|min:1',
            'factors.*'   => 'required|exists:factors,id',
            'types'       => 'required|array|min:1',
            'types.*'     => 'required|exists:types,id',
            'division_id' => 'required|exists:divisions,id|accessable',
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
            'summa'       => __('risks.summa'),
            'damage'      => __('risks.damage'),
            'level'       => __('risks.level'),
            'status'      => __('risks.status'),
            'likelihood'  => __('risks.likelihood'),
            'impact'      => __('risks.impact'),
            'expired_at'  => __('risks.expired_at'),
            'factors'     => __('factors.factors'),
            'types'       => __('types.types'),
            'division_id' => __('divisions.division'),
        ];
    }

    /**
     * Валидационные сообщения
     *
     * @return array
     */
    public function messages()
    {
        return [
            'expired_at.after' => __('validation.after', ['date' => now()->format('d.m.Y')])
        ];
    }
}
