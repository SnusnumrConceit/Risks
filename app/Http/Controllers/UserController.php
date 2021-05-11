<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use App\Division;
use App\Http\Requests\User\IndexUser;
use App\Http\Requests\User\StoreUser;
use App\Http\Requests\User\UpdateUser;

class UserController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            $this->authorizeResource(User::class);

            return $next($request);
        });
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

        $canPersonalUsersAccess = ! $this->user->hasPermission('users_view');

        $usersQuery->when($canPersonalUsersAccess, function ($query) {
           return $query->whereNotNull('division_id')->where('id', '<>', $this->user->id);
        });

        $users = $usersQuery->with(['role', 'division' => function ($query) use ($canPersonalUsersAccess) {
            if (! $canPersonalUsersAccess) return $query;

            return $query->whereIn(
                'id',
                Division::getDescendantsIds($this->user->division_id, $this->user->division->level)
            );
        }])->paginate();
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
        $roles = Role::all();

        if ($this->user->hasPermission('divisions_view')) {
            $availableDivisions = Division::orphans()->get();
        } else {
            $availableDivisions = collect()->push($this->user->division);
        }

        return view('users.create', compact('roles', 'availableDivisions'));
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
        $user->load('role', 'division');

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
        $user->load('role', 'division');
        $roles = Role::all();

        if ($this->user->hasPermission('divisions_view')) {
            $availableDivisions = Division::orphans()->get();
        } else {
            $availableDivisions = collect()->push($this->user->division);
        }

        return view('users.edit', compact('roles', 'availableDivisions', 'user'));
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
