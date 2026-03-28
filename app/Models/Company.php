<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Company extends Model implements HasMedia
{
    use HasHashid, HashidRouting, InteractsWithMedia;
    
    protected $table = 'companies';
    protected $primaryKey = 'id';
    protected $appends = ['hashid'];
    protected $casts = ['has_footer_logo' => 'boolean'];
    protected $fillable = [
        'name', 'code', 'email', 'secondary_email', 'primary_phone', 'secondary_phone', 'primary_whatsapp', 'secondary_whatsapp',
        'country', 'state', 'city', 'website', 'physical_address', 'postal_address', 'tax_identification_pin', 'x_profile',
        'fb_profile', 'ig_profile', 'linkedin_profile', 'youtube_profile', 'tiktok_profile', 'pinterest_profile', 'has_footer_logo',
    ];
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
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
        
        $this->addMediaCollection('footer-logo')
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
        
        $this->addMediaCollection('favicon')
            ->useDisk('media')
            ->useFallbackPath(public_path('/dummy-image.jpg'))
            ->acceptsFile(function (File $file) {
                return in_array($file->mimeType, [
                    'image/jpg', 'image/jpeg', 'image/png',
                ]);
            })
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(291)
                    ->height(301);
            });
    }
    
    // Create a formatting function
    public static function phoneNumberFormatting($phone): void
    {
        // Pass phone number in preg_match function
        $format = '';
        if (preg_match(
            '/^\+[0-9]([0-9]{3})([0-9]{3})([0-9]{4})$/',
            $phone, $value)) {
            
            // Store value in format variable
            $format = $value[1] . '-' .
                $value[2] . '-' . $value[3];
        } else {
            
            // If given number is invalid
            echo "Invalid phone number <br>";
        }
        
        // Print the given format
        echo("$format" . "<br>");
    }
}
