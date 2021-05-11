<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

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

    /**
     * Сброс поиска путём редиректа на страницу без GET-параметров
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws ValidationException
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->url());
    }
}
