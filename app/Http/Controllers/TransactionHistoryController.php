<?php

namespace App\Http\Controllers;

use App\Models\Inbound;
use App\Models\Outbound;
use Illuminate\Http\Request;

class TransactionHistoryController extends Controller
{
    public function index()
    {
        $inbounds = Inbound::with('product')
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'Inbound',
                    'name' => $item->product->product_name,
                    'quantity' => $item->quantity,
                    'status' => '-',
                    'date' => $item->inbound_date,
                ];
            });

        $outbounds = Outbound::with('bundle')
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'Outbound',
                    'name' => $item->bundle->bundle_name,
                    'quantity' => $item->quantity,
                    'status' => $item->status,
                    'date' => $item->outbound_date,
                ];
            });

        $history = $inbounds
            ->concat($outbounds)
            ->sortByDesc('date')
            ->values();

        return view('transaction-history.index', compact('history'));
    }
}
