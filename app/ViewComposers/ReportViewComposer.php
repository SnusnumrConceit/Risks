<?php


namespace App\ViewComposers;


use Illuminate\View\View;
use Maatwebsite\Excel\Excel;

class ReportViewComposer
{
    public function compose(View $view)
    {
        $extensions = [];
        $available  = [Excel::XLSX, Excel::XLS, Excel::CSV, Excel::ODS];

        foreach ((new \ReflectionClass(Excel::class))->getConstants() as $extension) {
            if (! in_array($extension, $available) || in_array($extension, $extensions)) continue;

            $extensions[$extension] = strtoupper($extension);
        }

        return $view->with(compact('extensions'));
    }
}
