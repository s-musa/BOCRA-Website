<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use App\Traits\HasMetaSeo;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Service extends Model implements HasMedia
{
    use HasHashid, HashidRouting, HasMetaSeo, InteractsWithMedia;
    
    protected $table = 'services';
    protected $primaryKey = 'id';
    protected $casts = ['active' => 'boolean'];
    protected $appends = ['hashid'];
    protected $fillable = [
        'title', 'slug', 'details', 'short_description', 'order', 'active'
    ];
    
    public function gallery(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Gallery::class, 'owner');
    }
    
    public function features(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ServiceFeature::class, 'service_id', 'id');
    }
    
    public function sub_services(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SubService::class, 'service_id', 'id');
    }
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('service-image')
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
        
        $this->addMediaCollection('service-gallery')
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
        static::deleting(function ($service) {
            $service->features->each->delete();
            $service->sub_services->each(function ($sub) {
                $sub->features()->delete();
                $sub->delete();
            });
            
            $service->clearMediaCollection('service-image');
            $service->clearMediaCollection('service-gallery');
        });
    }
}
