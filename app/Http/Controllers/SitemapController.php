<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Roastery;
use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    /**
     * Cache duration in seconds (24 hours)
     */
    private const CACHE_DURATION = 86400;

    /**
     * Main sitemap index - lists all sub-sitemaps
     */
    public function index(): Response
    {
        [$locale, $baseUrl, $host] = $this->detectLocaleAndBaseUrl();
        $cacheKey = 'sitemap_index_' . $host;

        $xml = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($baseUrl) {
            return $this->generateSitemapIndex($baseUrl);
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * Static pages sitemap
     */
    public function static(): Response
    {
        [$locale, $baseUrl, $host] = $this->detectLocaleAndBaseUrl();
        $cacheKey = 'sitemap_static_' . $host;

        $xml = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($locale, $baseUrl) {
            return $this->generateStaticSitemap($locale, $baseUrl);
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * Products sitemap
     */
    public function products(): Response
    {
        [$locale, $baseUrl, $host] = $this->detectLocaleAndBaseUrl();
        $cacheKey = 'sitemap_products_' . $host;

        $xml = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($locale, $baseUrl) {
            return $this->generateProductsSitemap($locale, $baseUrl);
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * Roasteries sitemap
     */
    public function roasteries(): Response
    {
        [$locale, $baseUrl, $host] = $this->detectLocaleAndBaseUrl();
        $cacheKey = 'sitemap_roasteries_' . $host;

        $xml = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($locale, $baseUrl) {
            return $this->generateRoasteriesSitemap($locale, $baseUrl);
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * Blog sitemap
     */
    public function blog(): Response
    {
        [$locale, $baseUrl, $host] = $this->detectLocaleAndBaseUrl();
        $cacheKey = 'sitemap_blog_' . $host;

        $xml = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($locale, $baseUrl) {
            return $this->generateBlogSitemap($locale, $baseUrl);
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * Generate sitemap index XML
     */
    private function generateSitemapIndex(string $baseUrl): string
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = true;

        $sitemapindex = $dom->createElement('sitemapindex');
        $sitemapindex->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $dom->appendChild($sitemapindex);

        // Add all sub-sitemaps
        $sitemaps = [
            $baseUrl . '/sitemap-static.xml',
            $baseUrl . '/sitemap-products.xml',
            $baseUrl . '/sitemap-roasteries.xml',
            $baseUrl . '/sitemap-blog.xml',
        ];

        foreach ($sitemaps as $sitemapUrl) {
            $sitemap = $dom->createElement('sitemap');

            $loc = $dom->createElement('loc', htmlspecialchars($sitemapUrl));
            $sitemap->appendChild($loc);

            $lastmod = $dom->createElement('lastmod', now()->toIso8601String());
            $sitemap->appendChild($lastmod);

            $sitemapindex->appendChild($sitemap);
        }

        return $dom->saveXML();
    }

    /**
     * Generate static pages sitemap
     */
    private function generateStaticSitemap(string $locale, string $baseUrl): string
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = true;

        $urlset = $dom->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $dom->appendChild($urlset);

        // Add static pages
        foreach ($this->getStaticPages() as $page) {
            try {
                // Check if route exists before attempting to generate URL
                if (!Route::has($page['route'])) {
                    continue;
                }

                $url = localizedRoute($page['route']);

                $this->addUrl($dom, $urlset, [
                    'loc' => $url,
                    'lastmod' => now()->toIso8601String(),
                    'changefreq' => $page['changefreq'],
                    'priority' => (string) $page['priority'],
                ]);
            } catch (\Exception $e) {
                // Skip if route generation fails
                continue;
            }
        }

        return $dom->saveXML();
    }

    /**
     * Generate products sitemap
     */
    private function generateProductsSitemap(string $locale, string $baseUrl): string
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = true;

        $urlset = $dom->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $dom->appendChild($urlset);

        // Get all products (active and inactive) except coffee_of_month
        $products = Product::with('roastery')
            ->where('is_coffee_of_month', false)
            ->select(['id', 'slug', 'updated_at', 'is_active'])
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($products as $product) {
            try {
                $url = localizedRoute('products.show', $product);

                // Set lower priority for inactive products
                $priority = $product->is_active ? '0.8' : '0.6';

                $this->addUrl($dom, $urlset, [
                    'loc' => $url,
                    'lastmod' => $product->updated_at->toIso8601String(),
                    'changefreq' => 'weekly',
                    'priority' => $priority,
                ]);
            } catch (\Exception $e) {
                // Skip if URL generation fails
                continue;
            }
        }

        return $dom->saveXML();
    }

    /**
     * Generate roasteries sitemap
     */
    private function generateRoasteriesSitemap(string $locale, string $baseUrl): string
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = true;

        $urlset = $dom->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $dom->appendChild($urlset);

        // Get all active roasteries
        $roasteries = Roastery::where('is_active', true)
            ->select(['id', 'slug', 'updated_at'])
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($roasteries as $roastery) {
            try {
                $url = localizedRoute('roasteries.show', $roastery);

                $this->addUrl($dom, $urlset, [
                    'loc' => $url,
                    'lastmod' => $roastery->updated_at->toIso8601String(),
                    'changefreq' => 'monthly',
                    'priority' => '0.7',
                ]);
            } catch (\Exception $e) {
                // Skip if URL generation fails
                continue;
            }
        }

        return $dom->saveXML();
    }

    /**
     * Generate blog sitemap
     */
    private function generateBlogSitemap(string $locale, string $baseUrl): string
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = true;

        $urlset = $dom->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $dom->appendChild($urlset);

        // Get all published blog posts
        $posts = Post::published()
            ->select(['id', 'slug_cs', 'slug_en', 'published_at', 'updated_at'])
            ->orderBy('published_at', 'desc')
            ->get();

        foreach ($posts as $post) {
            try {
                $url = localizedRoute('blog.show', $post);

                // Use published_at or updated_at for lastmod
                $lastmod = $post->published_at ? $post->published_at->toIso8601String() : $post->updated_at->toIso8601String();

                $this->addUrl($dom, $urlset, [
                    'loc' => $url,
                    'lastmod' => $lastmod,
                    'changefreq' => 'monthly',
                    'priority' => '0.6',
                ]);
            } catch (\Exception $e) {
                // Skip if URL generation fails
                continue;
            }
        }

        return $dom->saveXML();
    }

    /**
     * Add a URL element to the sitemap
     */
    private function addUrl(\DOMDocument $dom, \DOMElement $parent, array $data): void
    {
        $url = $dom->createElement('url');

        foreach ($data as $key => $value) {
            $element = $dom->createElement($key, htmlspecialchars($value));
            $url->appendChild($element);
        }

        $parent->appendChild($url);
    }

    /**
     * Get list of static pages to include in sitemap
     */
    private function getStaticPages(): array
    {
        return [
            ['route' => 'home', 'priority' => 1.0, 'changefreq' => 'daily'],
            ['route' => 'products.index', 'priority' => 0.9, 'changefreq' => 'daily'],
            ['route' => 'roasteries.index', 'priority' => 0.8, 'changefreq' => 'weekly'],
            ['route' => 'blog.index', 'priority' => 0.7, 'changefreq' => 'weekly'],
            ['route' => 'subscriptions.index', 'priority' => 0.9, 'changefreq' => 'weekly'],
            ['route' => 'monthly-feature.index', 'priority' => 0.8, 'changefreq' => 'monthly'],
            ['route' => 'how-it-works', 'priority' => 0.7, 'changefreq' => 'monthly'],
            ['route' => 'about', 'priority' => 0.6, 'changefreq' => 'monthly'],
            ['route' => 'contact', 'priority' => 0.5, 'changefreq' => 'monthly'],
            ['route' => 'privacy-policy', 'priority' => 0.3, 'changefreq' => 'yearly'],
            ['route' => 'terms-of-service', 'priority' => 0.3, 'changefreq' => 'yearly'],
        ];
    }

    /**
     * Detect current locale and base URL based on request host
     *
     * @return array [locale, baseUrl, host]
     */
    private function detectLocaleAndBaseUrl(): array
    {
        $host = request()->getHost();

        // Detect if this is the English/EUR domain
        $isEur = str_contains($host, 'kavibox.com') || str_contains($host, 'kavibox.local');

        $locale = $isEur ? 'en' : 'cs';
        $baseUrl = $isEur ? 'https://kavibox.com' : 'https://kavi.cz';

        // Set application locale for route generation
        app()->setLocale($locale);

        return [$locale, $baseUrl, $host];
    }
}
