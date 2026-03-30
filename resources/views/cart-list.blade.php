@extends('layouts.main')


@push('title')
<title>Cart List</title>
@endpush
@section('content')
<div class="container-fluid bg-light p-5">
    <h1 class="text-center text-secondary"><i class="fa-solid fa-cart-shopping"></i>Cart List </h1>
</div>

<!-- Cart List  -->
<section>
    <div class="container">
        <div class="row my-5">
            <div class="col-lg-12">
                <table class="table" id="cart-table">
                    <thead>
                        <tr>
                        <th scope="col"><h4>Product</h4></th>
                        <th scope="col"><h4>Price</h4></th>
                        <th scope="col"><h4>Quantity</h4></th>
                        <th scope="col"><h4>SubTotal</h4></th>
                        <th scope="col"><h4>Remove</h4></th>
                        </tr>
                    </thead>
                    <tbody id="cart-body">

                    
                    </tbody>
                </table>  
                <div id="empty-cart-message" class="text-center my-5">
                    <h3 class="text-muted">Your Cart is Empty!</h3>
                    <p class="text-muted">You have No Items in Your Shopping Cart</p>

                    <a href="{{ url('/')}}" class="btn theme-orange-btn text-white rounded-pill px-4 py-2">Continue Shopping</a>

                </div>
                </div>
                <div class="col-lg-5 ms-auto my-5" id="price-summary">
                        <div>
                            <h3>Price Details</h3> <hr>
                        </div>
                    <div class="d-flex">
                        <div>
                            <h5>Subtotal</h5>
                        </div>
                        <div class="ms-auto">
                            <h5 id="subtotal">₹ 0.00</h5>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div>
                            <h5>GST(18%)</h5>
                        </div>
                        <div class="ms-auto">
                            <h5 id="gst">₹ 0.00</h5>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div>
                            <h5>Delivery Charges</h5>
                        </div>
                        <div class="ms-auto">
                            <h5> Free</h5>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex">
                        <div>
                            <h4>Total</h4>
                        </div>
                        <div class="ms-auto">
                            <h5 id="total">₹ 0.00</h5>
                        </div>
                    </div>
                    <div class="mt-4" >
                    <a href="{{url('checkout/product')}}" 
                    class="btn theme-orange-btn text-light rounded-pill w-100 px-3 py-2">Proceed to Checkout
                    <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function (){
        renderCart();
    });

    function renderCart() {
        const cart = JSON.parse(localStorage.getItem("cart")) || [];
        const cartBody = document.getElementById("cart-body");
        const emptyMessage = document.getElementById("empty-cart-message");
        const priceSummary = document.getElementById("price-summary");
        const cartTable = document.getElementById("cart-table");
        let subtotal = 0;

        cartBody.innerHTML = "";

        if(cart.length === 0) {
            emptyMessage.style.display = "block";
            priceSummary.style.display = "none";
            cartTable.style.display = "none";

            document.getElementById("subtotal").innerText = "0.00"
            document.getElementById("gst").innerText = "0.00"
            document.getElementById("total").innerText = "0.00"
            return;
        }else{
            emptyMessage.style.display = "none";
            priceSummary.style.display = "block";
            cartTable.style.display = "table";
        }
        cart.forEach((item, index)=> {
            const itemTotal = item.product_price * item.quantity;
            subtotal += itemTotal;

            const row = 
            `
            <tr>
            <th>
                <div class="d-flex">
                    <div>
                        <img src="${item.image_url}" style="width: 70px;" class="rounded-3">
                    </div>
                    <div class="p-3">
                        <h5>${item.product_name}</h5>
                    </div>
                </div>
            
            </th>
            <td>${item.product_price.toLocaleString()}</td>
            <td><h5>${item.quantity}</h5></td>
            <td>${itemTotal.toLocaleString()}</td>
            <td>
                <button type="button" class="btn-close" aria-label="Close" onClick="removeItem(${index})"></button>
            </td>
            </tr>
            
            `;
            cartBody.insertAdjacentHTML("beforeend", row);
        })
        const gst = subtotal * 0.18;
        const total = subtotal + gst;

        document.getElementById("subtotal").innerText = `₹ ${subtotal.toLocaleString()}`;
        document.getElementById("gst").innerText = `₹ ${gst.toLocaleString(undefined)}`;
        document.getElementById("total").innerText = `₹ ${total.toLocaleString(undefined)}`;
    }

    function removeItem(index){
        if(!confirm("Are you Sure Want to Remove this Item From the Cart")) return;
        const cart = JSON.parse(localStorage.getItem("cart")) || [];
        cart.splice(index, 1);
        localStorage.setItem("cart", JSON.stringify(cart));
    update_cart_count();
        renderCart();

    }



</script>
@endsection

    