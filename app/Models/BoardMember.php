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

class BoardMember extends Model implements HasMedia
{
    use HasHashid, HashidRouting, InteractsWithMedia, HasMetaSeo;
    
    protected $table = 'board_members';
    protected $primaryKey = 'id';
    protected $casts = ['active' => 'boolean'];
    protected $appends = ['hashid'];
    protected $fillable = [
        'name', 'position', 'details', 'active', 'order'
    ];
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('board-image')
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
