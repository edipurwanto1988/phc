<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class SecurityRequestGuard
{
    private array $blockedUploadExtensions = [
        'asa', 'asax', 'ascx', 'ashx', 'asmx', 'asp', 'aspx', 'bat', 'cgi', 'cmd',
        'com', 'exe', 'htaccess', 'jsp', 'jspx', 'phtml', 'phar', 'php', 'php3',
        'php4', 'php5', 'php7', 'phps', 'pl', 'ps1', 'py', 'shtml', 'sh',
    ];

    private array $sqlInjectionPatterns = [
        '/(?:^|[\s\(\)])union\s+(?:all\s+)?select\b/i',
        '/\bselect\b.+\bfrom\b.+\binformation_schema\b/i',
        '/\b(?:sleep|benchmark|load_file|outfile|dumpfile)\s*\(/i',
        '/(?:^|[\s"\'])or\s+1\s*=\s*1(?:$|[\s"\'])/i',
        '/(?:^|[\s"\'])and\s+1\s*=\s*1(?:$|[\s"\'])/i',
        '/(?:--|#|\/\*)\s*$/',
    ];

    private array $xssPatterns = [
        '/<\s*script\b/i',
        '/<\s*iframe\b/i',
        '/<\s*object\b/i',
        '/<\s*embed\b/i',
        '/<\s*svg\b[^>]*\bon\w+\s*=/i',
        '/\bon\w+\s*=/i',
        '/(?:javascript|vbscript|data)\s*:/i',
    ];

    private array $pathTraversalPatterns = [
        '/\.\.[\/\\\\]/',
        '/[\/\\\\]\.\.[\/\\\\]/',
        '/%2e%2e(?:%2f|\/|%5c|\\\\)/i',
        '/(?:%252e){2}/i',
        '/(?:^|[\/\\\\])(?:etc\/passwd|proc\/self|windows\/win\.ini)\b/i',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $this->inspectString($request->path());
        $this->inspectArray($request->query->all());
        $this->inspectArray($request->request->all(), [], $request);
        $this->inspectFiles($request->allFiles());

        return $next($request);
    }

    private function inspectArray(array $values, array $keys = [], ?Request $request = null): void
    {
        foreach ($values as $key => $value) {
            $currentKeys = [...$keys, (string) $key];

            if (is_array($value)) {
                $this->inspectArray($value, $currentKeys, $request);
                continue;
            }

            if (is_string($value)) {
                if ($this->shouldSkipEncodedRichText($request, $currentKeys)) {
                    continue;
                }

                $this->inspectString($value, $this->allowsHtmlEmbed($request, $currentKeys));
            }
        }
    }

    private function inspectFiles(array $files): void
    {
        foreach ($files as $file) {
            if (is_array($file)) {
                $this->inspectFiles($file);
                continue;
            }

            if (! $file instanceof UploadedFile) {
                continue;
            }

            $extension = strtolower((string) $file->getClientOriginalExtension());
            $filename = strtolower($file->getClientOriginalName());

            if (in_array($extension, $this->blockedUploadExtensions, true)) {
                abort(403, 'Upload file type blocked.');
            }

            foreach ($this->blockedUploadExtensions as $blockedExtension) {
                if (str_contains($filename, '.'.$blockedExtension.'.')) {
                    abort(403, 'Upload filename blocked.');
                }
            }

            $this->inspectString($filename);
        }
    }

    private function inspectString(string $value, bool $allowHtmlEmbed = false): void
    {
        $decoded = $this->decode($value);

        $patterns = array_merge(
            $this->pathTraversalPatterns,
            $this->sqlInjectionPatterns,
            $allowHtmlEmbed ? [] : $this->xssPatterns
        );

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $decoded) === 1) {
                abort(403, 'Request blocked by security guard.');
            }
        }
    }

    private function allowsHtmlEmbed(?Request $request, array $keys): bool
    {
        return $request
            && $request->isMethod('post')
            && ($request->is('admin/settings') || $request->is('admin/pengaturan'));
    }

    private function shouldSkipEncodedRichText(?Request $request, array $keys): bool
    {
        $isRichTextRoute = $request && (
            $request->is('admin/berita/*') ||
            $request->is('admin/berita') ||
            $request->is('admin/posts/*') ||
            $request->is('admin/posts') ||
            $request->is('admin/services/*') ||
            $request->is('admin/services') ||
            $request->is('admin/halaman/*') ||
            $request->is('admin/halaman')
        );

        if (! $isRichTextRoute) {
            return false;
        }

        if (! in_array(strtolower($request->method()), ['post', 'put', 'patch'], true)) {
            return false;
        }

        // PHC posts & services: TinyMCE sends plain HTML (not base64url encoded)
        if ($request->is('admin/posts/*') || $request->is('admin/posts')) {
            return in_array('konten', $keys) || $keys === ['konten'];
        }

        if ($request->is('admin/services/*') || $request->is('admin/services')) {
            return in_array('deskripsi', $keys) || $keys === ['deskripsi'];
        }

        if ($request->is('admin/halaman/*') || $request->is('admin/halaman')) {
            return in_array('isi', $keys) || $keys === ['isi'];
        }

        // Reference project (berita) uses base64url encoded rich text
        if ($request->input('_rich_text_encoded') !== 'base64url') {
            return false;
        }

        return $keys === ['isi']
            || (count($keys) === 3 && $keys[0] === 'translations' && $keys[2] === 'isi');
    }

    private function decode(string $value): string
    {
        $decoded = $value;

        for ($i = 0; $i < 2; $i++) {
            $next = html_entity_decode(rawurldecode($decoded), ENT_QUOTES | ENT_HTML5, 'UTF-8');

            if ($next === $decoded) {
                break;
            }

            $decoded = $next;
        }

        return str_replace("\0", '', $decoded);
    }
}
