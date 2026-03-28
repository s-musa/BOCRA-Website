<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasHashid, HashidRouting;
    
    protected $table = 'tags';
    protected $primaryKey = 'id';
    protected $casts = ['active' => 'boolean'];
    protected $appends = ['hashid'];
    protected $fillable = [
        'name', 'active'
    ];
    
    public function projects(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }
    
    public function scopeActivated($query): void
    {
        $query->where('active', '=', true);
    }
}
