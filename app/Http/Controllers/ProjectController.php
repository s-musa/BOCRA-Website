<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\Resource;
use App\Models\Page;
use App\Models\Project;
use App\Models\Tag;
use App\Services\HtmlPurifierService;
use HTMLPurifier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{
    public function datatable()
    {
        $projects = QueryBuilder::for(
            Project::with('media', 'metaSeo', 'category', 'tags')->orderBy('title')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('active'),
            AllowedFilter::partial('title'),
        ])->jsonPaginate();
        
        return Resource::collection($projects);
    }
    
    public function index()
    {
        return Inertia::render('Admin/Components/Projects/Index', []);
    }
    
    public function store(ProjectRequest $request)
    {
        $validated = $request->validated();
        
        $validated['slug'] = Str::of($validated['title'])->slug('-');
        $purifier = new HtmlPurifier();
        $validated['details'] = $purifier->purify($validated['details']);
        
        $project = Project::create([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'slug' => $validated['slug'],
            'details' => $validated['details'],
            'short_description' => $validated['short_description'],
            'active' => $validated['active'],
        ]);
        
        if (isset($validated['project_tags']) && is_array($validated['project_tags'])) {
            $project->tags()->sync($validated['project_tags']);
        }
        
        if($request->hasFile('media')) {
            $project->addMedia($validated['media'])
                ->toMediaCollection('project-image');
        }
        
        return back(303)->with('success', 'Project created successfully.');
    }
    
    public function show($project)
    {
        $project = Project::where('slug', '=', $project)->firstOrFail();
        $project->load('media', 'category');
        $otherProjects = Project::with('media', 'category')
            ->where('id', '!=', $project->id)
            ->orderBy('title')
            ->get();
        
        $seo = $project->metaSeo;
        
        if($seo) {
            \App\Models\MetaSeo::applyMeta($seo);
        }
        
        return view('website.project-details', [
            'project' => $project,
            'otherProjects' => $otherProjects,
        ]);
    }
    
    public function update(ProjectRequest $request, Project $project)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::of($validated['slug'])->slug('-');
        $purifier = new HtmlPurifier();
        $validated['details'] = $purifier->purify($validated['details']);
        
        $project->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'category_id' => $validated['category_id'],
            'details' => $validated['details'],
            'short_description' => $validated['short_description'],
            'active' => $validated['active'],
        ]);
        
        if (isset($validated['project_tags']) && is_array($validated['project_tags'])) {
            $project->tags()->sync($validated['project_tags']);
        }
        
        return back(303)->with('success', 'Project updated successfully.');
    }
    
    public function destroy($projectId)
    {
        $project = Project::findOrFail($projectId);
        $project->delete();
        
        return back(303)->with('success', 'Project deleted successfully.');
    }
    
    public function uploadProjectImage(Request $request, $project)
    {
        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $project = Project::findOrFail($project);
        
        if($request->hasFile('file')) {
            $project->clearMediaCollection('project-image');
            $project->addMedia($validated['file'])
                ->toMediaCollection('project-image');
        }
        
        return back(303);
    }
    
    public function deleteProjectImage(Request $request, $project)
    {
        $project = Project::findOrFail($project);
        $project->clearMediaCollection('project-image');
        
        return back(303)->with('success', 'Project image deleted successfully.');
    }
    
    public function uploadProjectGallery(Request $request, $project)
    {
        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $project = Project::findOrFail($project);
        
        if($request->hasFile('file')) {
            $project->addMedia($validated['file'])
                ->toMediaCollection('project-gallery');
        }
        
        return back(303);
    }
    
    public function storeSeo(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => ['required'],
            'description' => ['required'],
            'keywords' => ['required'],
        ]);
        
        if ($project->metaSeo) {
            $project->metaSeo->update($validated);
        } else {
            $project->metaSeo()->create($validated);
        }
        
        return back(303)->with('message', 'Project SEO captured');
    }
}
