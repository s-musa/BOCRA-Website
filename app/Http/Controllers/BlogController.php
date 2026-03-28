<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Http\Resources\Resource;
use App\Models\Blog;
use Carbon\Carbon;
use HTMLPurifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BlogController extends Controller
{
    public function dataTable()
    {
        $blogs = QueryBuilder::for(
            Blog::with('media', 'user', 'tags')->orderBy('created_at')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::partial('title'),
        ])->jsonPaginate();
        
        return Resource::collection($blogs);
    }
    
    public function store(BlogRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();
        $validated['slug'] = Str::of($validated['title'])->slug('-');
        $purifier = new HtmlPurifier();
        $validated['details'] = $purifier->purify($validated['details']);
//        $date = Carbon::parse($validated['date'])->toDateTimeString();
        
        DB::beginTransaction();
        
        try {
            $blog = Blog::create([
                'user_id' => $user->id,
                'title' => $validated['title'],
                'slug' => $validated['slug'],
                'date' => $validated['date'],
                'details' => $validated['details'],
                'active' => $validated['active'],
            ]);
            
            if (isset($validated['blog_tags']) && is_array($validated['blog_tags'])) {
                $blog->tags()->sync($validated['blog_tags']);
            }
            
            if($request->hasFile('media')) {
                $blog->addMedia($validated['media'])
                    ->toMediaCollection('blog-image');
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
        $blog = Blog::where('slug', '=', $slug)->firstOrFail();
        $blog->load('media', 'user', 'tags');
        $otherBlogs = Blog::with('media', 'user', 'tags')
            ->where('id', '!=', $blog->id)
            ->orderByDesc('created_at')
            ->get();
        
        $seo = $blog->metaSeo;
        
        if($seo) {
            \App\Models\MetaSeo::applyMeta($seo);
        }
        
        return view('website.blog-details', [
            'blog' => $blog,
            'otherBlogs' => $otherBlogs,
        ]);
    }
    
    public function update(BlogRequest $request, Blog $blog)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::of($validated['title'])->slug('-');
        $purifier = new HtmlPurifier();
        $validated['details'] = $purifier->purify($validated['details']);
        
        DB::beginTransaction();
        
        try {
            $blog->update([
                'title' => $validated['title'],
                'slug' => $validated['slug'],
                'date' => $validated['date'],
                'details' => $validated['details'],
                'active' => $validated['active'],
            ]);
            
            if (isset($validated['blog_tags']) && is_array($validated['blog_tags'])) {
                $blog->tags()->sync($validated['blog_tags']);
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
    
    public function destroy($blog)
    {
        $blog->delete();
        return back(303)->with('success', 'Blog deleted successfully.');
    }
    
    public function uploadBlogImage(Request $request, $blog)
    {
        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $blog = Blog::findOrFail($blog);
        
        if($request->hasFile('file')) {
            $blog->clearMediaCollection('blog-image');
            $blog->addMedia($validated['file'])
                ->toMediaCollection('blog-image');
        }
        
        return back(303);
    }
    
    public function deleteBlogImage(Request $request, $blog)
    {
        $blog = Blog::findOrFail($blog);
        $blog->clearMediaCollection('blog-image');
        
        return back(303)->with('success', 'Blog image deleted successfully.');
    }
    
    public function uploadBlogGallery(Request $request, $blog)
    {
        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $blog = Blog::findOrFail($blog);
        
        if($request->hasFile('file')) {
            $blog->addMedia($validated['file'])
                ->toMediaCollection('blog-gallery');
        }
        
        return back(303);
    }
}
