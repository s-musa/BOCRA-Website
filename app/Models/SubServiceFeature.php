<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use Illuminate\Database\Eloquent\Model;

class SubServiceFeature extends Model
{
    use HasHashid, HashidRouting;
    
    protected $table = 'sub_service_features';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $appends = ['hashid'];
    
    public function sub_service(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SubService::class, 'sub_service_id', 'id');
    }
}
