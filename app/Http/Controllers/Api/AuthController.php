<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{

    /**
     * @throws InvalidCredentialsException
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|lowercase|email',
            'password' => ['required', Password::defaults()],
        ]);

        if (!Auth::attempt($credentials)) {
            throw new InvalidCredentialsException();
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
        return ApiResponse::login($user, $token);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'names' => 'required|string|max:100',
            'father_surname' => 'required|string|max:50',
            'mother_surname' => 'nullable|string|max:50',
            'birthday' => 'date',
            'email' => 'required|string|lowercase|email|unique:' . User::class,
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'names' => $data['names'],
            'father_surname' => $data['father_surname'],
            'mother_surname' => $data['mother_surname'],
            'birthday' => $data['birthday'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        event(new Registered($user));

        return ApiResponse::ok('Usuario registrado correctamente.', 201);
    }

    public function profile()
    {
        $user = Auth::user();
        return ApiResponse::ok($user);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return ApiResponse::ok('Sesión cerrada correctamente.');
    }

    public function logoutAll()
    {
        $user = Auth::user();
        // Revoca todos los tokens emitidos a este usuario (cierre global)
        $user->tokens()->delete();
        return ApiResponse::ok('Sesiones cerradas en todos los dispositivos.');
    }

}
