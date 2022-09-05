<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('Sip End Point')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($sipEndPoints as $key=>$sipEndPoint)
                    <tr id="cat-{{ $sipEndPoint->id }}">
                        <td>{{ $key+1 }}</td>
                        <td>{{ $sipEndPoint->name }}</td>
                        <td><a href="javascript:;" data-cat-id="{{ $sipEndPoint->id }}" class="btn btn-sm btn-success btn-rounded edit-category">@lang("app.edit")</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No Sip End Point</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {!! Form::open(['id'=>'createDepartment','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label>@lang('app.name')</label>
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" id="save-department"  class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
     


    $('#createDepartment').on('submit', (e) => {
        e.preventDefault();
        var name = $('#name').val();
        var token = "{{ csrf_token() }}";
        $.easyAjax({
            url: '{{route('super-admin.sip-end-point.store')}}',
            container: '#createProjectCategory',
            type: "POST",
            data: { 'name':name, '_token':token},
            success: function (response) {
                if(response.status == 'success'){
                    $('#designation').html(response.designationData);
                    $("#designation").select2();
                    $("#name").val(' ');
                }
            }
        })
        return false;
    })
</script>