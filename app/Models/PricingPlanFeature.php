<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use Illuminate\Database\Eloquent\Model;

class PricingPlanFeature extends Model
{
    use HasHashid, HashidRouting;
    
    protected $table = 'pricing_plan_features';
    protected $primaryKey = 'id';
    protected $casts = ['is_included' => 'boolean'];
    protected $appends = ['hashid'];
    protected $fillable = [
        'name', 'is_included'
    ];
    
    public function plans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PricingPlan::class);
    }
}
