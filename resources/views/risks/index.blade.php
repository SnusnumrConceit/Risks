@extends('layouts.app')

@section('content')
    <div class="row col-12">
        <div class="h2">
            @include('common.back', ['title' => __('risks.risks')])

            @can('create', \App\Risk::class)
                <a href="{{ route('risks.create') }}" class="btn btn-sm btn-outline-success">
                    {{ __('ui.add') }}
                </a>
            @endcan
        </div>
    </div>

    <div class="card card-body">
        <form>
            <div class="form-group row">
                    <div class="col">
                        <input type="text"
                               class="form-control"
                               name="keyword"
                               value="{{ request('keyword') }}"
                               placeholder="{{ __('risks.search_string') }}"
                        >
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-outline-success" type="submit">
                            {{ __('ui.search') }}
                        </button>
                    </div>
            </div>
            <div class="form-group row">
                <div class="col-6">
                    <input type="date"
                           name="created_at"
                           value="{{ old('created_at', request('created_at') ?? now()->toDateString()) }}"
                           class="form-control"
                           placeholder="{{ __('risks.created_at') }}"
                    >
                </div>
                <div class="col-6">
                    <input type="date"
                           name="expired_at"
                           value="{{ old('expired_at', request('expired_at') ?? now()->addWeeks(2)->toDateString()) }}"
                           class="form-control"
                           placeholder="{{ __('risks.expired_at') }}"
                    >
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-3">
                    <select class="form-control"
                            name="likelihood"
                    >
                        <option value="">{{ __('risks.likelihood') }}</option>
                        @for($likelihood = 1; $likelihood <= 5; $likelihood++)
                            <option value="{{ $likelihood }}"
                                    @if(intval($likelihood) === intval(request('likelihood'))) selected @endif
                            >
                                {{ $likelihood }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-lg-3">
                    <select class="form-control"
                            name="impact"
                    >
                        <option value="">{{ __('risks.impact') }}</option>
                        @for($impact = 1; $impact <=5; $impact++)
                            <option value="{{ $impact }}"
                                    @if(intval($impact) === intval(request('impact'))) selected @endif
                            >
                                {{ $impact }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-lg-3">
                    <select name="level" class="form-control">
                        <option value="">{{ __('risks.level') }}</option>
                        @foreach(\App\Risk::getLevels() as $level)
                            <option value="{{ $level }}"
                                    @if(request('level') === $level) selected @endif
                            >
                                {{ __('risks.levels.' . $level) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3">
                    <select name="status" class="form-control">
                        <option value="">{{ __('risks.status') }}</option>
                        @foreach(\App\Risk::getStatuses() as $status)
                            <option value="{{ $status }}"
                                    @if(request('status') === $status) selected @endif
                            >
                                {{ __('risks.statuses.' . $status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mt-3 row col-12">
                    <div class="col-lg-3">
                        <div class="dropdown">
                            <button class="btn btn-outline-dark dropdown-toggle"
                                    type="button"
                                    id="types-filter"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false">
                                {{ __('types.types') }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="types-filter">
                                @foreach(\App\Type::orderBy('name')->get() as $key => $type)
                                    <li>
                                        <label class="dropdown-item" for="type-{{ $type->id }}">
                                            <input type="checkbox"
                                                   value="{{ $type->id }}"
                                                   name="types[]"
                                                   id="type-{{ $type->id }}"
                                                   @if(in_array($type->id, request('types', []))) checked @endif
                                            >
                                            {{ $type->name }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-3 ml-2">
                        <div class="dropdown">
                            <button class="btn btn-outline-dark dropdown-toggle"
                                    type="button"
                                    id="factors-filter"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false">
                                {{ __('factors.factors') }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="factors-filter">
                                @foreach(\App\Factor::orderBy('name')->orphans()->get() as $key => $factor)
                                    <li>
                                        <label class="dropdown-item" for="factor-{{ $factor->id }}">
                                            <input type="checkbox"
                                                   value="{{ $factor->id }}"
                                                   name="factors[]" id="factor-{{ $factor->id }}"
                                                   @if(in_array($factor->id, request('factors', []))) checked @endif
                                            >
                                            {{ $factor->name }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-3 ml-2">
                        @if(auth()->user()->is_responsible || auth()->user()->hasPermission('divisions_view'))
                            @include('risks.divisions_selector', ['divisions' => $divisions, 'name' => 'division_id'])
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <table class="table table-hover table-responsive">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">{{ __('risks.name') }}</th>
                        <th scope="col">{{ __('divisions.division') }}</th>
                        <th scope="col">{{ __('risks.summa') }}</th>
                        <th scope="col">{{ __('risks.damage') }}</th>
                        <th scope="col">{{ __('risks.level') }}</th>
                        <th scope="col">{{ __('risks.status') }}</th>
                        <th scope="col">{{ __('risks.likelihood') }}</th>
                        <th scope="col">{{ __('risks.impact') }}</th>
                        <th scope="col">{{ __('risks.created_at') }}</th>
                        <th scope="col">{{ __('risks.expired_at') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($risks as $risk)
                    <tr>
                        <td>
                            <a href="{{ route('risks.show', ['risk' => $risk->uuid]) }}">
                                {{ $risk->name }}
                            </a>
                        </td>
                        <td>{{ optional($risk->division)->name ?? '' }}</td>
                        <td>{{ $risk->summa }}</td>
                        <td>{{ $risk->damage }}</td>
                        <td>{{ __('risks.levels.' . $risk->level) }}</td>
                        <td>{{ __('risks.statuses.'. $risk->status) }}</td>
                        <td>{{ $risk->likelihood }}</td>
                        <td>{{ $risk->impact }}</td>
                        <td>{{ $risk->created_at->format('d.m.Y') }}</td>
                        <td>{{ $risk->expired_at->format('d.m.Y') }}</td>
                        <td>
                            <div class="row col justify-content-end">
                                @include('common.actions', ['entityName' => 'risk', 'entity' => $risk])
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan=11>{{ __('risks.empty') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 text-center">
        {{ $risks->links() }}
    </div>
@endsection
