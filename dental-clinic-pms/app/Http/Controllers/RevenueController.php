<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RevenueController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Basic revenue metrics
        $metrics = [
            'total_revenue' => Invoice::where('status', 'paid')->sum('total_amount'),
            'this_month_revenue' => Invoice::where('status', 'paid')
                ->where('created_at', '>=', $thisMonth)
                ->sum('total_amount'),
            'last_month_revenue' => Invoice::where('status', 'paid')
                ->where('created_at', '>=', $lastMonth)
                ->where('created_at', '<', $thisMonth)
                ->sum('total_amount'),
            'outstanding_balance' => Invoice::whereIn('status', ['sent', 'partially_paid'])
                ->sum('outstanding_balance'),
            'total_invoices' => Invoice::count(),
            'paid_invoices' => Invoice::where('status', 'paid')->count(),
            'pending_invoices' => Invoice::whereIn('status', ['sent', 'partially_paid'])->count(),
        ];

        // Recent payments
        $recentPayments = Payment::with(['patient', 'invoice'])
            ->orderBy('payment_date', 'desc')
            ->take(10)
            ->get();

        // Monthly revenue for chart
        $monthlyRevenue = Invoice::where('status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Payment methods breakdown
        $paymentMethods = Payment::selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        return view('revenue.index', compact('metrics', 'recentPayments', 'monthlyRevenue', 'paymentMethods'));
    }

    public function reports()
    {
        $startDate = request('start_date', Carbon::now()->startOfMonth());
        $endDate = request('end_date', Carbon::now()->endOfMonth());

        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }

        // Revenue by date range
        $revenue = Invoice::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        // Daily revenue breakdown
        $dailyRevenue = Invoice::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue, COUNT(*) as invoice_count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top patients by revenue
        $topPatients = Invoice::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('patient')
            ->selectRaw('patient_id, SUM(total_amount) as total_revenue, COUNT(*) as invoice_count')
            ->groupBy('patient_id')
            ->orderBy('total_revenue', 'desc')
            ->take(10)
            ->get();

        // Outstanding invoices
        $outstandingInvoices = Invoice::whereIn('status', ['sent', 'partially_paid'])
            ->with('patient')
            ->orderBy('due_date')
            ->get();

        return view('revenue.reports', compact(
            'revenue', 
            'dailyRevenue', 
            'topPatients', 
            'outstandingInvoices',
            'startDate',
            'endDate'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }

        $invoices = Invoice::whereBetween('created_at', [$startDate, $endDate])
            ->with(['patient', 'createdBy'])
            ->orderBy('created_at')
            ->get();

        // Generate CSV
        $filename = 'revenue_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($invoices) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Invoice #', 'Date', 'Patient', 'Status', 'Total Amount', 
                'Amount Paid', 'Outstanding', 'Created By'
            ]);

            // CSV data
            foreach ($invoices as $invoice) {
                fputcsv($file, [
                    $invoice->id,
                    $invoice->created_at->format('Y-m-d'),
                    $invoice->patient->full_name ?? 'N/A',
                    $invoice->status,
                    $invoice->total_amount,
                    $invoice->total_amount - $invoice->outstanding_balance,
                    $invoice->outstanding_balance,
                    $invoice->createdBy->name ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}