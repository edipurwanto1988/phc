<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menyajikan gambar yang tersimpan lokal (di dalam public/) atau di Google (Drive / avatar).
 *
 * - Path lokal dibatasi ke file gambar di dalam folder public (anti path traversal).
 * - URL remote hanya boleh ke host Google (anti SSRF).
 * - Token Google Drive hanya dikirim ke host Drive.
 */
class ImageProxy
{
    private const ALLOWED_HOST_SUFFIXES = ['google.com', 'googleusercontent.com', 'googleapis.com'];

    private const DRIVE_HOSTS = ['drive.google.com', 'drive.usercontent.google.com', 'www.googleapis.com'];

    private const LOCAL_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    public static function respond(?string $pathOrUrl): Response
    {
        if (!$pathOrUrl) {
            abort(404);
        }

        if (!str_starts_with($pathOrUrl, 'http')) {
            return self::localFile($pathOrUrl);
        }

        return self::remote($pathOrUrl);
    }

    private static function localFile(string $path): Response
    {
        $publicRoot = realpath(public_path());
        $fullPath = realpath(public_path($path));

        abort_unless(
            $publicRoot && $fullPath
                && str_starts_with($fullPath, $publicRoot . DIRECTORY_SEPARATOR)
                && is_file($fullPath)
                && in_array(strtolower(pathinfo($fullPath, PATHINFO_EXTENSION)), self::LOCAL_IMAGE_EXTENSIONS, true),
            404
        );

        return response()->file($fullPath);
    }

    private static function remote(string $url): Response
    {
        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');

        abort_unless(($parts['scheme'] ?? '') === 'https' && self::isAllowedHost($host), 404);

        $headers = [];
        $token = self::driveAccessToken();
        if ($token && in_array($host, self::DRIVE_HOSTS, true)) {
            // curl tidak meneruskan header ini bila redirect pindah host
            $headers[] = 'Authorization: Bearer ' . $token;
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
            CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTPS,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $finalHost = strtolower((string) parse_url((string) curl_getinfo($ch, CURLINFO_EFFECTIVE_URL), PHP_URL_HOST));
        $error = curl_error($ch);
        curl_close($ch);

        if ($data === false) {
            Log::error('Image proxy error: ' . $error);
            abort(404, 'Gagal mengambil gambar.');
        }

        abort_unless($httpCode === 200 && $data !== '' && self::isAllowedHost($finalHost), 404, 'Gagal mengambil gambar.');

        // Hanya sajikan konten yang benar-benar gambar (cegah HTML/JS ikut tersaji dari domain kita)
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->buffer($data) ?: '';
        abort_unless(str_starts_with($mime, 'image/') && $mime !== 'image/svg+xml', 404);

        return response($data)
            ->header('Content-Type', $mime)
            ->header('X-Content-Type-Options', 'nosniff');
    }

    private static function isAllowedHost(string $host): bool
    {
        foreach (self::ALLOWED_HOST_SUFFIXES as $suffix) {
            if ($host === $suffix || str_ends_with($host, '.' . $suffix)) {
                return true;
            }
        }

        return false;
    }

    private static function driveAccessToken(): ?string
    {
        if (Setting::get('gdrive_connected') !== 'true') {
            return null;
        }

        $token = json_decode((string) Setting::get('gdrive_access_token'), true);

        return is_array($token) ? ($token['access_token'] ?? null) : null;
    }
}
