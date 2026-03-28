<?php

namespace App\Http\Controllers;

use App\Http\Resources\Resource;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function projects()
    {
        $projects = Project::activated()->with('media', 'category', 'tags')->orderBy('title')->get();
        
        return Resource::collection($projects);
    }
    
    public function tags()
    {
        $tags = Tag::activated()->orderBy('name')->get();
        
        return Resource::collection($tags);
    }
}
