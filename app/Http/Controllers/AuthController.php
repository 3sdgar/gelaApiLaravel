<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/login",
     * tags={"Autenticación"},
     * summary="Inicia sesión y genera un token de acceso",
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"email", "password"},
     * @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     * @OA\Property(property="password", type="string", format="password", example="password123")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Inicio de sesión exitoso",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Inicio de sesión exitoso"),
     * @OA\Property(property="token", type="string", example="2|G89r9bX6j1V4r8s5x7f9a2e3n5m8t0a1h5c7d1e0")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Credenciales inválidas"
     * )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'token' => $token
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/logout",
     * tags={"Autenticación"},
     * summary="Cierra sesión del usuario actual",
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=200,
     * description="Sesión cerrada exitosamente",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Sesión cerrada exitosamente")
     * )
     * )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }
}
