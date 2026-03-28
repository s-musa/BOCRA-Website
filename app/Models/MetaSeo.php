<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Database\Eloquent\Model;

class MetaSeo extends Model
{
    use HasHashid, HashidRouting;
    
    protected $table = 'meta_seos';
    protected $primaryKey = 'id';
    protected $appends = ['hashid'];
    protected $fillable = [
        'seo_able_type', 'seo_able_id', 'title', 'description', 'keywords'
    ];
    
    public function meta(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
    
    public static function applyMeta($seo): void
    {
        $currentUrl = url()->current();
        
        /** Meta SEO */
        SEOMeta::setTitle($seo->title);
        SEOMeta::setDescription($seo->description);
        SEOMeta::setKeywords($seo->keywords);
        SEOMeta::setCanonical($currentUrl);
        
        OpenGraph::setTitle($seo->title);
        OpenGraph::setDescription($seo->description);
        OpenGraph::setUrl($currentUrl);
        OpenGraph::addProperty('type', 'WebPage');
        OpenGraph::addProperty('locale', 'en-us');
        
        JsonLd::setTitle($seo->title);
        JsonLd::setDescription($seo->description);
        JsonLd::setType('WebPage');
    }
}
