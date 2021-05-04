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

        $risksQuery = Risk::query();

        $risksQuery->when($request->keyword, function ($query, $keyword) {
            $escapedKeyword = preg_replace('/[^\p{L}\p{N}_]+/u', ' ', $keyword);
            $escapedKeyword = preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $escapedKeyword);

           return $query->whereRaw("MATCH(name,description) AGAINST('+{$escapedKeyword}' IN BOOLEAN MODE)");
        });

        $risksQuery->when($request->level, function ($query, $level) {
            return $query->where('level', $level);
        });

        $risksQuery->when($request->status, function ($query, $status) {
            return $query->where('status', $status);
        });

        $risksQuery->when($request->created_at, function ($query, $created_at) {
            return $query->where(function($query) use ($created_at) {
                return $query->where('created_at', '>=', $created_at . ' 00:00:00')
                    ->where('expired_at', '<=', request('expired_at') . ' 23:59:59');
            });
        });

        $risksQuery->when($request->likelihood, function ($query, $likelihood) {
            return $query->where('likelihood', $likelihood);
        });

        $risksQuery->when($request->impact, function ($query, $impact) {
           return $query->where('impact', $impact);
        });

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
