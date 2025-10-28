<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FakturoidListNumberFormats extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'fakturoid:list-number-formats';

    /**
     * The console command description.
     */
    protected $description = 'List all available number formats (číselné řady) from Fakturoid';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $slug = config('services.fakturoid.slug');
        $clientId = config('services.fakturoid.client_id');
        $clientSecret = config('services.fakturoid.client_secret');
        $userAgent = config('services.fakturoid.user_agent', 'Kavi (info@kavi.cz)');

        if (!$slug || !$clientId || !$clientSecret) {
            $this->error('Fakturoid credentials are not configured in .env file.');
            $this->info('Please set: FAKTUROID_SLUG, FAKTUROID_CLIENT_ID, FAKTUROID_CLIENT_SECRET');
            return 1;
        }

        $this->info('Authenticating with Fakturoid...');

        try {
            // Get OAuth token
            $tokenResponse = Http::withBasicAuth($clientId, $clientSecret)
                ->withHeaders([
                    'User-Agent' => $userAgent,
                    'Accept' => 'application/json',
                ])
                ->asJson()
                ->post('https://app.fakturoid.cz/api/v3/oauth/token', [
                    'grant_type' => 'client_credentials',
                ]);

            if (!$tokenResponse->successful()) {
                $this->error('Failed to authenticate with Fakturoid.');
                $this->error('Status: ' . $tokenResponse->status());
                return 1;
            }

            $token = $tokenResponse->json()['access_token'];
            
            $this->info('Fetching number formats...');
            $this->newLine();

            $response = Http::withToken($token)
                ->withHeaders(['User-Agent' => $userAgent])
                ->get("https://app.fakturoid.cz/api/v3/accounts/{$slug}/number_formats.json");

            if (!$response->successful()) {
                if ($response->status() === 404) {
                    $this->warn('Number formats endpoint is not available with your credentials.');
                    $this->info('This is OK - you can still use Fakturoid integration.');
                    $this->newLine();
                    $this->info('To set number format ID:');
                    $this->line('1. Go to Fakturoid: Settings → Number Formats');
                    $this->line('2. Click on your format and check ID in URL');
                    $this->line('3. Add to .env: FAKTUROID_NUMBER_FORMAT=<ID>');
                    return 0;
                }
                $this->error('Failed to fetch number formats from Fakturoid.');
                $this->error('Status: ' . $response->status());
                $this->error('Response: ' . $response->body());
                return 1;
            }

            $formats = $response->json();

            if (empty($formats)) {
                $this->warn('No number formats found in your Fakturoid account.');
                return 0;
            }

            $this->info('Available number formats (číselné řady):');
            $this->newLine();

            $tableData = [];
            foreach ($formats as $format) {
                $tableData[] = [
                    'ID' => $format['id'],
                    'Name' => $format['name'] ?? 'N/A',
                    'Format' => $format['format'] ?? 'N/A',
                    'Document Type' => $format['document_type'] ?? 'N/A',
                ];
            }

            $this->table(
                ['ID', 'Name', 'Format', 'Document Type'],
                $tableData
            );

            $this->newLine();
            $this->info('To use a specific number format, add this to your .env file:');
            $this->line('FAKTUROID_NUMBER_FORMAT=<ID>');
            $this->newLine();
            $this->comment('Example: FAKTUROID_NUMBER_FORMAT=' . ($formats[0]['id'] ?? '1284290'));

            return 0;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}

