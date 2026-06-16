<?php

use Illuminate\Http\UploadedFile;

if (!function_exists('safeUploadFilename')) {
    /**
     * Build a safe, unguessable filename for an uploaded file.
     *
     * Never uses the client-supplied filename, which prevents path traversal
     * ("../"), double-extension tricks ("shell.php.jpg") and filename
     * collisions. The extension is derived from the file's actual content
     * (MIME) and reduced to a safe alphanumeric token.
     *
     * @param UploadedFile $file   The uploaded file
     * @param string       $prefix Optional human-readable prefix (e.g. "gallery_0")
     * @return string The generated filename, e.g. "gallery_0_1718200000_a1b2c3d4e5f6g7h8.jpg"
     */
    function safeUploadFilename(UploadedFile $file, string $prefix = ''): string
    {
        // Prefer the extension guessed from the file content over the client one.
        $extension = $file->extension() ?: $file->getClientOriginalExtension();
        $extension = preg_replace('/[^a-z0-9]/', '', strtolower((string) $extension)) ?: 'bin';

        $prefix = $prefix !== '' ? trim($prefix, '_') . '_' : '';

        return $prefix . time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
    }
}
