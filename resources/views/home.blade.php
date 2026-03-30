@extends('layouts.main')
@push('title')
<title>Home Page</title>
@endpush
@section('content')
<div id="carouselExampleIndicators" class="carousel slide">
  <div class="carousel-indicators">
    @for ($i=0; $i < count($banners); $i++)
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{$i}}" class="active" aria-current="true" aria-label="Slide 1"></button>
     @endfor
  </div>
  <div class="carousel-inner">
    @foreach ($banners as $banner)
    <div class="carousel-item active">
      <img src="{{ asset('storage/'.$banner->b_image)}}" class="d-block w-100" alt="{{ $banner->b_alt }}">
    </div>
     @endforeach
    
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<!-- Product Section -->

<section class="my-5"> 
<div class="container">
 
  <div class="d-flex">
      <div class="flex-grow-1"> <h2>Top Deals </h2></div>
      <div><a href="{{url('category/electronics')}}" class="btn btn-sm theme-green-btn text-light rounded-pill px-3 py-2">View All</a></div>
     
      </div>
        <div class="row theme-product row-gap-4">
            @foreach ($products as $product)
                
            <div class="col-lg-3">
                <div class="card " >
                    <a href="#"><img src="{{ asset('storage/'.$product->p_image)}}" class="card-img-top" alt="..."></a>

                    <div class="card-body">
                        <h6 class="card-title text-center"><a href="#" class="text-dark text-decoration-none">{{ $product->p_name }}</a></h6>
                        <h5 class="card-title text-center">₹ {{ $product->p_price }}.00 </h5>
                    </div>
                </div>
            </div>
            @endforeach
        </div>  
    </div>
</section>

<!-- Best Of Electronics -->

<section class="my-5"> 
    <div class="container">
    
        <div class="d-flex">
            <div class="flex-grow-1"> <h2>Best Of Electronics </h2></div>
                <div><a href="{{url('category/electronics')}}" class="btn btn-sm theme-green-btn text-light rounded-pill 
                px-3 py-2">View All</a></div>

                </div>
                <div class="row theme-product">
                    @foreach ($electronics as $subcategory)

                    @php
                    $product= $subcategory->products->first();
                    @endphp
                                      
                    <div class="col-lg-3">
                        <div class="card " >
                            <a href="#"><img src="{{asset('storage/'.$product->p_image)}}" class="card-img-top" alt="..."></a>

                            <div class="card-body">
                                <h6 class="card-title text-center"><a href="#" class="text-dark text-decoration-none">{{ $product->p_name }}</a></h6>
                                <h5 class="card-title text-center">₹ {{ $product->p_price }}.00 </h5>
                    
                            </div>
                        </div>
                    </div>    
                    @endforeach 
                </div>
            </div>
</section>



<!-- Popular Products -->

<section class="my-5"> 
<div class="container">
 
    <div class="d-flex">
      <div class="flex-grow-1"> <h2>Popular Products </h2></div>
      <div><a href="{{url('category/electronics')}}" class="btn btn-sm theme-green-btn text-light rounded-pill px-3 py-2">View All</a></div>
     
      </div>
        <div class="row theme-product">
             @foreach ( $popular as $pop )
            <div class="col-lg-3">
                <div class="card " >
                    <a href="#"><img src="{{ asset('storage/'.$pop->p_image) }}" class="card-img-top" alt="..."></a>

                    <div class="card-body">
                        <h6 class="card-title text-center"><a href="#" class="text-dark text-decoration-none">{{$pop->p_name}}</a></h6>
                        <h5 class="card-title text-center">₹ {{$pop->p_price}} .00 </h5>
                    
                
                    </div>
                </div>
            </div>
                @endforeach
        </div>      
    </div>
</section>



<!-- Recently Viewed -->


<section class="my-5"> 
    <div class="container">
        <div class="d-flex">
                <div class="flex-grow-1"> <h2>Recently Viewed</h2></div>
                <div><a href="{{url('category/electronics')}}" class="btn btn-sm theme-orange-btn text-light rounded-pill px-3 py-2">View All</a></div>
                </div>
            <div class="row theme-product">
                @foreach ( $recent as $rec )
                <div class="col-lg-3">
                    <div class="card " >
                        <a href="#"><img src="{{asset('storage/'.$rec->p_image)}}" class="card-img-top" alt="..."></a>

                        <div class="card-body">
                            <h6 class="card-title text-center"><a href="#" class="text-dark text-decoration-none">
                                {{$rec->p_name}}</a></h6>
                            <h5 class="card-title text-center">₹ {{$rec->p_price}} .00</h5>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
</section>


@endsection   