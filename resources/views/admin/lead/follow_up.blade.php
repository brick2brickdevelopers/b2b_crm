<link rel="stylesheet" href="{{ asset('plugins/datetime-picker/datetimepicker.css') }}">

<!--/span-->

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title"><i class="ti-plus"></i> @lang('modules.lead.leadFollowUp')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        {!! Form::open(['id' => 'followUpForm', 'class' => 'ajax-form', 'method' => 'POST']) !!}

        <div class="form-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="control-label">@lang('modules.lead.leadFollowUp')</label>
                        <input type="text" autocomplete="off" name="next_follow_up_date" id="next_follow_up_date"
                            class="form-control datepicker" value="">
                        <input type="hidden" name="type" class="form-control datepicker" value="datetime">
                    </div>
                    <div class="form-group">
                        <label class="control-label">@lang('modules.lead.remark')</label>
                        <textarea id="followRemark" name="remark" class="form-control"></textarea>
                    </div>
                </div>

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
                                        value="{{ $editUser->custom_fields_data['field_' . $field->id] ?? '' }}">
                                @elseif($field->type == 'password')
                                    <input type="password"
                                        name="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                        class="form-control" placeholder="{{ $field->label }}"
                                        value="{{ $editUser->custom_fields_data['field_' . $field->id] ?? '' }}">
                                @elseif($field->type == 'number')
                                    <input type="number"
                                        name="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                        class="form-control" placeholder="{{ $field->label }}"
                                        value="{{ $editUser->custom_fields_data['field_' . $field->id] ?? '' }}">
                                @elseif($field->type == 'textarea')
                                    <textarea name="custom_fields_data[{{ $field->name . '_' . $field->id }}]" class="form-control"
                                        id="{{ $field->name }}" cols="3">{{ $editUser->custom_fields_data['field_' . $field->id] ?? '' }}</textarea>
                                @elseif($field->type == 'radio')
                                    <div class="radio-list">
                                        @foreach ($field->values as $key => $value)
                                            <label class="radio-inline @if ($key == 0) p-0 @endif">
                                                <div class="radio radio-info">
                                                    <input type="radio"
                                                        name="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                                        id="optionsRadios{{ $key . $field->id }}"
                                                        value="{{ $value }}"
                                                        @if (isset($editUser) && $editUser->custom_fields_data['field_' . $field->id] == $value) checked @elseif($key == 0) checked @endif>>
                                                    <label
                                                        for="optionsRadios{{ $key . $field->id }}">{{ $value }}</label>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                @elseif($field->type == 'select')
                                    {!! Form::select('custom_fields_data[' . $field->name . '_' . $field->id . ']', $field->values, isset($editUser) ? $editUser->custom_fields_data['field_' . $field->id] : '', ['class' => 'form-control gender']) !!}
                                @elseif($field->type == 'checkbox')
                                    <div class="mt-checkbox-inline custom-checkbox checkbox-{{ $field->id }}">
                                        <input type="hidden"
                                            name="custom_fields_data[{{ $field->name . '_' . $field->id }}]"
                                            id="{{ $field->name . '_' . $field->id }}" value=" ">
                                        @foreach ($field->values as $key => $value)
                                            <label class="mt-checkbox mt-checkbox-outline">
                                                <input name="{{ $field->name . '_' . $field->id }}[]" type="checkbox"
                                                    onchange="checkboxChange('checkbox-{{ $field->id }}', '{{ $field->name . '_' . $field->id }}')"
                                                    value="{{ $value }}"> {{ $value }}
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


                <!--/span-->
                <div class="col-xs-12">
                    <div class="form-group">
                        <button class="btn btn-success" id="postFollowUpForm" type="button"><i
                                class="fa fa-check"></i> @lang('app.save')</button>

                        <button class="btn btn-danger" data-dismiss="modal" type="button"><i class="fa fa-times"></i>
                            @lang('app.close')</button>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::hidden('lead_id', $leadID) !!}
        {!! Form::close() !!}
        <!--/row-->
    </div>
</div>
<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
<script src="{{ asset('plugins/datetime-picker/datetimepicker.js') }}"></script>
<script>
    jQuery('#next_follow_up_date').datetimepicker({
        format: 'DD/MM/Y HH:mm',
    });

    //    update task
    $('#postFollowUpForm').click(function() {
        $.easyAjax({
            url: '{{ route('admin.leads.follow-up-store') }}',
            container: '#followUpForm',
            type: "POST",
            data: $('#followUpForm').serialize(),
            success: function(response) {
                $('#followUpModal').modal('hide');
                window.location.reload();
            }
        });

        return false;
    });
</script>
