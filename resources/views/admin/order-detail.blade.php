@extends('admin.includes.main')
@push('title')
<title>Order Detail</title>
@endpush

@section('content')
@php
$progress = match($order->status){
'pending' => 25,
'processing' => 50,
'on the way' => 75,
'delivered' => 100,
default => 0
};
@endphp

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-5">
            <div class="row my-5">
                <h6>Order Detail: {{ $order->created_at }}</h6>
                <div class="col-xl-6 col-md-6 mt-3 border border-primary p-3">

                    <h5 class="text-dark">Customer Information</h5>
                    <!-- <h6 class="text-dark">Reference site about Lorem Ipsum, giving information on its origins</h6> -->
                    <span class="text-dark"><strong>Name:</strong> {{$order->billing->fullname }}</span><br>
                    <span class="text-dark"><strong>Email:</strong> {{$order->billing->email }}</span><br>
                    <span class="text-dark"><strong>Phone:</strong> +91 {{$order->user->phone }}</span><br>
                    <span class="text-dark"><strong>Shipping Address:</strong> {{$order->billing->address }}</span><br>

                </div>
                <div class="col-xl-6 col-md-6 mt-3 border border-primary p-3">

                    <h5 class="text-dark">Order Summary</h5>
                    <span class="text-dark"><strong>Order ID:</strong> {{$order->order_no }}</span><br>
                    <span class="text-dark"><strong>Payment Method:</strong>
                        {{$order->payment_mode }}</span><br>

                    <span class="text-dark"><strong>Payment Status:</strong>
                        @php
                        $statusClass = match($order->status){
                        'pending' => 'text-bg-secondary',
                        'processing' => 'text-bg-warning',
                        'on the way' => 'text-bg-info',
                        'delivered' => 'text-bg-success',
                        }
                        @endphp
                        <span class="badge {{ $statusClass }}">{{$order->status }}</span>
                    </span><br>

                    <h5 class="text-dark mt-3">Total: ₹ {{$order->total }}</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12">
                    <div class="position-relative m-4">
                        <div class="progress" role="progressbar" aria-label="Progress" aria-valuenow="50"
                            aria-valuemin="0" aria-valuemax="100" style="height: 5px;">
                            <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                        </div>
                        <button type="button" class="position-absolute top-0 translate-middle btn btn-sm btn-primary rounded-pill" style="left: 0; width: 2rem; height:2rem;">1</button>
                        <button type="button" class="position-absolute top-0 translate-middle btn btn-sm btn-primary rounded-pill" style="left: 25%; width: 2rem; height:2rem;">2</button>
                        <button type="button" class="position-absolute top-0 translate-middle btn btn-sm btn-primary rounded-pill" style="left: 50%; width: 2rem; height:2rem;">3</button>
                        <button type="button" class="position-absolute top-0 translate-middle btn btn-sm btn-primary rounded-pill" style="left: 100%; transform: translateX(-50%); width: 2rem; height:2rem;">4</button>

                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="d-flex">
                        <div class="p-2 ms-5 flex-fill">Order Received</div>
                        <div class="p-2 ms-5 flex-fill">Processing</div>
                        <div class="p-2 ms-5  flex-fill">On the way</div>
                        <div class="p-2 ms-5 flex-fill">Delivered</div>
                    </div>
                </div>
            </div>

            <div class="row my-5">
                <div class="col-lg-12">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <h5>Product</h5>
                                </th>
                                <th scope="col">
                                    <h5>Price</h5>
                                </th>
                                <th scope="col">
                                    <h5>Quantity</h5>
                                </th>
                                <th scope="col">
                                    <h5>Subtotal</h5>
                                </th>
                                <!-- <th scope="col"><h5>Status</h5></th> -->
                                <!-- <th scope="col"><h5>Action</h5></th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                            <tr>
                                <th>
                                    <div class="d-flex">
                                        <div>
                                            <img src="{{ asset($item->image) }}" style="width: 70px;" class="rounded-3">
                                        </div>
                                        <div class="p-3">
                                            <h5>{{$item->name }}</h5>
                                        </div>
                                    </div>

                                </th>
                                <td>₹ {{$item->price }}</td>
                                <td>{{$item->qty }}</td>
                                <td>₹ {{$item->total }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

            </div>

        </div>
    </main>

    @endsection