<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $customer = Customer::where('user_id', Auth::id())->firstOrFail();
        $orders = Order::where('customer_id', $customer->id)
            ->orderBy('tanggal_jadwal', 'desc')
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(string $id)
    {
        $customer = Customer::where('user_id', Auth::id())->firstOrFail();
        $order = Order::where('customer_id', $customer->id)
            ->with(['items.service', 'assignments.cleaner'])
            ->findOrFail($id);

        return view('customer.orders.show', compact('order'));
    }
}
