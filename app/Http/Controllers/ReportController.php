<?php

namespace App\Http\Controllers;

use App\Http\Requests\Report\ExportReport;

class ReportController extends Controller
{
    /**
     * Получить форму с отчётами
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Выгрузка отчёта
     *
     * @param ExportReport $request
     * @return mixed
     */
    public function export(ExportReport $request)
    {
        return back()->withSuccess('Экспорт успешно завершён');
    }
}
