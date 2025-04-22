<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Location;
use App\Models\StockMovement;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class InventoryReportController extends Controller
{
    /**
     * Display the inventory report based on the selected period.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $period = $request->query('period', 'daily');
        $date = $request->query('date', Carbon::now()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);
        
        // Get date range based on period
        [$startDate, $endDate, $dateFormat, $previousPeriod, $nextPeriod] = $this->getDateRange($period, $selectedDate);
        
        // Get inventory summary
        $summary = $this->getInventorySummary($startDate, $endDate);
        
        // Get stock movement data for the period
        $stockMovements = StockMovement::whereBetween('created_at', [$startDate, $endDate])
            ->with(['item', 'fromLocation', 'toLocation'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Get top items assigned/transferred
        $topItems = StockMovement::whereBetween('created_at', [$startDate, $endDate])
            ->select('item_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('item_id')
            ->with('item')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();
        
        // Get top locations with most item movements
        $topLocations = StockMovement::whereBetween('created_at', [$startDate, $endDate])
            ->select('to_location_id', DB::raw('COUNT(*) as total_movements'))
            ->whereNotNull('to_location_id')
            ->groupBy('to_location_id')
            ->with('toLocation')
            ->orderBy('total_movements', 'desc')
            ->limit(5)
            ->get();
        
        // Get purchase orders during this period
        $purchaseOrders = PurchaseOrder::whereBetween('created_at', [$startDate, $endDate])
            ->with('supplier')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('inventory.reports.index', compact(
            'period', 
            'date', 
            'selectedDate',
            'startDate', 
            'endDate', 
            'dateFormat',
            'previousPeriod',
            'nextPeriod',
            'summary',
            'stockMovements', 
            'topItems', 
            'topLocations',
            'purchaseOrders'
        ));
    }
    
    /**
     * Export the inventory report as PDF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        try {
            $period = $request->query('period', 'daily');
            $date = $request->query('date', Carbon::now()->format('Y-m-d'));
            $selectedDate = Carbon::parse($date);
            
            // Get date range
            [$startDate, $endDate, $dateFormat, $previousPeriod, $nextPeriod] = $this->getDateRange($period, $selectedDate);
            
            // Get data for the report
            $summary = $this->getInventorySummary($startDate, $endDate);
            $stockMovements = StockMovement::whereBetween('created_at', [$startDate, $endDate])
                ->with(['item', 'fromLocation', 'toLocation'])
                ->orderBy('created_at', 'desc')
                ->limit(100)
                ->get();
            
            $topItems = StockMovement::whereBetween('created_at', [$startDate, $endDate])
                ->select('item_id', DB::raw('SUM(quantity) as total_quantity'))
                ->groupBy('item_id')
                ->with('item')
                ->orderBy('total_quantity', 'desc')
                ->limit(10)
                ->get();
            
            // Define the report title based on period
            $reportTitle = "Inventory Report - ";
            if ($period == 'daily') {
                $reportTitle .= "Daily (" . $selectedDate->format($dateFormat) . ")";
            } elseif ($period == 'weekly') {
                $reportTitle .= "Weekly (" . $startDate->format('M d') . " - " . $endDate->format('M d, Y') . ")";
            } elseif ($period == 'monthly') {
                $reportTitle .= "Monthly (" . $selectedDate->format($dateFormat) . ")";
            } elseif ($period == 'yearly') {
                $reportTitle .= "Yearly (" . $selectedDate->format($dateFormat) . ")";
            }
            
            // Prepare data for PDF generation
            $data = [
                'title' => $reportTitle,
                'period' => $period,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'summary' => $summary,
                'stockMovements' => $stockMovements,
                'topItems' => $topItems,
                'generatedAt' => now()->format('F d, Y H:i:s')
            ];
            
            // Generate PDF using DomPDF
            $pdf = Pdf::loadView('inventory.reports.pdf', $data);
            
            // Set paper size and orientation
            $pdf->setPaper('a4', 'portrait');
            
            // Generate the filename
            $filename = 'inventory_report_' . $period . '_' . $date . '.pdf';
            
            // Download the PDF
            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('PDF Generation Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could not generate PDF: ' . $e->getMessage());
        }
    }
    
    /**
     * Get date range based on period.
     *
     * @param  string  $period
     * @param  \Carbon\Carbon  $selectedDate
     * @return array
     */
    private function getDateRange($period, $selectedDate)
    {
        switch ($period) {
            case 'daily':
                $startDate = $selectedDate->copy()->startOfDay();
                $endDate = $selectedDate->copy()->endOfDay();
                $dateFormat = 'F d, Y';
                $previousPeriod = $selectedDate->copy()->subDay()->format('Y-m-d');
                $nextPeriod = $selectedDate->copy()->addDay()->format('Y-m-d');
                break;
                
            case 'weekly':
                $startDate = $selectedDate->copy()->startOfWeek();
                $endDate = $selectedDate->copy()->endOfWeek();
                $dateFormat = 'M d - M d, Y';
                $previousPeriod = $selectedDate->copy()->subWeek()->format('Y-m-d');
                $nextPeriod = $selectedDate->copy()->addWeek()->format('Y-m-d');
                break;
                
            case 'monthly':
                $startDate = $selectedDate->copy()->startOfMonth();
                $endDate = $selectedDate->copy()->endOfMonth();
                $dateFormat = 'F Y';
                $previousPeriod = $selectedDate->copy()->subMonth()->format('Y-m-d');
                $nextPeriod = $selectedDate->copy()->addMonth()->format('Y-m-d');
                break;
                
            case 'yearly':
                $startDate = $selectedDate->copy()->startOfYear();
                $endDate = $selectedDate->copy()->endOfYear();
                $dateFormat = 'Y';
                $previousPeriod = $selectedDate->copy()->subYear()->format('Y-m-d');
                $nextPeriod = $selectedDate->copy()->addYear()->format('Y-m-d');
                break;
                
            default:
                $startDate = $selectedDate->copy()->startOfDay();
                $endDate = $selectedDate->copy()->endOfDay();
                $dateFormat = 'F d, Y';
                $previousPeriod = $selectedDate->copy()->subDay()->format('Y-m-d');
                $nextPeriod = $selectedDate->copy()->addDay()->format('Y-m-d');
                break;
        }
        
        return [$startDate, $endDate, $dateFormat, $previousPeriod, $nextPeriod];
    }
    
    /**
     * Get inventory summary data.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    private function getInventorySummary($startDate, $endDate)
    {
        $totalItems = Item::count();
        $activeItems = Item::where('is_active', true)->count();
        
        $stockMovementsCount = StockMovement::whereBetween('created_at', [$startDate, $endDate])->count();
        
        $itemsAssignedCount = StockMovement::where('type', 'out')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('to_location_id')
            ->whereRaw("(notes IS NULL OR notes NOT LIKE 'Pullout:%')")
            ->count();
            
        $itemsPulledCount = StockMovement::where('type', 'out')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereRaw("notes LIKE 'Pullout:%'")
            ->count();
            
        $transfersCount = StockMovement::where('type', 'transfer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $purchaseOrdersCount = PurchaseOrder::whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $purchaseOrdersDeliveredCount = PurchaseOrder::where('status', 'delivered')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        return [
            'totalItems' => $totalItems,
            'activeItems' => $activeItems,
            'stockMovementsCount' => $stockMovementsCount,
            'itemsAssignedCount' => $itemsAssignedCount,
            'itemsPulledCount' => $itemsPulledCount,
            'transfersCount' => $transfersCount,
            'purchaseOrdersCount' => $purchaseOrdersCount,
            'purchaseOrdersDeliveredCount' => $purchaseOrdersDeliveredCount,
        ];
    }
} 