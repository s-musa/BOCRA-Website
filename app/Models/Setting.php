<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasHashid, HashidRouting;
    
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $appends = ['hashid'];
    protected $guarded = ['id'];
    
    public function module(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }
    
    public static function setSettings($settings, $module_id): void
    {
        foreach ($settings as $key => $value) {
            self::updateOrCreate(
                [
                    'module_id' => $module_id,
                    'key' => $key,
                ], [
                    'value' => $value,
                ]
            );
        }
    }
    
    public static function getSettings()
    {
        return static::all();
    }
}
