<?php

namespace App\Http\Controllers\api;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\User;
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
            'father_name' => 'required|string|max:50',
            'mother_name' => 'nullable|string|max:50',
            'birthday' => 'date',
            'email' => 'required|string|lowercase|email|unique:' . User::class,
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'names' => $data['names'],
            'father_name' => $data['father_name'],
            'mother_name' => $data['mother_name'],
            'birthday' => $data['birthday'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        event(new Registered($user));

        // Opcional: auto-login tras registro
        /*$token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 201);*/

        return ApiResponse::ok('Usuario registrado correctamente.', 201);
    }

    public function profile()
    {
        $user = Auth::user();
        return ApiResponse::ok($user, 201);
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
