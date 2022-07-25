@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 bg-title-right">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.leads.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('app.edit')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <link rel="stylesheet"
        href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/summernote/dist/summernote.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-xs-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.lead.updateTitle')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                    {!! Form::open(['id'=>'updateLead','class'=>'ajax-form','method'=>'PUT']) !!}
                        <div class="form-body">
                            <h3 class="box-title">@lang('modules.lead.companyDetails')</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-1 ">
                                    <div class="form-group salutation" style="margin-top: 23px">
                                        <select name="salutation" id="salutation" class="form-control">
                                            <option value="">@lang('app.select')</option>
                                            <option value="mr" @if( $lead->client_surname == 'mr' ) selected  @endif  >@lang('app.mr')</option>
                                            <option value="mrs"  @if( $lead->client_surname == 'mrs' ) selected @endif>@lang('app.mrs')</option>
                                            <option value="miss"  @if( $lead->client_surname == 'miss' ) selected  @endif>@lang('app.miss')</option>
                                            <option value="dr"  @if( $lead->client_surname == 'dr' ) selected @endif>@lang('app.dr')</option>
                                            <option value="sir"  @if( $lead->client_surname == 'sir' ) selected  @endif>@lang('app.sir')</option>
                                            <option value="madam"  @if( $lead->client_surname == 'madam' ) selected @endif>@lang('app.madam')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('app.name')</label>
                                        <input type="text" name="client_name" value="{{ $lead->client_name }}" id="name" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="required">@lang('modules.lead.mobile') <span style="color: red">
                                            *</span></label>
                                    <div class="form-group" style="display: flex;">
                                        <select class="select2 phone_country_code form-control" name="phone_code"
                                            style=" border-right-top-radius: 0px;border-top-right-radius: 0px !important;   border-bottom-right-radius: 0px !important;">
                                            {{-- <option value="91">+91 (IN)</option> --}}
                                            @foreach ($countries as $item)
                                                <option value="{{ $item->phonecode }}" {{ ($item->phonecode == '91') ? 'selected' : '' }}>
                                                    +{{ $item->phonecode . ' (' . $item->iso . ')' }}</option>
                                            @endforeach
                                        </select>
                                        
                                        <input type="tel" name="mobile" value="{{ substr($lead->mobile, 3)  }}" id="mobile" class="form-control"
                                            style="border-top-left-radius: 0px;border-bottom-left-radius: 0px;">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('app.email')</label>
                                        <input type="email" name="email" value="{{ $lead->email }}" id="email" class="form-control">
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('app.next_follow_up')</label>
                                        <select name="next_follow_up" id="next_follow_up" class="form-control">
                                            <option value="yes" {{ $lead->next_follow_up =='yes'?'selected':'' }}> @lang('app.yes')</option>
                                            <option value="no" {{ $lead->next_follow_up =='no'?'selected':'' }}> @lang('app.no')</option>
                                        </select>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <h3 class="box-title required">Lead Address</h3>
                            <hr>
                        
                            <div class="row">
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <label>@lang('modules.stripeCustomerAddress.city')</label>
                                        <input type="text" name="city" value="{{ $lead->city }}" id="city" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <label>@lang('modules.stripeCustomerAddress.state')</label>
                                        <input type="text" name="state" id="state" value="{{ $lead->state }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <label>@lang('modules.stripeCustomerAddress.country')</label>
                                        <input type="text" name="country" value="{{ $lead->country }}" id="country" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <label>@lang('modules.stripeCustomerAddress.postalCode')</label>
                                        <input type="text" name="postal_code" value="{{ $lead->postal_code }}" id="postalCode" class="form-control">
                                    </div>
                                </div>
                            </div>
                            

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">@lang('modules.tickets.chooseAgents')
                                            <a href="javascript:;" id="addLeadAgent"
                                                class="btn btn-xs btn-outline btn-success"><i class="fa fa-plus"></i>
                                                @lang('app.add') @lang('app.leadAgent')</a></label>
                                        <select class="select2 form-control" data-placeholder="@lang('modules.tickets.chooseAgents')"
                                            id="agent_id" name="agent_id">
                                            <option value="">@lang('modules.tickets.chooseAgents')</option>
                                                        {# @foreach ($leadAgents as $emp)
                                                            <option value="{{ $emp->id }}">{{ ucwords($emp->user->name) }}
                                                                @if ($emp->user->id == $user->id)
                                                                    (YOU)
                                                                @endif
                                                            </option>
                                                        @endforeach #}

                                            @foreach ($leadAgents as $emp)
                                                <option @if ($emp->id == $lead->agent_id) selected @endif
                                                    value="{{ $emp->id }}">{{ ucwords($emp->user->name) }}
                                                    @if ($emp->user->id == $user->id)
                                                        (YOU)
                                                    @endif
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">@lang('modules.lead.leadSource') <a href="javascript:;" id="addLeadsource"
                                                class="btn btn-xs btn-outline btn-success"><i class="fa fa-plus"></i>
                                                @lang('app.add') @lang('modules.lead.leadSource')</a></label>
                                        <select class="select2 form-control" data-placeholder="@lang('modules.lead.leadSource')"
                                            id="source_id" name="source_id">
                                            @forelse($sources as $source)
                                                <option @if ($lead->source_id == $source->id) selected @endif
                                                    value="{{ $source->id }}"> {{ ucfirst($source->type) }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4 ">
                                    <div class="form-group" style="margin-top: 7px;">
                                        <label>@lang('modules.lead.leadCategory')
                                            <a href="javascript:;" id="addLeadCategory"
                                                class="btn btn-xs btn-success btn-outline"><i
                                                    class="fa fa-plus"></i></a>
                                        </label>
                                        <select class="select2 form-control" name="category_id" id="category_id"
                                            data-style="form-control">
                                            @forelse($categories as $category)
                                            <option value="{{ $category->id }}"
                                                    @if ($lead->category_id == $category->id) selected @endif>
                                                    {{ ucwords($category->category_name) }}</option>
                                            @empty
                                                <option value="">@lang('messages.noCategoryAdded')</option>
                                            @endforelse

                                        </select>
                                    </div>
                                </div>
                            </div>
                                <h3 class="box-title required">Additional Information</h3>
                                <hr>
                                <div class="row">
                                    @if (isset($fields))
                                        @foreach ($fields as $field)
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label
                                                        @if ($field->required == 'yes') class="required" @endif>{{ ucfirst($field->label) }}</label>
                                                    @if ($field->type == 'text')
                                                        <input type="text"
                                                            name="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                                            class="form-control" placeholder="{{ $field->label }}"
                                                            value="{{ $lead->custom_fields_data['field_' . $field->id] ?? '' }}">
                                                    @elseif($field->type == 'password')
                                                        <input type="password"
                                                            name="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                                            class="form-control" placeholder="{{ $field->label }}"
                                                            value="{{ $lead->custom_fields_data['field_' . $field->id] ?? '' }}">
                                                    @elseif($field->type == 'number')
                                                        <input type="number"
                                                            name="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                                            class="form-control" placeholder="{{ $field->label }}"
                                                            value="{{ $lead->custom_fields_data['field_' . $field->id] ?? '' }}">
                                                    @elseif($field->type == 'textarea')
                                                        <textarea name="custom_fields_data[{{ $field->name . '_' . $field->id }}]" class="form-control"
                                                            id="{{ $field->name }}"
                                                            cols="3">{{ $lead->custom_fields_data['field_' . $field->id] ?? '' }}</textarea>
                                                    @elseif($field->type == 'radio')
                                                        <div class="radio-list">
                                                            @foreach ($field->values as $key => $value)
                                                                <label
                                                                    class="radio-inline @if ($key == 0) p-0 @endif">
                                                                    <div class="radio radio-info">
                                                                        <input type="radio"
                                                                            name="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                                                            id="optionsRadios{{ $key . $field->id }}"
                                                                            value="{{ $value }}"
                                                                            @if (isset($lead) && $lead->custom_fields_data['field_' . $field->id] == $value) checked @elseif($key == 0) checked @endif>>
                                                                        <label
                                                                            for="optionsRadios{{ $key . $field->id }}">{{ $value }}</label>
                                                                    </div>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    @elseif($field->type == 'select')
                                                        {!! Form::select('custom_fields_data[' . $field->name . '_' . $field->id . ']', $field->values, isset($lead) ? $lead->custom_fields_data['field_' . $field->id] : '', ['class' => 'form-control gender']) !!}
                                                    @elseif($field->type == 'checkbox')
                                                        <div
                                                            class="mt-checkbox-inline custom-checkbox checkbox-{{ $field->id }}">
                                                            <input type="hidden"
                                                                name="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                                                id="{{ $field->name . '_' . $field->id }}" value=" ">
                                                            @foreach ($field->values as $key => $value)
                                                                <label class="mt-checkbox mt-checkbox-outline">
                                                                <input name="{{ $field->name . '_' . $field->id }}[]"
                                                                    class="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                                                    type="checkbox" value="{{ $value }}"
                                                                    onchange="checkboxChange('checkbox-{{ $field->id }}', '{{ $field->name . '_' . $field->id }}')"
                                                                    @if ($lead->custom_fields_data['field_' . $field->id] != '' && in_array($value, explode(', ', $lead->custom_fields_data['field_' . $field->id]))) checked @endif>
                                                                {{ $value }}
                                                                <span></span>
                                                            </label>
                                                            @endforeach
                                                        </div>
                                                    @elseif($field->type == 'date')
                                                        <input type="text" class="form-control date-picker" size="16"
                                                            name="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                                            value="{{ isset($editUser->dob) ? Carbon\Carbon::parse($editUser->dob)->format('Y-m-d') : Carbon\Carbon::now()->format($global->date_format) }}">
                                                    @endif
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block"></span>

                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label>@lang('app.note')</label>
                                        <div class="form-group">
                                            <textarea name="note" id="note" class="form-control summernote" rows="5">
                                                {{ $lead->note ?? '' }}
                                            </textarea>
                                        </div>
                                    </div>
                                </div>


                        </div>
                        <div class="form-actions">
                            <button type="submit" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.update')</button>
                            <a href="{{ route('member.leads.index') }}" class="btn btn-default">@lang('app.back')</a>
                        </div>
                        {!! Form::close() !!}






                    
                      
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- .row -->
    {{-- Ajax Modal --}}
    <div class="modal fade bs-modal-md in" id="projectCategoryModal" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{-- Ajax Modal Ends --}}
@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/summernote/dist/summernote.min.js') }}"></script>

    <script type="text/javascript">
        function checkboxChange(parentClass, id) {
            var checkedData = '';
            $('.' + parentClass).find("input[type= 'checkbox']:checked").each(function() {
                if (checkedData !== '') {
                    checkedData = checkedData + ', ' + $(this).val();
                } else {
                    checkedData = $(this).val();
                }
            });
            $('#' + id).val(checkedData);
        }

        $(".select2").select2({
            formatNoMatches: function() {
                return "{{ __('messages.noRecordFound') }}";
            }
        });
        $('.summernote').summernote({
            height: 200, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: false,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough']],
                ['fontsize', ['fontsize']],
                ['para', ['ul', 'ol', 'paragraph']],
                ["view", ["fullscreen"]]
            ]
        });
        $(".date-picker").datepicker({
            todayHighlight: true,
            autoclose: true,
            weekStart: '{{ $global->week_start }}',
        });

        $('#updateLead').on('click', '#addLeadAgent', function() {
            var url = '{{ route('admin.lead-agent-settings.create') }}';
            $('#modelHeading').html('Manage Lead Agent');
            $.ajaxModal('#projectCategoryModal', url);
        })

        $('#save-form').click(function() {
            $.easyAjax({
                url: '{{ route('admin.leads.update', [$lead->id]) }}',
                container: '#updateLead',
                type: "POST",
                redirect: true,
                data: $('#updateLead').serialize()
            })
        });
        $('#addLeadCategory').click(function() {
            var url = '{{ route('admin.leadCategory.create') }}';
            $('#modelHeading').html('...');
            $.ajaxModal('#projectCategoryModal', url);
        })
    </script>
@endpush
