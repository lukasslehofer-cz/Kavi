<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $tags = Tag::withCount(['posts' => function ($query) {
            $query->where('status', 'published');
        }])->orderBy('name_cs')->get();

        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_cs' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'name_sk' => 'nullable|string|max:255',
            'slug_cs' => 'nullable|string|max:255|unique:tags,slug_cs',
            'slug_en' => 'nullable|string|max:255|unique:tags,slug_en',
            'slug_sk' => 'nullable|string|max:255|unique:tags,slug_sk',
        ]);

        // Generate Czech slug if not provided
        if (empty($validated['slug_cs'])) {
            $validated['slug_cs'] = Tag::generateSlug($validated['name_cs'], 'cs');
        }

        // Generate English slug if not provided but name_en exists
        if (empty($validated['slug_en']) && !empty($validated['name_en'])) {
            $validated['slug_en'] = Tag::generateSlug($validated['name_en'], 'en');
        }

        // Generate Slovak slug if not provided but name_sk exists
        if (empty($validated['slug_sk']) && !empty($validated['name_sk'])) {
            $validated['slug_sk'] = Tag::generateSlug($validated['name_sk'], 'sk');
        }

        Tag::create($validated);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag byl úspěšně vytvořen.');
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name_cs' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'name_sk' => 'nullable|string|max:255',
            'slug_cs' => 'nullable|string|max:255|unique:tags,slug_cs,' . $tag->id,
            'slug_en' => 'nullable|string|max:255|unique:tags,slug_en,' . $tag->id,
            'slug_sk' => 'nullable|string|max:255|unique:tags,slug_sk,' . $tag->id,
        ]);

        // Generate Czech slug if not provided
        if (empty($validated['slug_cs'])) {
            $validated['slug_cs'] = Tag::generateSlug($validated['name_cs'], 'cs', $tag->id);
        }

        // Generate English slug if not provided but name_en exists
        if (empty($validated['slug_en']) && !empty($validated['name_en'])) {
            $validated['slug_en'] = Tag::generateSlug($validated['name_en'], 'en', $tag->id);
        }

        // Generate Slovak slug if not provided but name_sk exists
        if (empty($validated['slug_sk']) && !empty($validated['name_sk'])) {
            $validated['slug_sk'] = Tag::generateSlug($validated['name_sk'], 'sk', $tag->id);
        }

        $tag->update($validated);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag byl úspěšně aktualizován.');
    }

    public function destroy(Tag $tag)
    {
        // Check if tag is used by any posts
        if ($tag->posts()->count() > 0) {
            return redirect()->route('admin.tags.index')
                ->with('error', 'Nelze smazat tag, který je přiřazen k článkům.');
        }
        
        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag byl úspěšně smazán.');
    }
}
