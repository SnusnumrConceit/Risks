<?php

namespace App\Http\Requests\Risk;

use App\Risk;

class UpdateRisk extends StoreRisk
{
    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                'status' => 'required|in:' . implode(',', Risk::getStatuses()),
                'damage' => 'nullable|between:0,99999999.00',
            ]
        );
    }

    /**
     * Атрибуты валидации
     *
     * @return array
     */
    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            [
                'damage'      => __('risks.damage'),
                'status'      => __('risks.status'),
            ]
        );
    }
}
