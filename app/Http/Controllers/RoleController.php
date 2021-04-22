<?php

namespace App\Http\Controllers;

use App\Role;
use App\Permission;
use Illuminate\Support\Arr;
use App\Http\Requests\Role\IndexRole;
use App\Http\Requests\Role\StoreRole;
use App\Http\Requests\Role\UpdateRole;
use App\Http\Requests\Role\DestroyRole;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Role::class);
    }

    /**
     * Список и поиск ролей
     *
     * @param IndexRole $request
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(IndexRole $request)
    {
        $this->authorize('viewAny', Role::class);

        $rolesQuery = Role::query();

        $rolesQuery->when($request->keyword, function ($query, $keyword) {
            return $query->where('name', 'like', $keyword . '%');
        });

        $roles = $rolesQuery->paginate();

        return view('roles.index', compact('roles'));
    }

    /**
     * Показ формы создания роли
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /** @var  $permissionGroups - TODO вынести в ViewComposer */
        $permissionGroups = Permission::where('name', '<>', 'full')
            ->get()
            ->groupBy('group');

        return view('roles.create', compact('permissionGroups'));
    }

    /**
     * Сохранение роли
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function store(StoreRole $request)
    {
        $data = $request->validated();
        $permissions = Arr::pull($data, 'permissions');

        $role = Role::create($data);

        $role->permissions()->sync($permissions);

        return redirect()->route('roles.show', ['role' => $role->uuid])
            ->withSuccess(__('roles.created'));
    }

    /**
     * Карточка роли
     *
     * @param  \App\Role  $role
     * @return \Illuminate\View\View
     */
    public function show(Role $role)
    {
        /** @var  $permissionGroups - TODO вынести в ViewComposer */
        $permissionGroups = Permission::where('name', '<>', 'full')
            ->get()
            ->groupBy('group');

        $role->load('permissions');

        return view('roles.show', compact('role', 'permissionGroups'));
    }

    /**
     * Показ формы редактирования роли
     *
     * @param  \App\Role  $role
     * @return \Illuminate\View\View
     */
    public function edit(Role $role)
    {
        /** @var  $permissionGroups - TODO вынести в ViewComposer */
        $permissionGroups = Permission::where('name', '<>', 'full')
            ->get()
            ->groupBy('group');

        $role->load('permissions');

        return view('roles.edit', compact('role', 'permissionGroups'));
    }

    /**
     * Обновление роли
     *
     * @param  UpdateRole  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRole $request, Role $role)
    {
        $data = $request->validated();
        $permissions = Arr::pull($data, 'permissions');

        $role->update($data);

        $role->permissions()->sync($permissions);

        return redirect()->route('roles.show', ['role' => $role->uuid])
            ->withSuccess(__('roles.updated'));

    }

    /**
     *  Удаление роли
     *
     * @param DestroyRole $request
     * @param Role $role
     * @return mixed
     * @throws \Exception
     */
    public function destroy(DestroyRole $request, Role $role)
    {
        $role->delete();

        $role->permissions()->detach();

        return redirect()->route('roles.index')
            ->withSuccess(__('roles.deleted'));
    }
}
