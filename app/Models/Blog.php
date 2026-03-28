<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use App\Traits\HasMetaSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Blog extends Model implements HasMedia
{
    use HasHashid, HashidRouting, InteractsWithMedia, HasMetaSeo;
    
    protected $table = 'blogs';
    protected $primaryKey = 'id';
    protected $casts = ['active' => 'boolean'];
    protected $appends = ['hashid'];
    protected $fillable = [
        'user_id', 'title', 'slug', 'date', 'details', 'active'
    ];
    
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'blog_tags', 'blog_id', 'tag_id');
    }
    
    public function gallery(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Gallery::class, 'owner');
    }
    
    public function scopeActivated($query): void
    {
        $query->where('active', '=', true);
    }
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('blog-image')
            ->useDisk('media')
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
}
