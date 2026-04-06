@extends('admin.includes.main')
@push('title')
<title>Order Report - Admin</title>
@endpush

@section('content')
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="my-4">Order Report</h1>

            {{-- FILTER --}}
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ url('admin/order-report') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label>From</label>
                                <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                            </div>
                            <div class="col-md-4">
                                <label>To</label>
                                <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-dark w-100 mt-4">Generate</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CHART --}}
            @if(isset($orders) && $orders->count())

            @php
            $grouped = $orders->groupBy(function($o){
            return \Carbon\Carbon::parse($o->created_at)->format('Y-m-d');
            });

            $labels = [];
            $data = [];

            foreach($grouped as $date => $group){
            $labels[] = \Carbon\Carbon::parse($date)->format('d M');
            $data[] = $group->sum('total');
            }
            @endphp

            <div class="card mb-4">
                <div class="card-header">Orders Chart</div>
                <div class="card-body">
                    <canvas id="ordersChart"></canvas>
                </div>
            </div>

            {{-- PASS DATA --}}
            <script>
                window.chartLabels = @json($labels);
                window.chartData = @json($data);
            </script>

            @endif

            {{-- TABLE --}}
            @if(isset($orders) && $orders->count())
            <div class="card mb-4">
                <div class="card-header">
                    Orders from {{ request('from') }} to {{ request('to') }}
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Order</th>
                                <th>Date</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $i => $o)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $o->order_no }}</td>
                                <td>{{ \Carbon\Carbon::parse($o->created_at)->format('d-m-Y') }}</td>
                                <td>₹ {{ $o->total }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </main>
</div>

{{-- CHART --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        if (!window.chartLabels || !window.chartData) {
            console.log("Chart data missing");
            return;
        }

        const ctx = document.getElementById('ordersChart');
        if (!ctx) {
            console.log("Canvas not found");
            return;
        }

        const colors = window.chartLabels.map((_, i) => {
            const c = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];
            return c[i % c.length];
        });

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: window.chartLabels,
                datasets: [{
                    label: 'Order Amount',
                    data: window.chartData,
                    backgroundColor: colors
                }]
            }
        });

    });
</script>

@endsection