<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PersonController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/people",
     * operationId="getPeopleList",
     * tags={"Personas"},
     * summary="Obtener la lista de personas",
     * description="Devuelve una lista de todas las personas.",
     * @OA\Response(
     * response=200,
     * description="Operación exitosa",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/Person")
     * )
     * )
     * )
     */
    public function index()
    {
        return Person::all();
    }

    /**
     * @OA\Post(
     * path="/api/people",
     * operationId="storePerson",
     * tags={"Personas"},
     * summary="Crear una nueva persona",
     * description="Crea un nuevo registro de persona en la base de datos y su estructura de carpetas asociada.",
     * @OA\RequestBody(
     * required=true,
     * description="Datos de la persona",
     * @OA\JsonContent(
     * required={"first_name", "last_name"},
     * @OA\Property(property="first_name", type="string", example="Edgar Fabian"),
     * @OA\Property(property="last_name", type="string", example="Gonzalez Perez"),
     * @OA\Property(property="date_of_birth", type="string", format="date", example="1990-01-01"),
     * @OA\Property(property="id_card", type="string", example="12345678-9"),
     * @OA\Property(property="phone_number", type="string", example="+506 88887777"),
     * @OA\Property(property="address", type="string", example="123 Main St, Anytown"),
     * @OA\Property(property="email", type="string", format="email", example="jane.doe@example.com"),
     * @OA\Property(property="linkedin_url", type="string", format="url", example="https://www.linkedin.com/in/janedoe"),
     * @OA\Property(property="facebook_url", type="string", format="url", example="https://www.facebook.com/janedoe"),
     * @OA\Property(property="indeed_url", type="string", format="url", example="https://www.indeed.com/profiles/janedoe")
     * ),
     * ),
     * @OA\Response(
     * response=201,
     * description="Persona creada exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/Person")
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación"
     * )
     * )
     * )
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'date_of_birth' => 'nullable|date',
                'id_card' => 'nullable|string',
                'phone_number' => 'nullable|string',
                'address' => 'nullable|string',
                'email' => 'nullable|email|unique:people,email',
                'linkedin_url' => 'nullable|url',
                'facebook_url' => 'nullable|url',
                'indeed_url' => 'nullable|url',
            ]);

            DB::beginTransaction();
            $person = Person::create($request->all());

            $subfolders = ['ProfilePhotos', 'CertificationImages', 'Docs'];
            $full_name = str_replace(' ', '_', $person->first_name . '_' . $person->last_name);
            $folderName = $person->id . '-' . $full_name;
            $basePath = "public/uploads/{$folderName}";
            foreach ($subfolders as $subfolder) {
                Storage::makeDirectory("{$basePath}/{$subfolder}");
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Persona creada exitosamente y carpetas de archivos generadas.',
                'data' => $person
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error de validación.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Hubo un error al crear la persona.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/people/{id}",
     * operationId="getPersonById",
     * tags={"Personas"},
     * summary="Obtener una persona por su ID",
     * description="Devuelve los detalles de una persona específica.",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID de la persona a obtener",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Operación exitosa",
     * @OA\JsonContent(ref="#/components/schemas/Person")
     * ),
     * @OA\Response(
     * response=404,
     * description="Persona no encontrada"
     * )
     * )
     * )
     */
    public function show(Person $person)
    {
        return response()->json($person);
    }

    /**
     * @OA\Put(
     * path="/api/people/{id}",
     * operationId="updatePerson",
     * tags={"Personas"},
     * summary="Actualizar una persona",
     * description="Actualiza un registro de persona existente y renombra su carpeta si el nombre ha cambiado.",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID de la persona a actualizar",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Datos de la persona a actualizar",
     * @OA\JsonContent(
     * @OA\Property(property="first_name", type="string", example="Edgar Fabian"),
     * @OA\Property(property="last_name", type="string", example="Gonzalez Perez"),
     * @OA\Property(property="date_of_birth", type="string", format="date", example="1990-01-01"),
     * @OA\Property(property="id_card", type="string", example="12345678-9"),
     * @OA\Property(property="phone_number", type="string", example="+506 88887777"),
     * @OA\Property(property="address", type="string", example="123 Main St, Anytown"),
     * @OA\Property(property="email", type="string", format="email", example="jane.doe@example.com"),
     * @OA\Property(property="linkedin_url", type="string", format="url", example="https://www.linkedin.com/in/janedoe"),
     * @OA\Property(property="facebook_url", type="string", format="url", example="https://www.facebook.com/janedoe"),
     * @OA\Property(property="indeed_url", type="string", format="url", example="https://www.indeed.com/profiles/janedoe")
     * ),
     * ),
     * @OA\Response(
     * response=200,
     * description="Persona actualizada exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/Person")
     * ),
     * @OA\Response(
     * response=404,
     * description="Persona no encontrada"
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación"
     * )
     * )
     * )
     */
    public function update(Request $request, Person $person)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'date_of_birth' => 'nullable|date',
                'id_card' => 'nullable|string',
                'phone_number' => 'nullable|string',
                'address' => 'nullable|string',
                'email' => ['nullable', 'email', Rule::unique('people')->ignore($person->id)],
                'linkedin_url' => 'nullable|url',
                'facebook_url' => 'nullable|url',
                'indeed_url' => 'nullable|url',
            ]);

            DB::beginTransaction();

            $oldFolderName = $person->id . '-' . str_replace(' ', '_', $person->first_name . '_' . $person->last_name);
            $person->update($request->all());
            $newFolderName = $person->id . '-' . str_replace(' ', '_', $person->first_name . '_' . $person->last_name);

            // Renombrar la carpeta si el nombre completo ha cambiado
            if ($oldFolderName !== $newFolderName) {
                Storage::move("public/uploads/{$oldFolderName}", "public/uploads/{$newFolderName}");
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Persona actualizada exitosamente.',
                'data' => $person
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error de validación.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Hubo un error al actualizar la persona.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     * path="/api/people/{id}",
     * operationId="deletePerson",
     * tags={"Personas"},
     * summary="Eliminar una persona",
     * description="Elimina una persona y su estructura de carpetas asociada.",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID de la persona a eliminar",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Persona eliminada exitosamente"
     * ),
     * @OA\Response(
     * response=404,
     * description="Persona no encontrada"
     * )
     * )
     * )
     */
    public function destroy(Person $person)
    {
        try {
            DB::beginTransaction();

            $folderName = $person->id . '-' . str_replace(' ', '_', $person->first_name . '_' . $person->last_name);
            $person->delete();

            // Eliminar la carpeta completa asociada a la persona
            Storage::deleteDirectory("public/uploads/{$folderName}");

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Persona y archivos eliminados exitosamente.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Hubo un error al eliminar la persona.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
