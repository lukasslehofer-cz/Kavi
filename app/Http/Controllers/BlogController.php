<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostSlugRedirect;
use App\Models\Tag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display the blog index with published posts
     */
    public function index(Request $request)
    {
        $query = Post::with('tags')
            ->published()
            ->orderByNewest();

        // Filter by tag if provided
        if ($request->filled('tag')) {
            $query->withTag($request->tag);
            $currentTag = Tag::where('slug_cs', $request->tag)
                            ->orWhere('slug_en', $request->tag)
                            ->first();
        } else {
            $currentTag = null;
        }

        $posts = $query->paginate(12)->withQueryString();

        // Get all tags with published posts count for sidebar
        $tags = Tag::withCount(['posts' => function ($query) {
            $query->published();
        }])->having('posts_count', '>', 0)
          ->orderBy('name_cs')
          ->get();

        return view('blog.index', compact('posts', 'tags', 'currentTag'));
    }

    /**
     * Display a single blog post
     *
     * Bez implicitního route model bindingu: ten běží před DetectRegion, takže by
     * neznal jazyk domény, a potřebujeme rozlišit ID, aktuální a starý slug.
     */
    public function show(string $post)
    {
        // Číselné ID (staré URL ze sitemap) → 301 na URL se slugem
        if (ctype_digit($post)) {
            $byId = Post::find((int) $post);
            abort_unless($byId && $byId->isPublished(), 404);

            return redirect()->to(localizedRoute('blog.show', ['post' => $byId->getSlug()]), 301);
        }

        $slug = $post;
        $post = Post::findBySlug($slug);

        // Starý slug přejmenovaného článku → 301 na aktuální
        if (! $post) {
            $redirect = PostSlugRedirect::with('post')->where('old_slug', $slug)->first();
            abort_unless($redirect && $redirect->post && $redirect->post->isPublished(), 404);

            return redirect()->to(localizedRoute('blog.show', ['post' => $redirect->post->getSlug()]), 301);
        }

        // Only show published posts
        if (!$post->isPublished()) {
            abort(404);
        }

        // Load tags relationship
        $post->load('tags');

        // Get related posts (same tags, excluding current)
        $relatedPosts = Post::with('tags')
            ->published()
            ->where('id', '!=', $post->id)
            ->whereHas('tags', function ($query) use ($post) {
                $query->whereIn('tags.id', $post->tags->pluck('id'));
            })
            ->orderByNewest()
            ->limit(3)
            ->get();

        // If not enough related posts, fill with recent posts
        if ($relatedPosts->count() < 3) {
            $excludeIds = $relatedPosts->pluck('id')->push($post->id)->toArray();
            $additionalPosts = Post::with('tags')
                ->published()
                ->whereNotIn('id', $excludeIds)
                ->orderByNewest()
                ->limit(3 - $relatedPosts->count())
                ->get();
            $relatedPosts = $relatedPosts->concat($additionalPosts);
        }

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
