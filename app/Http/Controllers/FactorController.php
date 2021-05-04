<?php

namespace App\Http\Controllers;

use App\Factor;
use App\Http\Requests\Factor\IndexFactor;
use App\Http\Requests\Factor\StoreFactor;
use App\Http\Requests\Factor\UpdateFactor;

class FactorController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Factor::class);
    }

    /**
     * Список факторов рисков с результатами поиска
     *
     * @param IndexFactor $request
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(IndexFactor $request)
    {
        $this->authorize('viewAny', Factor::class);

        $factoryQuery = Factor::query();

        $factoryQuery->when($request->keyword, function ($query, $keyword) {
            return $query->where('name', 'LIKE', $keyword . '%');
        });

        $factors = $factoryQuery->with('parent')->paginate();

        return view('factors.index', compact('factors'));
    }

    /**
     * Показ формы создания фактора
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('factors.create');
    }

    /**
     * Сохранение фактора
     *
     * @param  StoreFactor  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFactor $request)
    {
        $factor = Factor::create($request->validated());

        return redirect()->route('factors.edit', ['factor' => $factor->id])
            ->withSuccess(__('factors.created'));
    }

    /**
     * Карточка фактора
     *
     * @param  \App\Factor  $factor
     * @return \Illuminate\View\View
     */
    public function show(Factor $factor)
    {
        $factor->load('parent');

        return view('factors.show', compact('factor'));
    }

    /**
     * Показ формы редактирования фактора
     *
     * @param  \App\Factor  $factor
     * @return \Illuminate\View\View
     */
    public function edit(Factor $factor)
    {
        $factor->load('parent');

        $availableFactors = Factor::whereIn(
            'id',
            Factor::whereNull('parent_id')
                ->where('id', '<>', $factor->id)
                ->pluck('id')
                ->toArray() // на случай внедрения MaterializedPath array_merge($factor->children->pluck('id')->toArray(), [$factor->id])
        )->get();

        return view('factors.edit', compact('factor', 'availableFactors'));
    }

    /**
     * Обновление фактора
     *
     * @param  UpdateFactor  $request
     * @param  \App\Factor  $factor
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFactor $request, Factor $factor)
    {
        $factor->update($request->validated());

        return redirect()->route('factors.edit', ['factor' => $factor])
            ->withSuccess(__('factors.updated'));
    }

    /**
     * Удаление фактора
     *
     * @param Factor $factor
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Factor $factor)
    {
        $factor->delete();

        return redirect()->route('factors.index')
            ->withSuccess(__('factors.deleted'));
    }
}
