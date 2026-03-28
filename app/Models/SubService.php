<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SubService extends Model implements HasMedia
{
    use HasHashid, HashidRouting, InteractsWithMedia;
    
    protected $table = 'sub_services';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $appends = ['hashid'];
    protected $casts = ['has_image' => 'boolean'];
    
    public function service(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }
    
    public function features(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SubServiceFeature::class, 'sub_service_id', 'id');
    }
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('sub-service-image')
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
}
