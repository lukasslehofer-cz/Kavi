<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter.
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Prosím, zadejte platnou e-mailovou adresu.'
            ], 422);
        }

        $email = $request->input('email');

        // Check if already subscribed
        $existing = NewsletterSubscriber::where('email', $email)->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Tento e-mail je již přihlášen k odběru novinek.'
            ], 422);
        }

        // Create new subscriber
        NewsletterSubscriber::create([
            'email' => $email,
            'source' => 'form',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Děkujeme! Váš e-mail byl úspěšně přihlášen k odběru novinek.'
        ]);
    }
}
