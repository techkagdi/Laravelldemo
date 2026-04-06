@extends('layouts.main')

@push('title')
<title>Register</title>
@endpush

@section('content')
<div class="container-fluid bg-light p-5">
    <h1 class="text-center text-secondary"><i class="fa-solid fa-user"></i>User Register</h1>
</div>

<section>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row">

                    {{-- Left image --}}
                    <div class="col-lg-6 d-none d-lg-block">
                        <img src="{{ asset('assets/images/register.jpg') }}" class="rounded-3 img-fluid">
                    </div>

                    {{-- Registration Form --}}
                    <div class="col-lg-6">
                        <form id="registerForm">

                            <div class="mb-3">
                                <div class="form-text mb-2">Full Name</div>
                                <input type="text" id="fullname" name="fullname"
                                    class="form-control form-control-lg" placeholder="Your Full Name" required>
                            </div>

                            <div class="mb-3">
                                <div class="form-text mb-2">Email Address</div>
                                <input type="email" id="email" name="email"
                                    class="form-control form-control-lg" placeholder="example@email.com" required>
                            </div>

                            <div class="mb-3">
                                <div class="form-text mb-2">Mobile Number</div>
                                <input type="tel" id="phone" name="phone" maxlength="10"
                                    class="form-control form-control-lg" placeholder="10-digit mobile number" required>
                            </div>

                            <div class="mb-3">
                                <div class="form-text mb-2">Address</div>
                                <textarea id="address" name="address" rows="2"
                                    class="form-control form-control-lg" placeholder="Your Address" required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="form-text mb-2">City</div>
                                    <input type="text" id="city" name="city"
                                        class="form-control form-control-lg" placeholder="City" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="form-text mb-2">State</div>
                                    <input type="text" id="state" name="state"
                                        class="form-control form-control-lg" placeholder="State" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="form-text mb-2">Pincode</div>
                                    <input type="text" id="pincode" name="pincode" maxlength="6"
                                        class="form-control form-control-lg" placeholder="Pincode" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="form-text mb-2">Landmark</div>
                                    <input type="text" id="landmark" name="landmark"
                                        class="form-control form-control-lg" placeholder="Landmark">
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-text mb-2">Country</div>
                                <input type="text" id="country" name="country"
                                    class="form-control form-control-lg" placeholder="Country" value="India">
                            </div>

                            <div id="registerError" class="text-danger mb-3"></div>
                            <div id="registerSuccess" class="text-success mb-3"></div>

                            <button type="submit" class="btn theme-orange-btn text-light form-control form-control-lg">
                                Send OTP & Register
                            </button>

                            <div class="text-center p-4">
                                Already have an account?
                                <a href="{{ url('login') }}" class="text-decoration-none">Login</a>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

{{-- OTP Modal --}}
<div class="modal fade" id="otpModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="otpForm">
                <div class="modal-header">
                    <h5 class="modal-title">Verify OTP</h5>
                </div>
                <div class="modal-body">
                    <p class="text-muted" id="otpHint"></p>
                    <input type="text" id="otp" name="otp" maxlength="6"
                        class="form-control form-control-lg text-center"
                        placeholder="Enter 6-digit OTP" required>
                    <div id="otpError" class="text-danger mt-2"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn theme-orange-btn text-white w-100">
                        Verify & Complete Registration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // ─────────────────────────────────────────
    // STEP 1: Submit form → send OTP to email
    // ─────────────────────────────────────────
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const errorDiv = document.getElementById('registerError');
        const successDiv = document.getElementById('registerSuccess');
        errorDiv.textContent = '';
        successDiv.textContent = '';

        const formData = new FormData(this);
        const plainForm = Object.fromEntries(formData.entries());

        // Basic validation
        if (plainForm.phone.length !== 10) {
            errorDiv.textContent = 'Please enter a valid 10-digit mobile number.';
            return;
        }

        fetch('{{ route("submit.email.billing") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(plainForm)
            })
            .then(r => r.json())
            .then(data => {
                if (data.otp_sent) {
                    document.getElementById('otpHint').textContent =
                        'OTP sent to ' + plainForm.email + '. Check your inbox.';
                    new bootstrap.Modal(document.getElementById('otpModal')).show();
                } else if (data.error === 'email_exists') {
                    errorDiv.textContent = 'This email is already registered. Please login.';
                } else {
                    errorDiv.textContent = data.error || 'Something went wrong. Try again.';
                }
            })
            .catch(() => {
                errorDiv.textContent = 'Server error. Please try again.';
            });
    });

    // ─────────────────────────────────────────
    // STEP 2: Verify OTP → create user + billing
    // ─────────────────────────────────────────
    document.getElementById('otpForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const otpError = document.getElementById('otpError');
        otpError.textContent = '';

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
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.verified) {
                    document.getElementById('registerSuccess').textContent =
                        'Registration successful! Redirecting...';
                    bootstrap.Modal.getInstance(document.getElementById('otpModal')).hide();
                    setTimeout(() => {
                        window.location.href = '{{ url("/") }}';
                    }, 1000);
                } else {
                    otpError.textContent = data.error || 'Invalid OTP. Please try again.';
                }
            })
            .catch(() => {
                otpError.textContent = 'Server error. Please try again.';
            });
    });
</script>

@endsection