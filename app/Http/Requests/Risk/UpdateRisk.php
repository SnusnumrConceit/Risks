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
        $rules = parent::rules();

        $rules['status'] = 'required|in:' . implode(',', Risk::getStatuses());

        return $rules;
    }
}
