<?php

namespace App\Services\Game\Help\Normalizers;

class SlugNormalizer
{
    public function normalize(string $name): string
    {
        // Trim whitespace
        $slug = trim($name);

        // Convert to lowercase
        $slug = mb_strtolower($slug);

        // Try replacing whitespace with a dash
        $slug = preg_replace('/\s+/u', '-', $slug) ?? $slug;

        // Try removing characters other than letters, numbers, and marks.
        $slug = preg_replace('/[^\p{L}\p{Nd}\p{Nl}\p{M}-]+/u', '', $slug) ?? $slug;

        // Trim to default string length
        return mb_substr($slug, 0, 255);
    }
}
