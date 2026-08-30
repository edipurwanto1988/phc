<?php
/**
 * Updated: 20 August 2026
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\GoogleDriveService;
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
            $setting = Setting::where('key', $key)->first();
            $group = $setting ? $setting->group : 'general';
            
            Setting::set($key, $value, $group);
        }

        return redirect()->route('admin.settings.index', ['tab' => $request->input('tab', 'general')])->with('success', 'Pengaturan website berhasil diperbarui.');
    }

    public function redirectToGoogleDrive(GoogleDriveService $driveService)
    {
        try {
            $url = $driveService->getAuthUrl();
            return redirect()->away($url);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal menginisialisasi OAuth: ' . $e->getMessage()]);
        }
    }

    public function handleGoogleDriveCallback(Request $request, GoogleDriveService $driveService)
    {
        if (!$request->has('code')) {
            return redirect()->route('admin.settings.index', ['tab' => 'gdrive'])
                ->withErrors(['error' => 'Akses ditolak atau kode verifikasi OAuth kosong.']);
        }

        try {
            $driveService->authenticate($request->code);
            return redirect()->route('admin.settings.index', ['tab' => 'gdrive'])
                ->with('success', 'Google Drive berhasil terhubung!');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.index', ['tab' => 'gdrive'])
                ->withErrors(['error' => 'Gagal menghubungkan Google Drive: ' . $e->getMessage()]);
        }
    }

    public function disconnectGDrive()
    {
        Setting::set('gdrive_connected', 'false', 'general');
        Setting::set('gdrive_access_token', '', 'general');
        Setting::set('gdrive_refresh_token', '', 'general');
        Setting::set('gdrive_account_email', '', 'general');

        return redirect()->route('admin.settings.index', ['tab' => 'gdrive'])
            ->with('success', 'Koneksi Google Drive berhasil diputuskan.');
    }

    public function testGDriveUpload(Request $request, GoogleDriveService $driveService)
    {
        $request->validate([
            'test_image' => 'required|image|max:5120'
        ]);

        $gdriveConnected = Setting::get('gdrive_connected') === 'true';
        if (!$gdriveConnected) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal: Google Drive belum terhubung.'
            ], 400);
        }

        try {
            $file = $request->file('test_image');
            $tempPath = $file->getRealPath();
            $filename = 'test_gdrive_' . time() . '.' . $file->getClientOriginalExtension();

            $result = $driveService->uploadFile($tempPath, $filename);

            return response()->json([
                'success' => true,
                'message' => 'Sukses! Gambar berhasil diunggah dan disinkronisasi ke Google Drive Anda.',
                'data' => [
                    'filename' => $filename,
                    'drive_url' => $result['web_content_link'],
                    'account' => Setting::get('gdrive_account_email', 'Terhubung'),
                    'folder_id' => Setting::get('gdrive_folder_id', 'root')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal sinkronisasi ke Google Drive: ' . $e->getMessage()
            ], 500);
        }
    }
}
