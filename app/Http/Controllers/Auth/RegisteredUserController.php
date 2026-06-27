<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeUser;
use App\Models\Stuff;
use App\Models\Whistleblower;
use App\Support\PhoneNumber;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'phone_number' => PhoneNumber::stripSpaces((string) $request->input('phone_number', '')),
        ]);

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Stuff::class],
            'phone_number' => PhoneNumber::validationRules(),
            'password'     => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'phone_number.required' => 'A phone number is required for SMS tracking updates.',
            'phone_number.regex'    => 'Enter a valid Ugandan mobile number (e.g. 0712345678 or +256712345678).',
        ]);

        $validated['phone_number'] = PhoneNumber::normalize($validated['phone_number']);

        $nameParts = preg_split('/\s+/', trim($validated['name']), 2);
        $firstName = $nameParts[0];
        $lastName  = $nameParts[1] ?? $nameParts[0];

        $user = DB::transaction(function () use ($validated, $firstName, $lastName) {
            $stuff = Stuff::create([
                'email'     => $validated['email'],
                'password'  => $validated['password'],
                'role'      => 'Whistleblower',
                'is_active' => true,
            ]);

            Whistleblower::create([
                'stuff_id'          => $stuff->stuff_id,
                'first_name'        => $firstName,
                'last_name'         => $lastName,
                'phone_number'      => $validated['phone_number'],
                'registration_date' => now()->toDateString(),
                'is_anonymous'      => false,
            ]);

            return $stuff;
        });

        event(new Registered($user));

        $user->load('whistleblowerProfile');

        $emailSent = false;

        try {
            Mail::to($user->email)->send(new WelcomeUser($user));
            $emailSent = true;
        } catch (\Throwable $e) {
            Log::error('Welcome email failed after registration.', [
                'stuff_id' => $user->stuff_id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
        }

        $message = $emailSent
            ? 'Account created successfully! Please sign in. A welcome email has been sent to your inbox.'
            : 'Account created successfully! Please sign in.';

        return redirect()->route('login')->with('success', $message);
    }
}
