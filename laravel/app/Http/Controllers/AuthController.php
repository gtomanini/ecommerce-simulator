<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        AuditService::logAction(
            $user,
            'user_registered',
            "User '{$user->name}' registered with email {$user->email}",
            'User',
            $user->id
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * One-click "guest" checkout: log in as a single shared guest account
     * (created on first use). Every guest purchase is attributed to this
     * account, which keeps product-sales tracking intact without forcing
     * anyone to register.
     */
    public function guest(Request $request)
    {
        $user = User::firstOrCreate(
            ['email' => 'guest@shopsim.local'],
            ['name' => 'Guest Shopper', 'password' => Hash::make(Str::random(40))]
        );

        // Start the guest with a clean cart.
        $cart = Cart::where('user_id', $user->id)->first();
        if ($cart) {
            $cart->items()->delete();
        }

        AuditService::logAction(
            $user,
            'guest_checkout',
            'A guest started shopping without an account',
            'User',
            $user->id
        );

        $token = $user->createToken('guest_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        AuditService::logAction(
            $user,
            'user_login',
            "User '{$user->name}' logged in",
            'User',
            $user->id
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        AuditService::logAction(
            $user,
            'user_logout',
            "User '{$user->name}' logged out",
            'User',
            $user->id
        );

        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
