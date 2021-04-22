<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseIndexRequest;

class IndexUser extends BaseIndexRequest
{
    protected $maxKeywordSymbols = 50;

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
                'role' => 'nullable|exists:roles,uuid'
            ]
        );
    }
}
