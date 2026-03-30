@extends('admin.includes.main')
@push('title')
<title>Add Product</title>
@endpush
@section('content')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="card p-4 mt-4">
                <form method="POST" action="{{url('admin/add-product')}}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        @session('msg')
                        <div class="alert alert-success">{{ session('msg') }}</div>
                        @endsession

                        <div class="col-xl-8 col-md-8">
                            <h4>Add Product</h4>



                            <div class="row mt-3">

                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" name="p_name" class="form-control" placeholder="Watch">
                                    @error('p_name')
                                    <div class="text-danger"> {{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">Price</label>
                                    <input type="text" name="p_price" class="form-control" placeholder="₹ 1499.00">
                                    @error('p_price')
                                    <div class="text-danger"> {{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="c_id" aria-label="Default select example">
                                        @if ($category->count() > 0 )
                                        @foreach ($category as $cat )
                                        <option value="{{ $cat->c_id}}">{{ $cat->c_name}}</option>
                                        @endforeach
                                        @endif

                                    </select>
                                    @error('c_id')
                                    <div class="text-danger"> {{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">Stock Quantity</label>
                                    <input type="text" name="p_stock" class="form-control" placeholder="25 pcs">
                                    @error('p_stock')
                                    <div class="text-danger"> {{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">Product Description</label>
                                    <textarea class="form-control" name="p_description" placeholder="Fill Product Description here"
                                        id="floatingTextarea"></textarea>
                                    @error('p_description')
                                    <div class="text-danger"> {{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-3">
                                    <button class="btn btn-primary" type="submit">Add Product</button>
                                </div>

                            </div>
                        </div>


                        <div class="col-xl-4 col-md-4 mt-5">
                            <div class="text-center">
                                <img src="{{asset('dashboard/assets/img/products/2.jpg')}}" style="width:155px;" class="rounded-circle">
                                <div class="mt-3">
                                    <label for="image" class="form-label btn btn-dark">Choose Image</label>
                                    <input type="file" name="p_image" class="form-control d-none" id="image">
                                    @error('p_image')
                                    <div class="text-danger"> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </main>





    @endsection