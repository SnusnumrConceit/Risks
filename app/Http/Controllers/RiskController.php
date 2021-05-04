<?php

namespace App\Http\Controllers;

use App\Risk;
use App\Factor;
use App\Division;
use App\Http\Requests\Risk\IndexRisk;
use App\Http\Requests\Risk\StoreRisk;
use App\Http\Requests\Risk\UpdateRisk;

class RiskController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Risk::class);
    }

    /**
     * Список рисков с результатами поиска
     *
     * @param IndexRisk $request
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(IndexRisk $request)
    {
        $this->authorize('viewAny', Risk::class);

        $risksQuery = Risk::search($request);

        $risks = $risksQuery->paginate();

        return view('risks.index', compact('risks'));
    }

    /**
     * Показ формы создания риска
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $factors = Factor::orphans()->get();
        $divisions = Division::orphans()->get();
        return view('risks.create', compact('factors', 'divisions'));
    }

    /**
     * Сохранение риска
     *
     * @param  StoreRisk  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRisk $request)
    {
        $data = $request->except(['factors', 'types', 'divisions']);
        $risk = Risk::create($data);

        return redirect()->route('risks.show', ['risk' => $risk->uuid])
            ->withSuccess(__('risks.created'));
    }

    /**
     * Карточка риска
     *
     * @param  \App\Risk  $risk
     * @return \Illuminate\View\View
     */
    public function show(Risk $risk)
    {
        $risk->load(['factors', 'divisions', 'types']);

        return view('risks.show', compact('risk'));
    }

    /**
     * Показ формы редактирования риска
     *
     * @param  \App\Risk  $risk
     * @return \Illuminate\View\View
     */
    public function edit(Risk $risk)
    {
        $factors = Factor::orphans()->get();
        $divisions = Division::orphans()->get();
        $risk->load(['factors', 'divisions', 'types']);

        return view('risks.edit', compact('risk', 'factors', 'divisions'));
    }

    /**
     * Обновление риска
     *
     * @param  UpdateRisk  $request
     * @param  \App\Risk  $risk
     * @return \Illuminate\View\View
     */
    public function update(UpdateRisk $request, Risk $risk)
    {
        $data = $request->except(['factors', 'types']);

        $risk->update($data);

        return redirect()->route('risks.show', ['risk' => $risk->uuid])
            ->withSuccess(__('risks.updated'));
    }

    /**
     * Удаление риска
     *
     * @param \App\Risk $risk
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Risk $risk)
    {
        $risk->delete();

        return redirect()->route('risks.index')
            ->withSuccess(__('risks.deleted'));
    }
}
