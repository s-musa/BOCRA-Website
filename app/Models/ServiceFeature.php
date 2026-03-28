<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use Illuminate\Database\Eloquent\Model;

class ServiceFeature extends Model
{
    use HasHashid, HashidRouting;
    
    protected $table = 'service_features';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $appends = ['hashid'];
    
    public function service(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }
}
