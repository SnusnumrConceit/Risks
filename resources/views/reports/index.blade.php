@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="row col-12">
                <div class="h2">
                    @include('common.back', ['title' => __('reports.reports')])
                </div>
            </div>
            <div class="card card-body">
                <form action="{{ route('reports.export') }}" method="POST">
                    {{ csrf_field() }}
                    <legend class="border-bottom">
                        {{ __('reports.choose.date_range') }}
                    </legend>
                    <div class="form-group row">
                        <div class="col-6">
                            <input type="date"
                                   class="form-control @if($errors->has('from')) is-invalid @endif"
                                   name="from"
                                   value="{{ old('from', request('from')) }}"
                            >
                            @if($errors->has('from'))
                                <span class="invalid-feedback">
                                    {{ $errors->first('from') }}
                                </span>
                            @endif
                        </div>
                        <div class="col-6">
                            <input type="date"
                                   class="form-control @if($errors->has('to')) is-invalid @endif"
                                   name="to"
                                   value="{{ old('to', request('to')) }}"
                            >
                            @if($errors->has('to'))
                                <span class="invalid-feedback">
                                    {{ $errors->first('to') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <legend class="border-bottom">
                        {{ __('reports.choose.cols') }}
                    </legend>
                    <div class="form-group row">
                        @foreach(config('report.cols') as $col)
                            <div class="col-3">
                                <label for="{{ $col }}">
                                    <input type="checkbox"
                                           id="{{ $col }}"
                                           name="cols[]"
                                           value="{{ $col }}"
                                           @if(empty($cols = old('cols', [])) || in_array($col, $cols)) checked @endif
                                    >
                                    {{ __('risks.' . $col) }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-group row">
                        <div class="col-2">
                            <select name="extension" id="extension" class="form-control">
                                @foreach($extensions as $value => $extension)
                                    <option value="{{ $value }}"
                                            @if($extension === \Maatwebsite\Excel\Excel::XLSX) selected @endif>
                                        {{ $extension }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-10">
                            <button type="submit" class="btn btn-outline-success">
                                {{ __('ui.export') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
