@extends('user.layouts.main')
@push('title')
    <title>Dashboard - User</title>
@endpush

@php
    $user = auth()->user();
    $billing = null;
    if($user && $user->is_login){
        $billing = \App\Models\Billing::where('user_id', $user->user_id)->first();
        $orders = \App\Models\Order::where('user_id', $user->user_id)->get();
    }
@endphp

@section('content')
       
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="my-4">Dashboard</h1>
                        
                    
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="card bg-info text-white mb-4">
                                    <div class="card-body mx-auto">
                                        <img src="{{asset('dashboard/assets/img/user.png')}}" style="width:155px;">
                                    </div>
                                    <div class="my-3">
                                        <h5 class="text-center text-dark">{{ $billing->fullname}}</h5>    
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="card bg-info text-white mb-4" style="height: 250px;">
                                    <div class="card-body my-4">
                                        <h5 class="text-dark">Billing Address</h5>
                                        <h6 class="text-dark">{{ $billing->address}}</h6>
                                        <span class="text-dark"><strong>Email:</strong> {{ $billing->email}}</span><br>
                                        <span class="text-dark"><strong>Phone:</strong> {{ $user->phone}}</span><br>
                                    </div>
                                    
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-xl-12 col-md-12">
                                <div class="d-flex">
                                        <h4>Recent Orders</h4>
                                        <div class="ms-auto ">
                                        <a href="{{url('order-history')}}" class="text-decoration-none btn btn-dark btn-sm">View All </a>
                                        </div>
                                </div>

                                <div class="mt-3">
                                    <table id="datatablesSimple">
                                                <thead>
                                                    <tr>
                                                    <th scope="col">Order Id</th>
                                                    <th scope="col">Date</th>
                                                    <th scope="col">Total</th>
                                                    <th scope="col">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($orders as $order )
                                                    <tr>
                                                    <th scope="row">{{ $order->order_no}}</th>
                                                    <td>{{ $order->created_at->format('d-m-y') }}</td>
                                                    <td>₹ {{ $order->total}}</td>
                                                    <td>
                                                        @php
                                                            $statusClass = match($order->status){
                                                            'pending' => 'text-bg-secondary',
                                                            'processing' => 'text-bg-warning',
                                                            'on the way' => 'text-bg-info',
                                                            'delivered' => 'text-bg-success',
                                                        }
                                                        @endphp
                                                    <span class="badge rounded-pill {{ $statusClass  }} ">{{ $order->status }}</span>
                                                    <a href="{{url('user/detail/' .$order->order_id)}}" class=" text-decoration-none mx-2">View Details</a>
                                                    </td>
                                                    </tr>
                                                    @endforeach

                                                    
                                            </tbody>
                                    </table>                                                                     
                                </div>
                            </div>
                        </div>

                    </div>
                </main>



                     
        
@endsection