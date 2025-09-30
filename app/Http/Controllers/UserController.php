<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/users",
     * summary="Obtener la lista de usuarios",
     * tags={"Usuarios"},
     * @OA\Response(
     * response=200,
     * description="Operación exitosa",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer"),
     * @OA\Property(property="name", type="string"),
     * @OA\Property(property="email", type="string"),
     * @OA\Property(property="created_at", type="string", format="date-time"),
     * @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     * )
     * )
     * )
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    /**
     * @OA\Post(
     * path="/api/users",
     * summary="Crear un nuevo usuario",
     * tags={"Usuarios"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="name", type="string", example="Edgar"),
     * @OA\Property(property="email", type="string", format="email", example="edgar@gmail.com"),
     * @OA\Property(property="password", type="string", format="password", example="12345678")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Usuario creado exitosamente",
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer"),
     * @OA\Property(property="name", type="string"),
     * @OA\Property(property="email", type="string")
     * )
     * )
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        return response()->json($user, 201);
    }

    /**
     * @OA\Get(
     * path="/api/users/{id}",
     * summary="Obtener un usuario específico",
     * tags={"Usuarios"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del usuario",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Operación exitosa",
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer"),
     * @OA\Property(property="name", type="string"),
     * @OA\Property(property="email", type="string")
     * )
     * )
     * )
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * @OA\Put(
     * path="/api/users/{id}",
     * summary="Actualizar un usuario existente",
     * tags={"Usuarios"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del usuario a actualizar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="name", type="string", example="Edgar Actualizado"),
     * @OA\Property(property="email", type="string", format="email", example="edgar.update@gmail.com")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Usuario actualizado exitosamente",
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer"),
     * @OA\Property(property="name", type="string"),
     * @OA\Property(property="email", type="string")
     * )
     * )
     * )
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|string|min:8',
        ]);

        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }
    
        $user->update($validatedData);

        return response()->json($user);
    }

    /**
     * @OA\Delete(
     * path="/api/users/{id}",
     * summary="Eliminar un usuario",
     * tags={"Usuarios"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del usuario a eliminar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=204,
     * description="Usuario eliminado exitosamente"
     * )
     * )
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(null, 204);
    }
}