<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query()->with('orders');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_wa', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $customers = $query->latest()->paginate(10)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        // Get users that are not already assigned to a customer
        $users = User::whereHas('role', function($q) {
            $q->where('name', 'Customer');
        })
        ->whereNotIn('id', Customer::whereNotNull('user_id')->pluck('user_id')->toArray())
        ->get();

        return view('admin.customers.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_wa' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'required|string',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kota' => 'required|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'user_id' => 'nullable|exists:users,id|unique:customers,user_id',
            'sumber_info' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        Customer::create($request->all());

        return redirect()->route('admin.customers.index')->with('success', 'Customer berhasil ditambahkan.');
    }

    public function show(Customer $customer)
    {
        $customer->load(['orders' => function($q) {
            $q->orderBy('tanggal_order', 'desc');
        }]);
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        // Get users that are not already assigned to a customer (except the current linked one)
        $users = User::whereHas('role', function($q) {
            $q->where('name', 'Customer');
        })
        ->where(function($q) use ($customer) {
            $q->whereNotIn('id', Customer::whereNotNull('user_id')->where('user_id', '!=', $customer->user_id)->pluck('user_id')->toArray());
        })
        ->get();

        return view('admin.customers.edit', compact('customer', 'users'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_wa' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'required|string',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kota' => 'required|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'user_id' => 'nullable|exists:users,id|unique:customers,user_id,' . $customer->id,
            'sumber_info' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $customer->update($request->all());

        return redirect()->route('admin.customers.index')->with('success', 'Data customer berhasil diubah.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Customer berhasil dihapus.');
    }
}
