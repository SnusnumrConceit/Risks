<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use App\Http\Requests\User\IndexUser;
use App\Http\Requests\User\StoreUser;
use App\Http\Requests\User\UpdateUser;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * Список и поиск пользователей
     *
     * @param IndexUser $request
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(IndexUser $request)
    {
        $this->authorize('viewAny', User::class);

        $usersQuery = User::query();

        $usersQuery->when($request->keyword, function ($query, $keyword) {
            return $query->search($keyword);
        });

        $usersQuery->when($request->role, function ($query, $role) {
           return $query->whereHas('role', function ($roleQuery) use ($role){
              return $roleQuery->where('uuid', '=', $role);
           });
        });

        $users = $usersQuery->with('role')->paginate();
        /** @var  $roles - TODO вынести в композер */
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Показ формы создания пользователя
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        /** @var  $roles - TODO вынести в композер */
        $roles = Role::all();

        return view('users.create', compact('roles'));
    }

    /**
     * Создание пользователя
     *
     * @param  StoreUser  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {
        $user = User::create($request->validated());

        return redirect()->route('users.show', ['user' => $user->uuid])
            ->withSuccess(__('users.created'));
    }

    /**
     * Карточка пользователя
     *
     * @param  \App\User $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        $user->load('role');

        return view('users.show', compact('user'));
    }

    /**
     * Показ формы редактирования пользователя
     *
     * @param  \App\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        /** @var  $roles - TODO вынести в композер */
        $roles = Role::all();
        $user->load('role');

        return view('users.edit', compact('roles', 'user'));
    }

    /**
     * Обновление пользователя
     *
     * @param  UpdateUser  $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, User $user)
    {
        $user->update($request->validated());

        return redirect()->route('users.show', ['user' => $user->uuid])
            ->withSuccess(__('users.updated'));
    }

    /**
     * Удаление пользователя
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->withSuccess(__('users.deleted'));
    }
}
