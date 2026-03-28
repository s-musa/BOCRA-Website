<?php

namespace App\Models;

use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use Database\Seeders\CountrySeeder;
use Database\Seeders\CurrencySeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Module extends Model
{
    use HasHashid, HashidRouting;
    
    protected $table = 'modules';
    protected $primaryKey = 'id';
    protected $casts = [
        'installed' => 'boolean',
        'enabled' => 'boolean',
    ];
    protected $appends = ['hashid'];
    protected $guarded = ['id'];
    
    public function dependencies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            Module::class,
            'module_dependencies',
            'module_id',
            'depends_on_module_id'
        );
    }
    
    public function scopeInstalled($query): void
    {
        $query->where('installed', '=', true);
    }
    
    public function scopeEnabled($query): void
    {
        $query->where('enabled', '=', true);
    }
    
    public function scopeInstalledAndEnabled($query): void
    {
        $query->where('installed', '=', true)->where('enabled', '=', true);
    }
    
    public static function installedCount(): int
    {
        return self::where('installed', true)->count();
    }
    
    public static function install($module): void
    {
        if($module->slug === 'ecommerce') {
            self::runEcommerceRelatedMigrations();
        }
        if($module->slug === 'eproperty') {
            self::runEpropertyRelatedMigrations();
        }
        
        if($module->slug === 'restaurant') {
            self::runRestaurantRelatedMigrations();
        }
        
        $migrationFile = ucfirst($module->slug);
        Artisan::call('migrate', [
            '--path' => "database/migrations/{$migrationFile}",
            '--force' => true,
        ]);
        
        $seeders = [
            'ecommerce' => \Database\Seeders\EcommerceSeeder::class,
            'eproperty' => \Database\Seeders\EpropertySeeder::class,
            'restaurant' => \Database\Seeders\RestaurantSeeder::class,
        ];
        $seederClass = $seeders[$module->slug] ?? null;
        
        if ($seederClass && class_exists($seederClass)) {
            Artisan::call('db:seed', [
                '--class' => $seederClass,
                '--force' => true,
            ]);
        }
        
        $module->update(['installed' => true]);
        
        if($module->slug === 'ecommerce' && $module->installed) {
            self::registerDefaultEcommerceSettings($module);
        }
        
        if($module->slug === 'eproperty' && $module->installed) {
            $module->update(['enabled' => true]);
            self::registerDefaultEpropertySettings($module);
        }
        
        if($module->slug === 'restaurant' && $module->installed) {
            // $module->update(['enabled' => true]);
            self::registerDefaultRestaurantSettings($module);
        }
    }
    
    public static function enable($module)
    {
        $module->update(['enabled' => true]);
    }
    
    public static function runEcommerceRelatedMigrations(): void
    {
        if (!Schema::hasTable('currencies')) {
            Artisan::call('migrate', [
                '--path' => "database/migrations/Currency",
                '--force' => true,
            ]);
        }
        
        if (DB::table('currencies')->count() === 0) {
            Artisan::call('db:seed', [
                '--class' => \Database\Seeders\CurrencySeeder::class,
                '--force' => true,
            ]);
        }
        
        if (!Schema::hasTable('countries')) {
            Artisan::call('migrate', [
                '--path' => "database/migrations/Country",
                '--force' => true,
            ]);
        }
        
        if (DB::table('countries')->count() === 0) {
            Artisan::call('db:seed', [
                '--class' => \Database\Seeders\CountrySeeder::class,
                '--force' => true,
            ]);
        }
        
        if (!Schema::hasTable('customers')) {
            Artisan::call('migrate', [
                '--path' => "database/migrations/Customer",
                '--force' => true,
            ]);
        }
    }
    
    public static function registerDefaultEcommerceSettings($module): void
    {
        $settings = [
            'ecommerce_module_installed' => 'YES',
            'enable_product_reviews' => 'NO',
            'enable_inventory_tracking' => 'NO',
            'enable_product_coupons' => 'NO',
            'enable_wishlist' => 'NO',
            'enable_shipment_tracking' => 'NO',
        ];
        
        Setting::setSettings($settings, $module->id);
    }
    
    public static function runEpropertyRelatedMigrations(): void
    {
        if (!Schema::hasTable('currencies')) {
            Artisan::call('migrate', [
                '--path' => "database/migrations/Currency",
                '--force' => true,
            ]);
        }
        
        if (DB::table('currencies')->count() === 0) {
            Artisan::call('db:seed', [
                '--class' => \Database\Seeders\CurrencySeeder::class,
                '--force' => true,
            ]);
        }
    }
    
    public static function registerDefaultEpropertySettings($module): void
    {
        $settings = [
            'eproperty_module_installed' => 'YES',
        ];
        
        Setting::setSettings($settings, $module->id);
    }
    
    public static function runRestaurantRelatedMigrations(): void
    {
        if (!Schema::hasTable('currencies')) {
            Artisan::call('migrate', [
                '--path' => "database/migrations/Currency",
                '--force' => true,
            ]);
        }
        
        if (DB::table('currencies')->count() === 0) {
            Artisan::call('db:seed', [
                '--class' => \Database\Seeders\CurrencySeeder::class,
                '--force' => true,
            ]);
        }
        
        if (!Schema::hasTable('customers')) {
            Artisan::call('migrate', [
                '--path' => "database/migrations/Customer",
                '--force' => true,
            ]);
        }
    }
    
    public static function registerDefaultRestaurantSettings($module): void
    {
        $settings = [
            'restaurant_module_installed' => 'YES',
        ];
        
        Setting::setSettings($settings, $module->id);
    }
}
