<?php

namespace App\Http\Controllers;

use App\Http\Requests\SkillRequest;
use App\Http\Resources\Resource;
use App\Models\Skill;
use App\Services\HtmlPurifierService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SkillController extends Controller
{
    public function datatable()
    {
        $skills = QueryBuilder::for(
            Skill::orderBy('title')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('active'),
            AllowedFilter::partial('title'),
        ])->jsonPaginate();
        
        return Resource::collection($skills);
    }
    
    public function store(SkillRequest $request, HtmlPurifierService $purifier)
    {
        $validated = $request->validated();
        $validated['details'] = $purifier->purify($validated['details']);
        
        Skill::create([
            'title' => $validated['title'],
            'details' => $validated['details'],
            'active' => $validated['active'],
        ]);
        
        return back(303)->with('success', 'Skill added successfully.');
    }
    
    public function update(SkillRequest $request, Skill $skill, HtmlPurifierService $purifier)
    {
        $validated = $request->validated();
        $validated['details'] = $purifier->purify($validated['details']);
        
        $skill->update([
            'title' => $validated['title'],
            'details' => $validated['details'],
            'active' => $validated['active'],
        ]);
        
        return back(303)->with('success', 'Skill updated successfully.');
    }
    
    public function destroy(Skill $skill)
    {
        $skill->delete();
        return back(303)->with('success', 'Skill deleted successfully.');
    }
}
