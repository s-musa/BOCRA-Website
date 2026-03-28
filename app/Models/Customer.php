<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable, HasHashid, HashidRouting;
    
    protected string $guard = 'customer';
    
    protected $table = 'customers';
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'currency_id'
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
    
    protected $appends = ['hashid'];
    
    public function currency(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
    
    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Ecommerce\EcmOrder::class, 'customer_id');
    }
    
    public function addresses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Ecommerce\EcmAddress::class, 'customer_id');
    }
}
