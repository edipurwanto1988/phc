<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->first();

        if (!$customer) {
            // Auto-create customer profile if it doesn't exist
            $customer = Customer::create([
                'user_id' => $user->id,
                'nama' => $user->name,
                'email' => $user->email,
                'no_wa' => '6281234567890', // placeholder, user can update
                'alamat' => 'Alamat belum diatur',
                'status' => 'active',
            ]);
        }

        $orders = Order::where('customer_id', $customer->id)
            ->with(['assignments.cleaner'])
            ->orderBy('tanggal_jadwal', 'desc')
            ->limit(5)
            ->get();

        return view('customer.dashboard', compact('customer', 'orders'));
    }

    public function submitTestimonial(Request $request)
    {
        $request->validate([
            'konten' => 'required|string|max:500',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->first();

        Testimonial::create([
            'customer_id' => $customer ? $customer->id : null,
            'nama' => $user->name,
            'konten' => $request->konten,
            'rating' => $request->rating,
            'is_approved' => false, // Requires admin moderation
            'is_featured' => false,
        ]);

        return redirect()->back()->with('success', 'Testimoni Anda berhasil dikirim dan sedang menunggu moderasi admin.');
    }
}
