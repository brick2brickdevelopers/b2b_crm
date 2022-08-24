@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
       
        <!-- /.breadcrumb -->
    </div>
@endsection
@push('head-script')
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
@endpush
@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="white-box">


                <div class="table-responsive">
                    <table class="table table-bordered table-hover toggle-circle default footable-loaded footable"
                        id="users-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Did Number</th>
                                <th>Is Default</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @forelse($did_numbers as $did_number)
                               
                                <tr id="group{{ $did_number->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $did_number->number }}</td>
                             
                                    <td>
                                        <div class="switchery-demo">
                                            <input onchange="defaultSwitch({{ $did_number->id }})" type="checkbox"
                                                {{ $did_number->is_default ? 'checked' : '' }} class="js-switch"
                                                data-size="small" data-color="#00c292" />
                                        </div>
                                    </td>
                                   
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">
                                        no data 
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->

@endsection

@push('footer-script')
    <script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>

    <script>
        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());

        });
    </script>
    <script>
        function defaultSwitch(id) {
            // alert('ok');
            var url = '{{ route('admin.did-number.default-switch') }}'
            console.log(id);
            $.easyAjax({
                url: url,
                type: "POST",
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                }
            })

        }
    </script>
@endpush
