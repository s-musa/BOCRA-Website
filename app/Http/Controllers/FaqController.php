<?php

namespace App\Http\Controllers;

use App\Http\Requests\FaqRequest;
use App\Http\Resources\Resource;
use App\Models\Faq;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class FaqController extends Controller
{
    public function datatable()
    {
        $faqs = QueryBuilder::for(
            Faq::orderBy('question')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('active'),
            AllowedFilter::partial('question'),
        ])->jsonPaginate();
        
        return Resource::collection($faqs);
    }
    
    public function index()
    {
        return Inertia::render('Admin/Components/Faqs/Index', []);
    }
    
    public function store(FaqRequest $request)
    {
        $validated = $request->validated();
        
        Faq::create([
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'active' => $validated['active'],
        ]);
        
        return to_route('admin.components.index');
    }
    
    public function update(FaqRequest $request, Faq $faq)
    {
        $validated = $request->validated();
        
        $faq->update([
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'active' => $validated['active'],
        ]);
        
        return to_route('admin.components.index');
    }
    
    public function destroy(Faq $faq)
    {
        $faq->delete();
        return to_route('admin.components.index')->with('success', 'Faq deleted successfully.');
    }
}
