<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends BaseController
{
    public function redirect(string $provider): RedirectResponse
    {
        if (! in_array($provider, ['facebook', 'gmail'])) {
            return redirect()->route('login')
                ->withErrors('An unexpected error occurred.');
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        if (! in_array($provider, ['facebook', 'gmail'])) {
            return redirect()->route('login')
                ->withErrors('An unexpected error occurred.');
        }

        try {
            $user = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            return redirect()->route('login')
                ->withErrors('An unexpected error occurred.');
        }

        if (! $user->getEmail()) {
            return redirect()->route('login')
                ->withErrors('The email address is invalid.');
        }

        if ($exists = User::where('email', $user->getEmail())->first()) {
            if ($exists->oauth_provider !== $provider || (string) $exists->oauth_id !== (string) $user->getId()) {
                return redirect()->route('login')
                    ->withErrors('The email address is already associated with another account.');
            }
        }

        User::unguard();


        $resource = User::firstOrCreate(
            [
                // 'oauth_provider' => $provider,
                // 'oauth_id' => $user->getId(),
                'email' => $user->getEmail(),
            ],
            [
                'oauth_provider' => $provider,
                'oauth_id' => $user->getId(),

                'nickname' => $user->getNickname(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'avatar' => $user->getAvatar(),

                'email_verified_at' => now(),
                // 'remember_token' => Str::random(10),
                'password' => bcrypt(Str::random(32)),

                'extra_attributes' => [
                    'oauth' => Crypt::encrypt([
                        'user' => $user,

                        'token' => $user->token ?? null,
                        'refreshToken' => $user->refreshToken ?? null,
                        'expiresIn' => $user->expiresIn ?? null,
                    ]),
                ],
            ],
        );

        User::reguard();

        Auth::login($resource, true);

        return redirect()->route('dashboard');
    }
}