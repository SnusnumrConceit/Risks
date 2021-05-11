<?php

namespace App\Http\Controllers;

use App\Division;
use App\Http\Requests\Division\IndexDivision;
use App\Http\Requests\Division\StoreDivision;
use App\Http\Requests\Division\UpdateDivision;
use App\Http\Requests\Division\DestroyDivision;

class DivisionController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            $this->authorizeResource(Division::class);

            return $next($request);
        });
    }

    /**
     * Список подразделений с результатами поиска.
     *
     * @param IndexDivision $request
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(IndexDivision $request)
    {
        $this->authorize('viewAny', Division::class);

        $divisionsQuery = Division::query();

        $divisionsQuery->when($request->keyword, function ($query, $keyword) {
            return $query->where('name', 'LIKE', $keyword . '%');
        });

        if (! $this->user->hasPermission('divisions_view')) {
            $divisionsQuery->whereIn(
                'id',
                Division::getDescendantsIds($this->user->division_id, $this->user->division->level)
            );
        }

        $divisions = $divisionsQuery->paginate();

        return view('divisions.index', compact('divisions'));
    }

    /**
     * Показ формы создания подразделения
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if ($this->user->hasPermission('divisions_view')) {
            $availableDivisions = Division::orphans()->get();
        } else {
            $availableDivisions = collect()->push($this->user->division);
        }

        return view('divisions.create', compact('availableDivisions'));
    }

    /**
     * Сохранение подразделения
     *
     * @param  StoreDivision  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDivision $request)
    {
        $division = Division::create($request->validated());

        return redirect()->route('divisions.show', ['division' => $division->uuid])
            ->withSuccess(__('divisions.created'));
    }

    /**
     * Карточка подразделения
     *
     * @param  \App\Division  $division
     * @return \Illuminate\View\View
     */
    public function show(Division $division)
    {
        $division->load('parent');

        return view('divisions.show', compact('division'));
    }

    /**
     * Показ формы редактирования подразделения
     *
     * @param  \App\Division  $division
     * @return \Illuminate\View\View
     */
    public function edit(Division $division)
    {
        $division->load('parent');

        if ($this->user->hasPermission('divisions_view')) {
            $availableDivisions = Division::orphans()->get();
        } else {
            $availableDivisions = collect()->push($this->user->division);
        }

        return view('divisions.edit', compact('division', 'availableDivisions'));
    }

    /**
     * Обновление подразделения
     *
     * @param  UpdateDivision $request
     * @param  \App\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDivision $request, Division $division)
    {
        $division->update($request->validated());

        return redirect()->route('divisions.show', ['division' => $division->uuid])
            ->withSuccess(__('divisions.updated'));
    }

    /**
     * Удаление подразделения
     *
     * @param DestroyDivision $request
     * @param Division $division
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(DestroyDivision $request, Division $division)
    {
        $division->delete();

        return redirect()->route('divisions.index')
            ->withSuccess(__('divisions.deleted'));
    }
}
