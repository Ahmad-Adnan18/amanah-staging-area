<?php

namespace App\Http\Controllers;

use App\Models\SliderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    /**
     * Display a listing of the slider items for management.
     * This method will return all items (active and inactive)
     * so they can be managed in the admin panel.
     */
    public function index()
    {
        $sliderItems = SliderItem::orderBy('order')->get([
            'id', 'title', 'type', 'path', 'external_url', 'order', 'is_active'
        ]);

        // Accessors (file_url, embed_url) will automatically be appended due to $appends in model

        return response()->json([
            'success' => true,
            'data' => $sliderItems
        ]);
    }

    /**
     * Store a newly created slider item in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'required|in:image,video,external',
            // File is required only if type is image/video, and must be a file max 10MB
            'file' => 'required_if:type,image,video|file|mimes:jpeg,png,jpg,gif,svg,mp4,webm,ogg|max:10240',
            // External URL is required if type is external, and must be a valid URL
            'external_url' => 'required_if:type,external|url|nullable',
            'is_active' => 'boolean', // Optional: allow setting active status on creation
            'order' => 'integer|min:0', // Ensure order is an integer and non-negative
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $path = null;
        if ($request->type !== 'external' && $request->hasFile('file')) {
            $path = $request->file('file')->store('slider', 'public');
        }

        $sliderItem = SliderItem::create([
            'title' => $request->title,
            'type' => $request->type,
            'path' => $path,
            'external_url' => $request->type === 'external' ? $request->external_url : null,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true, // Default to true if not provided
            'order' => $request->order ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Slider item created successfully',
            'data' => $sliderItem // Accessors will be included automatically
        ], 201);
    }

    /**
     * Update the specified slider item in storage.
     */
    public function update(Request $request, SliderItem $sliderItem)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:image,video,external',
            'is_active' => 'sometimes|required|boolean',
            'order' => 'sometimes|required|integer|min:0',
            // File is optional for update. If provided, validate it.
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,mp4,webm,ogg|max:10240',
            // External URL is required if type becomes 'external', and must be a valid URL
            'external_url' => 'required_if:type,external|url|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only(['title', 'type', 'is_active', 'order']);

        // Handle file upload if provided and type is image/video
        if ($request->hasFile('file')) {
            if ($sliderItem->path) {
                Storage::disk('public')->delete($sliderItem->path); // Delete old file
            }
            $data['path'] = $request->file('file')->store('slider', 'public');
            $data['external_url'] = null; // Clear external_url if local file is uploaded
        } elseif ($request->type === 'external') {
            // If type is updated to 'external'
            if ($sliderItem->path) {
                Storage::disk('public')->delete($sliderItem->path); // Delete old local file
            }
            $data['path'] = null; // Clear path
            $data['external_url'] = $request->external_url;
        } else {
            // If type remains image/video but no new file is uploaded, keep existing path
            // If type becomes image/video from external, path will be null unless new file is uploaded
            if ($sliderItem->type === 'external' && in_array($request->type, ['image', 'video']) && !$request->hasFile('file')) {
                 // Transition from external to local, but no file uploaded. This might be an error state or user intent to clear previous external.
                 // We'll just clear the external_url and path for now, user needs to upload a file to make it valid.
                 $data['path'] = null;
                 $data['external_url'] = null;
            } elseif (in_array($request->type, ['image', 'video']) && $request->type === $sliderItem->type && !$request->hasFile('file')) {
                // Type unchanged (image/video), no new file, keep current path.
                $data['path'] = $sliderItem->path;
                $data['external_url'] = null;
            }
        }


        // Ensure is_active is boolean
        $data['is_active'] = (bool)($request->is_active ?? $sliderItem->is_active);

        $sliderItem->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Slider item updated successfully',
            'data' => $sliderItem // Accessors will be included automatically
        ]);
    }

    /**
     * Remove the specified slider item from storage.
     */
    public function destroy(SliderItem $sliderItem)
    {
        // Delete the file from storage if it's a local file
        if ($sliderItem->path) {
            Storage::disk('public')->delete($sliderItem->path);
        }

        $sliderItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Slider item deleted successfully'
        ]);
    }

    /**
     * Display the slider management page.
     */
    public function indexView()
    {
        return view('slider.index');
    }
}