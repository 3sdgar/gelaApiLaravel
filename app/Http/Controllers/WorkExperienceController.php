<?php

namespace App\Http\Controllers;

use App\Models\WorkExperience;
use App\Models\Person;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class WorkExperienceController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/people/{person_id}/work-experiences",
     * tags={"Experiencia Laboral"},
     * summary="Obtener una lista de experiencia laboral para una persona",
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
     * @OA\Items(ref="#/components/schemas/WorkExperience")
     * )
     * )
     * )
     */
    public function index(Person $person)
    {
        return $person->workExperiences;
    }

    /**
     * @OA\Post(
     * path="/api/people/{person_id}/work-experiences",
     * tags={"Experiencia Laboral"},
     * summary="Crear una nueva experiencia laboral para una persona",
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
     * description="Datos de la experiencia laboral",
     * @OA\JsonContent(
     * required={"position", "company", "start_date"},
     * @OA\Property(property="position", type="string", example="Full Stack Developer"),
     * @OA\Property(property="company", type="string", example="SWF Software Factory - Datasys Group"),
     * @OA\Property(property="start_date", type="string", format="date", example="2021-01-01"),
     * @OA\Property(property="end_date", type="string", format="date", example="2025-09-25", nullable=true),
     * @OA\Property(property="description", type="string", example="Desarrollo y mantenimiento de aplicaciones empresariales...", nullable=true)
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Experiencia laboral creada exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/WorkExperience")
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
            'position' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $workExperience = $person->workExperiences()->create($validatedData);

        return response()->json($workExperience, 201);
    }

    /**
     * @OA\Get(
     * path="/api/people/{person_id}/work-experiences/{id}",
     * tags={"Experiencia Laboral"},
     * summary="Obtener una experiencia laboral por ID para una persona",
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
     * name="id",
     * in="path",
     * required=true,
     * description="ID de la experiencia laboral",
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Operación exitosa",
     * @OA\JsonContent(ref="#/components/schemas/WorkExperience")
     * ),
     * @OA\Response(
     * response=404,
     * description="Experiencia laboral no encontrada"
     * )
     * )
     */
    public function show(Person $person, WorkExperience $workExperience)
    {
        return $workExperience;
    }

    /**
     * @OA\Put(
     * path="/api/people/{person_id}/work-experiences/{id}",
     * tags={"Experiencia Laboral"},
     * summary="Actualizar una experiencia laboral existente para una persona",
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
     * name="id",
     * in="path",
     * required=true,
     * description="ID de la experiencia laboral a actualizar",
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Datos de la experiencia laboral a actualizar",
     * @OA\JsonContent(
     * @OA\Property(property="position", type="string", example="Senior Full Stack Developer", nullable=true),
     * @OA\Property(property="company", type="string", example="Acme Corp.", nullable=true),
     * @OA\Property(property="start_date", type="string", format="date", example="2021-01-01", nullable=true),
     * @OA\Property(property="end_date", type="string", format="date", example="2025-09-25", nullable=true),
     * @OA\Property(property="description", type="string", example="Responsable del ciclo de vida del desarrollo de software.", nullable=true)
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Experiencia laboral actualizada exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/WorkExperience")
     * ),
     * @OA\Response(
     * response=404,
     * description="Experiencia laboral no encontrada"
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación"
     * )
     * )
     */
    public function update(Request $request, Person $person, WorkExperience $workExperience)
    {
        $validatedData = $request->validate([
            'position' => 'string|max:255',
            'company' => 'string|max:255',
            'start_date' => 'date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $workExperience->update($validatedData);

        return response()->json($workExperience, 200);
    }

    /**
     * @OA\Delete(
     * path="/api/people/{person_id}/work-experiences/{id}",
     * tags={"Experiencia Laboral"},
     * summary="Eliminar una experiencia laboral para una persona",
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
     * name="id",
     * in="path",
     * required=true,
     * description="ID de la experiencia laboral a eliminar",
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     * @OA\Response(
     * response=204,
     * description="Experiencia laboral eliminada exitosamente"
     * ),
     * @OA\Response(
     * response=404,
     * description="Experiencia laboral no encontrada"
     * )
     * )
     */
    public function destroy(Person $person, WorkExperience $workExperience)
    {
        $workExperience->delete();

        return response()->json(null, 204);
    }
}
