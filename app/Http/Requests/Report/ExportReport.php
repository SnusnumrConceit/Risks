<?php

namespace App\Http\Requests\Report;

use Maatwebsite\Excel\Excel;
use Illuminate\Foundation\Http\FormRequest;

class ExportReport extends FormRequest
{
    /**
     * Проверка авторизации
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Правила валидации параметров для экспорта отчёта
     *
     * @return array
     */
    public function rules()
    {
        return [
            'from'      => 'required|date|before_or_equal:to',
            'to'        => 'required|date|after:from',
            'cols'      => 'required|array|min:1',
            'cols.*'    => 'required|in:' . $this->getStringRequiredCols(),
            'extension' => 'nullable|in:' . $this->getExportStringExtensions()
        ];
    }

    /**
     * Расширения в строковом формате
     *
     * @return string
     * @throws \ReflectionException
     */
    private function getExportStringExtensions() : string
    {
        $extensions = (new \ReflectionClass(Excel::class))->getConstants();

        return implode(',', $extensions);
    }

    /**
     * Колонки в строковом формате
     *
     * @return string
     */
    private function getStringRequiredCols() : string
    {
        return implode(',', config('report.cols'));
    }

    /**
     * Атрибуты параметров
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'from'      => __('reports.risks.from'),
            'to'        => __('reports.risks.to'),
            'cols'      => __('reports.risks.cols'),
            'cols.*'    => __('reports.risks.col'),
            'extension' => __('reports.extension')
        ];
    }
}
