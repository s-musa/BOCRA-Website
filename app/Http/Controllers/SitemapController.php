<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [];
        
        $urls[] = [
            'loc' => url('/'),
            'changefreq' => 'daily',
            'priority' => '1.0',
        ];
        
        $pages = Page::where('published', true)
            ->where('title', '!=', 'Home')
            ->get();
        
        if($pages) {
            foreach ($pages as $page) {
                $urls[] = [
                    'loc' => route('page.show', $page->slug),
                    'lastmod' => $page->updated_at ? $page->updated_at->toW3cString() : now()->toW3cString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            }
        }
        
        $projects = Project::where('active', true)->get();
        if($projects) {
            foreach ($projects as $project) {
                $urls[] = [
                    'loc' => route('projects.show', $project->slug),
                    'lastmod' => $project->updated_at ? $project->updated_at->toW3cString() : now()->toW3cString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ];
            }
        }
        
        $services = Service::where('active', true)->get();
        
        if($services) {
            foreach ($services as $service) {
                $urls[] = [
                    'loc' => route('services.show', $service->slug),
                    'lastmod' => $service->updated_at ? $service->updated_at->toW3cString() : now()->toW3cString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ];
            }
        }
        
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset></urlset>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        
        foreach ($urls as $url) {
            $u = $xml->addChild('url');
            $u->addChild('loc', htmlspecialchars($url['loc'], ENT_QUOTES));
            if (!empty($url['lastmod']))
                $u->addChild('lastmod', $url['lastmod']);
            if (!empty($url['changefreq']))
                $u->addChild('changefreq', $url['changefreq']);
            if (!empty($url['priority']))
                $u->addChild('priority', $url['priority']);
        }
        
        return Response::make($xml->asXML(), 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
}
