@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="row col-12">
            <div class="h2">
                @include('common.back', ['title' => __('metrics.metrics')])
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h2>{{ __('risks.risks') }}</h2>
                    <div class="form-group mb-4 pb-4 border-bottom">
                        <div class="row my-4">
                            {{-- Суммарная метрика по рискам --}}
                            <div class="col-md-6">
                                <risks-amount-metric
                                    :headers="['{{ __('risks.risks') }}']"
                                    label="{{ __('risks.risks') }}"
                                    :data="[{{ $risks_amount }}]"
                                    :colors="['rgb(54, 162, 235)']"  синий
                                />
                            </div>
                            {{-- Суммарная метрика по головным подразделениям --}}
                            <div class="col-md-6">
                                <risks-divisions-metric
                                    :headers="['{{ implode("', '", array_keys($risks_divisions_metric)) }}']"
                                    label="{{ __('divisions.divisions') }}"
                                    :data="[{{ implode(',', $divisions = array_values($risks_divisions_metric)) }}]"
                                    :colors="[
                                @foreach($divisions as $division)
                                        '{{ generate_color() }}',
                                @endforeach
                                        ]"
                                />
                            </div>
                        </div>

                        <div class="row my-4">
                            {{-- Суммарная метрика по видам рисков --}}
                            <div class="col-md-6">
                                <risks-types-metric
                                    :headers="['{{ implode("', '", array_keys($risks_types_metric)) }}']"
                                    label="{{ __('types.types') }}"
                                    :data="[{{ implode(',', $types = array_values($risks_types_metric)) }}]"
                                    :colors="[
                                @foreach($types as $type)
                                        '{{ generate_color() }}',
                                @endforeach
                                        ]"
                                />
                            </div>
                            {{-- Суммарная метрика по факторам рисков --}}
                            <div class="col-md-6">
                                <risks-factors-metric
                                    :headers="['{{ implode("', '", array_keys($risks_factors_metric)) }}']"
                                    label="{{ __('factors.factors') }}"
                                    :data="[{{ implode(',', $factors = array_values($risks_factors_metric)) }}]"
                                    :colors="[
                                @foreach($factors as $factor)
                                        '{{ generate_color() }}',
                                @endforeach
                                        ]"
                                />
                            </div>
                        </div>

                        <div class="row my-4">
                            {{-- Суммарная метрика по статусам рисков --}}
                            <div class="col-md-6">
                                <risks-statuses-metric
                                    :headers="['{{ implode("', '", $statuses = array_keys($risks_statuses_metric)) }}']"
                                    label="{{ __('factors.factors') }}"
                                    :data="[{{ implode(',', array_values($risks_statuses_metric)) }}]"
                                    :colors="[
                                @foreach($statuses as $status)
                                        '{{ $statusesColors[$status] }}',
                                @endforeach
                                        ]"
                                />
                            </div>
                        </div>
                    </div>

                    @if(auth()->user()->hasPermission('users_view'))
                        <div class="form-group">
                            <h2>{{ __('users.users') }}</h2>
                            <div class="row my-4">
                                {{-- Суммарная метрика по пользователям --}}
                                <div class="col-md-6">
                                    <users-amount-metric
                                        :headers="['{{ __('users.users') }}']"
                                        label="{{ __('users.users') }}"
                                        :data="[{{ $users_amount }}]"
                                        :colors="['rgb(255, 99, 132)']" {{-- красный --}}
                                    />
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
