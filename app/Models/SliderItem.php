<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // Penting: Import Storage Facade

class SliderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'path', // Untuk file lokal (gambar/video)
        'external_url', // Untuk URL eksternal
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * The accessors to append to the model's array form.
     * This makes `file_url` and `embed_url` automatically available when
     * the model is converted to JSON or an array.
     * ini penting!
     *
     * @var array
     */
    protected $appends = ['file_url', 'embed_url'];

    /**
     * Get the publicly accessible URL for local files (image/video).
     * Returns a placeholder if the file does not exist.
     */
    public function getFileUrlAttribute()
    {
        // Hanya untuk tipe image dan video yang disimpan lokal
        if ($this->type === 'image' || $this->type === 'video') {
            if ($this->path && Storage::disk('public')->exists($this->path)) {
                return Storage::url($this->path);
            } else {
                // Placeholder jika file tidak ditemukan atau path null
                return $this->type === 'image'
                    ? 'https://via.placeholder.com/800x400.png?text=Image+Not+Found'
                    : 'data:video/mp4;base64,AAAAHGZ0eXBtcDQyAAAAAG1wNDRpc29t'; // Minimal base64 for video placeholder
            }
        }
        return null; // Untuk tipe 'external', file_url tidak relevan
    }

    /**
     * Get the embed URL for external media (YouTube, Instagram, etc.).
     * This is intended for displaying content within an iframe.
     */
    public function getEmbedUrlAttribute()
    {
        if ($this->type !== 'external' || !$this->external_url) {
            return null;
        }

        // Handle YouTube URLs
        if (str_contains($this->external_url, 'youtube.com') || str_contains($this->external_url, 'youtu.be')) {
            $videoId = $this->extractYouTubeId($this->external_url);
            // Gunakan parameter yang masih valid untuk YouTube embed
            return $videoId ? "https://www.youtube.com/embed/{$videoId}?rel=0&modestbranding=1&playsinline=1&iv_load_policy=3&fs=0&disablekb=1" : null;
        }

        // Handle Instagram URLs (simplified, might need more robust solution for all cases)
        if (str_contains($this->external_url, 'instagram.com/p/')) {
            // Instagram embed URL might be more complex, this is a basic attempt
            // Often requires /embed/ or oEmbed.
            // For robust solution, consider a dedicated package or instructing users to provide embed code.
            return str_replace('/p/', '/embed/', $this->external_url); // This might need further '/embed/' suffix or more complex logic
        }

        // Fallback: return the original external URL if no specific embed logic matches
        return $this->external_url;
    }

    /**
     * Extract YouTube video ID from various URL formats.
     */
    private function extractYouTubeId($url): ?string
    {
        $patterns = [
            '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }
}