<?php

namespace App\Models;

use App\Helpers\MoneyHelper;
use App\Traits\HasHashid;
use App\Traits\HashidRouting;
use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    use HasHashid, HashidRouting;
    
    protected $table = 'pricing_plans';
    protected $primaryKey = 'id';
    protected $casts = [
        'featured' => 'boolean',
        'active' => 'boolean'
    ];
    protected $appends = ['hashid'];
    protected $guarded = ['id'];
    
    public function section(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }
    
    public function page(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id', 'id');
    }
    
    public function features(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PricingPlanFeature::class, 'pricing_plan_id', 'id');
    }
    
    public static function createNewPricingPlan(array $validated = []): PricingPlan
    {
        $pricingPlan = self::create([
            'section_id' => $validated['section_id'],
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'tagline' => $validated['tagline'] ?? null,
            'description' => $validated['description'] ?? null,
            'price' => MoneyHelper::parseToMoney($validated['price'])->getAmount(),
            'billing_period' => $validated['billing_period'],
            'billing_period_label' => $validated['billing_period_label'] ?? null,
            'featured' => $validated['featured'] ?? false,
            'active' => $validated['active'] ?? true,
            'order' => $validated['order'] ?? 0,
            'button_text' => $validated['button_text'] ?? 'Get Started',
            'button_type' => $validated['button_type'] ?? 'page',
            'button_url' => $validated['button_url'] ?? null,
            'section_url' => $validated['section_url'] ?? null,
            'page_id' => $validated['page_id'] ?? null,
        ]);
        
        if (isset($validated['features']) && is_array($validated['features'])) {
            foreach ($validated['features'] as $feature) {
                $pricingPlan->features()->create([
                    'name' => $feature['name'],
                    'is_included' => $feature['is_included'] ?? true,
                    'order' => $feature['order'] ?? null,
                ]);
            }
        }
        
        return $pricingPlan;
    }
    
    public static function updatePricingPlan(PricingPlan $pricingPlan, $validated = [])
    {
        $pricingPlan->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'tagline' => $validated['tagline'] ?? null,
            'description' => $validated['description'] ?? null,
            'price' => MoneyHelper::parseToMoney($validated['price'])->getAmount(),
            'currency' => $validated['currency'] ?? 'USD',
            'billing_period' => $validated['billing_period'],
            'billing_period_label' => $validated['billing_period_label'] ?? null,
            'featured' => $validated['featured'] ?? false,
            'active' => $validated['active'] ?? true,
            'order' => $validated['order'] ?? 0,
            'button_text' => $validated['button_text'] ?? 'Get Started',
            'button_type' => $validated['button_type'] ?? 'page',
            'button_url' => $validated['button_url'] ?? null,
            'section_url' => $validated['section_url'] ?? null,
            'page_id' => $validated['page_id'] ?? null,
        ]);
        
        $existingFeatureIds = [];
        
        if (isset($validated['features']) && is_array($validated['features'])) {
            
            foreach ($validated['features'] as $feature) {
                if (!empty($feature['id'])) {
                    $pricingPlan->features()->where('id', $feature['id'])->update([
                        'name' => $feature['name'],
                        'is_included' => $feature['is_included'] ?? true,
                        'icon' => $feature['icon'] ?? null,
                        'order' => $feature['order'] ?? 0,
                    ]);
                    $existingFeatureIds[] = $feature['id'];
                } else {
                    $newFeature = $pricingPlan->features()->create([
                        'name' => $feature['name'],
                        'is_included' => $feature['is_included'] ?? true,
                        'order' => $feature['order'] ?? 0,
                    ]);
                    $existingFeatureIds[] = $newFeature->id;
                }
            }
        }
        
        $pricingPlan->features()->whereNotIn('id', $existingFeatureIds)->delete();
        
        return $pricingPlan;
    }
}
