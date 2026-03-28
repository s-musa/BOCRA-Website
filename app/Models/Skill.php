<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasHashid, HashidRouting;
    
    protected $table = 'skills';
    protected $primaryKey = 'id';
    protected $casts = ['active' => 'boolean'];
    protected $appends = ['hashid'];
    protected $fillable = [
        'title', 'details', 'percentage', 'active'
    ];
}
