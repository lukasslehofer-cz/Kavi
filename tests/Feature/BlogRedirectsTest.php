<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\PostSlugRedirect;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * SEO fáze 1: přesměrování blogových URL (6a číselné ID, 6c staré slugy, 6d historie slugů)
 * a ruční související články (9).
 */
class BlogRedirectsTest extends TestCase
{
    use RefreshDatabase;

    private function makePost(string $slug, array $attributes = []): Post
    {
        return Post::create(array_merge([
            'title_cs' => 'Článek ' . $slug,
            'slug_cs' => $slug,
            'content_cs' => '<p>Obsah článku.</p>',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ], $attributes));
    }

    // 6a

    public function test_numeric_id_redirects_permanently_to_slug(): void
    {
        $post = $this->makePost('jak-pripravit-kavu-doma');

        $this->get('/blog/' . $post->id)
            ->assertStatus(301)
            ->assertRedirect(localizedRoute('blog.show', ['post' => 'jak-pripravit-kavu-doma']));
    }

    public function test_unknown_numeric_id_returns_404(): void
    {
        $this->get('/blog/999')->assertNotFound();
    }

    public function test_numeric_id_of_draft_returns_404(): void
    {
        $draft = $this->makePost('koncept', ['status' => 'draft']);

        $this->get('/blog/' . $draft->id)->assertNotFound();
    }

    public function test_current_slug_renders_article(): void
    {
        $this->makePost('jak-pripravit-kavu-doma');

        $this->get('/blog/jak-pripravit-kavu-doma')->assertOk()->assertSee('Článek jak-pripravit-kavu-doma');
    }

    public function test_unknown_slug_returns_404(): void
    {
        $this->get('/blog/neexistujici-clanek')->assertNotFound();
    }

    // 6c

    public function test_legacy_slugs_from_migration_redirect_permanently(): void
    {
        $this->makePost('bezkofeinova-kava-myty-a-fakta-pro-rok-2026');
        $this->makePost('kava-na-espresso-nebo-na-filtr');

        // Migrace běží nad prázdnou DB, proto ji pustíme znovu až s články
        $migration = require database_path('migrations/2026_11_26_100001_add_legacy_blog_slug_redirects.php');
        $migration->up();

        $expected = [
            'bezkofeinova-myty-a-fakta-pro-rok-2026' => 'bezkofeinova-kava-myty-a-fakta-pro-rok-2026',
            'bezkofeinova-kava-myty-a-fakta-for-2026' => 'bezkofeinova-kava-myty-a-fakta-pro-rok-2026',
            'kava-bez-kofeinu-myty-a-fakta-pro-rok-2026' => 'bezkofeinova-kava-myty-a-fakta-pro-rok-2026',
            'kava-nebo-na-filtr' => 'kava-na-espresso-nebo-na-filtr',
        ];

        foreach ($expected as $old => $current) {
            $this->get('/blog/' . $old)
                ->assertStatus(301)
                ->assertRedirect(localizedRoute('blog.show', ['post' => $current]));
        }
    }

    // 6d

    public function test_changing_slug_keeps_old_slug_as_permanent_redirect(): void
    {
        $post = $this->makePost('stary-slug');

        $post->update(['slug_cs' => 'novy-slug']);

        $this->assertDatabaseHas('post_slug_redirects', ['old_slug' => 'stary-slug', 'post_id' => $post->id, 'locale' => 'cs']);
        $this->get('/blog/stary-slug')
            ->assertStatus(301)
            ->assertRedirect(localizedRoute('blog.show', ['post' => 'novy-slug']));
        $this->get('/blog/novy-slug')->assertOk();
    }

    public function test_slug_chain_redirects_straight_to_current_slug(): void
    {
        $post = $this->makePost('prvni');
        $post->update(['slug_cs' => 'druhy']);
        $post->update(['slug_cs' => 'treti']);

        $this->get('/blog/prvni')->assertRedirect(localizedRoute('blog.show', ['post' => 'treti']));
        $this->get('/blog/druhy')->assertRedirect(localizedRoute('blog.show', ['post' => 'treti']));
    }

    public function test_reverting_to_old_slug_removes_its_redirect(): void
    {
        $post = $this->makePost('puvodni');
        $post->update(['slug_cs' => 'docasny']);
        $post->update(['slug_cs' => 'puvodni']);

        $this->assertDatabaseMissing('post_slug_redirects', ['old_slug' => 'puvodni']);
        $this->assertDatabaseHas('post_slug_redirects', ['old_slug' => 'docasny']);
        $this->get('/blog/puvodni')->assertOk();
        $this->get('/blog/docasny')->assertRedirect(localizedRoute('blog.show', ['post' => 'puvodni']));
    }

    public function test_redirect_to_unpublished_post_returns_404(): void
    {
        $post = $this->makePost('stary');
        $post->update(['slug_cs' => 'novy', 'status' => 'draft']);

        $this->get('/blog/stary')->assertNotFound();
    }

    public function test_deleting_post_removes_its_redirects(): void
    {
        $post = $this->makePost('a');
        $post->update(['slug_cs' => 'b']);
        $post->delete();

        $this->assertSame(0, PostSlugRedirect::count());
    }

    // 6b

    public function test_blog_sitemap_uses_slugs_not_ids(): void
    {
        $this->makePost('jak-pripravit-kavu-doma');

        $this->get('/sitemap-blog.xml')
            ->assertOk()
            ->assertSee('/blog/jak-pripravit-kavu-doma</loc>', false)
            ->assertDontSee('/blog/1</loc>', false);
    }

    // 9

    public function test_manual_related_posts_are_shown_in_order_and_saved_from_admin(): void
    {
        $post = $this->makePost('hlavni');
        $a = $this->makePost('souvisejici-a');
        $b = $this->makePost('souvisejici-b');
        $c = $this->makePost('souvisejici-c');

        $admin = new User();
        $admin->forceFill([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => bcrypt('test-heslo'),
            'is_admin' => true,
        ])->save();

        $this->actingAs($admin)->put(route('admin.posts.update', $post), [
            'title_cs' => $post->title_cs,
            'slug_cs' => $post->slug_cs,
            'content_cs' => $post->content_cs,
            'status' => 'published',
            'related' => [(string) $c->id, '', (string) $a->id],
        ])->assertRedirect(route('admin.posts.index'));

        $this->assertSame([$c->id, $a->id], $post->relatedPosts()->pluck('posts.id')->all());

        // Na webu ruční výběr v pořadí, volné místo doplní nejnovější článek
        $html = $this->get('/blog/hlavni')->assertOk()->getContent();
        $positions = array_map(fn (Post $p) => strpos($html, '/blog/' . $p->slug_cs . '"'), [$c, $a, $b]);
        $this->assertTrue($positions[0] < $positions[1] && $positions[1] < $positions[2], 'Pořadí souvisejících článků');
    }
}
