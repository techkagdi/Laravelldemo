@php
  use App\Models\Category;
  $category= Category::where('p_c_id', 0)->get();
    $user = auth()->user();
@endphp

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   @stack('title')  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

 <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
 
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet"/>

</head>
  <body>
   <nav class="navbar navbar-expand-lg theme-navbar">
  <div class="container">
    <a class="navbar-brand" href="{{url('/')}}"><img src="{{ asset('assets/images/logo/logo.png') }}" style="width:200px;"></a>
    <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" 
     aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button> -->
    <div>
         <form class="d-flex" role="search">
            <div class="input-group">
                <input class="form-control form-control-sm" style="width: 350px;" type="search" placeholder="Search For Products" aria-label="Search"/>
                <button class="btn btn-light text-secondary btn-sm" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div> 
    </form>
</div>
    <div>
        <a href="{{url('vendor/signup')}}" target="_blank" class="text-decoration-none text-light ">Become a Seller</a>
        <!-- <a href="{{url('cart-list/product')}}" class="btn theme-green-btn btn-sm text-light ms-1 rounded-pill px-3 py-2 "><i class="fa-solid fa-cart-arrow-down"></i>Cart</a> -->
        
        @if ($user && $user->is_login)
        <a href="{{url('user/' . $user->user_id)}}" class="btn theme-orange-btn btn-sm text-light ms-1 rounded-pill px-3 py-2">
        <i class="fa-solid fa-user"></i>Dashboard</a>
        @else
        <a href="{{url('login')}}" class="btn theme-orange-btn btn-sm text-light ms-1 rounded-pill px-3 py-2">
        <i class="fa-solid fa-user"></i>Login</a>
        @endif
        
        <a href="{{url('cart-list/product')}}" class="position-absolute mt-1 mx-2"><i class="fa-solid fa-2x fa-cart-shopping text-white"></i>
      <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
    0</a>

</div>
  </div>
</nav>

<!--  Category Nav-->

<nav class="navbar navbar-expand-lg shadow p-3 bg-body-tertiary rounded">
  <div class="container">
   
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
      <ul class="nav">
        @foreach ($category as $cat)   
          <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-dark" href="{{url('category/electronics')}}" role="button" 
                data-bs-toggle="dropdown" aria-expanded="false">{{ $cat->c_name }}</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{route('category', $cat->c_name)}}">All</a></li>
                @if ($cat->subCategory->count()>0)
                @foreach ($cat->subCategory as $subcat)
                <li><a class="dropdown-item" href="{{route('category.sub_category', ['category'=>$cat->c_name,
                'sub_category'=>$subcat->c_name])}}">{{ $subcat->c_name }}</a></li>
                @endforeach
                 @endif
              </ul>
               
            </li>
        @endforeach

      </ul>
    </div>
  </div>
</nav>

<script>

  function update_cart_count(){
    var cart = JSON.parse(localStorage.getItem("cart")) || [];
    let totalQty = cart.reduce((sum, item) => sum + item.quantity, 0);
    $("#cart-count").text(totalQty);

  }
  $(document).ready(function(){
    update_cart_count();

  })
</script>