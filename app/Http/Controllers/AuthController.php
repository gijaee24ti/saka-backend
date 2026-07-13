<?php

namespace App\Http\Controllers;

use App\Models\Rider;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function adminLogin(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $admin = User::where('email', $validated['email'])->first();

        if (! $admin || ! Hash::check($validated['password'], $admin->password)) {
            return response()->json(['message' => 'Email atau password admin salah.'], 401);
        }

        $admin->tokens()->delete();
        $token = $admin->createToken('admin-web', ['admin'], now()->addHours(12))->plainTextToken;

        return response()->json([
            'message' => 'Login admin berhasil.',
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => 43_200,
            'data' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => 'Admin',
            ],
        ]);
    }

    public function riderLogin(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $rider = Rider::with('outlet')->where('username', $validated['username'])->first();

        if (! $rider || ! Hash::check($validated['password'], $rider->password)) {
            return response()->json(['message' => 'Username atau password rider salah.'], 401);
        }

        if ($rider->account_status !== 'Aktif') {
            return response()->json(['message' => 'Akun rider tidak aktif.'], 403);
        }

        $rider->tokens()->delete();
        $token = $rider->createToken('rider-web', ['rider'], now()->addHours(12))->plainTextToken;

        return response()->json([
            'message' => 'Login rider berhasil.',
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => 43_200,
            'data' => $this->riderData($rider),
        ]);
    }

    public function adminProfile(Request $request): JsonResponse
    {
        $admin = $request->user();

        return response()->json(['data' => [
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'Admin',
        ]]);
    }

    public function riderProfile(Request $request): JsonResponse
    {
        return response()->json(['data' => $this->riderData($request->user()->load('outlet'))]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(['message' => 'Logout berhasil.']);
    }

    private function riderData(Rider $rider): array
    {
        return [
            'id' => $rider->id,
            'name' => $rider->name,
            'username' => $rider->username,
            'phone' => $rider->phone,
            'account_status' => $rider->account_status,
            'operational_status' => $rider->operational_status,
            'outlet' => $rider->outlet,
            'role' => 'Rider',
        ];
    }
}
