<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use App\Traits\HasMetaSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Artisan;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Page extends Model implements HasMedia
{
    use SoftDeletes, HasHashid, HashidRouting, HasMetaSeo, InteractsWithMedia;
    
    protected $table = 'pages';
    protected $primaryKey = 'id';
    protected $casts = ['published' => 'boolean'];
    protected $appends = ['hashid', 'background_color'];
    protected $fillable = [
        'title', 'slug', 'description', 'parent_id', 'page_type_id', 'banner_style', 'banner_background_type',
        'banner_background_color', 'published'
    ];
    
    public function sections(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Section::class, 'page_id', 'id');
    }
    
    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PageType::class, 'page_type_id', 'id');
    }
    
    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id', 'id');
    }
    
    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Page::class, 'parent_id', 'id');
    }
    
    public function getBackgroundColorAttribute()
    {
        $bannerBackgroundColor = $this->attributes['banner_background_color'];
        
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
    
    protected static function booted(): void
    {
        static::saved(function () {
            Artisan::call('app:generate-sitemap');
        });
        
        static::updated(function () {
            Artisan::call('app:generate-sitemap');
        });
        
        static::deleted(function () {
            Artisan::call('app:generate-sitemap');
        });
    }
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('page-image')
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
