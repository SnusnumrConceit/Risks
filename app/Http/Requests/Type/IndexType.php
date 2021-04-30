<?php

namespace App\Http\Requests\Type;

use App\Http\Requests\BaseIndexRequest;

class IndexType extends BaseIndexRequest
{
    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules()
    {
        return [
            'keyword' => 'nullable|between:5,100'
        ];
    }
}
