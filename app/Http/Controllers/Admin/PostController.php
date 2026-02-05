<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = Post::with('tags');

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by title
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title_cs', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%");
            });
        }

        // Filter by tag
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag);
            });
        }

        // Order by date
        $query->orderBy('created_at', 'desc');

        $posts = $query->paginate(20)->withQueryString();

        // Stats
        $stats = [
            'total' => Post::count(),
            'published' => Post::where('status', 'published')->count(),
            'drafts' => Post::where('status', 'draft')->count(),
        ];

        $tags = Tag::orderBy('name_cs')->get();

        return view('admin.posts.index', compact('posts', 'stats', 'tags'));
    }

    public function create()
    {
        $tags = Tag::orderBy('name_cs')->get();
        
        return view('admin.posts.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_cs' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'slug_cs' => 'nullable|string|max:255|unique:posts,slug_cs',
            'slug_en' => 'nullable|string|max:255|unique:posts,slug_en',
            'perex_cs' => 'nullable|string',
            'perex_en' => 'nullable|string',
            'content_cs' => 'required|string',
            'content_en' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'author' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Generate Czech slug if not provided
        if (empty($validated['slug_cs'])) {
            $validated['slug_cs'] = Post::generateSlug($validated['title_cs'], 'cs');
        }

        // Generate English slug if not provided but title_en exists
        if (empty($validated['slug_en']) && !empty($validated['title_en'])) {
            $validated['slug_en'] = Post::generateSlug($validated['title_en'], 'en');
        }

        // Handle published_at
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/blog'), $filename);
            $validated['featured_image'] = 'images/blog/' . $filename;
        }

        // Remove tags from validated data (will be synced separately)
        $tagIds = $validated['tags'] ?? [];
        unset($validated['tags']);

        $post = Post::create($validated);

        // Sync tags
        if (!empty($tagIds)) {
            $post->tags()->sync($tagIds);
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'Článek byl úspěšně vytvořen.');
    }

    public function edit(Post $post)
    {
        $tags = Tag::orderBy('name_cs')->get();
        $selectedTags = $post->tags->pluck('id')->toArray();
        
        return view('admin.posts.edit', compact('post', 'tags', 'selectedTags'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title_cs' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'slug_cs' => 'nullable|string|max:255|unique:posts,slug_cs,' . $post->id,
            'slug_en' => 'nullable|string|max:255|unique:posts,slug_en,' . $post->id,
            'perex_cs' => 'nullable|string',
            'perex_en' => 'nullable|string',
            'content_cs' => 'required|string',
            'content_en' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'author' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Generate Czech slug if not provided
        if (empty($validated['slug_cs'])) {
            $validated['slug_cs'] = Post::generateSlug($validated['title_cs'], 'cs', $post->id);
        }

        // Generate English slug if not provided but title_en exists
        if (empty($validated['slug_en']) && !empty($validated['title_en'])) {
            $validated['slug_en'] = Post::generateSlug($validated['title_en'], 'en', $post->id);
        }

        // Handle published_at
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = $post->published_at ?? now();
        }

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($post->featured_image && file_exists(public_path($post->featured_image))) {
                unlink(public_path($post->featured_image));
            }
            
            $file = $request->file('featured_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/blog'), $filename);
            $validated['featured_image'] = 'images/blog/' . $filename;
        }

        // Remove tags from validated data (will be synced separately)
        $tagIds = $validated['tags'] ?? [];
        unset($validated['tags']);

        $post->update($validated);

        // Sync tags
        $post->tags()->sync($tagIds);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Článek byl úspěšně aktualizován.');
    }

    public function destroy(Post $post)
    {
        // Delete featured image if exists
        if ($post->featured_image && file_exists(public_path($post->featured_image))) {
            unlink(public_path($post->featured_image));
        }
        
        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Článek byl úspěšně smazán.');
    }
}
