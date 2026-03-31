@extends('admin.includes.main')
@push('title')
<title>Order Report - Admin</title>
@endpush

@section('content')
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="my-4">Order Report</h1>

            {{-- Filter Form --}}
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ url('admin/order-report') }}" id="reportForm">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">From Date</label>
                                <input type="date" name="from" class="form-control"
                                    value="{{ request('from') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">To Date</label>
                                <input type="date" name="to" class="form-control"
                                    value="{{ request('to') }}" required>
                            </div>
                            <div class="col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-dark flex-fill">
                                    Generate Report
                                </button>
                                @if(isset($orders) && $orders->count())
                                <a href="{{ url('admin/order-report/export') }}?from={{ request('from') }}&to={{ request('to') }}"
                                    class="btn btn-success flex-fill">
                                    Export Excel
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Report Table --}}
            @if(isset($orders))
            @if($orders->count())
            <div class="card mb-4" id="reportTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">
                        Orders from <strong>{{ request('from') }}</strong> to <strong>{{ request('to') }}</strong>
                    </span>
                    <span class="badge bg-secondary">{{ $orders->count() }} orders</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Products</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $i => $order)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td><strong>{{ $order->order_no }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}</td>
                                    <td>
                                        <ul class="mb-0 ps-3">
                                            @foreach($order->items as $item)
                                            <li>{{ $item->product_name }} &times; {{ $item->quantity }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>₹ {{ number_format($order->total, 2) }}</td>
                                    <td>
                                        @php
                                        $statusClass = match($order->status){
                                        'pending' => 'bg-secondary',
                                        'processing' => 'bg-warning text-dark',
                                        'on the way' => 'bg-info text-dark',
                                        'delivered' => 'bg-success',
                                        default => 'bg-secondary',
                                        };
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ $order->status }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-dark">
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Grand Total</td>
                                    <td class="fw-bold">₹ {{ number_format($orders->sum('total'), 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-warning">No orders found for the selected date range.</div>
            @endif
            @endif

        </div>
    </main>
</div>
@endsection