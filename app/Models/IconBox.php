<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use Illuminate\Database\Eloquent\Model;

class IconBox extends Model
{
    use HasHashid, HashidRouting;
    
    protected $table = 'icon_boxes';
    protected $primaryKey = 'id';
    protected $appends = ['hashid'];
    protected $casts = ['has_link' => 'boolean'];
    protected $fillable = [
        'section_id', 'title', 'icon', 'details', 'has_link', 'icon_link_type', 'custom_url', 'section_url', 'page_id'
    ];
    
    public function section(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }
    
    public function page(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id', 'id');
    }
}
