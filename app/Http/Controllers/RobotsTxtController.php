<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsTxtController extends Controller
{
    /**
     * Generate robots.txt dynamically based on domain
     */
    public function index(): Response
    {
        $host = request()->getHost();

        // Detect domain and set base URL
        $baseUrl = $this->getBaseUrl($host);

        // Generate robots.txt content
        $content = $this->generateRobotsTxt($baseUrl);

        return response($content, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }

    /**
     * Generate robots.txt content
     */
    private function generateRobotsTxt(string $baseUrl): string
    {
        $lines = [
            '# Robots.txt for ' . $baseUrl,
            '',
            'User-agent: *',
            'Allow: /',
            '',
            '# Disallow admin area',
            'Disallow: /admin/',
            'Disallow: /admin',
            '',
            '# Disallow authentication pages',
            'Disallow: /prihlaseni',
            'Disallow: /login',
            'Disallow: /registrace',
            'Disallow: /register',
            'Disallow: /zapomenute-heslo',
            'Disallow: /forgot-password',
            '',
            '# Disallow user dashboard',
            'Disallow: /nastenka/',
            'Disallow: /dashboard/',
            '',
            '# Disallow checkout process',
            'Disallow: /pokladna',
            'Disallow: /checkout',
            'Disallow: /kosik',
            'Disallow: /cart',
            '',
            '# Disallow technical endpoints',
            'Disallow: /webhook/',
            'Disallow: /api/',
            '',
            '# Disallow feeds (not for search crawlers)',
            'Disallow: /feed/',
            '',
            '# Sitemap',
            'Sitemap: ' . $baseUrl . '/sitemap.xml',
        ];

        return implode("\n", $lines);
    }

    /**
     * Get base URL based on host
     */
    private function getBaseUrl(string $host): string
    {
        $isEur = str_contains($host, 'kavibox.com') || str_contains($host, 'kavibox.local');
        return $isEur ? 'https://kavibox.com' : 'https://kavi.cz';
    }
}
