<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use App\Exports\RisksExport;
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
        $filters   = $request->validated();
        $extension = Arr::pull($filters, 'extension');

        return new RisksExport($request->validated(), $extension);
    }
}
