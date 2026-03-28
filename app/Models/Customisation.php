<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Customisation extends Model implements HasMedia
{
    use HasHashid, HashidRouting, InteractsWithMedia;
    
    protected $table = 'customisations';
    protected $primaryKey = 'id';
    protected $appends = ['hashid', 'background_color'];
    protected $casts = [
        'top_header' => 'boolean',
        'footer_has_background' => 'boolean',
    ];
    protected $fillable = [
        'primary_color', 'primary_color_rgb', 'primary_color_light', 'primary_color_light_rgb',
        'secondary_color', 'secondary_color_rgb', 'secondary_color_light', 'secondary_color_light_rgb',
        'button_style', 'header_style', 'header_bg', 'top_header', 'top_header_bg', 'footer_style', 'footer_has_background',
        'footer_bg_type', 'footer_bg_color', 'footer_text', 'banner_style', 'banner_bg', 'section_width_style', 'section_width',
    ];
    
    public function getBackgroundColorAttribute()
    {
        $bannerBackgroundColor = $this->attributes['banner_bg'];
        
        if(!$bannerBackgroundColor) {
            return null;
        }
        
        $parts = explode('-', $bannerBackgroundColor);
        
        // If only two parts (bg-light, bg-dark), return as is
        if (count($parts) <= 2) {
            return $bannerBackgroundColor;
        }
        
        return $parts[0] . '-' . $parts[1];
    }
    
    public static function hexToRgb(string $hex): string
    {
        $hex = ltrim($hex, '#');
        
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] .
                $hex[1] . $hex[1] .
                $hex[2] . $hex[2];
        }
        
        $int = hexdec($hex);
        // Return just the numeric values for CSS vars
        return sprintf("%d, %d, %d", ($int >> 16) & 255, ($int >> 8) & 255, $int & 255);
    }
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('banner-image')
            ->useDisk('media')
            ->useFallbackPath(public_path('/dummy-image.jpg'))
            ->acceptsFile(function (File $file) {
                return in_array($file->mimeType, [
                    'image/jpg', 'image/jpeg', 'image/png',
                ]);
            })
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(831)
                    ->height(301);
            });
    }
}
