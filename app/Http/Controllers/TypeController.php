<?php

namespace App\Http\Controllers;

use App\Type;
use App\Http\Requests\Type\IndexType;
use App\Http\Requests\Type\StoreType;
use App\Http\Requests\Type\UpdateType;

class TypeController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Type::class);
    }

    /**
     * Список видов рисков с результатами поиска
     *
     * @param IndexType $request
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(IndexType $request)
    {
        $this->authorize('viewAny', Type::class);

        $typeyQuery = Type::query();

        $typeyQuery->when($request->keyword, function ($query, $keyword) {
            return $query->where('name', 'LIKE', $keyword . '%');
        });

        $types = $typeyQuery->paginate();

        return view('types.index', compact('types'));
    }

    /**
     * Показ формы создания вида риска
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('types.create');
    }

    /**
     * Сохранение вида риска
     *
     * @param  StoreType  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreType $request)
    {
        $type = Type::create($request->validated());

        return redirect()->route('types.edit', ['type' => $type->id])
            ->withSuccess(__('types.created'));
    }

    /**
     * Карточка вида риска
     *
     * @param  \App\Type  $type
     * @return \Illuminate\View\View
     */
    public function show(Type $type)
    {
        return view('types.show', compact('type'));
    }

    /**
     * Показ формы редактирования вида риска
     *
     * @param  \App\Type  $type
     * @return \Illuminate\View\View
     */
    public function edit(Type $type)
    {
        return view('types.edit', compact('type'));
    }

    /**
     * Обновление вида риска
     *
     * @param  UpdateType  $request
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateType $request, Type $type)
    {
        $type->update($request->validated());

        return redirect()->route('types.edit', ['type' => $type->id])
            ->withSuccess(__('types.updated'));
    }

    /**
     * Удаление вида риска
     *
     * @param Type $type
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Type $type)
    {
        $type->delete();

        return redirect()->route('types.index')
            ->withSuccess(__('types.deleted'));
    }
}
