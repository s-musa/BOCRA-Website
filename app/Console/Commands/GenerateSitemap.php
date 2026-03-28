<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Models\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap for the website';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sitemap = Sitemap::create();
        
        // 1. Homepage
        $sitemap->add(Url::create('/')
            ->setPriority(1.0)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );
        
        // 2. Static pages (from Pages model)
        Page::where('published', true)
            ->where('title', '!=', 'Home')
            ->get()->each(function ($page) use ($sitemap) {
            $sitemap->add(
                Url::create(route('page.show', $page->slug))
                    ->setLastModificationDate($page->updated_at ?? now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8)
            );
        });
        
        // 3. Projects
        Project::where('active', true)->get()->each(function ($project) use ($sitemap) {
            $sitemap->add(
                Url::create(route('projects.show', $project->slug))
                    ->setLastModificationDate($project->updated_at ?? now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.7)
            );
        });
        
        Storage::delete(storage_path('sitemap.xml'));
        
        $sitemap->writeToFile(storage_path('sitemap.xml'));
        
        $this->info('Sitemap generated successfully.');
    }
}
