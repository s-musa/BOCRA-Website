<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use Illuminate\Database\Eloquent\Model;

class SectionLink extends Model
{
    use HasHashid, HashidRouting;
    
    protected $table = 'section_links';
    protected $primaryKey = 'id';
    protected $appends = ['hashid'];
    protected $fillable = ['section_id', 'linkable_type', 'linkable_id'];
    
    public function section(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
    
    public function linkable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
