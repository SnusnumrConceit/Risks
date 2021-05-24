<nav class="bg-white col-md-auto d-md-block d-none sidebar border-top shadow-sm">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item py-2">
                <a class="nav-link @if(request()->is('metrics*')) bg-info text-white @endif" href="{{ route('metrics.index') }}">
                    {{ __('metrics.metrics') }}
                </a>
            </li>
            @can('viewAny', \App\Risk::class)
                <li class="nav-item py-2">
                    <a class="nav-link @if(request()->is('risks*')) bg-info text-white @endif" href="{{ route('risks.index') }}">
                        {{ __('risks.risks') }}
                    </a>
                </li>
            @endcan
            @can('viewAny', \App\Division::class)
                <li class="nav-item py-2">
                    <a class="nav-link @if(request()->is('divisions*')) bg-info text-white @endif" href="{{ route('divisions.index') }}">
                        {{ __('divisions.divisions') }}
                    </a>
                </li>
            @endcan
            @can('viewAny', \App\Factor::class)
                <li class="nav-item py-2">
                    <a class="nav-link @if(request()->is('factors*')) bg-info text-white @endif" href="{{ route('factors.index') }}">
                        {{ __('factors.factors') }}
                    </a>
                </li>
            @endcan
            @can('viewAny', \App\Type::class)
                <li class="nav-item py-2">
                    <a class="nav-link @if(request()->is('types*')) bg-info text-white @endif" href="{{ route('types.index') }}">
                        {{ __('types.types') }}
                    </a>
                </li>
            @endcan
            @can('viewAny', \App\User::class)
                <li class="nav-item py-2">
                    <a class="nav-link @if(request()->is('users*')) bg-info text-white @endif" href="{{ route('users.index') }}">
                        {{ __('users.users') }}
                    </a>
                </li>
            @endcan
            @can('viewAny', \App\Role::class)
                <li class="nav-item py-2">
                    <a class="nav-link @if(request()->is('roles*')) bg-info text-white @endif" href="{{ route('roles.index') }}">
                        {{ __('roles.roles') }}
                    </a>
                </li>
            @endcan
        </ul>
        <hr class="m-0">
        <ul class="nav flex-column mb-2">
            <li class="nav-item py-2">
                <a class="nav-link @if(request()->is('reports*')) bg-info text-white @endif" href="{{ route('reports.index') }}">
                    {{ __('reports.reports') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('reports.index', ['from' => now()->startOfMonth(1)->toDateString(), 'to' => now()->toDateString()]) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    {{ __('reports.period.current_month') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('reports.index', ['from' => now()->startOfQuarter()->toDateString(), 'to' => now()->toDateString()]) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    {{ __('reports.period.quarter') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('reports.index', ['from' => ( now()->month < 7 ? now()->startOfYear() : now()->startOfYear()->addMonth(6))->toDateString(), 'to' => now()->toDateString()]) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    {{ __('reports.period.half_year') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('reports.index', ['from' => now()->startOfYear()->toDateString(), 'to' => now()->toDateString()]) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    {{ __('reports.period.year') }}
                </a>
            </li>
        </ul>
    </div>
</nav>
