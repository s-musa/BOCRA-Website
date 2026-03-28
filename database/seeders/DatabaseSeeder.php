<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\Customisation;
use App\Models\Faq;
use App\Models\Menu;
use App\Models\Module;
use App\Models\ModuleDependency;
use App\Models\Page;
use App\Models\PageType;
use App\Models\Project;
use App\Models\Section;
use App\Models\SectionType;
use App\Models\Service;
use App\Models\Skill;
use App\Models\Tag;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Services\HtmlPurifierService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        
        $this->companies();
        
        User::create([
            'name' => 'Admin',
            'email' => 'admin@app.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin@!2026'),
            'activated' => true,
        ]);
        
        $this->call(LaratrustSeeder::class);
        
        $this->customisations();
        
        $this->sectionTypes();
        
        $this->pages();
        
        $this->menus();
        
        Schema::enableForeignKeyConstraints();
    }
    
    public function companies(): void
    {
        Company::truncate();
        
        Company::create([
            'name' => 'Botswana Communications Regulatory Authority',
            'email' => 'info@bocra.org.bw',
            'primary_phone' => '+267 395 7755',
            'secondary_phone' => null,
            'website' => 'www.bocra.org.bw',
            'country' => 'BOTSWANA',
            'state' => 'GABORONE',
            'city' => 'GABORONE',
            'physical_address' => 'Plot 50671 Independence Avenue',
            'postal_address' => '12345-8001',
            'tax_identification_pin' => null,
            'has_footer_logo' => true,
        ]);
    }
    
    public function customisations(): void
    {
        Customisation::truncate();
        
        Customisation::create([
            'primary_color' => '#0a113c',
            'primary_color_rgb' => '10, 17, 60',
            'primary_color_light' => '#203a81',
            'primary_color_light_rgb' => '32, 58, 129',
            'secondary_color' => '#00c0f8',
            'secondary_color_rgb' => '0, 192, 248',
            'secondary_color_light' => '#99e7fe',
            'secondary_color_light_rgb' => '153, 231, 254',
            'button_style' => 'default',
            'header_style' => 'default',
            'header_bg' => 'default',
            'top_header' => true,
            'top_header_bg' => 'bg-light',
            'footer_has_background' => true,
            'footer_style' => 'default',
            'footer_bg_color' => 'bg-dark',
            'footer_text' => null,
            'banner_style' => 'default',
            'banner_bg' => 'bg-gradient-overlay',
            'section_width_style' => 'container'
        ]);
    }
    
    public function pages(): void
    {
        $type = PageType::firstOrCreate(
            ['name' => 'CMS'],
            ['active' => true]
        );
        
        Page::insert([
            ['title' => 'Home', 'slug' => '/', 'published' => true, 'page_type_id' => $type->id,
                'created_at' => now(), 'updated_at' => now()],
        ]);
    }
    
    public function menus(): void
    {
        $pages = Page::all();
        $menus = [
            [
                'title' => 'Home', 'page_id' => $pages->where('title', 'Home')->first()->id,
                'type' => Menu::TYPE_PAGE, 'url' => null, 'has_children' => false, 'child_type' => null, 'component' => null,
                'parent_id' => null, 'created_at' => now(), 'updated_at' => now(),
            ],
        ];
        
        foreach ($menus as $menu) {
            Menu::create([
                'page_id' => $menu['page_id'],
                'title' => $menu['title'],
                'type' => $menu['type'],
                'url' => $menu['url'],
                'has_children' => $menu['has_children'],
                'child_type' => $menu['child_type'],
                'component' => $menu['component'],
                'parent_id' => $menu['parent_id'],
                'order' => Menu::max('order') + 1,
            ]);
        }
    }
    
    public function sectionTypes(): void
    {
        $types = [
            ['value' => 'hero-section', 'name' => 'Hero Section', 'description' => 'A large banner section at the top of the page, usually with a headline, text, and a background image or color.'],
            ['value' => 'section-with-image', 'name' => 'Section With Image', 'description' => 'A content section that includes an image alongside text.'],
            ['value' => 'section-without-image', 'name' => 'Section Without Image', 'description' => 'A simple text-only section without any images.'],
            ['value' => 'section-with-cards', 'name' => 'Section With Cards', 'description' => 'A section that displays multiple items in card layouts, often with images, titles, and short descriptions.'],
            ['value' => 'section-with-component', 'name' => 'Section With Component', 'description' => 'A customizable section where a pre-designed component can be inserted.'],
            ['value' => 'section-with-services', 'name' => 'Section With Services', 'description' => 'A section showcasing the services your business offers.'],
            ['value' => 'section-with-projects', 'name' => 'Section With Projects', 'description' => 'A portfolio-style section displaying recent or featured projects.'],
            ['value' => 'section-with-products', 'name' => 'Section With Products', 'description' => 'A section displaying a set of products with images and descriptions.'],
            ['value' => 'section-with-blogs', 'name' => 'Section With Blogs', 'description' => 'A section displaying recent or featured blogs.'],
            ['value' => 'section-with-events', 'name' => 'Section With Events', 'description' => 'A section displaying recent or featured events.'],
            ['value' => 'section-with-partners', 'name' => 'Section With Partners', 'description' => 'A section showing logos or names of partner companies or organizations.'],
            ['value' => 'section-with-board-members', 'name' => 'Section With Board Members', 'description' => 'A team section displaying your board members with names, roles, and images.'],
            ['value' => 'section-with-faqs', 'name' => 'Section With FAQs', 'description' => 'A frequently asked questions section with collapsible answers.'],
            ['value' => 'section-with-contact-form', 'name' => 'Section With Contact Form', 'description' => 'A section containing a form for visitors to send you messages or inquiries.'],
            ['value' => 'section-with-contact-cards', 'name' => 'Section With Contact Cards', 'description' => 'A section displaying contact details in card format, such as phone, email, and address.'],
            ['value' => 'section-with-qr-code', 'name' => 'Section With QR Code', 'description' => 'A section displaying a QR code for quick access to links, downloads, or information.'],
            ['value' => 'section-with-map', 'name' => 'Section With Map', 'description' => 'A section displaying an interactive or static map location.'],
            ['value' => 'section-with-gallery', 'name' => 'Section With Gallery', 'description' => 'A section displaying multiple images in a gallery layout design.'],
            ['value' => 'section-with-video', 'name' => 'Section With Video', 'description' => 'A section displaying a video sourced from youtube, cloud or the web in general.'],
            ['value' => 'section-with-clients', 'name' => 'Section With Clients', 'description' => 'A section showing logos or names of client companies or organizations.'],
            ['value' => 'section-with-pricing-plans', 'name' => 'Section With Pricing Plans', 'description' => null],
            ['value' => 'section-with-icon-box', 'name' => 'Section With Icon Box', 'description' => 'Section With Icon Box'],
        ];
        
        foreach ($types as $type) {
            SectionType::create([
                'value' => $type['value'],
                'name' => $type['name'],
                'description' => $type['description'],
                'active' => true,
            ]);
        }
    }
}
