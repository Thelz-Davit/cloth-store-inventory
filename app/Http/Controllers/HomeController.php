<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Inbound;
use App\Models\Outbound;
use App\Models\Product;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'owner') {
            return $this->ownerDashboard();
        }

        return $this->staffDashboard();
    }
    public function ownerDashboard()
    {
        // Owner dashboard
        $totalProducts = Product::count();
        $totalBundles = Bundle::count();
        $totalInbounds = Inbound::sum('quantity');
        $totalOutbounds = Outbound::sum('quantity');

        $inStock = Product::where('stock', '>=', 20)->count();
        $lowStock = Product::where('stock', '<', 20)->count();

        $recentInbounds = Inbound::latest()->take(5)->get();
        $recentOutbounds = Outbound::latest()->take(5)->get();

        $inboundTopCard = Inbound::whereMonth('inbound_date', now()->month)
            ->sum('quantity');
        $outboundTopCard = Outbound::whereMonth('outbound_date', now()->month)
            ->sum('quantity');

        $topBundles = Outbound::selectRaw('
        bundle_id,
        SUM(quantity) as total_quantity
    ')
            ->with('bundle:id,bundle_name')
            ->groupBy('bundle_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();


        // Inbound
        $inbound = Inbound::selectRaw("
    MONTH(inbound_date) as month,
    SUM(quantity) as total
")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Outbound
        $outbound = Outbound::selectRaw("
    MONTH(outbound_date) as month,
    SUM(quantity) as total
")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $inboundData = array_fill(1, 12, 0);
        $outboundData = array_fill(1, 12, 0);

        foreach ($inbound as $row) {
            $inboundData[$row->month] = $row->total;
        }

        foreach ($outbound as $row) {
            $outboundData[$row->month] = $row->total;
        }

        $months = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
        ];

        $inboundSeries = array_values($inboundData);
        $outboundSeries = array_values($outboundData);


        return view('dashboard/owner', compact('totalProducts', 'totalBundles', 'totalInbounds', 'totalOutbounds', 'inStock', 'lowStock', 'recentOutbounds', 'recentInbounds', 'inbound', 'outbound', 'inboundTopCard', 'outboundTopCard', 'months', 'inboundSeries', 'outboundSeries', 'topBundles'));


    }

    public function staffDashboard()
    {
        $totalProducts = Product::count();
        $totalBundles = Bundle::count();

        $todayInbound = Inbound::whereDate('inbound_date', today())
            ->sum('quantity');

        $todayOutbound = Outbound::whereDate('outbound_date', today())
            ->sum('quantity');

        //change by day
        $inbound = Inbound::selectRaw("
    DATE(inbound_date) as day,
    SUM(quantity) as total
")
            ->whereMonth('inbound_date', now()->month)
            ->whereYear('inbound_date', now()->year)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // Query outbound
        $outbound = Outbound::selectRaw("
    DATE(outbound_date) as day,
    SUM(quantity) as total
")
            ->whereMonth('outbound_date', now()->month)
            ->whereYear('outbound_date', now()->year)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        $days = [];
        $inboundData = [];
        $outboundData = [];

        $inboundMap = $inbound->pluck('total', 'day');
        $outboundMap = $outbound->pluck('total', 'day');

        foreach (CarbonPeriod::create($start, $end) as $date) {
            $key = $date->format('Y-m-d');

            $days[] = $date->format('d'); // 01, 02, 03...

            $inboundData[] = $inboundMap[$key] ?? 0;
            $outboundData[] = $outboundMap[$key] ?? 0;
        }


        $lowStock = Product::where('stock', '<', 20)->count();

        $lowStockItems = Product::where('stock', '<=', 20)
            ->orderBy('stock')
            ->take(7)
            ->get();

        $recentInbounds = Inbound::with('product')
            ->select('id', 'product_id', 'quantity', 'inbound_date')
            ->latest('inbound_date')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $item->type = 'Inbound';
                $item->date = $item->inbound_date;
                return $item;
            });

        $recentOutbounds = Outbound::with('bundle')
            ->select('id', 'bundle_id', 'quantity', 'outbound_date')
            ->latest('outbound_date')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $item->type = 'Outbound';
                $item->date = $item->outbound_date;
                return $item;
            });

        $recentActivities = $recentInbounds
            ->concat($recentOutbounds)
            ->sortByDesc('quantity')
            ->take(10);

        return view('dashboard/staff', compact(
            'totalProducts',
            'totalBundles',
            'recentOutbounds',
            'recentInbounds',
            'inbound',
            'outbound',
            'days',
            'inboundData',
            'outboundData',
            'todayInbound',
            'todayOutbound',
            'lowStockItems',
            'recentActivities',
            'lowStock',
        ));
    }
}
