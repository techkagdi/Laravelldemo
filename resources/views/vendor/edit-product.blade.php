@extends('vendor.includes.main')
@push('title')
    <title>Edit Product</title>
@endpush

@section('content')
       
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <div class="card p-4 mt-4">
                            <form method="POST" action="{{url('admin/edit-product',$product->p_id)}}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">

                                    <div class="col-xl-8 col-md-8">
                                        <h4>Edit Product</h4>



                                        <div class="row mt-3">
                                            <div class="col-lg-12 mb-3">
                                                <label class="form-label">Product Name</label>
                                                <input type="text" class="form-control" name="p_name" value="{{$product->p_name}}">
                                            </div>

                                        <div class="col-lg-12 mb-3">
                                            <label class="form-label">Price</label>
                                            <input type="text" class="form-control" name="p_price" value="{{$product->p_price}}">
                                        </div>

                                        <div class="col-lg-12 mb-3">
                                                <label class="form-label">Category</label>
                                                    <select class="form-select" name="c_id" aria-label="Default select example">
                                                        @foreach ($category as $cat)
                                                            <option @if ($cat->c_id==$product->c_id)
                                                                selected
                                                            @endif value="{{$cat->c_id}}">{{$cat->c_name}}</option>
                                                        @endforeach
                                                    </select>
                                        </div>

                                        <div class="col-lg-12 mb-3">
                                                <label class="form-label">Stock Quantity</label>
                                                <input type="text" class="form-control" name="p_stock" value="{{$product->p_stock}}">
                                        </div>

                                        <div class="col-lg-12 mb-3">
                                                <label class="form-label">Product Description</label>
                                            <textarea class="form-control" name="p_description" placeholder="Fill Product Description here" 
                                            id="floatingTextarea">{{$product->p_description }}</textarea>
                                        </div>

                                    <div class="col-lg-3">
                                        <button class="btn btn-primary" type="submit" >Edit Product</button>
                                        </div>
                                        </div>
                                    </div> 
                                        <div class="col-xl-4 col-md-4 mt-5">
                                            <div class="text-center">
                                                <img src="{{asset('storage/'.$product->p_image)}}" style="width:155px;" class="rounded-circle">
                                                    <div class="mt-3">
                                                        <label for="image" class="form-label btn btn-dark">Choose Image</label>
                                                        <input type="file" name="p_image" class="form-control d-none" id="image">
                                                    </div>
                                            </div>
                                        </div> 
                                </div>
                            </form>
                        </div>     
                    </div>
                </main>



                     
        
@endsection