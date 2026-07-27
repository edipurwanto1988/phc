<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;

class ServiceController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::with(['services' => function($q) {
            $q->where('is_active', true)->orderBy('urutan');
        }])
        ->where('is_active', true)
        ->orderBy('urutan')
        ->get();

        return view('pages.services.index', compact('categories'));
    }

    public function show(string $slug)
    {
        $service = Service::with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get related services in same category
        $relatedServices = Service::where('kategori_id', $service->kategori_id)
            ->where('id', '!=', $service->id)
            ->where('is_active', true)
            ->limit(3)
            ->get();

        return view('pages.services.show', compact('service', 'relatedServices'));
    }
}
