<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SectionCard extends Model implements HasMedia
{
    use HasHashid, HashidRouting, InteractsWithMedia;
    
    protected $table = 'section_cards';
    protected $primaryKey = 'id';
    protected $appends = ['hashid'];
    protected $casts = ['has_link' => 'boolean'];
    protected $fillable = [
        'section_id', 'title', 'icon', 'details', 'has_link', 'card_link_type', 'custom_url', 'section_url', 'page_id'
    ];
    
    public function section(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }
    
    public function page(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id', 'id');
    }
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('card-image')
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
