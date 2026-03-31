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

<!-- Billing Information -->
<section>
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-1 mb-5">
                <a href="{{ url('cart-list/product') }}" class="btn theme-orange-btn text-white"
                    style="display:inline-flex;align-items:center;">
                    <i class="fa-solid fa-arrow-left" style="margin-right:6px;"></i>Back
                </a>
            </div>

            <h2>Billing Details</h2>

            <div class="col-lg-12">
                <form id="billingForm">

                    <div class="row my-3">

                        {{-- EMAIL — changed from phone, this is what OTP is sent to --}}
                        <div class="col-lg-12 mb-3">
                            <input id="email" name="email" type="email" class="form-control"
                                placeholder="Enter your Email Address"
                                value="{{ ($user && $user->is_login) ? ($billing?->email ?? '') : '' }}"
                                @if($user && $user->is_login) readonly @endif>
                        </div>

                        <div class="col-lg-12 mb-3">
                            @php $selectedCountry = $billing->country ?? ''; @endphp
                            <select class="form-select form-control" name="country"
                                @if($user && $user->is_login) disabled @endif>
                                <option selected>Select Your Country</option>
                                <option value="India" {{ $selectedCountry == 'India' ? 'selected' : '' }}>India</option>
                            </select>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <input type="text" name="fullname" class="form-control" placeholder="Full Name"
                                value="{{ $billing?->fullname }}"
                                @if($user && $user->is_login) readonly @endif>
                        </div>

                        {{-- PHONE kept as billing info field (not login field) --}}
                        <div class="col-lg-6 mb-3">
                            <input type="tel" name="phone" class="form-control" maxlength="10"
                                placeholder="Phone Number"
                                value="{{ ($user && $user->is_login) ? $user->phone : '' }}"
                                @if($user && $user->is_login) readonly @endif>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <input type="text" name="pincode" class="form-control" placeholder="Pin Code"
                                value="{{ $billing?->pincode }}"
                                @if($user && $user->is_login) readonly @endif>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <input type="text" name="landmark" class="form-control" placeholder="Landmark"
                                value="{{ $billing?->landmark }}"
                                @if($user && $user->is_login) readonly @endif>
                        </div>

                        <div class="col-lg-6 mb-3">
                            @php $selectedCity = $billing->city ?? ''; @endphp
                            <select class="form-select form-control" name="city"
                                @if($user && $user->is_login) disabled @endif>
                                <option selected>Select Your City</option>
                                <option value="Ludhiana" {{ $selectedCity == 'Ludhiana' ? 'selected' : '' }}>Ludhiana</option>
                            </select>
                            @if($user && $user->is_login)
                            <input type="hidden" name="city" value="{{ $selectedCity }}">
                            @endif
                        </div>

                        <div class="col-lg-6 mb-3">
                            @php $selectedState = $billing->state ?? ''; @endphp
                            <select class="form-select form-control" name="state"
                                @if($user && $user->is_login) disabled @endif>
                                <option selected>Select Your State</option>
                                <option value="Punjab" {{ $selectedState == 'Punjab' ? 'selected' : '' }}>Punjab</option>
                            </select>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <textarea class="form-control" name="address" placeholder="Your Address" rows="4"
                                @if($user && $user->is_login) readonly @endif>{{ $billing?->address }}</textarea>
                        </div>

                        @if(!($user && $user->is_login))
                        <div class="col-lg-12 mb-3 text-end">
                            <button type="submit" class="btn theme-green-btn btn-lg text-white">Submit</button>
                        </div>
                        @endif

                    </div>
                </form>
            </div>

            {{-- OTP Modal — maxlength changed from 4 to 6 --}}
            <div class="modal fade" id="otpModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <form id="otpForm">
                            <div class="modal-header">
                                <h5 class="modal-title">Enter OTP</h5>
                            </div>
                            <div class="modal-body">
                                <p class="text-muted mb-3" id="otpHint"></p>
                                <input type="text" name="otp" id="otp" maxlength="6"
                                    class="form-control" placeholder="Enter 6-digit OTP">
                                <div id="otpError" class="text-danger mt-2"></div>
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
@if($user && $user->is_login)
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
                    <a id="placeOrderBtn" class="btn theme-orange-btn text-light rounded-pill my-4 px-3 py-2">
                        Place Order <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<script>
    // ─────────────────────────────────────────
    // STEP 1: Billing form submit → send OTP to email
    // ─────────────────────────────────────────
    document.getElementById('billingForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const plainForm = Object.fromEntries(formData.entries());

        // CHANGED: route was submit.phone.billing, now submit.email.billing
        fetch('{{ route("submit.email.billing") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(plainForm)
            })
            .then(response => response.json())
            .then(data => {
                if (data.otp_sent) {
                    // Show OTP modal with hint message
                    document.getElementById('otpHint').textContent =
                        'OTP has been sent to ' + plainForm.email;
                    $('#otpModal').modal('show');
                } else if (data.error === 'email_exists') {
                    // CHANGED: was phone_exists
                    alert('Email already registered. Please login instead.');
                } else {
                    alert(data.error || 'Something went wrong. Please try again.');
                }
            })
            .catch(() => alert('Server error. Please try again.'));
    });

    // ─────────────────────────────────────────
    // STEP 2: OTP form submit → verify OTP
    // ─────────────────────────────────────────
    document.getElementById('otpForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // CHANGED: was reading phone input, now reading email input
        const email = document.getElementById('email').value;
        const otp = document.getElementById('otp').value;

        fetch('{{ route("verify.otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    email,
                    otp
                }) // CHANGED: was { phone, otp }
            })
            .then(response => response.json())
            .then(data => {
                if (data.verified) {
                    alert('Account created & billing saved!');
                    location.reload();
                } else {
                    document.getElementById('otpError').textContent =
                        data.error || 'Invalid OTP. Please try again.';
                }
            })
            .catch(() => alert('Server error. Please try again.'));
    });

    // ─────────────────────────────────────────
    // Place Order
    // ─────────────────────────────────────────
    document.getElementById('placeOrderBtn').addEventListener('click', function() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];

        if (cart.length === 0) {
            alert('Your Cart is Empty');
            return;
        }

        const paymentMode = document.querySelector('input[name="radioDefault"]:checked')
            ?.nextElementSibling?.innerText.trim();

        fetch('{{ route("place.order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    cart,
                    payment_mode: paymentMode
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('HTTP error ' + response.status);
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Expected JSON, got HTML');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
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