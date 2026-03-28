<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Http\Resources\Resource;
use App\Models\Event;
use HTMLPurifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EventController extends Controller
{
    public function dataTable()
    {
        $events = QueryBuilder::for(
            Event::with('media', 'user', 'category', 'tags')->orderBy('created_at')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::partial('title'),
        ])->jsonPaginate();
        
        return Resource::collection($events);
    }
    
    public function store(EventRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();
        $validated['slug'] = Str::of($validated['title'])->slug('-');
        $purifier = new HtmlPurifier();
        $validated['details'] = $purifier->purify($validated['details']);
        
        DB::beginTransaction();
        
        try {
            $event = Event::create([
                'user_id' => $user->id,
                'title' => $validated['title'],
                'slug' => $validated['slug'],
                'date' => $validated['date'],
                'category_id' => $validated['category_id'],
                'hosted_by' => $validated['hosted_by'],
                'location' => $validated['location'],
                'map_url' => $validated['map_url'],
                'details' => $validated['details'],
                'active' => $validated['active'],
            ]);
            
            if (isset($validated['event_tags']) && is_array($validated['event_tags'])) {
                $event->tags()->sync($validated['event_tags']);
            }
            
            if($request->hasFile('media')) {
                $event->addMedia($validated['media'])
                    ->toMediaCollection('event-image');
            }
            
            DB::commit();
            return back(303);
            
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error('Error: ' . $exception->getMessage());
            report($exception);
            return redirect()->back()->withInput()->withErrors(['message' => $exception->getMessage()]);
        }
    }
    
    public function show($slug)
    {
        $event = Event::where('slug', '=', $slug)->firstOrFail();
        $event->load('media', 'user', 'category', 'tags');
        $otherEvents = Event::with('media', 'user', 'tags')
            ->where('id', '!=', $event->id)
            ->orderByDesc('created_at')
            ->get();
        
        $seo = $event->metaSeo;
        
        if($seo) {
            \App\Models\MetaSeo::applyMeta($seo);
        }
        
        return view('website.event-details', [
            'event' => $event,
            'otherEvents' => $otherEvents,
        ]);
    }
    
    public function update(EventRequest $request, Event $event)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::of($validated['title'])->slug('-');
        $purifier = new HtmlPurifier();
        $validated['details'] = $purifier->purify($validated['details']);
        
        DB::beginTransaction();
        
        try {
            $event->update([
                'title' => $validated['title'],
                'slug' => $validated['slug'],
                'date' => $validated['date'],
                'category_id' => $validated['category_id'],
                'hosted_by' => $validated['hosted_by'],
                'location' => $validated['location'],
                'map_url' => $validated['map_url'],
                'details' => $validated['details'],
                'active' => $validated['active'],
            ]);
            
            if (isset($validated['event_tags']) && is_array($validated['event_tags'])) {
                $event->tags()->sync($validated['event_tags']);
            }
            
            DB::commit();
            return back(303);
            
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error('Error: ' . $exception->getMessage());
            report($exception);
            return redirect()->back()->withInput()->withErrors(['message' => $exception->getMessage()]);
        }
    }
    
    public function destroy($event)
    {
        $event->delete();
        return back(303)->with('success', 'Event deleted successfully.');
    }
    
    public function uploadEventImage(Request $request, $event)
    {
        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $event = Event::findOrFail($event);
        
        if($request->hasFile('file')) {
            $event->clearMediaCollection('event-image');
            $event->addMedia($validated['file'])
                ->toMediaCollection('event-image');
        }
        
        return back(303);
    }
    
    public function deleteEventImage(Request $request, $event)
    {
        $event = Event::findOrFail($event);
        $event->clearMediaCollection('event-image');
        
        return back(303)->with('success', 'Event image deleted successfully.');
    }
    
    public function uploadEventGallery(Request $request, $event)
    {
        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $event = Event::findOrFail($event);
        
        if($request->hasFile('file')) {
            $event->addMedia($validated['file'])
                ->toMediaCollection('event-gallery');
        }
        
        return back(303);
    }
}
