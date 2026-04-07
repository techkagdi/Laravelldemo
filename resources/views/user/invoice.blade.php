<!DOCTYPE html>
<html>

<head>
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container my-5">

        <div class="card p-4 shadow">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center">
                <h2>Invoice</h2>
                <button onclick="window.print()" class="btn btn-dark">Print</button>
            </div>

            <hr>

            {{-- USER DETAILS --}}
            <div class="row">
                <div class="col-md-6">
                    <h5>Billing Details</h5>
                    <p>{{ $billing->fullname ?? '' }}</p>
                    <p>{{ $billing->address ?? '' }}</p>
                    <p>Email: {{ $billing->email ?? '' }}</p>
                    <p>Phone: {{ $user->phone }}</p>
                </div>

                <div class="col-md-6 text-end">
                    <h5>Order Info</h5>
                    <p><strong>Order ID:</strong> {{ $order->order_no }}</p>
                    <p><strong>Date:</strong> {{ $order->created_at->format('d-m-Y') }}</p>
                    <p><strong>Payment:</strong> {{ ucfirst($order->payment_mode) }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                </div>
            </div>

            <hr>

            {{-- PRODUCTS --}}
            <table class="table table-bordered mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>₹ {{ $item->price }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>₹ {{ $item->total }}</td>
                    </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Grand Total</strong></td>
                        <td><strong>₹ {{ $order->total }}</strong></td>
                    </tr>
                </tfoot>
            </table>

            <hr>

            {{-- FOOTER --}}
            <div class="text-center">
                <p>Thank you for your purchase 🙏</p>
            </div>

        </div>

    </div>

</body>

</html>