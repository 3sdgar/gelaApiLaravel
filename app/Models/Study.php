<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 * schema="Study",
 * title="Study",
 * description="Modelo de datos para un estudio académico",
 * @OA\Property(
 * property="id",
 * type="integer",
 * format="int64",
 * description="ID único del estudio",
 * example=1
 * ),
 * @OA\Property(
 * property="person_id",
 * type="integer",
 * format="int64",
 * description="ID de la persona a la que pertenece el estudio",
 * example=1
 * ),
 * @OA\Property(
 * property="institution",
 * type="string",
 * description="Nombre de la institución educativa",
 * example="Universidad de Costa Rica"
 * ),
 * @OA\Property(
 * property="degree",
 * type="string",
 * description="Título o grado académico",
 * example="Licenciatura en Ingeniería de Software"
 * ),
 * @OA\Property(
 * property="level",
 * type="string",
 * description="Nivel de estudio",
 * example="Universitario"
 * ),
 * @OA\Property(
 * property="start_date",
 * type="string",
 * format="date",
 * description="Fecha de inicio del estudio",
 * example="2015-01-01"
 * ),
 * @OA\Property(
 * property="end_date",
 * type="string",
 * format="date",
 * description="Fecha de finalización del estudio",
 * example="2019-12-31",
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
class Study extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'studies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'person_id',
        'institution',
        'degree',
        'level',
        'start_date',
        'end_date',
        'description',
    ];

    /**
     * Get the person that owns the study.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}