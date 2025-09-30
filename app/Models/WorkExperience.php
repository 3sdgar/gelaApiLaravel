<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 * schema="WorkExperience",
 * title="Work Experience",
 * description="Modelo de datos para la experiencia laboral",
 * @OA\Property(
 * property="id",
 * type="integer",
 * format="int64",
 * description="ID único de la experiencia laboral",
 * example=1
 * ),
 * @OA\Property(
 * property="person_id",
 * type="integer",
 * format="int64",
 * description="ID de la persona a la que pertenece la experiencia laboral",
 * example=1
 * ),
 * @OA\Property(
 * property="position",
 * type="string",
 * description="Título del puesto de trabajo",
 * example="Desarrollador de Software Senior"
 * ),
 * @OA\Property(
 * property="company",
 * type="string",
 * description="Nombre de la empresa",
 * example="Tech Solutions Inc."
 * ),
 * @OA\Property(
 * property="start_date",
 * type="string",
 * format="date",
 * description="Fecha de inicio del empleo",
 * example="2020-03-15"
 * ),
 * @OA\Property(
 * property="end_date",
 * type="string",
 * format="date",
 * description="Fecha de finalización del empleo",
 * example="2024-05-30",
 * nullable=true
 * ),
 * @OA\Property(
 * property="description",
 * type="string",
 * description="Descripción de las responsabilidades y logros",
 * example="Lideré un equipo de desarrollo para crear una nueva plataforma en la nube.",
 * nullable=true
 * ),
 * @OA\Property(
 * property="created_at",
 * type="string",
 * format="date-time",
 * example="2025-07-29T12:00:00.000000Z"
 * ),
 * @OA\Property(
 * property="updated_at",
 * type="string",
 * format="date-time",
 * example="2025-07-29T12:00:00.000000Z"
 * )
 * )
 */
class WorkExperience extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'work_experiences';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'person_id',
        'position',
        'company',
        'start_date',
        'end_date',
        'description',
    ];

    /**
     * Get the person that owns the work experience.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
