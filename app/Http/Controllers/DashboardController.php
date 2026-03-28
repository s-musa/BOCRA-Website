<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

class DashboardController extends Controller
{
    public function index()
    {
        // Clear cache for fresh data (optional - remove in production)
        // Cache::forget('analytics.visitors');
        // Cache::forget('analytics.topPages');
        // Cache::forget('analytics.countries');
        
        $visitorsAndPageViews = Cache::remember('analytics.visitors', now()->addHours(12), function () {
            $data = Analytics::get(
                Period::days(30),
                ['activeUsers', 'screenPageViews'], // metrics
                ['date'] // dimension - this gives us the date
            );
            
            // Transform data to include formatted dates
            return $data->map(function ($item) {
                return [
                    'date' => $item['date']->format('Y-m-d'),
                    'visitors' => $item['activeUsers'],
                    'pageViews' => $item['screenPageViews']
                ];
            })->toArray();
        });
        
        $topPages = Cache::remember('analytics.topPages', now()->addHours(12), function () {
            $pages = Analytics::fetchMostVisitedPages(Period::days(30), 10);
            
            return $pages->map(function ($item) {
                return [
                    'url' => $item['fullPageUrl'],
                    'pageTitle' => $item['pageTitle'] ?? $item['fullPageUrl'],
                    'pageViews' => $item['screenPageViews'],
                ];
            })->toArray();
        });
        
        $topCountries = Cache::remember('analytics.countries', now()->addHours(12), function () {
            return Analytics::get(
                Period::days(30),
                ['screenPageViews'],
                ['country'],
                10
            );
        });
        
        $trafficSources = Cache::remember('analytics.trafficSources', now()->addHours(12), function () {
            $data = Analytics::get(
                Period::days(30),
                ['activeUsers'],
                ['sessionDefaultChannelGroup'],
                10
            );
            
            return $data->map(function ($item) {
                return [
                    'source' => $item['sessionDefaultChannelGroup'],
                    'users' => (int) $item['activeUsers']
                ];
            })->toArray();
        });
        
        return Inertia::render('Admin/Dashboard', [
            'visitorsAndPageViews' => $visitorsAndPageViews,
            'topPages' => $topPages,
            'topCountries' => $topCountries,
            'trafficSources' => $trafficSources,
        ]);
    }
    
    public function refreshAnalytics()
    {
        // Clear all analytics cache
        Cache::forget('analytics.visitors');
        Cache::forget('analytics.topPages');
        Cache::forget('analytics.countries');
        Cache::forget('analytics.trafficSources');
        
        return redirect()->route('admin.dashboard')->with('success', 'Analytics data refreshed');
    }
}
