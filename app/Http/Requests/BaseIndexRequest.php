<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class BaseIndexRequest extends FormRequest
{
    protected $maxKeywordSymbols = 100;

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
            'keyword' => 'nullable|between:5,' . $this->maxKeywordSymbols
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
            'keyword' => __('ui.keyword')
        ];
    }
}
