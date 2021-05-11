<?php

namespace App\Http\Requests\Risk;

use App\Risk;
use App\Http\Requests\BaseIndexRequest;

class IndexRisk extends BaseIndexRequest
{
    use ShouldVerifyDivision;

    private $user;

    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules()
    {
        return [
            'keyword'    => 'nullable|max:255',
            'level'      => 'nullable|in:' . implode(',', Risk::getLevels()),
            'status'     => 'nullable|in:' . implode(',', Risk::getStatuses()),
            'created_at' => 'nullable|date',
            'expired_at' => 'nullable|required_with:created_at|date|after:created_at',
            'likelihood' => 'nullable|integer',
            'impact'     => 'nullable|integer',
            'factors'     => 'nullable|array|min:1',
            'factors.*'   => 'required|exists:factors,id',
            'types'       => 'nullable|array|min:1',
            'types.*'     => 'required|exists:types,id',
            'division'    => 'nullable|exists:divisions,id|accessable',
        ];
    }

    /**
     * Переводы атрибутов
     *
     * @return array
     */
    public function attributes()
    {
        return array_merge(
            parent::rules(),
            [
                'level'      => __('risks.level'),
                'status'     => __('risks.status'),
                'created_at' => __('risks.created_at'),
                'expired_at' => __('risks.expired_at'),
                'likelihood' => __('risks.likelihood'),
                'impact'     => __('risks.impact'),
                'factors'    => __('factors.factors'),
                'types'      => __('types.types'),
                'division'   => __('divisions.division'),
            ]
        );
    }
}
