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
        $redirect = safeRedirectPath($request->input('redirect'));

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
                Mail::to($email)->send(new MagicLoginLink($loginUrl, 15, app()->getLocale()));
            } catch (\Exception $e) {
                \Log::error('Failed to send magic link email: ' . $e->getMessage());
                return back()->with('error', __('flash.auth.email_send_failed'));
            }
        }

        return back()->with('success', __('flash.auth.magic_link_sent'));
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
            return redirect(localizedRoute('login'))
                ->with('error', __('flash.auth.magic_link_invalid'));
        }

        // Find user
        $user = User::where('email', $loginToken->email)->first();

        if (!$user) {
            return redirect(localizedRoute('login'))
                ->with('error', __('flash.auth.user_not_found'));
        }

        // Mark token as used
        DB::table('login_tokens')
            ->where('token', $hashedToken)
            ->update(['used_at' => now()]);

        // Log user in
        Auth::login($user);
        $request->session()->regenerate();

        // Check if there's a redirect parameter (only safe internal paths allowed)
        $redirect = safeRedirectPath($request->input('redirect'));

        if ($redirect) {
            return redirect($redirect)->with('success', __('flash.auth.login_success'));
        }

        // Default redirect to dashboard
        return redirect()->intended(localizedRoute('dashboard.index'))
            ->with('success', __('flash.auth.magic_link_login_success'));
    }

    /**
     * Show request magic link form (optional - can be integrated into login page)
     */
    public function showRequestForm()
    {
        return view('auth.magic-link');
    }
}
