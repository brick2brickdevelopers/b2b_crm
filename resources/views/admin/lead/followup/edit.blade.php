<div class="panel panel-default">
    <div class="panel-heading "><i class="ti-pencil"></i> @lang('modules.followup.updateFollow')
        <div class="panel-action">
            <a href="javascript:;" class="close" id="hide-edit-follow-panel" data-dismiss="modal"><i
                    class="ti-close"></i></a>
        </div>
    </div>
    <div class="panel-wrapper collapse in">
        <div class="panel-body">
            {!! Form::open(['id' => 'updateFollow', 'class' => 'ajax-form']) !!}
            {!! Form::hidden('lead_id', $follow->lead_id) !!}
            {!! Form::hidden('id', $follow->id) !!}

            <div class="form-body">
                <div class="row">
                    <!--/span-->
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="control-label">@lang('app.next_follow_up')</label>
                            <input type="text" autocomplete="off" name="next_follow_up_date" id="next_follow_up_date2"
                                class="form-control"
                                @if ($follow->next_follow_up_date) value="{{ $follow->next_follow_up_date->format('d/m/Y H:i') }}" @endif>
                            <input type="hidden" name="type" class="form-control datepicker" value="datetime">

                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="control-label">@lang('app.remark')</label>
                            <textarea id="remark" name="remark" class="form-control">{{ $follow->remark }}</textarea>
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
                                                <label
                                                    class="radio-inline @if ($key == 0) p-0 @endif">
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
                                                    <input name="{{ $field->name . '_' . $field->id }}[]"
                                                        type="checkbox"
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
                </div>
                <!--/row-->

            </div>
            <div class="form-actions">
                <button type="button" id="update-follow" class="btn btn-success"><i class="fa fa-check"></i>
                    @lang('app.save')</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<script>
    //    update task
    $('#update-follow').click(function() {
        $.easyAjax({
            url: '{{ route('admin.leads.follow-up-update') }}',
            container: '#updateFollow',
            type: "POST",
            data: $('#updateFollow').serialize(),
            success: function(data) {
                $('#follow-list-panel .list-group').html(data.html);
            }
        })
    });

    jQuery('#next_follow_up_date2').datetimepicker({
        format: 'DD/MM/Y HH:mm',
    });
</script>
