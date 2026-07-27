<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Service;
use App\Models\Post;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with(['parent', 'children'])
            ->orderBy('posisi')
            ->orderBy('urutan')
            ->get();

        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        $parents = Menu::where('parent_id', null)->orderBy('nama')->get();
        $urlOptions = $this->getUrlOptions();
        return view('admin.menu.create', compact('parents', 'urlOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'icon' => 'nullable|string|max:100',
            'url' => 'nullable|string|max:255',
            'target' => 'required|in:_self,_blank',
            'parent_id' => 'nullable|exists:menus,id',
            'posisi' => 'required|in:header,footer,sidebar',
            'urutan' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        Menu::create($request->only(['nama', 'icon', 'url', 'target', 'parent_id', 'posisi', 'urutan', 'status']));

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil dibuat.');
    }

    public function edit(Menu $menu)
    {
        $parents = Menu::where('parent_id', null)
            ->where('id', '!=', $menu->id)
            ->orderBy('nama')
            ->get();
            
        $urlOptions = $this->getUrlOptions();
        return view('admin.menu.edit', compact('menu', 'parents', 'urlOptions'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'icon' => 'nullable|string|max:100',
            'url' => 'nullable|string|max:255',
            'target' => 'required|in:_self,_blank',
            'parent_id' => 'nullable|exists:menus,id',
            'posisi' => 'required|in:header,footer,sidebar',
            'urutan' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $menu->update($request->only(['nama', 'icon', 'url', 'target', 'parent_id', 'posisi', 'urutan', 'status']));

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil dihapus.');
    }

    private function getUrlOptions(): array
    {
        $options = [];

        // Services
        $options['--- Layanan Kami ---'] = Service::where('is_active', true)
            ->orderBy('nama')
            ->get()
            ->map(fn($s) => ['value' => '/layanan/' . $s->slug, 'label' => $s->nama])
            ->toArray();

        // Posts / Blog
        $options['--- Blog / Tips ---'] = Post::where('status', 'published')
            ->orderBy('judul')
            ->limit(10)
            ->get()
            ->map(fn($p) => ['value' => '/blog/' . $p->slug, 'label' => $p->judul])
            ->toArray();

        // Core routes
        $options['--- Rute Utama ---'] = [
            ['value' => '/', 'label' => 'Beranda (Home)'],
            ['value' => '/layanan', 'label' => 'Daftar Layanan'],
            ['value' => '/blog', 'label' => 'Daftar Blog'],
            ['value' => '/#tentang', 'label' => 'Tentang Kami'],
            ['value' => '/#kontak', 'label' => 'Kontak Kami'],
        ];

        return $options;
    }
}
