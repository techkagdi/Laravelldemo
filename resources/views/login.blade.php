@extends('layouts.main')


@push('title')
<title>Login</title>
@endpush
@section('content')
<div class="container-fluid bg-light p-5">
    <h1 class="text-center text-secondary"><i class="fa-solid fa-user"></i>User Login </h1>
</div>

<section>
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-10">
                <div class="row">
                    <div class="col-lg-6">
                        <div>
                            <img src="{{ asset('assets/images/register.jpg') }}" class="rounded-3 img-fluid">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <form id="loginForm">

                            {{-- CHANGED: was type="tel" id="phone" name="phone" --}}
                            <div class="mb-3">
                                <div class="form-text mb-2">Please Enter Your Email Address</div>
                                <input type="email" id="email" name="email"
                                    class="form-control form-control-lg" placeholder="example@email.com" required>
                            </div>

                            {{-- OTP section — maxlength changed from 4 to 6 --}}
                            <div class="mb-3 otp-section d-none">
                                <div class="form-text mb-2">Please Enter the OTP sent to your email</div>
                                <input type="text" id="otp" name="otp" maxlength="6"
                                    class="form-control form-control-lg" placeholder="______">
                            </div>

                            <button type="submit" class="btn theme-orange-btn text-light
                            form-control form-control-lg">Login</button>

                            <div id="loginError" class="text-danger mt-3"></div>
                            <div id="loginSuccess" class="text-success mt-3"></div>

                            <div class="text-center p-5 my-5">Don't have an account? <a href="{{
                            url('register') }}" class="text-decoration-none">Signup</a></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('loginForm');
        const emailInput = document.getElementById('email'); // CHANGED: was phoneInput
        const otpInput = document.getElementById('otp');
        const otpSection = document.querySelector('.otp-section');
        const errorDiv = document.getElementById('loginError');
        const successDiv = document.getElementById('loginSuccess');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const email = emailInput.value; // CHANGED: was phone
            const otp = otpInput.value;

            // STEP 2: OTP is visible — user is submitting the OTP
            if (!otpSection.classList.contains('d-none')) {
                fetch('{{ route("authentication.verify.otp") }}', {
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
                            window.location.href = data.dashboard_url;
                        } else {
                            errorDiv.textContent = data.error || "Login failed.";
                        }
                    });

                // STEP 1: OTP is hidden — user is submitting their email
            } else {
                // CHANGED: was check.phone, now authentication.send.otp
                fetch('{{ route("authentication.send.otp") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            email
                        }) // CHANGED: was { phone }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            otpSection.classList.remove('d-none');
                            errorDiv.textContent = '';
                            successDiv.textContent = 'OTP sent! Check your email.';
                        } else {
                            errorDiv.textContent = data.error || "Email not registered.";
                            successDiv.textContent = '';
                        }
                    });
            }
        });
    });
</script>


@endsection