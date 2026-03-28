<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class HeroSlide extends Model implements HasMedia
{
    use HasHashid, HashidRouting, InteractsWithMedia;
    
    protected $table = 'hero_slides';
    protected $primaryKey = 'id';
    protected $appends = ['hashid'];
    protected $fillable = [
        'section_id', 'page_id', 'title', 'sub_title', 'details', 'cta_button_text', 'cta_button_type'
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
        $this->addMediaCollection('slide_image')
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
