<?php

namespace App\Services;

use App\Exceptions\LoginInvalidException;
use App\Exceptions\UserHasBeenTakenException;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * Class used to handle auth situations
 */
class AuthService
{
    /**
     * Handle registration
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string $role
     * @return App\Models\User
     * @throws App\Exceptions\UserHasBeenTakenException
     */
    public function register(string $name, string $email, string $password, string $role) : User
    {
        $user = User::where('email', $email)->exists();

        if (!empty($user))
        {
            throw new UserHasBeenTakenException();
        }

        $user_password = bcrypt($password ?? Str::random(10));

        $user = User::create([
            'name'       => $name,
            'email'      => $email,
            'password'   => $user_password,
            'role'       => $role,
            'confirmation_token' => Str::random(60),
        ]);

        return $user;
    }

    /**
     * Handle login
     * @param string $email
     * @param string $password
     * @return array
     * @throws App\Exceptions\LoginInvalidException
     */
    public function login(string $email, string $password) : array
    {
        $login = [
            'email'    => $email,
            'password' => $password,
        ];

        if (!$token = auth()->attempt($login))
        {
            throw new LoginInvalidException();
        }

        return [
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => auth()->factory()->getTTL()
        ];
    }
}
