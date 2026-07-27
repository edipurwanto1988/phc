<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\Customer;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::with('customer')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->orderBy('nama')->get();
        return view('admin.testimonials.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'konten' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Testimonial::create([
            'customer_id' => $request->customer_id,
            'nama' => $request->nama,
            'konten' => $request->konten,
            'rating' => $request->rating,
            'is_approved' => $request->has('is_approved'),
            'is_featured' => $request->has('is_featured'),
        ]);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function edit(Testimonial $testimonial)
    {
        $customers = Customer::where('status', 'active')->orderBy('nama')->get();
        return view('admin.testimonials.edit', compact('testimonial', 'customers'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'konten' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $testimonial->update([
            'customer_id' => $request->customer_id,
            'nama' => $request->nama,
            'konten' => $request->konten,
            'rating' => $request->rating,
            'is_approved' => $request->has('is_approved'),
            'is_featured' => $request->has('is_featured'),
        ]);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil diubah.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil dihapus.');
    }
}
