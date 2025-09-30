<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/roles",
     * summary="Obtener la lista de roles",
     * tags={"Roles"},
     * @OA\Response(
     * response=200,
     * description="Operación exitosa",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer"),
     * @OA\Property(property="rol", type="string")
     * )
     * )
     * )
     * )
     */
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    /**
     * @OA\Post(
     * path="/api/roles",
     * summary="Crear un nuevo rol",
     * tags={"Roles"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="rol", type="string", example="admin")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Rol creado exitosamente",
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer"),
     * @OA\Property(property="rol", type="string")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validación de datos fallida"
     * )
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'rol' => 'required|string|max:255|unique:roles',
        ]);
        
        $role = Role::create($validatedData);

        return response()->json($role, 201);
    }

    /**
     * @OA\Get(
     * path="/api/roles/{id}",
     * summary="Obtener un rol específico",
     * tags={"Roles"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del rol",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Operación exitosa",
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer"),
     * @OA\Property(property="rol", type="string")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Rol no encontrado"
     * )
     * )
     */
    public function show(Role $role)
    {
        return response()->json($role);
    }

    /**
     * @OA\Put(
     * path="/api/roles/{id}",
     * summary="Actualizar un rol existente",
     * tags={"Roles"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del rol a actualizar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="rol", type="string", example="editor")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Rol actualizado exitosamente",
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer"),
     * @OA\Property(property="rol", type="string")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Rol no encontrado"
     * ),
     * @OA\Response(
     * response=422,
     * description="Validación de datos fallida"
     * )
     * )
     */
    public function update(Request $request, Role $role)
    {
        $validatedData = $request->validate([
            'rol' => 'sometimes|required|string|max:255|unique:roles,rol,'.$role->id,
        ]);

        $role->update($validatedData);

        return response()->json($role);
    }

    /**
     * @OA\Delete(
     * path="/api/roles/{id}",
     * summary="Eliminar un rol",
     * tags={"Roles"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del rol a eliminar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=204,
     * description="Rol eliminado exitosamente"
     * ),
     * @OA\Response(
     * response=404,
     * description="Rol no encontrado"
     * )
     * )
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json(null, 204);
    }
}