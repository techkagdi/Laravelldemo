@extends('admin.includes.main')
@push('title')
    <title>View Category</title>
@endpush

@section('content')
       
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                       
                       
                    <div class="card p-4 mt-4">
                         @session('msg')
                       <div class="alert alert-success"> {{ session('msg') }} </div>
                        @endsession
                            <div class="row">
                                <div class="col-xl-12 col-md-12">
                                    <div class="d-flex">
                                            <h4>View Categories</h4>
                                    </div>
                                    <div class="mt-3">
                                        <table id="datatablesSimple">
                                            <thead>
                                                <tr>                               
                                                <th scope="col"><h5>Sr. No.</h5></th>                                       
                                                <th scope="col"><h5>Parent Category </h5></th>                                       
                                                <th scope="col"><h5>Category Name</h5></th>                                       
                                                <th scope="col"><h5>Commission (%)</h5></th>                                       
                                                <th scope="col"><h5>Action</h5></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @foreach ( $category as $cat )
                                                <tr>                                  
                                                <td>{{ $cat->c_id }}</td>                                  
                                                <td>@if ($cat->parentCategory)
                                                    {{ $cat->parentCategory->c_name }}
                                                    @else {{ '-'}}
                                                @endif</td>  
                                                <td>{{ $cat->c_name }}</td>  
                                                  <td>{{ $cat->c_commission }}</td>                   
                                                <td>
                                                     <a href="{{url('admin/edit-category',$cat->c_id)}}" class="btn btn-primary btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                                                     <form method="POST" action="{{url('admin/delete-category',$cat->c_id)}}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                                     </form>
                                                </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            </table>                             
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                </div>
                </main>



                     
        
@endsection