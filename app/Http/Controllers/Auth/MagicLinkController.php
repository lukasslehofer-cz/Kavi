<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\MagicLoginLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MagicLinkController extends Controller
{
    /**
     * Send magic login link to user's email
     */
    public function sendLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'redirect' => 'nullable|string',
        ]);

        $email = $request->email;
        $redirect = $request->input('redirect');

        // Check if user exists
        $user = User::where('email', $email)->first();

        // For security, always show success message even if user doesn't exist
        // This prevents email enumeration attacks
        
        if ($user) {
            // Generate unique token
            $token = Str::random(64);
            $expiresAt = now()->addMinutes(15);

            // Store token in database
            DB::table('login_tokens')->insert([
                'email' => $email,
                'token' => hash('sha256', $token), // Hash for security
                'expires_at' => $expiresAt,
                'created_at' => now(),
            ]);

            // Generate magic link URL with redirect parameter if provided
            $loginUrl = route('magic-link.verify', ['token' => $token]);
            if ($redirect) {
                $loginUrl .= '?redirect=' . urlencode($redirect);
            }

            // Send email with magic link
            try {
                Mail::to($email)->send(new MagicLoginLink($loginUrl, 15));
            } catch (\Exception $e) {
                \Log::error('Failed to send magic link email: ' . $e->getMessage());
                return back()->with('error', 'Nepodařilo se odeslat email. Zkuste to prosím znovu.');
            }
        }

        return back()->with('success', 'Pokud účet s tímto emailem existuje, byl na něj odeslán přihlašovací odkaz. Platnost odkazu je 15 minut.');
    }

    /**
     * Verify magic link token and log user in
     */
    public function verify(Request $request, $token)
    {
        // Hash the token to match database
        $hashedToken = hash('sha256', $token);

        // Find valid token
        $loginToken = DB::table('login_tokens')
            ->where('token', $hashedToken)
            ->where('expires_at', '>', now())
            ->whereNull('used_at')
            ->first();

        if (!$loginToken) {
            return redirect()->route('login')
                ->with('error', 'Přihlašovací odkaz je neplatný nebo vypršel. Požádejte o nový odkaz.');
        }

        // Find user
        $user = User::where('email', $loginToken->email)->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Uživatel nebyl nalezen.');
        }

        // Mark token as used
        DB::table('login_tokens')
            ->where('token', $hashedToken)
            ->update(['used_at' => now()]);

        // Log user in
        Auth::login($user);
        $request->session()->regenerate();

        // Check if there's a redirect parameter
        $redirect = $request->input('redirect');
        
        if ($redirect && filter_var($redirect, FILTER_VALIDATE_URL) === false) {
            // If it's a relative path, redirect to it
            return redirect($redirect)->with('success', 'Byli jste úspěšně přihlášeni!');
        }

        // Default redirect to dashboard
        return redirect()->intended(route('dashboard.index'))
            ->with('success', 'Byli jste úspěšně přihlášeni pomocí magic linku!');
    }

    /**
     * Show request magic link form (optional - can be integrated into login page)
     */
    public function showRequestForm()
    {
        return view('auth.magic-link');
    }
}
