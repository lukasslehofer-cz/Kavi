<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminNewsletterController extends Controller
{
    /**
     * Display a listing of newsletter subscribers.
     */
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::query()->with('user');

        // Filter by source
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        // Search by email
        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $query->orderBy($sortBy, $sortOrder);

        $subscribers = $query->paginate(50);

        // Stats
        $stats = [
            'total' => NewsletterSubscriber::count(),
            'from_form' => NewsletterSubscriber::where('source', 'form')->count(),
            'from_customers' => NewsletterSubscriber::where('source', 'customer')->count(),
            'this_month' => NewsletterSubscriber::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return view('admin.newsletter.index', compact('subscribers', 'stats'));
    }

    /**
     * Remove the specified subscriber from storage.
     */
    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();

        return redirect()->route('admin.newsletter.index')
            ->with('success', 'Přihlášení k newsletteru bylo odstraněno.');
    }

    /**
     * Sync customer emails to newsletter.
     */
    public function syncCustomers()
    {
        // Get all unique customer emails from users who have made orders or subscriptions
        $customerEmails = User::whereHas('orders')
            ->orWhereHas('subscriptions')
            ->pluck('email')
            ->unique();

        $synced = 0;

        foreach ($customerEmails as $email) {
            $user = User::where('email', $email)->first();
            
            // Check if not already in newsletter
            $existing = NewsletterSubscriber::where('email', $email)->first();
            
            if (!$existing) {
                NewsletterSubscriber::create([
                    'email' => $email,
                    'source' => 'customer',
                    'user_id' => $user?->id,
                ]);
                $synced++;
            } elseif ($existing->source === 'form' && $user) {
                // Update existing form subscriber to customer if they became a customer
                $existing->update([
                    'source' => 'customer',
                    'user_id' => $user->id,
                ]);
                $synced++;
            }
        }

        return redirect()->route('admin.newsletter.index')
            ->with('success', "Synchronizováno {$synced} zákaznických emailů.");
    }

    /**
     * Export newsletter subscribers.
     */
    public function export(Request $request)
    {
        $query = NewsletterSubscriber::query();

        // Filter by source if requested
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        $subscribers = $query->orderBy('created_at', 'desc')->get();

        $filename = 'newsletter-subscribers-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($subscribers) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, ['Email', 'Zdroj', 'Datum registrace'], ';');

            // Data
            foreach ($subscribers as $subscriber) {
                fputcsv($file, [
                    $subscriber->email,
                    $subscriber->source === 'customer' ? 'Zákazník' : 'Formulář',
                    $subscriber->created_at->format('d.m.Y H:i'),
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
