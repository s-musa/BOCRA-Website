<?php

namespace App\Services;

use App\Models\Eproperty\Property;
use App\Models\IconBox;
use App\Models\Service;
use App\Models\Project;
use App\Models\Blog;
use App\Models\Event;
use App\Models\Product;
use App\Models\BoardMember;
use App\Models\Partner;
use App\Models\CompanyClient;
use App\Models\PricingPlan;
use App\Models\Faq;
use App\Models\Skill;
use App\Models\Ecommerce\EcmProduct;
use App\Models\Ecommerce\EcmCategory;
use Illuminate\Support\Facades\Cache;

class SectionResourceLoader
{
    /**
     * Get resources needed for a specific section type
     */
    public function getResourcesForSection(string $sectionType, $component = null, $linkable = null): array
    {
        return match ($sectionType) {
            'section-with-services' => $this->getServices(),
            'section-with-projects' => $this->getProjects(),
            'section-with-blogs' => $this->getBlogs(),
            'section-with-events' => $this->getEvents(),
            'section-with-products' => $this->getProducts(),
            'section-with-board-members' => $this->getBoardMembers(),
            'section-with-partners' => $this->getPartners(),
            'section-with-clients' => $this->getClients(),
            'section-with-pricing-plans' => $this->getPricingPlans(),
            'section-with-icon-box' => $this->getIconBoxes(),
            'section-with-faqs' => $this->getFaqs(),
            'section-with-component' => $this->getComponentItems($component),
            default => [],
        };
    }
    
    /**
     * Get resources for multiple sections at once (optimized)
     */
    public function getResourcesForSections($sections): array
    {
        $resources = [];
        
        foreach ($sections as $section) {
            $linkable = $section->section_link?->linkable;
            $component = $section->component_type;
            
            // Use section ID as key to handle duplicate types
            $resources[$section->id] = $this->getResourcesForSection(
                $section->type,
                $component,
                $linkable
            );
        }
        
        return $resources;
    }
    
    protected function getComponentItems($component): array
    {
        $availableComponents = [
            'services' => $this->getServices(),
            'projects' => $this->getProjects(),
            'blogs' => $this->getBlogs(),
            'events' => $this->getEvents(),
            'products' => $this->getProducts(),
            'board-members' => $this->getBoardMembers(),
            'faqs' => $this->getFaqs(),
            'default' => [],
        ];
        
        return match ($component) {
            $component => $availableComponents[$component] ?? [],
        };
    }
    
    protected function getServices(): array
    {
        return Cache::remember('section_services', 3600, function () {
            return [
                'services' => Service::with('media')
                    ->where('active', '=', true)
                    ->orderByDesc('created_at')
                    ->get()
            ];
        });
    }
    
    protected function getProjects(): array
    {
        return Cache::remember('section_projects', 3600, function () {
            return [
                'projects' => Project::with('media', 'category', 'tags')
                    ->where('active', '=', true)
                    ->orderByDesc('created_at')
                    ->get()
            ];
        });
    }
    
    protected function getBlogs(): array
    {
        return Cache::remember('section_blogs', 3600, function () {
            return [
                'blogs' => Blog::with('media', 'user', 'tags')
                    ->where('active', '=', true)
                    ->orderByDesc('created_at')
                    ->get()
            ];
        });
    }
    
    protected function getEvents(): array
    {
        return Cache::remember('section_events', 3600, function () {
            $events = Event::with('user', 'category', 'tags', 'media')
                ->where('active', '=', true)
                ->orderByDesc('created_at')
                ->get();
            
            $groupedEvents = $events->groupBy(function ($event) {
                return $event->category->name ?? 'Uncategorized';
            });
            
            return [
                'events' => $events,
                'groupedEvents' => $groupedEvents
            ];
        });
    }
    
    protected function getProducts(): array
    {
        return Cache::remember('section_products', 3600, function () {
            $products = Product::with(['type', 'media'])
                ->where('active', '=', true)
                ->orderBy('name')
                ->get();
            
            $groupedProducts = $products->groupBy(function ($product) {
                return $product->type->name ?? 'Uncategorized';
            });
            
            return [
                'products' => $products,
                'groupedProducts' => $groupedProducts
            ];
        });
    }
    
    protected function getBoardMembers(): array
    {
        return Cache::remember('section_board_members', 3600, function () {
            return [
                'board_members' => BoardMember::with('media')
                    ->where('active', '=', true)
                    ->orderBy('order')
                    ->orderBy('created_at')
                    ->get()
            ];
        });
    }
    
    protected function getPartners(): array
    {
        return Cache::remember('section_partners', 3600, function () {
            return [
                'partners' => Partner::with('media')
                    ->where('active', '=', true)
                    ->orderBy('created_at')
                    ->get()
            ];
        });
    }
    
    protected function getClients(): array
    {
        return Cache::remember('section_clients', 3600, function () {
            return [
                'clients' => CompanyClient::with('media')
                    ->where('active', '=', true)
                    ->orderBy('created_at')
                    ->get()
            ];
        });
    }
    
    protected function getPricingPlans(): array
    {
        return Cache::remember('section_pricing_plans', 3600, function () {
            return [
                'pricingPlans' => PricingPlan::with('features', 'page')
                    ->where('active', '=', true)
                    ->orderBy('order')
                    ->get()
            ];
        });
    }
    
    protected function getIconBoxes(): array
    {
        return Cache::remember('section_icon_boxes', 3600, function () {
            return [
                'iconBoxes' => IconBox::orderBy('id')->get()
            ];
        });
    }
    
    protected function getFaqs(): array
    {
        return Cache::remember('section_faqs', 3600, function () {
            return [
                'faqs' => Faq::where('active', '=', true)
                    ->orderBy('created_at')->get()
            ];
        });
    }
    
    protected function isModuleEnabled(string $key): bool
    {
        return \App\Models\Setting::where('key', $key)->value('value') === 'YES';
    }
}
