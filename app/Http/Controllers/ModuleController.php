<?php

namespace App\Http\Controllers;

use App\Http\Requests\EcommerceModuleRequest;
use App\Http\Requests\Restaurant\RestaurantRequest;
use App\Http\Resources\Resource;
use App\Models\Ecommerce\EcmAddress;
use App\Models\Ecommerce\EcmShop;
use App\Models\Module;
use App\Models\Restaurant\RstRestaurant;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ModuleController extends Controller
{
    public function dataTable()
    {
        $modules = QueryBuilder::for(
            Module::orderBy('id')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('installed'),
            AllowedFilter::exact('enabled'),
            AllowedFilter::partial('name'),
        ])->jsonPaginate();
        
        return Resource::collection($modules);
    }
    
    public function index()
    {
        return Inertia::render('Admin/Modules/Index', [
            'modules' => Module::all(),
            'totalInstalled' => Module::installedCount()
        ]);
    }
    
    public function install(Request $request)
    {
        $validated = $request->validate([
            'module_id' => ['required', 'exists:modules,id'],
        ]);
        
        $module = Module::findOrFail($validated['module_id']);
        
        if ($module->installed) {
            return back()->withErrors(['message' => 'Module is already installed.']);
        }
        
        try {
            Module::install($module);
            
            return back(303)->with('success', "{$module->name} installed successfully!");
            
        } catch (\Throwable $exception) {
            Log::error("Module installation failed: {$exception->getMessage()}");
            report($exception);
            $module->update(['installed' => false]);
            return back()->withErrors(['message' => 'Installation failed: ' . $exception->getMessage()]);
        }
    }
    
    public function enable(Request $request)
    {
        $validated = $request->validate([
            'module_id' => ['required', 'exists:modules,id'],
        ]);
        
        $module = Module::findOrFail($validated['module_id']);
        
        if ($module->enabled) {
            return back()->withErrors(['message' => 'Module is already enabled.']);
        }
        
        try {
            Module::enable($module);
            
            return back(303)->with('success', "{$module->name} enabled successfully!");
            
        } catch (\Throwable $exception) {
            Log::error("Failed to enable module: {$exception->getMessage()}");
            report($exception);
            $module->update(['enabled' => false]);
            return back()->withErrors(['message' => 'Failed to enable module: ' . $exception->getMessage()]);
        }
    }
    
    public function enable_ecommerce(EcommerceModuleRequest $request)
    {
        $validated = $request->validated();
        
        DB::beginTransaction();
        
        try {
            $module = Module::findOrFail($validated['module_id']);
            
            $shop = EcmShop::create([
                'name' => $validated['name'],
                'slug' => Str::of($validated['name'])->slug('-'),
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'website' => $validated['website'],
                'currency_id' => $validated['currency_id'],
            ]);
            
            if($request->hasFile('shop_logo')) {
                $shop->addMedia($validated['shop_logo'])
                    ->toMediaCollection('logo');
            }
            
            EcmAddress::create([
                'type' => EcmAddress::BUSINESS_ADDRESS,
                'addressable_id' => $shop->id,
                'addressable_type' => EcmShop::class,
                'first_name' => $validated['business_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'country_id' => $validated['country_id'],
                'line1' => $validated['line1'],
                'line2' => $validated['line2'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'postal_code' => $validated['postal_code'],
                'is_default' => true,
                'is_billing' => true,
                'is_shipping' => true,
            ]);
            
            $settings = [
                'enable_product_reviews' => $validated['enable_product_reviews'] ? 'YES' : 'NO',
                'enable_product_coupons' => $validated['enable_product_coupons'] ? 'YES' : 'NO',
                'enable_inventory_tracking' => $validated['enable_inventory_tracking'] ? 'YES' : 'NO',
                'enable_shipment_tracking' => $validated['enable_shipment_tracking'] ? 'YES' : 'NO',
            ];
            
            Setting::setSettings($settings, $module->id);
            
            $module->update([
                'enabled' => true,
            ]);
            
            $shop->checkAddonSettings($settings);
            
            DB::commit();
            return back(303)->with('success', "Module {$module->name} enabled successfully!");
            
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error('Error: ' . $exception->getMessage());
            report($exception);
            return redirect()->back()->withInput()->withErrors(['message' => $exception->getMessage()]);
        }
    }
    
    public function enable_restaurant(RestaurantRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);
        
        DB::beginTransaction();
        
        try {
            $module = Module::findOrFail($validated['module_id']);
            
            $restaurant = RstRestaurant::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'email_primary' => $validated['email_primary'],
                'email_secondary' => $validated['email_secondary'],
                'phone_primary' => $validated['phone_primary'],
                'phone_secondary' => $validated['phone_secondary'],
                'website' => $validated['website'],
                'address' => $validated['address'],
                'country' => $validated['country'],
                'state' => $validated['state'],
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'currency_id' => $validated['currency_id'],
                'status' => $validated['status'],
                'opening_time' => $validated['opening_time'],
                'closing_time' => $validated['closing_time'],
                'opening_days' => $validated['opening_days'],
                'cuisine_type' => $validated['cuisine_type'],
                'terms_and_conditions' => $validated['terms_and_conditions'],
                'cancellation_policy' => $validated['cancellation_policy'],
            ]);
            
            if($request->hasFile('restaurant_logo')) {
                $restaurant->addMedia($validated['restaurant_logo'])
                    ->toMediaCollection('restaurant-logo');
            }
            
            if (!empty($validated['amenity_ids'])) {
                $restaurant->amenities()->sync($validated['amenity_ids']);
            }
            
            $module->update([
                'enabled' => true,
            ]);
            
            DB::commit();
            return back(303)->with('success', "Module {$module->name} enabled successfully!");
            
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error("Restaurant creation failed: {$exception->getMessage()}");
            report($exception);
            return back()->withErrors(['message' => 'Restaurant creation failed: ' . $exception->getMessage()]);
        }
    }
}
