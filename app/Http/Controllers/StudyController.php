<?php

namespace App\Http\Controllers;

use App\Models\Study;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class StudyController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/people/{person_id}/studies",
     * tags={"Estudios"},
     * summary="Obtener una lista de estudios para una persona",
     * @OA\Parameter(
     * name="person_id",
     * in="path",
     * required=true,
     * description="ID de la persona",
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Operación exitosa",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/Study")
     * )
     * )
     * )
     */
    public function index(Person $person)
    {
        return $person->studies;
    }

    /**
     * @OA\Post(
     * path="/api/people/{person_id}/studies",
     * tags={"Estudios"},
     * summary="Crear un nuevo estudio para una persona",
     * @OA\Parameter(
     * name="person_id",
     * in="path",
     * required=true,
     * description="ID de la persona",
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Datos del estudio",
     * @OA\JsonContent(
     * required={"institution", "degree", "level"},
     * @OA\Property(property="institution", type="string", example="INA"),
     * @OA\Property(property="degree", type="string", example="Software Application Developer"),
     * @OA\Property(property="level", type="string", example="Técnico"),
     * @OA\Property(property="start_date", type="string", format="date", example="2016-01-01", nullable=true),
     * @OA\Property(property="end_date", type="string", format="date", example="2016-12-31", nullable=true),
     * @OA\Property(property="description", type="string", example="Descripción del curso", nullable=true)
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Estudio creado exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/Study")
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación"
     * )
     * )
     */
    public function store(Request $request, Person $person)
    {
        $validatedData = $request->validate([
            'institution' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $study = $person->studies()->create($validatedData);

        return response()->json($study, 201);
    }

    /**
     * @OA\Get(
     * path="/api/people/{person_id}/studies/{study_id}",
     * tags={"Estudios"},
     * summary="Obtener un estudio específico de una persona",
     * @OA\Parameter(
     * name="person_id",
     * in="path",
     * required=true,
     * description="ID de la persona",
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     * @OA\Parameter(
     * name="study_id",
     * in="path",
     * required=true,
     * description="ID del estudio",
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Operación exitosa",
     * @OA\JsonContent(ref="#/components/schemas/Study")
     * ),
     * @OA\Response(
     * response=404,
     * description="Estudio no encontrado"
     * )
     * )
     */
    public function show(Person $person, Study $study)
    {
        return response()->json($study);
    }

    /**
     * @OA\Put(
     * path="/api/people/{person_id}/studies/{study_id}",
     * tags={"Estudios"},
     * summary="Actualizar un estudio específico de una persona",
     * @OA\Parameter(
     * name="person_id",
     * in="path",
     * required=true,
     * description="ID de la persona",
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     * @OA\Parameter(
     * name="study_id",
     * in="path",
     * required=true,
     * description="ID del estudio",
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Datos del estudio a actualizar",
     * @OA\JsonContent(
     * @OA\Property(property="institution", type="string", example="INA"),
     * @OA\Property(property="degree", type="string", example="Software Application Developer"),
     * @OA\Property(property="level", type="string", example="Técnico"),
     * @OA\Property(property="start_date", type="string", format="date", example="2016-01-01", nullable=true),
     * @OA\Property(property="end_date", type="string", format="date", example="2016-12-31", nullable=true),
     * @OA\Property(property="description", type="string", example="Descripción del curso", nullable=true)
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Estudio actualizado exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/Study")
     * ),
     * @OA\Response(
     * response=404,
     * description="Estudio no encontrado"
     * )
     * )
     */
    public function update(Request $request, Person $person, Study $study)
    {
        $validatedData = $request->validate([
            'institution' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);
    
        $study->update($validatedData);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Estudio actualizado exitosamente.',
            'data' => $study
        ]);
    }
    
    /**
     * @OA\Delete(
     * path="/api/people/{person_id}/studies/{study_id}",
     * tags={"Estudios"},
     * summary="Eliminar un estudio específico de una persona",
     * @OA\Parameter(
     * name="person_id",
     * in="path",
     * required=true,
     * description="ID de la persona",
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     * @OA\Parameter(
     * name="study_id",
     * in="path",
     * required=true,
     * description="ID del estudio",
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Estudio eliminado exitosamente"
     * ),
     * @OA\Response(
     * response=404,
     * description="Estudio no encontrado"
     * )
     * )
     */
    public function destroy(Person $person, Study $study)
    {
        try {
            DB::beginTransaction();
    
            // Obtener la ruta de la carpeta del usuario
            $full_name_sanitized = str_replace(' ', '_', $person->first_name . '_' . $person->last_name);
            $folderPath = "public/uploads/{$person->id}-{$full_name_sanitized}/certificationImages";
    
            // Si el estudio tiene un archivo, lo eliminamos del storage
            if ($study->img_name) {
                Storage::delete("{$folderPath}/{$study->img_name}");
            }
    
            // Eliminar el registro de la base de datos
            $study->delete();
    
            DB::commit();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Estudio y archivo asociado eliminados exitosamente.'
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Hubo un error al eliminar el estudio.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     * path="/api/people/{person_id}/studies/{study_id}/upload-file",
     * tags={"Estudios"},
     * summary="Subir un archivo a un registro de estudio",
     * description="Sube un archivo (imagen de certificación, etc.) y lo asocia con un registro de estudio específico, renombrando el archivo con un formato único.",
     * @OA\Parameter(
     * name="person_id",
     * in="path",
     * required=true,
     * description="ID de la persona a la que pertenece el estudio",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Parameter(
     * name="study_id",
     * in="path",
     * required=true,
     * description="ID del registro de estudio",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Archivo a subir",
     * @OA\MediaType(
     * mediaType="multipart/form-data",
     * @OA\Schema(
     * @OA\Property(
     * property="file",
     * type="string",
     * format="binary",
     * description="El archivo a subir"
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Archivo subido exitosamente",
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="message", type="string", example="Archivo subido exitosamente."),
     * @OA\Property(property="path", type="string", example="public/uploads/1-john_doe/certificationImages/1_1_cert.jpg")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Registro de estudio o persona no encontrado"
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación o el archivo no fue subido"
     * )
     * )
     */
    public function storeFile(Request $request, Person $person, Study $study)
    {
        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpeg,png,jpg,gif,svg,pdf',
        ]);

        try {
            DB::beginTransaction();

            $file = $request->file('file');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $timestamp = Carbon::now()->format('YmdHis');

            // Formato: idPersona_idestudio_nombreDelArchivo_fechaHora.extension
            $fileName = "{$person->id}_{$study->id}_{$originalName}_{$timestamp}.{$extension}";

            $full_name_sanitized = str_replace(' ', '_', $person->first_name . '_' . $person->last_name);
            $folderPath = "public/uploads/{$person->id}-{$full_name_sanitized}/certificationImages";

            $path = $file->storeAs($folderPath, $fileName);
            $study->update(['img_name' => $fileName]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Archivo subido exitosamente.',
                'path' => Storage::url($path),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Hubo un error al subir el archivo.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
