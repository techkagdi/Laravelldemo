@extends('admin.includes.main')
@push('title')
<title>Orders</title>
@endpush

@php
$orders = App\Models\Order::with('billing', 'items.product.category')->get();
@endphp

@section('content')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="card p-4 mt-4">
                <div class="row">
                    <div class="col-xl-12 col-md-12">
                        <div class="d-flex">
                            <h4>Orders</h4>
                        </div>
                        @if(session('msg'))
                        <div class="alert alert-success">
                            {{ session('msg') }}
                        </div>
                        @endif
                        <div class="mt-3">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th scope="col">Order Id</th>
                                        <th scope="col">Customer Name</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                    <tr>
                                        <th scope="row">{{ $order->order_no }}</th>

                                        {{-- FIX: null-safe in case billing record is missing --}}
                                        <td>{{ $order->billing->fullname ?? 'N/A' }}</td>

                                        <td>₹ {{ number_format($order->total, 2) }}</td>

                                        <td>
                                            @php
                                            $statusClass = match($order->status) {
                                            'pending' => 'text-bg-secondary',
                                            'processing' => 'text-bg-warning',
                                            'on the way' => 'text-bg-info',
                                            'delivered' => 'text-bg-success',
                                            default => 'text-bg-secondary', // FIX: was missing, caused crash
                                            };
                                            @endphp
                                            <span class="badge rounded-pill {{ $statusClass }}">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 flex-wrap">

                                                @if($order->status == 'pending')
                                                <a href="{{ route('order.processing', $order->order_id) }}"
                                                    class="btn btn-sm btn-outline-secondary shadow-sm action-btn">
                                                    <i class="fa-solid fa-gear"></i> Processing
                                                </a>
                                                @endif

                                                @if($order->status == 'processing')
                                                <a href="{{ route('order.ontheway', $order->order_id) }}"
                                                    class="btn btn-sm btn-outline-primary shadow-sm action-btn">
                                                    <i class="fa-solid fa-truck"></i> Ship
                                                </a>
                                                @endif

                                                @if($order->status == 'on the way')
                                                <a href="{{ route('order.delivered', $order->order_id) }}"
                                                    class="btn btn-sm btn-outline-success shadow-sm action-btn">
                                                    <i class="fa-solid fa-check-circle"></i> Deliver
                                                </a>
                                                @endif

                                                <a href="{{ url('admin/order-detail/'.$order->order_id) }}"
                                                    class="btn btn-sm btn-outline-warning shadow-sm action-btn">
                                                    <i class="fa-solid fa-eye"></i> View
                                                </a>

                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div> {{-- FIX: closing div for layoutSidenav_content was missing --}}

@endsection