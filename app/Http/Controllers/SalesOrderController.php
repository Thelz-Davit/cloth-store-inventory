<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesOrderController extends Controller
{
    public function index()
    {
        $orders = SalesOrder::getOrders();

        return view('sales.index', [
            'title' => 'Sales Orders',
            'subtitle' => 'Order Management',
            'orders' => $orders,
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('home')],
                ['label' => 'Sales Orders', 'url' => null],
            ],
        ]);
    }

    public function create()
    {
        return view('sales.create', [
            'title' => 'Create Sales Order',
            'subtitle' => 'Input pesanan baru',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('home')],
                ['label' => 'Sales Orders', 'url' => route('sales-orders.index')],
                ['label' => 'Create', 'url' => null],
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:150',
            'order_date' => 'required|date',
            'customer_phone' => 'nullable|string|max:30',
            'customer_address' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
        ]);

        $orderNo = 'SO-' . now()->format('YmdHis');

        $id = SalesOrder::createOrder(
            $orderNo,
            $validated['order_date'],
            $validated['customer_name'],
            Auth::id(),
            $validated['customer_phone'] ?? null,
            $validated['customer_address'] ?? null,
            $validated['note'] ?? null
        );

        return redirect()->route('sales-orders.show', $id)
            ->with('success', 'Order dibuat. Silakan tambah item produk.');
    }

    public function show($id)
    {
        $order = SalesOrder::findOrder($id);
        abort_if(!$order, 404);

        $items = SalesOrder::getItems($id);
        $products = Product::getProduct();

        return view('sales.show', [
            'title' => 'Sales Order Detail',
            'subtitle' => 'Tambah item & qty',
            'order' => $order,
            'items' => $items,
            'products' => $products,
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('home')],
                ['label' => 'Sales Orders', 'url' => route('sales-orders.index')],
                ['label' => $order->order_no, 'url' => null],
            ],
        ]);
    }

    public function addItem(Request $request, $id)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'qty' => 'required|integer|min:1',
        ]);

        $order = SalesOrder::findOrder($id);
        abort_if(!$order, 404);

        SalesOrder::addItem($id, (int)$validated['product_id'], (int)$validated['qty']);

        return back()->with('success', 'Item berhasil ditambahkan.');
    }

    public function deleteItem($itemId)
    {
        SalesOrder::deleteItem($itemId);
        return back()->with('success', 'Item berhasil dihapus.');
    }

    public function deleteOrders(Request $request)
    {
        $ids = $request->selected_orders;

        if (!$ids || count($ids) === 0) {
            return back()->with('error', 'Tidak ada order dipilih');
        }

        $result = SalesOrder::deleteOrders($ids);

        return back()->with($result['type'], $result['message']);
    }
}
