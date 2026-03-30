@extends('user.layouts.main')
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
                                @if(isset($products) && $products->count())
                                <a href="{{ url('admin/product-report/export') }}?from={{ request('from') }}&to={{ request('to') }}"
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
            @if(isset($products))
            @if($products->count())
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">
                        Product Sales from <strong>{{ request('from') }}</strong> to <strong>{{ request('to') }}</strong>
                    </span>
                    <span class="badge bg-secondary">{{ $products->count() }} products</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Rank</th>
                                    <th>Product Name</th>
                                    <th>Total Qty Sold</th>
                                    <th>Total Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $i => $product)
                                <tr>
                                    <td>
                                        @if($i === 0)
                                        <span class="badge bg-warning text-dark">#1</span>
                                        @elseif($i === 1)
                                        <span class="badge bg-secondary">#2</span>
                                        @elseif($i === 2)
                                        <span class="badge" style="background:#cd7f32">#3</span>
                                        @else
                                        #{{ $i + 1 }}
                                        @endif
                                    </td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>
                                        <strong>{{ $product->total_qty }}</strong> units
                                    </td>
                                    <td>₹ {{ number_format($product->total_revenue, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-dark">
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Totals</td>
                                    <td class="fw-bold">{{ $products->sum('total_qty') }} units</td>
                                    <td class="fw-bold">₹ {{ number_format($products->sum('total_revenue'), 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-warning">No product sales found for the selected date range.</div>
            @endif
            @endif

        </div>
    </main>
</div>
@endsection