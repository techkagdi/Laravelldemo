<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Login</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color:#f5f5f5;">

    <section>
        <div class="container position-absolute top-50 start-50 translate-middle">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row align-items-center shadow bg-white rounded p-3">

                        <!-- Image -->
                        <div class="col-lg-6">
                            <img src="{{ asset('dashboard/assets/img/vendor1.jpg') }}" class="rounded-3 img-fluid">
                        </div>

                        <!-- Login Form -->
                        <div class="col-lg-6 mt-3 p-4">

                            @if(session('msg'))
                            <div class="alert alert-danger">{{ session('msg') }}</div>
                            @endif

                            <form method="POST" action="{{ url('admin/login') }}">
                                @csrf

                                <h2 class="mb-4 text-center">Admin Login</h2>

                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input value="{{ old('phone') }}" type="tel" name="phone" class="form-control" placeholder="+91">
                                    @error("phone")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="******">
                                    @error("password")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Login</button>

                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>