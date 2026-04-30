<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PressRelease extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'video_url',
        'description',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    /**
     * Get the YouTube video ID from the URL.
     */
    public function getYoutubeIdAttribute()
    {
        // Support for:
        // - youtube.com/watch?v=...
        // - youtu.be/...
        // - youtube.com/embed/...
        // - youtube.com/live/...
        // - youtube.com/v/...
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|live)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $this->video_url, $match);
        return $match[1] ?? null;
    }

    /**
     * Get the YouTube thumbnail URL.
     */
    public function getThumbnailUrlAttribute()
    {
        $id = $this->youtube_id;
        return $id ? "https://img.youtube.com/vi/{$id}/mqdefault.jpg" : null;
    }

    /**
     * Get the embed URL.
     */
    public function getEmbedUrlAttribute()
    {
        $id = $this->youtube_id;
        return $id ? "https://www.youtube.com/embed/{$id}?autoplay=1&mute=0" : null;
    }
}
