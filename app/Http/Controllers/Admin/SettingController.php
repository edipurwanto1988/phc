<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string',
        ]);

        foreach ($request->settings as $key => $value) {
            // Find setting to determine group, default to 'general'
            $setting = Setting::where('key', $key)->first();
            $group = $setting ? $setting->group : 'general';
            
            Setting::set($key, $value, $group);
        }

        return redirect()->route('admin.settings.index', ['tab' => $request->input('tab', 'general')])->with('success', 'Pengaturan website berhasil diperbarui.');
    }
}
