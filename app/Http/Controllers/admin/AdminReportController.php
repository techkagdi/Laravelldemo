<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

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

        $rows   = [];
        $rows[] = ['#', 'Order ID', 'Date', 'Product', 'Qty', 'Order Total', 'Status'];

        $i = 1;
        foreach ($orders as $order) {
            $firstRow = true;
            foreach ($order->items as $item) {
                $rows[] = [
                    $firstRow ? $i               : '',
                    $firstRow ? $order->order_no : '',
                    $firstRow ? \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') : '',
                    $item->product_name . ' x' . $item->quantity,
                    $item->quantity,
                    $firstRow ? $order->total    : '',
                    $firstRow ? $order->status   : '',
                ];
                $firstRow = false;
            }
            $i++;
        }

        $rows[] = ['', '', '', '', 'Grand Total', $orders->sum('total'), ''];

        return $this->downloadExcel($rows, 'order_report_' . $from . '_to_' . $to . '.xlsx');
    }

    // ─────────────────────────────────────────
    // PRODUCT REPORT
    // ─────────────────────────────────────────

    public function productReport(Request $request)
    {
        $products = null;

        if ($request->has('from') && $request->has('to')) {
            $products = OrderItem::select(
                'name',
                DB::raw('SUM(qty) as total_qty'),
                DB::raw('SUM(qty * price) as total_revenue')
            )
                ->whereHas('order', function ($q) use ($request) {
                    $q->whereBetween(DB::raw('DATE(created_at)'), [
                        $request->from,
                        $request->to,
                    ]);
                })
                ->groupBy('name')
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

        $rows   = [];
        $rows[] = ['Rank', 'Product Name', 'Total Qty Sold', 'Total Revenue (Rs)'];

        foreach ($products as $i => $product) {
            $rows[] = [
                '#' . ($i + 1),
                $product->product_name,
                $product->total_qty,
                number_format($product->total_revenue, 2),
            ];
        }

        $rows[] = ['', 'TOTAL', $products->sum('total_qty'), number_format($products->sum('total_revenue'), 2)];

        return $this->downloadExcel($rows, 'product_report_' . $from . '_to_' . $to . '.xlsx');
    }

    // ─────────────────────────────────────────
    // SHARED: Excel download helper
    // ─────────────────────────────────────────

    private function downloadExcel(array $rows, string $filename): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        foreach ($rows as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                $cellRef   = $colLetter . ($rowIndex + 1);
                $sheet->setCellValue($cellRef, $value);
            }

            $isHeader = $rowIndex === 0;
            $isTotal  = $rowIndex === count($rows) - 1;

            if ($isHeader || $isTotal) {
                $lastColLetter = Coordinate::stringFromColumnIndex(count($row));
                $range         = 'A' . ($rowIndex + 1) . ':' . $lastColLetter . ($rowIndex + 1);

                $sheet->getStyle($range)->getFont()->setBold(true);

                if ($isHeader) {
                    $sheet->getStyle($range)
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FF343A40');

                    $sheet->getStyle($range)
                        ->getFont()
                        ->setColor(new Color('FFFFFFFF'));
                }

                if ($isTotal) {
                    $sheet->getStyle($range)
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FFE9ECEF');
                }
            }
        }

        // Auto-size all columns
        $totalCols = max(array_map('count', $rows));
        for ($col = 1; $col <= $totalCols; $col++) {
            $sheet->getColumnDimension(
                Coordinate::stringFromColumnIndex($col)
            )->setAutoSize(true);
        }

        $filepath = storage_path('app/' . $filename);
        $writer   = new Xlsx($spreadsheet);
        $writer->save($filepath);

        return response()->download($filepath, $filename)->deleteFileAfterSend(true);
    }
}
