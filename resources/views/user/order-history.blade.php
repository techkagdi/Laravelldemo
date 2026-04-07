@extends('user.layouts.main')
@push('title')
<title>Order History</title>
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
        <div class="container-fluid px-4 mt-4">

            <div class="card p-4">
                <div class="row">
                    <div class="col-xl-12 col-md-12">
                        <div class="d-flex">
                            <h4>Order History</h4>
                        </div>
                        <div class="mt-3">
                            <table id="datatablesSimple" class="table table-bordered table-striped font-center center">
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
                                        <td>{{ $order->order_no }}</td>
                                        <td>{{ $order->created_at->format('d-m-Y') }}</td>
                                        <td>₹ {{ $order->total }}</td>

                                        <td>
                                            @php
                                            $statusClass = match($order->status){
                                            'pending' => 'bg-secondary',
                                            'processing' => 'bg-warning text-dark',
                                            'on the way' => 'bg-info text-dark',
                                            'delivered' => 'bg-success',
                                            default => 'bg-secondary'
                                            };
                                            @endphp

                                            <span class="badge {{ $statusClass }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>

                                        <td class="d-flex gap-2">
                                            <a href="{{ url('user/detail/'.$order->order_id) }}"
                                                class="btn btn-sm btn-primary">
                                                View
                                            </a>

                                            <a href="{{ url('user/invoice/'.$order->order_id) }}"
                                                class="btn btn-sm btn-success">
                                                Invoice
                                            </a>
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

    @endsection