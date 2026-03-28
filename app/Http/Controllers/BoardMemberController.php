<?php

namespace App\Http\Controllers;

use App\Http\Requests\BoardMemberRequest;
use App\Http\Resources\Resource;
use App\Models\BoardMember;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BoardMemberController extends Controller
{
    public function datatable()
    {
        $members = QueryBuilder::for(
            BoardMember::with('media')->orderBy('order')->orderBy('created_at')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('active'),
            AllowedFilter::partial('name'),
        ])->jsonPaginate();
        
        return Resource::collection($members);
    }
    
    public function store(BoardMemberRequest $request)
    {
        $validated = $request->validated();
        
        $member = BoardMember::create([
            'name' => $validated['name'],
            'position' => $validated['position'],
            'details' => $validated['details'],
            'active' => $validated['active'],
        ]);
        
        if($request->hasFile('media')) {
            $member->addMedia($validated['media'])
                ->toMediaCollection('board-image');
        }
        
        return back(303)->with('success', 'Member added successfully.');
    }
    
    public function update(BoardMemberRequest $request, BoardMember $boardMember)
    {
        $validated = $request->validated();
        
        $boardMember->update([
            'name' => $validated['name'],
            'position' => $validated['position'],
            'details' => $validated['details'],
            'active' => $validated['active'],
        ]);
        
        return back(303)->with('success', 'Member updated successfully.');
    }
    
    public function destroy(BoardMember $boardMember)
    {
        $boardMember->delete();
        return back(303)->with('success', 'Board Member deleted successfully.');
    }
    
    public function updateOrder(Request $request)
    {
        $members = $request->members[0];
        
        foreach ($members as $member) {
            BoardMember::where('id', $member['id'])
                ->update([
                    'order' => $member['order']
                ]);
        }
        
        return back(303)->with(['message' => 'Border Members order updated successfully']);
    }
}
