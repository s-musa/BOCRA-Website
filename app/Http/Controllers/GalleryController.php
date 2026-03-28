<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function upload(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'owner_type' => 'required|string', // e.g. "App\\Models\\Project"
            'owner_id' => 'required|integer',
            'collection' => 'required|string', // e.g. "project-gallery"
        ]);
        
        $ownerType = $request->header('X-Owner-Type');
        $ownerId = $request->header('X-Owner-Id');
        $collection = $request->header('X-Collection');
        
        abort_unless(class_exists($ownerType), 400, 'Invalid model type');
        
        $model = $ownerType::findOrFail($ownerId);
        
        $gallery = Gallery::firstOrCreate([
            'owner_id' => $model->id,
            'owner_type' => $ownerType,
        ], [
            'title' => class_basename($ownerType) . ' Gallery',
            'description' => 'Auto-generated gallery for ' . class_basename($ownerType),
        ]);
        
        $gallery->addMedia($validated['file'])
            ->toMediaCollection($collection);
        
        return response()->json(['success' => true]);
    }
}
