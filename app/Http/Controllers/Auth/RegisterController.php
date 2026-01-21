<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validate reCAPTCHA if configured
        if (config('services.recaptcha.secret_key')) {
            $recaptchaToken = $request->input('recaptcha_token');
            
            if (!$recaptchaToken) {
                return back()->withErrors(['recaptcha' => 'Ověření reCAPTCHA selhalo. Zkuste to prosím znovu.'])->withInput();
            }
            
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $recaptchaToken,
                'remoteip' => $request->ip(),
            ]);
            
            $recaptchaData = $response->json();
            
            if (!$recaptchaData['success'] || $recaptchaData['score'] < config('services.recaptcha.min_score', 0.5)) {
                Log::warning('reCAPTCHA failed for registration', [
                    'ip' => $request->ip(),
                    'email' => $request->input('email'),
                    'score' => $recaptchaData['score'] ?? null,
                    'action' => $recaptchaData['action'] ?? null,
                ]);
                return back()->withErrors(['recaptcha' => 'Ověření reCAPTCHA selhalo. Zkuste to prosím znovu.'])->withInput();
            }
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'locale' => app()->getLocale(),
            'password' => Hash::make($validated['password']),
            'password_set_by_user' => true, // User explicitly set this password
        ]);

        Auth::login($user);

        // Send welcome email
        try {
            \Mail::to($user->email)->send(new \App\Mail\WelcomeEmail($user, app()->getLocale()));
            \Log::info('Welcome email sent', ['user_id' => $user->id, 'email' => $user->email]);
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            // Don't fail registration if email fails
        }

        return redirect()->to(localizedRoute('dashboard.index'));
    }
}




