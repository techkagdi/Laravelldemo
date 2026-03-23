@extends('vendor.includes.main')
@push('title')
    <title>View Products</title>
@endpush

@section('content')
       
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                       
                    <div class="card p-4 mt-4">
                        @session('msg')
                            <div class="alert alert-success">{{ session('msg') }}</div>
                        @endsession

                            <div class="row">
                                <div class="col-xl-12 col-md-12">
                                    <div class="d-flex">
                                            <h4>View Products</h4>
                                    </div>
                                    <div class="mt-3">
                                        <table id="datatablesSimple">
                                            <thead>
                                                <tr>
                                                <th scope="col"><h5>Product</h5></th>
                                                <th scope="col"><h5>Price</h5></th>
                                                <th scope="col"><h5>Category</h5></th>
                                                <th scope="col"><h5>Stock</h5></th>
                                                <th scope="col"><h5>Description</h5></th>
                                                <th scope="col"><h5>Action</h5></th>


                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($products->count() > 0)
                                                    @foreach ($products as $product)
                                                    <tr>
                                                <th>
                                                    <div class="d-flex">
                                                        <div>
                                                        <img src="{{asset('storage/'.$product->p_image)}}" style="width: 70px;" class="rounded-3"> 
                                                        </div>   
                                                        <div class="p-3"> <h5>{{ $product->p_name }}</h5></div>
                                                    </div>
                                            
                                                </th>
                                                <td>₹ {{ $product->p_price }}.00</td>
                                                <td>{{ $product->category->c_name }}</td>
                                                <td>{{ $product->p_stock }}</td>
                                                <td>{{ $product->p_description }}</td>
                                                <td>
                                                     <a href="{{url('vendor/edit-product',$product->p_id)}}" class="btn btn-primary btn-sm">
                                                        <i class="fa-solid fa-pen-to-square"></i></a>
                                                        <form method="POST" action="{{url('vendor/delete-product',$product->p_id)}}"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        
                                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                                        </form>
                                                </td>

                                                
                                                </tr>

                                                    @endforeach
                                                @endif 
                                            </tbody>
                                            </table>                             
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                </div>
                </main>



                     
        
@endsection