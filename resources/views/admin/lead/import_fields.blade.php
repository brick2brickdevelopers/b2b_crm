
@extends('layouts.app')

@section('page-title')
    {{-- <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-8 col-sm-8 col-md-8 col-xs-12 text-right bg-title-right">
            <a href="{{ route('admin.leads.create') }}" class="btn btn-outline btn-success btn-sm">@lang('modules.lead.addNewLead') <i
                    class="fa fa-plus" aria-hidden="true"></i></a>

            <a href="{{ route('admin.leads.kanbanboard') }}" class="btn btn-outline btn-primary btn-sm">@lang('modules.lead.kanbanboard')
            </a>

            <a href="{{ route('admin.lead-form.index') }}" class="btn btn-outline btn-inverse btn-sm">@lang('modules.lead.leadForm') <i
                    class="fa fa-pencil" aria-hidden="true"></i></a>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>

     
    </div> --}}
@endsection


@section('content')
<div class="container">
    <form class="form-horizontal" method="POST" action="{{ route('admin.leads.import_process') }}">
        {{ csrf_field() }}
        <input type="hidden" name="csv_data_file_id" value="{{ $csv_data_file->id }}" />
    
         <table class="table table-responsive ">
        @if (isset($csv_header_fields))
        <tr>
            @foreach ($csv_header_fields as $csv_header_field)
                <th>{{ $csv_header_field }}</th>
            @endforeach
        </tr>
        @endif
        @foreach ($csv_data as $row)
            <tr>
            @foreach ($row as $key => $value)
                <td>{{ $value }}</td>
            @endforeach
            </tr>
        @endforeach
        <tr>
            @foreach ($csv_data[0] as $key => $value)
                <td>
                    <select name="fields[{{ $key }}]">
                        @foreach (config('app.db_fields') as $db_field)
                            <option value="{{ (\Request::has('header')) ? $db_field : $loop->index }}"
                                @if ($key === $db_field) selected @endif>{{ $db_field }}</option>
                        @endforeach
                    </select>
                </td>
            @endforeach
        </tr>
    </table>

    <button type="submit" class="btn btn-primary">
        Import Data
    </button>
    </form>
</div>
@endsection

