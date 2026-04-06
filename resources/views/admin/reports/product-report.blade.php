@extends('admin.includes.main')
@push('title')
<title>Product Report - Admin</title>
@endpush

@section('content')
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="my-4">Product Sales Report</h1>

            {{-- Filter Form --}}
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ url('admin/product-report') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label>From Date</label>
                                <input type="date" name="from" class="form-control"
                                    value="{{ request('from') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label>To Date</label>
                                <input type="date" name="to" class="form-control"
                                    value="{{ request('to') }}" required>
                            </div>
                            <div class="col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-dark flex-fill">
                                    Generate Report
                                </button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- PIE CHART --}}
            @if(isset($products) && $products->count())

            @php
            $labels = $products->pluck('name');
            $data = $products->pluck('total_qty'); // or total_revenue
            @endphp

            <div class="card mb-4">
                <div class="card-header">
                    Product Sales Chart (Pie)
                </div>
                <div class="card-body">
                    <canvas id="productChart"
                        data-labels='@json($labels)'
                        data-values='@json($data)'>
                    </canvas>
                </div>
            </div>

            @endif

            {{-- TABLE --}}
            @if(isset($products) && $products->count())
            <div class="card mb-4">
                <div class="card-header">
                    Product Sales from {{ request('from') }} to {{ request('to') }}
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Rank</th>
                                <th>Product Name</th>
                                <th>Total Qty</th>
                                <th>Total Revenue</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($products as $i => $product)
                            <tr>
                                <td>#{{ $i+1 }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->total_qty }}</td>
                                <td>₹ {{ $product->total_revenue }}</td>
                            </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="2">Total</td>
                                <td>{{ $products->sum('total_qty') }}</td>
                                <td>₹ {{ $products->sum('total_revenue') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </main>
</div>

{{-- CHART SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const chartEl = document.getElementById('productChart');
        if (!chartEl) return;

        const labels = JSON.parse(chartEl.dataset.labels);
        const data = JSON.parse(chartEl.dataset.values);

        const colors = [
            '#FF6384', // red
            '#36A2EB', // blue
            '#FFCE56', // yellow
            '#4BC0C0', // teal
            '#9966FF', // purple
            '#FF9F40', // orange
            '#00C49F', // green
            '#C9CBCF', // grey
            '#8E44AD', // dark purple
            '#2ECC71' // emerald
        ];

        new Chart(chartEl, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors,
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

    });
</script>
@endsection