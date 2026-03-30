@extends('layouts.main')


@push('title')
<title>Sub-Category</title>
@endpush
@section('content')
<div class="container-fluid bg-light p-5">
            <h1 class="text-center text-secondary"><i class="fa-solid fa-list"></i>
            {{$subcat->parentCategory->c_name.'/'.$subcat->c_name}}</h1>
    </div>

    <!-- Products -->
     
    <section class="my-5"> 
<div class="container">
  
    <div class="row theme-product">
        @if ($subcat->products->count()>0)


        @foreach($subcat->products as $product )
                  <div class="col-lg-3 mb-4">
            <div class="card">
             
                <a href="{{route('product_detail', ['category'=>$subcat->parentCategory->c_name,
                'sub_category'=>$subcat->c_name,'product_detail'=>$product->p_name])}}"> <img src="{{asset
                ('storage/'.$product->p_image)}}" class="card-img-top" alt="..."></a>
                <div class="card-body">
                    <h6 class="card-title text-center"><a href="{{url('category/electronics/tv/detail')}}" class="text-dark text-decoration-none">
                        {{ $product->p_name }}</a> </h6>
                    <h5 class="card-title text-center">₹{{ $product->p_price }}.00 </h5>
                   
                </div>
                </div>
                </div>   
        @endforeach
        @else <p class="alert alert-danger text-center">Data Not Found!</p>
        @endif


                 
</div>          
</div>
</section>


@endsection

    