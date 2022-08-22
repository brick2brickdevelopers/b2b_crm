@extends('layouts.super-admin')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12 bg-title-left">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right bg-title-right">
            <a href="" data-toggle="modal" data-target="#addModal"
            class="btn btn-outline btn-success btn-sm">Add Did Number<i class="fa fa-plus"
                                                                                               aria-hidden="true"></i></a>
            <ol class="breadcrumb">
                <li><a href="">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@section('content')
    <div class="row">

         <!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Add New Did Number</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
  
          <form action="{{route('super-admin.did-number.store')}}" method="post">
            @csrf
            <div class="form-group">
              <label for="number">Did Number</label>
              <input type="number" class="form-control" id="number" name="number" placeholder="Enter number">
            </div>
            <div class="mt-4">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
  
        </div>
  
      </div>
    </div>
  </div>

        <div class="col-xs-12">
            <div class="white-box">
                <div class="card">
                    <div class="card-body">
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover toggle-circle default footable-loaded footable">
                                <thead>
                                    <tr>
                                        <th class=" text-center">#</th>
                                        <th class=" text-center">Number</th>
                                        <th class=" text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                      @foreach ($didNumbers as $key=>$didNumber)
                                          
                                     
                                        <tr>
                                            <td class=" text-center">
                                                {{ ++$key }}
                                            </td>
                                            <td class=" text-center">
                                               {{ $didNumber->number }}
                                            </td>

                                            <td class="text-center">
                                                <a href="#editModal{{$didNumber->id}}"  class="btn btn-info btn-circle editFeature"
                                                    data-toggle="modal" ><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                <a href="#deleteModal{{$didNumber->id}}" class="btn btn-danger btn-circle sa-params"
                                                data-toggle="modal" ><i class="fa fa-times" aria-hidden="true"></i></a>
                                            </td>
                                           
                                        </tr>

                                        <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{$didNumber->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Edit Did Number</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">

                                            <form action="{{route('super-admin.did-number.update',$didNumber->id)}}" method="post">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                <label for="number">Did Number</label>
                                                <input type="number" class="form-control" value="{{$didNumber->number}}" id="number" name="number" placeholder="Enter Did Number">
                                                </div>
                                                <div class="mt-4">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>

                                            </div>

                                        </div>
                                        </div>
                                    </div>

                                     <!-- DElete Modal -->
                                    <div class="modal fade" id="deleteModal{{$didNumber->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Delete Did Number</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
            
                                            <form action="{{route('super-admin.did-number.destroy',$didNumber->id)}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <div class="form-group">
                                                {{$didNumber->number}} will be Deleted!!
                                                </div>
                                                <div class="mt-4">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancle</button>
                                                <button type="submit" class="btn btn-primary">OK Confirm</button>
                                                </div>
                                            </form>
            
                                            </div>
            
                                        </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>

                          
                        </div>
                    </div>
                </div>



            </div>
            <!-- end col-->
        </div>
    @endsection
   
