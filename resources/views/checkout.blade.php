@extends('layouts.main')


@push('title')
<title>Checkout</title>
@endpush
@php
    $user = auth()->user();
    $billing = null;
    if($user && $user->is_login){
        $billing = \App\Models\Billing::where('user_id', $user->user_id)->first();
    }
@endphp

@section('content')
<div class="container-fluid bg-light p-5">
    <h1 class="text-center text-secondary"><i class="fa-solid fa-cart-shopping"></i>Checkout</h1>
</div>

<!-- Billing Information  -->


 <section>
            <div class="container my-5">
                 <div class="row">
                    <div class="col-lg-1 mb-5">
                        <a href="{{url('cart-list/product')}}" class="btn theme-orange-btn text-white" style="display: inline-flex; align-items:center;">
                            <i class="fa-solid fa-arrow-left" style="margin-right:6px;"></i>Back</a>
                    </div>
                <h2>Billing Details</h2>
                    <div class="col-lg-12">
                        <form id="billingForm" >
                           <div class="row my-3">
                                    <div class="col-lg-12 mb-3">
                                    <input id="phone" name="phone" type="tel" class="form-control " maxlength="10" 
                                    placeholder="Enter your phone No."
                                    value="{{ ($user  && $user->is_login) ? $user->phone : '' }}"
                                    @if ($user && $user->is_login)
                                    readonly
                                    @endif
                                    >
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        @php
                                            $selectedCountry = $billing->country ?? '';
                                        @endphp
                                   <select class="form-select form-control" name="country" aria-label="Default select example" @if ($user && $user->is_login)
                                    disabled
                                    @endif>
                                        <option selected>Select Your Country</option>
                                        <option value="India" {{ $selectedCountry == '1' ? 'selected' : '' }}>India</option>
                                        
                                    </select> 
                                    </div>

                                    <div class="col-lg-6 mb-3">
                                    <input type="text" name="fullname" class="form-control" placeholder="Full Name"
                                    value="{{ $billing?->fullname }}"
                                    @if ($user && $user->is_login)
                                    readonly
                                    @endif
                                    >
                                    </div>
                            
                                    <div class="col-lg-6 mb-3">
                                    <input type="email" name="email" class="form-control" placeholder="Your Email"
                                    value="{{ $billing?->email }}"
                                    @if ($user && $user->is_login)
                                    readonly
                                    @endif
                                    >
                                    </div>
                                    
                                    <div class="col-lg-6 mb-3">
                                    <input type="text" name="pincode" class="form-control" placeholder="Pin Code"
                                    value="{{ $billing?->pincode }}"
                                    @if ($user && $user->is_login)
                                    readonly
                                    @endif
                                    >
                                    </div>

                                    <div class="col-lg-6 mb-3">
                                    <input type="text" name="landmark" class="form-control" placeholder="Landmark"
                                    value="{{ $billing?->landmark }}"
                                    @if ($user && $user->is_login)
                                    readonly
                                    @endif
                                    >
                                    </div>

                                    <div class="col-lg-6 mb-3">
                                        @php
                                        $selectedCity = $billing->city ?? '';
                                        @endphp
                                   <select class=" form-select form-control" name="city" @if ($user && $user->is_login)
                                    disabled
                                    @endif>
                                        <option selected>Select Your City</option>
                                        <option value="Ludhiana" {{ $selectedCity == '1' ? 'selected' : '' }}>Ludhiana</option>
                                        
                                    </select> 
                                    @if($user && $user->is_login)
                                    <input type="hidden" name="city" value="{{ $selectedCity }} ">
                                    @endif
                                    
                                    </div>

                                    <div class="col-lg-6 mb-3">
                                         @php
                                            $selectedState = $billing->state ?? '';
                                        @endphp
                                    <select class=" form-select form-control" name="state" aria-label="Default select example" @if ($user && $user->is_login)
                                    disabled
                                    @endif>
                                        <option selected>Select Your State</option>
                                        <option value="Punjab" {{ $selectedState == '1' ? 'selected' : '' }}>Punjab</option>
                                        
                                    </select> 
                                    </div>


                                    <div class="col-lg-12 mb-3">
                                    <textarea class="form-control" name="address" placeholder="Your Address" rows="4"
                                    @if ($user && $user->is_login)
                                    readonly
                                    @endif>{{ $billing?->address }}
                                    
                                    </textarea>

                                    </div>
                                    @if(!($user && $user->is_login))

                                    <div class="col-lg-12 mb-3 text-end">
                                        <button type="submit" class="btn  theme-green-btn btn-lg text-white">Submit</button>
                                    </div>
                                    @endif
                        </form>
                    </div>
                    <!-- Otp Modal -->
                    <div class="modal fade" id="otpModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <form id="otpForm">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Enter OTP</h5>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" name="otp" id="otp" maxlength="4" class="form-control" placeholder="Enter 4-digit OTP" >
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn theme-green-btn text-white">Verify OTP</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
                
            </div>
         </section>



<!-- Payment -->
 @if(($user && $user->is_login))
 <section>
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="form-check">
                <input class="form-check-input" type="radio" name="radioDefault" id="radioDefault1" checked>
                <label class="form-check-label" for="radioDefault1">
                  <h5>UPI</h5>
                </label>
                </div>
                <div class="form-check">
                <input class="form-check-input" type="radio" name="radioDefault" id="radioDefault2">
                <label class="form-check-label" for="radioDefault2">
                    <h5>Credit/Debit card</h5>
                    </label>
                </div>
                <div class="form-check">
                <input class="form-check-input" type="radio" name="radioDefault" id="radioDefault3">
                <label class="form-check-label" for="radioDefault3">
                    <h5>Cash On Delivery</h5>
                    </label>
                </div>
                <div>
                <a id="placeOrderBtn" class="btn theme-orange-btn text-light rounded-pill my-4 px-3 py-2">Place Order <i class="fa-solid fa-arrow-right"></i> </a>
                </div>

            </div>
        </div>
        
    </div>
 </section>
 @endif

 <script>
    document.getElementById('billingForm').addEventListener('submit', function(e)
    {
        e.preventDefault();

        const formData = new FormData(this);
        const plainForm = Object.fromEntries(formData.entries());

        fetch('{{ route("submit.phone.billing") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body:JSON.stringify(plainForm)
        })
        .then(response => response.json())
        .then(data => {
            if(data.otp_sent){
                $('#otpModal').modal('show');
            } else { (data.error === 'phone_exists') 
                alert('Phone Number Already Registered.')
            }
        });
    });
    
        document.getElementById('otpForm').addEventListener('submit', function(e){
        e.preventDefault();

        const phone = document.getElementById('phone').value;
        const otp = document.getElementById('otp').value;
        
        fetch('{{ route("verify.otp") }}',{
            method: 'POST',
            headers:  {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                phone: phone,
                otp: otp
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.verified){
                alert('User Created & Billing Saved');
                location.reload();
            }else{
                    alert('Invalid OTP');
            }
        });
    });


    // document.getElementById('placeOrderBtn').addEventListener('click', function(){
    //     const cart = JSON.parse(localStorage.getItem('cart')) || [];

    //     if(cart.length === 0){
    //         alert('Your Cart is Empty');
    //         return;
    //     }

    //     const paymentMode = document.querySelector('input[name="flexRadioDefault"]:checked')?.nextElementSibling?.innerText.trim();
        
    //     fetch('{{ route("place.order") }}',{
    //         method: 'POST',
    //         headers:  {
    //             'Content-Type': 'application/json',
    //             'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //         },
    //         body: JSON.stringify({
    //             cart: cart,
    //             payment_mode: paymentMode
    //         })
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //         if(data.success){
    //             alert('Order Placed Successfully');
    //             localStorage.removeItem('cart');
    //             window.location.href = '{{ url("/")}}';
    //         }else{
    //                 alert('Failed to Place Order');
    //         }
    //     });
    // });

    document.getElementById('placeOrderBtn').addEventListener('click', function(){
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    if(cart.length === 0){
        alert('Your Cart is Empty');
        return;
    }

    const paymentMode = document.querySelector('input[name="radioDefault"]:checked')?.nextElementSibling?.innerText.trim();

    fetch('{{ route("place.order") }}',{
        method: 'POST',
        headers:  {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ cart, payment_mode: paymentMode })
    })
    .then(response => {
        if(!response.ok) throw new Error('HTTP error ' + response.status);
        const contentType = response.headers.get('content-type');
        if(!contentType || !contentType.includes('application/json')){
            throw new Error('Expected JSON, got HTML');
        }
        return response.json();
    })
    .then(data => {
        if(data.success){
            alert('Order Placed Successfully');
            localStorage.removeItem('cart');
            window.location.href = '{{ url("/") }}';
        } else {
            alert('Failed to Place Order');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Server Error: Could not place order.');
    });
});
 </script>

@endsection

    