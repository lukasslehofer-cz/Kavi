<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Roastery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoasteryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $roasteries = Roastery::orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.roasteries.index', compact('roasteries'));
    }

    public function create()
    {
        $countries = $this->getCountries();
        return view('admin.roasteries.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'website_url' => 'nullable|url|max:255',
            'instagram' => 'nullable|string|max:255',
            'country' => 'required|string|max:100',
            'country_flag' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'full_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
            'featured_month' => 'nullable|string|regex:/^\d{4}-\d{2}$/',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Handle main image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/roasteries'), $filename);
            $validated['image'] = 'images/roasteries/' . $filename;
        }

        // Handle gallery images (max 4)
        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            $galleryFiles = array_slice($request->file('gallery'), 0, 4); // Max 4 images
            
            foreach ($galleryFiles as $index => $file) {
                $filename = time() . '_gallery_' . $index . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/roasteries/gallery'), $filename);
                $galleryPaths[] = 'images/roasteries/gallery/' . $filename;
            }
            
            $validated['gallery'] = $galleryPaths;
        }

        Roastery::create($validated);

        return redirect()->route('admin.roasteries.index')
            ->with('success', 'Pražírna byla úspěšně vytvořena.');
    }

    public function edit(Roastery $roastery)
    {
        $countries = $this->getCountries();
        return view('admin.roasteries.edit', compact('roastery', 'countries'));
    }

    public function update(Request $request, Roastery $roastery)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'website_url' => 'nullable|url|max:255',
            'instagram' => 'nullable|string|max:255',
            'country' => 'required|string|max:100',
            'country_flag' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'full_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
            'featured_month' => 'nullable|string|regex:/^\d{4}-\d{2}$/',
            'remove_gallery' => 'nullable|array',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Handle main image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($roastery->image && file_exists(public_path($roastery->image))) {
                unlink(public_path($roastery->image));
            }
            
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/roasteries'), $filename);
            $validated['image'] = 'images/roasteries/' . $filename;
        }

        // Handle gallery images
        $existingGallery = $roastery->gallery ?? [];
        
        // Remove selected gallery images
        if ($request->has('remove_gallery')) {
            foreach ($request->remove_gallery as $imageToRemove) {
                if (($key = array_search($imageToRemove, $existingGallery)) !== false) {
                    // Delete file
                    if (file_exists(public_path($imageToRemove))) {
                        unlink(public_path($imageToRemove));
                    }
                    unset($existingGallery[$key]);
                }
            }
            $existingGallery = array_values($existingGallery); // Reindex array
        }

        // Add new gallery images (max 4 total)
        if ($request->hasFile('gallery')) {
            $remainingSlots = 4 - count($existingGallery);
            $newFiles = array_slice($request->file('gallery'), 0, $remainingSlots);
            
            foreach ($newFiles as $index => $file) {
                $filename = time() . '_gallery_' . $index . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/roasteries/gallery'), $filename);
                $existingGallery[] = 'images/roasteries/gallery/' . $filename;
            }
        }
        
        $validated['gallery'] = $existingGallery;

        $roastery->update($validated);

        return redirect()->route('admin.roasteries.index')
            ->with('success', 'Pražírna byla úspěšně aktualizována.');
    }

    public function destroy(Roastery $roastery)
    {
        // Delete main image if exists
        if ($roastery->image && file_exists(public_path($roastery->image))) {
            unlink(public_path($roastery->image));
        }
        
        // Delete gallery images
        if ($roastery->gallery) {
            foreach ($roastery->gallery as $image) {
                if (file_exists(public_path($image))) {
                    unlink(public_path($image));
                }
            }
        }
        
        $roastery->delete();

        return redirect()->route('admin.roasteries.index')
            ->with('success', 'Pražírna byla úspěšně smazána.');
    }

    private function getCountries()
    {
        return [
            'Česká republika' => '🇨🇿',
            'Slovensko' => '🇸🇰',
            'Polsko' => '🇵🇱',
            'Rakousko' => '🇦🇹',
            'Německo' => '🇩🇪',
            'Švýcarsko' => '🇨🇭',
            'Itálie' => '🇮🇹',
            'Francie' => '🇫🇷',
            'Španělsko' => '🇪🇸',
            'Portugalsko' => '🇵🇹',
            'Velká Británie' => '🇬🇧',
            'Irsko' => '🇮🇪',
            'Nizozemsko' => '🇳🇱',
            'Belgie' => '🇧🇪',
            'Dánsko' => '🇩🇰',
            'Norsko' => '🇳🇴',
            'Švédsko' => '🇸🇪',
            'Finsko' => '🇫🇮',
        ];
    }
}

