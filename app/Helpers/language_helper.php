<?php

use App\Models\Bahasa;

if (!function_exists('get_bahasa')) {
    function get_bahasa()
    {
        try {
            return Bahasa::where('status', 'active')->orderBy('is_default', 'desc')->get();
        } catch (Throwable) {
            return collect();
        }
    }
}

if (!function_exists('current_bahasa')) {
    function current_bahasa()
    {
        static $cached = null;
        if ($cached) return $cached;

        try {
            $kode = session('locale', config('app.locale', 'id'));
            $cached = Bahasa::where('kode', $kode)->where('status', 'active')->first();
            if (!$cached) {
                $cached = Bahasa::where('is_default', 'yes')->where('status', 'active')->first();
            }
        } catch (Throwable) {
            $cached = null;
        }

        return $cached;
    }
}

if (!function_exists('default_bahasa')) {
    function default_bahasa()
    {
        static $cached = null;
        if ($cached) return $cached;
        try {
            $cached = Bahasa::where('is_default', 'yes')->where('status', 'active')->first();
        } catch (Throwable) {
            $cached = null;
        }

        return $cached;
    }
}

if (!function_exists('trans_content')) {
    function trans_content($model, string $field, ?string $locale = null)
    {
        if (!$model) return '';

        $locale = $locale ?? session('locale', config('app.locale', 'id'));
        $defaultBahasa = default_bahasa();

        if ($locale === optional($defaultBahasa)->kode) {
            $original = $model->{$field} ?? '';
            if (!empty($original)) return $original;
        }

        if (!$model->relationLoaded('translations')) {
            $model->load('translations');
        }

        $bahasa = current_bahasa();
        if (!$bahasa || $bahasa->kode !== $locale) {
            $bahasa = Bahasa::where('kode', $locale)->where('status', 'active')->first();
        }
        if (!$bahasa) {
            $bahasa = $defaultBahasa;
        }

        $translation = $model->translations->firstWhere('bahasa_id', $bahasa?->id);
        $transValue = $translation?->{$field};

        if (empty($transValue) && $defaultBahasa && $defaultBahasa->id !== $bahasa?->id) {
            $translation = $model->translations->firstWhere('bahasa_id', $defaultBahasa->id);
            $transValue = $translation?->{$field};
        }

        return $transValue ?? $model->{$field} ?? '';
    }
}

if (!function_exists('public_image_url')) {
    function public_image_url(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        $path = trim($path);

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            $parts = parse_url($path);
            $host = $parts['host'] ?? '';

            if (in_array($host, ['localhost', '127.0.0.1'], true) && !empty($parts['path'])) {
                return $parts['path'] . (isset($parts['query']) ? '?' . $parts['query'] : '');
            }

            return $path;
        }

        if (str_starts_with($path, '/storage/') || str_starts_with($path, '/uploads/')) {
            return $path;
        }

        if (str_starts_with($path, 'storage/')) {
            return '/' . $path;
        }

        return \Illuminate\Support\Facades\Storage::url(ltrim($path, '/'));
    }
}

if (!function_exists('safe_url')) {
    function safe_url(?string $url, ?string $fallback = '#'): ?string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return $fallback;
        }

        $decoded = html_entity_decode($url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $normalized = strtolower(preg_replace('/[\x00-\x20]+/', '', $decoded));

        if (
            str_starts_with($normalized, 'javascript:')
            || str_starts_with($normalized, 'data:')
            || str_starts_with($normalized, 'vbscript:')
        ) {
            return $fallback;
        }

        $parts = parse_url($url);
        $scheme = isset($parts['scheme']) ? strtolower($parts['scheme']) : null;

        if ($scheme && !in_array($scheme, ['http', 'https', 'mailto', 'tel'], true)) {
            return $fallback;
        }

        return $url;
    }
}

if (!function_exists('safe_html')) {
    function safe_html(?string $html): string
    {
        $html = (string) $html;

        if ($html === '') {
            return '';
        }

        $allowedTags = [
            'a', 'abbr', 'blockquote', 'br', 'caption', 'code', 'col', 'colgroup', 'div',
            'em', 'figcaption', 'figure', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'hr', 'i',
            'img', 'li', 'ol', 'p', 'pre', 'span', 'strong', 'sub', 'sup', 'table',
            'tbody', 'td', 'tfoot', 'th', 'thead', 'tr', 'u', 'ul',
        ];

        $allowedAttributes = [
            '*' => ['class'],
            'a' => ['href', 'title', 'target', 'rel'],
            'img' => ['src', 'alt', 'title', 'width', 'height'],
            'td' => ['colspan', 'rowspan'],
            'th' => ['colspan', 'rowspan', 'scope'],
        ];

        $html = preg_replace('/<(script|style)\b[^>]*>.*?<\/\1>/is', '', $html) ?? '';

        $document = new DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body><div id="safe-html-root">'.$html.'</div></body></html>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $document->getElementById('safe-html-root');

        if (! $root) {
            return e(strip_tags($html));
        }

        $sanitizeNode = function (DOMNode $node) use (&$sanitizeNode, $allowedTags, $allowedAttributes): void {
            if ($node instanceof DOMElement) {
                $tagName = strtolower($node->tagName);

                if (!in_array($tagName, $allowedTags, true)) {
                    $parent = $node->parentNode;

                    if ($parent) {
                        while ($node->firstChild) {
                            $parent->insertBefore($node->firstChild, $node);
                        }

                        $parent->removeChild($node);
                    }

                    return;
                }

                $allowedForTag = array_merge($allowedAttributes['*'] ?? [], $allowedAttributes[$tagName] ?? []);

                for ($i = $node->attributes->length - 1; $i >= 0; $i--) {
                    $attribute = $node->attributes->item($i);
                    $attributeName = strtolower($attribute->name);

                    if (str_starts_with($attributeName, 'on') || !in_array($attributeName, $allowedForTag, true)) {
                        $node->removeAttributeNode($attribute);
                        continue;
                    }

                    if (in_array($attributeName, ['href', 'src'], true)) {
                        $cleanUrl = safe_url($attribute->value, '');

                        if ($cleanUrl === '') {
                            $node->removeAttributeNode($attribute);
                        } else {
                            $node->setAttribute($attributeName, $cleanUrl);
                        }
                    }
                }

                if ($tagName === 'a') {
                    $target = $node->getAttribute('target');

                    if ($target !== '' && !in_array($target, ['_blank', '_self', '_parent', '_top'], true)) {
                        $node->removeAttribute('target');
                    }

                    if ($node->getAttribute('target') === '_blank') {
                        $node->setAttribute('rel', trim($node->getAttribute('rel').' noopener noreferrer'));
                    }
                }
            }

            foreach (iterator_to_array($node->childNodes) as $child) {
                $sanitizeNode($child);
            }
        };

        $sanitizeNode($root);

        $clean = '';
        foreach ($root->childNodes as $child) {
            $clean .= $document->saveHTML($child);
        }

        return $clean;
    }
}

if (!function_exists('safe_store_uploaded_image')) {
    function safe_store_uploaded_image(\Illuminate\Http\UploadedFile $file, string $folder, int $maxKilobytes = 5120): string
    {
        if (! $file->isValid()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => 'File upload tidak valid.',
            ]);
        }

        if ($file->getSize() > $maxKilobytes * 1024) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => 'Ukuran gambar maksimal '.round($maxKilobytes / 1024, 1).' MB.',
            ]);
        }

        $mime = strtolower((string) $file->getMimeType());
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];

        if (!isset($extensions[$mime]) || @getimagesize($file->getPathname()) === false) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => 'File harus berupa gambar JPG, PNG, GIF, atau WebP yang valid.',
            ]);
        }

        $folder = trim($folder, '/');
        $filename = \Illuminate\Support\Str::random(40).'.'.$extensions[$mime];

        $path = $file->storeAs($folder, $filename, 'public');
        mirror_public_storage_file($path);

        return $path;
    }
}

if (!function_exists('mirror_public_storage_file')) {
    function mirror_public_storage_file(?string $path): void
    {
        $path = ltrim((string) $path, '/');

        if ($path === '') {
            return;
        }

        $publicStoragePath = public_path('storage');
        $storageTargetPath = storage_path('app/public');

        if (! is_dir($publicStoragePath) || realpath($publicStoragePath) === realpath($storageTargetPath)) {
            return;
        }

        $source = $storageTargetPath.DIRECTORY_SEPARATOR.$path;
        $target = $publicStoragePath.DIRECTORY_SEPARATOR.$path;

        if (! is_file($source)) {
            return;
        }

        $targetDirectory = dirname($target);
        if (! is_dir($targetDirectory)) {
            @mkdir($targetDirectory, 0755, true);
        }

        @copy($source, $target);
    }
}

if (!function_exists('safe_store_uploaded_document')) {
    function safe_store_uploaded_document(\Illuminate\Http\UploadedFile $file, string $folder, array $allowedMimes, int $maxKilobytes = 5120): string
    {
        if (! $file->isValid()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => 'File upload tidak valid.',
            ]);
        }

        if ($file->getSize() > $maxKilobytes * 1024) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => 'Ukuran file maksimal '.round($maxKilobytes / 1024, 1).' MB.',
            ]);
        }

        $mime = strtolower((string) $file->getMimeType());
        $allowedMimes = array_change_key_case($allowedMimes, CASE_LOWER);

        if (!isset($allowedMimes[$mime])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => 'Tipe file tidak diizinkan.',
            ]);
        }

        $folder = trim($folder, '/');
        $filename = \Illuminate\Support\Str::random(40).'.'.$allowedMimes[$mime];

        $path = $file->storeAs($folder, $filename, 'public');
        mirror_public_storage_file($path);

        return $path;
    }
}
