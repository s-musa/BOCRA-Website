<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasHashid, HashidRouting;
    
    protected $table = 'product_types';
    protected $primaryKey = 'id';
    protected $casts = ['active' => 'boolean'];
    protected $appends = ['hashid'];
    protected $fillable = [
        'name', 'active'
    ];
    
    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }
}
