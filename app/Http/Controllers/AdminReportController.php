<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Common\Entity\Style\Color;

class AdminReportController extends Controller
{
    // ─────────────────────────────────────────
    // ORDER REPORT
    // ─────────────────────────────────────────

    public function orderReport(Request $request)
    {
        $orders = null;

        if ($request->has('from') && $request->has('to')) {
            $orders = Order::with('items')
                ->whereBetween(DB::raw('DATE(created_at)'), [
                    $request->from,
                    $request->to,
                ])
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('admin.reports.order-report', compact('orders'));
    }

    public function exportOrderReport(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;

        $orders = Order::with('items')
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->orderBy('created_at', 'asc')
            ->get();

        // Build flat rows for Excel
        $rows   = [];
        $grandTotal = 0;

        // Header row
        $rows[] = ['#', 'Order ID', 'Date', 'Product', 'Qty', 'Order Total', 'Status'];

        $i = 1;
        foreach ($orders as $order) {
            $firstRow = true;
            foreach ($order->items as $item) {
                $rows[] = [
                    $firstRow ? $i        : '',
                    $firstRow ? $order->order_no : '',
                    $firstRow ? \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') : '',
                    $item->product_name . ' x' . $item->quantity,
                    $item->quantity,
                    $firstRow ? $order->total : '',
                    $firstRow ? $order->status : '',
                ];
                $firstRow = false;
            }
            $grandTotal += $order->total;
            $i++;
        }

        // Grand total row
        $rows[] = ['', '', '', '', 'Grand Total', $grandTotal, ''];

        return $this->downloadExcel(
            $rows,
            'order_report_' . $from . '_to_' . $to . '.xlsx'
        );
    }

    // ─────────────────────────────────────────
    // PRODUCT REPORT
    // ─────────────────────────────────────────

    public function productReport(Request $request)
    {
        $products = null;

        if ($request->has('from') && $request->has('to')) {
            $products = OrderItem::select(
                'product_name',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(quantity * price) as total_revenue')
            )
                ->whereHas('order', function ($q) use ($request) {
                    $q->whereBetween(DB::raw('DATE(created_at)'), [
                        $request->from,
                        $request->to,
                    ]);
                })
                ->groupBy('product_name')
                ->orderByDesc('total_qty')
                ->get();
        }

        return view('admin.reports.product-report', compact('products'));
    }

    public function exportProductReport(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;

        $products = OrderItem::select(
            'product_name',
            DB::raw('SUM(quantity) as total_qty'),
            DB::raw('SUM(quantity * price) as total_revenue')
        )
            ->whereHas('order', function ($q) use ($from, $to) {
                $q->whereBetween(DB::raw('DATE(created_at)'), [$from, $to]);
            })
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->get();

        $rows = [];
        $rows[] = ['Rank', 'Product Name', 'Total Qty Sold', 'Total Revenue (₹)'];

        foreach ($products as $i => $product) {
            $rows[] = [
                '#' . ($i + 1),
                $product->product_name,
                $product->total_qty,
                number_format($product->total_revenue, 2),
            ];
        }

        // Totals row
        $rows[] = [
            '',
            'TOTAL',
            $products->sum('total_qty'),
            number_format($products->sum('total_revenue'), 2),
        ];

        return $this->downloadExcel(
            $rows,
            'product_report_' . $from . '_to_' . $to . '.xlsx'
        );
    }

    // ─────────────────────────────────────────
    // SHARED: Excel download helper using openpyxl via PHP temp file
    // ─────────────────────────────────────────

    private function downloadExcel(array $rows, string $filename)
    {
        $filepath = storage_path('app/' . $filename);

        // Use PhpSpreadsheet (installed via composer) OR simple CSV fallback
        // We use openpyxl-style via PhpSpreadsheet
        if (class_exists(\PhpOffice\PhpSpreadsheet\Spreadsheet::class)) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet       = $spreadsheet->getActiveSheet();

            foreach ($rows as $rowIndex => $row) {
                foreach ($row as $colIndex => $value) {
                    $sheet->setCellValueByColumnAndRow($colIndex + 1, $rowIndex + 1, $value);
                }
                // Bold the header row and the last (total) row
                if ($rowIndex === 0 || $rowIndex === count($rows) - 1) {
                    $sheet->getStyle('A' . ($rowIndex + 1) . ':G' . ($rowIndex + 1))
                        ->getFont()->setBold(true);
                }
            }

            // Auto-size columns
            foreach (range('A', 'G') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($filepath);
        } else {
            // Fallback: export as CSV if PhpSpreadsheet not installed
            $filename = str_replace('.xlsx', '.csv', $filename);
            $filepath = storage_path('app/' . $filename);
            $fp = fopen($filepath, 'w');
            foreach ($rows as $row) {
                fputcsv($fp, $row);
            }
            fclose($fp);
        }

        return response()->download($filepath, $filename)->deleteFileAfterSend(true);
    }
}
