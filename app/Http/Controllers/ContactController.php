<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Send contact form message
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'nullable|string|max:2000',
        ]);

        try {
            // Send email to admin - use info@kavi.cz as recipient
            $adminEmail = 'info@kavi.cz';
            
            // Data for the email template
            $emailData = [
                'customerName' => $validated['name'],
                'customerEmail' => $validated['email'],
                'customerMessage' => $validated['message'] ?? '',
            ];
            
            Mail::send('emails.contact', $emailData, function ($message) use ($validated, $adminEmail) {
                $message->to($adminEmail)
                    ->subject('Nový dotaz z kontaktního formuláře - KAVI')
                    ->replyTo($validated['email'], $validated['name']);
            });

            // Log the contact form submission
            Log::info('Contact form submitted', [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Děkujeme! Vaše zpráva byla odeslána. Ozveme se vám co nejdříve.',
            ]);

        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Omlouváme se, ale nepodařilo se odeslat zprávu. Zkuste nás prosím kontaktovat přímo na info@kavi.cz.',
            ], 500);
        }
    }
}

