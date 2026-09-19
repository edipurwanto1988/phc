<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Loker;
use App\Models\PeriodeLoker;
use App\Models\Setting;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;

class LokerController extends Controller
{
    public function index()
    {
        $periodeAktif = $this->getOpenPeriode();
        $captchaQuestion = $this->generateCaptcha();
        return view('pages.loker', compact('periodeAktif', 'captchaQuestion'));
    }

    /**
     * Buat soal captcha matematika sederhana dan simpan jawabannya di session.
     */
    protected function generateCaptcha(): string
    {
        $a = rand(1, 9);
        $b = rand(1, 9);
        $answer = $a + $b;

        session(['loker_captcha_answer' => $answer]);

        return "{$a} + {$b} = ?";
    }

    /**
     * Ambil periode yang sedang dibuka (status aktif + dalam rentang tanggal).
     */
    protected function getOpenPeriode(): ?PeriodeLoker
    {
        return PeriodeLoker::where('status', 'aktif')
            ->get()
            ->first(fn ($p) => $p->isOpen());
    }

    public function submit(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'pengalaman' => 'nullable|string',
            'keahlian_khusus' => 'nullable|string',
            'cerita' => 'nullable|string',
            'no_wa' => 'required|string|max:20',
            'ktp' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'captcha_answer' => 'required|string',
        ]);

        // Validasi captcha
        $storedAnswer = session('loker_captcha_answer');
        if ($storedAnswer === null || (int) $request->captcha_answer !== (int) $storedAnswer) {
            // regenerate soal agar tidak bisa brute-force dengan jawaban lama
            session()->forget('loker_captcha_answer');
            return back()
                ->withInput()
                ->with('error', 'Captcha salah. Silakan isi jawaban dengan benar.');
        }

        // Periode aktif — hanya yang status aktif DAN masih dalam rentang tanggal
        $periodeAktif = $this->getOpenPeriode();

        if (!$periodeAktif) {
            return back()
                ->withInput()
                ->with('error', 'Maaf, loker saat ini belum tersedia. Silakan cek kembali nanti.');
        }

        $noWa = preg_replace('/[^0-9]/', '', $request->no_wa);

        // Cek duplikat: no WA sudah pernah terdaftar
        $existing = Loker::where('no_wa', $request->no_wa)
            ->orWhere('no_wa', $noWa)
            ->first();

        if ($existing) {
            return back()
                ->withInput()
                ->with('error', 'Anda sudah pernah mendaftar. Nomor WhatsApp tersebut sudah terdaftar.');
        }

        $data = $request->only([
            'nama', 'email', 'alamat', 'jenis_kelamin',
            'pengalaman', 'keahlian_khusus', 'cerita', 'no_wa',
        ]);

        $data['periode_id'] = $periodeAktif->id;

        // Upload KTP, file di-rename sesuai no WA — ke Google Drive jika terhubung, fallback lokal
        if ($request->hasFile('ktp')) {
            $this->uploadKtp($request->file('ktp'), $request->no_wa, $data);
        }

        Loker::create($data);

        session()->forget('loker_captcha_answer');

        return back()->with('success', 'Terima kasih! Lamaran Anda berhasil dikirim. Kami akan menghubungi Anda melalui WhatsApp.');
    }

    protected function uploadKtp($file, string $noWa, array &$data)
    {
        $safeNoWa = preg_replace('/[^0-9]/', '', $noWa);
        $filename = 'ktp_' . $safeNoWa . '.' . $file->getClientOriginalExtension();

        $gdriveConnected = Setting::get('gdrive_connected') === 'true';
        if ($gdriveConnected) {
            try {
                $driveService = new GoogleDriveService();
                $result = $driveService->uploadFile($file->getRealPath(), $filename, 'PHC_Loker');
                $data['ktp'] = $result['web_content_link'];
                $data['ktp_drive_id'] = $result['id'];
                return;
            } catch (\Exception $e) {
                \Log::error("Failed to upload public Loker KTP to GDrive: " . $e->getMessage());
            }
        }

        $dir = public_path('uploads/loker');
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $file->move($dir, $filename);
        $data['ktp'] = 'uploads/loker/' . $filename;
    }
}
