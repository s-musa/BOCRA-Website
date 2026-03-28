<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasHashid, HashidRouting;
    
    protected $table = 'faqs';
    protected $primaryKey = 'id';
    protected $casts = ['active' => 'boolean'];
    protected $appends = ['hashid'];
    protected $fillable = [
        'question', 'answer', 'active'
    ];
}
