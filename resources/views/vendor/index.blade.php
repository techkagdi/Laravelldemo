@extends('vendor.includes.main')
@push('title')
    <title>Dashboard - vendor</title>
@endpush


@section('content')
       
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="my-4">Dashboard</h1>
                    
                        <div class="row">
                            <div class="col-xl-4 col-md-4">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body mx-auto mt-4">
                                      <h5 class="text-dark">Total Orders</h5>
                                    </div>
                                    <div class="mb-4">
                                        <h2 class="text-center text-dark">{{ $totalOrders }}</h2>
                                    </div>
                                </div>
                            </div>

                            

                            <div class="col-xl-4 col-md-4">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body mx-auto mt-4">
                                      <h5 class="text-dark">Total Sale</h5>
                                    </div>
                                    <div class="mb-4">
                                        <h2 class="text-center text-dark">₹ {{ number_format($totalSale, 2) }}</h2>    
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-4">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body mx-auto mt-4">
                                      <h5 class="text-dark">Pending Orders</h5>
                                    </div>
                                    <div class="mb-4">
                                        <h2 class="text-center text-dark">{{ $pendingOrders }}</h2>    
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                        <div class="row">
                            <div class="col-xl-12 col-md-12">
                                <div class="d-flex">
                                        <h4>Recent Orders</h4>
                                        <div class="ms-auto ">
                                        <a href="{{url('vendor/orders')}}" class="text-decoration-none btn btn-dark btn-sm">View All </a>
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
                                                    @foreach ($orders as $order)
                                                    <tr>
                                                    <th scope="row">{{ $order->order_no }}</th>
                                                    <td>{{ $order->created_at }}</td>
                                                    <td>₹ {{ $order->total }}</td>
                                                    <td>
                                                       @php
                                                        $statusClass = match($order->status){
                                                            'pending' => 'text-bg-secondary',
                                                            'processing' => 'text-bg-warning',
                                                            'on the way' => 'text-bg-info',
                                                            'delivered' => 'text-bg-success',
                                                        }
                                                        @endphp
                                                        
                                                    <span class="badge rounded-pill {{ $statusClass }}">{{ $order->status}}</span>
                                                    <a href="{{url('vendor/order-detail/' .$order->order_id)}}" class=" text-decoration-none mx-2">View Details</a>
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