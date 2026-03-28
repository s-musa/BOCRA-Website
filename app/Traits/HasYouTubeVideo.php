<?php

namespace App\Traits;

trait HasYouTubeVideo
{
    public function getYoutubeVideoIdAttribute(): ?string
    {
        $url = $this->video_link ?? null;
        
        if (!$url) {
            return null;
        }
        
        $patterns = [
            '/youtu\.be\/([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }
    
}
