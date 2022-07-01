@foreach ($searchResults as $item)
<div class="col-md-2 m-b-5 p-r-10">
    <div class="row b-all employee-list h-50">

        <div class="col-sm-3 col-xs-4 p-t-10">
            <img src="{{ $item->image_url }}" alt="user" class="img-circle" width="30">

        </div>
        <div class="col-sm-7 col-xs-6 p-t-10 font-12">
            {{ ucwords($item->name) }}

        </div>

        <div class="col-sm-2 col-xs-2 p-t-15">
            <a href="javascript:;" class="remove-employee" data-id="{{ $item->empgp_id }}"><i
                    class="fa fa-times text-danger"></i></a>
        </div>
    </div>
</div>
@endforeach