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
                            <input type="date" class="form-control" name="from" value="{{ old('from', request('from')) }}">
                        </div>
                        <div class="col-6">
                            <input type="date" class="form-control" name="to" value="{{ old('to', request('to')) }}">
                        </div>
                    </div>
                    <legend class="border-bottom">
                        {{ __('reports.choose.cols') }}
                    </legend>
                    <div class="form-group row">
                        @foreach(config('report.cols') as $col)
                            <div class="col-3">
                                <label for="{{ $col }}">
                                    <input type="checkbox" id="{{ $col }}" name="cols[]" value="{{ $col }}" checked>
                                    {{ __('risks.' . $col) }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-group row">
                        <div class="col-12">
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
