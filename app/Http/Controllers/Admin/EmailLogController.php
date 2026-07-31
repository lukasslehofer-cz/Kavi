<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailLogController extends Controller
{
    /**
     * Display a listing of email logs
     */
    public function index(Request $request)
    {
        $query = EmailLog::with(['user', 'order', 'subscription']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by mailable type
        if ($request->has('mailable_type') && $request->mailable_type !== 'all') {
            $query->where('mailable_class', 'like', '%' . $request->mailable_type . '%');
        }

        // Filter by region
        if ($request->has('region') && $request->region !== 'all') {
            $query->where('region', $request->region);
        }

        // Search by recipient email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('recipient', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->where('sent_at', '>=', $request->date_from . ' 00:00:00');
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->where('sent_at', '<=', $request->date_to . ' 23:59:59');
        }

        $emailLogs = $query->orderBy('sent_at', 'desc')->paginate(50);

        // Get unique mailable classes for filter dropdown
        $mailableTypes = EmailLog::select('mailable_class')
            ->distinct()
            ->pluck('mailable_class')
            ->map(function ($class) {
                return [
                    'value' => class_basename($class),
                    'label' => preg_replace('/(?<!^)[A-Z]/', ' $0', class_basename($class)),
                ];
            })
            ->sortBy('label');

        // Statistics
        $stats = [
            'total' => EmailLog::count(),
            'sent' => EmailLog::where('status', 'sent')->count(),
            'failed' => EmailLog::where('status', 'failed')->count(),
            'today' => EmailLog::whereDate('sent_at', today())->count(),
            'this_week' => EmailLog::whereBetween('sent_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        return view('admin.email-logs.index', compact('emailLogs', 'mailableTypes', 'stats'));
    }

    /**
     * Display the specified email log
     */
    public function show(EmailLog $emailLog)
    {
        $emailLog->load(['user', 'order', 'subscription']);
        
        return view('admin.email-logs.show', compact('emailLog'));
    }

    /**
     * Resend an email
     */
    public function resend(EmailLog $emailLog)
    {
        try {
            $mailableClass = $emailLog->mailable_class;

            // Check if mailable class exists
            if (!class_exists($mailableClass)) {
                return back()->with('error', 'Email třída neexistuje: ' . $mailableClass);
            }

            // Reconstruct the mailable instance
            $mailable = $this->reconstructMailable($emailLog);

            if (!$mailable) {
                return back()->with('error', 'Tento typ e-mailu nelze znovu odeslat z administrace – potřebuje víc dat, než je v logu. Použij příslušný artisan příkaz.');
            }

            // Send the email
            Mail::to($emailLog->recipient)->send($mailable);

            return back()->with('success', 'Email byl úspěšně znovu odeslán na ' . $emailLog->recipient);

        } catch (\Exception $e) {
            Log::error('Failed to resend email', [
                'email_log_id' => $emailLog->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Nepodařilo se znovu odeslat email: ' . $e->getMessage());
        }
    }

    /**
     * Reconstruct a mailable instance from email log
     *
     * Funguje jen pro mailable, které se dají poskládat z jednoho modelu.
     * Cokoliv s dalšími povinnými argumenty (platba, odměna, tělo zprávy)
     * se znovu odeslat nedá – radši vrátíme null, než abychom poslali mail
     * s podstrčenými daty.
     */
    private function reconstructMailable(EmailLog $emailLog)
    {
        $mailableClass = $emailLog->mailable_class;

        if (! $this->isSingleModelMailable($mailableClass)) {
            return null;
        }

        // Try to reconstruct based on what we have
        if ($emailLog->order_id && $emailLog->order) {
            return new $mailableClass($emailLog->order);
        }

        if ($emailLog->subscription_id && $emailLog->subscription) {
            return new $mailableClass($emailLog->subscription);
        }

        if ($emailLog->user_id && $emailLog->user) {
            // Some mailables might just need a user
            return new $mailableClass($emailLog->user);
        }

        return null;
    }

    /**
     * Má mailable jen jeden povinný argument konstruktoru (model)?
     */
    private function isSingleModelMailable(string $mailableClass): bool
    {
        try {
            $constructor = (new \ReflectionClass($mailableClass))->getConstructor();
        } catch (\ReflectionException $e) {
            return false;
        }

        if (! $constructor) {
            return false;
        }

        return $constructor->getNumberOfRequiredParameters() <= 1;
    }
}
