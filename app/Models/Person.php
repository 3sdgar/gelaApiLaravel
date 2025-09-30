<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 * schema="Person",
 * title="Persona",
 * description="Modelo de una persona",
 * @OA\Property(
 * property="id",
 * type="integer",
 * format="int64",
 * description="ID de la persona",
 * example=1
 * ),
 * @OA\Property(
 * property="first_name",
 * type="string",
 * description="Primer nombre de la persona",
 * example="Jane"
 * ),
 * @OA\Property(
 * property="last_name",
 * type="string",
 * description="Apellido de la persona",
 * example="Doe"
 * ),
 * @OA\Property(
 * property="date_of_birth",
 * type="string",
 * format="date",
 * description="Fecha de nacimiento",
 * example="1990-01-01"
 * ),
 * @OA\Property(
 * property="id_card",
 * type="string",
 * description="Número de identificación",
 * example="12345678-9"
 * ),
 * @OA\Property(
 * property="phone_number",
 * type="string",
 * description="Número de teléfono",
 * example="+50688887777"
 * ),
 * @OA\Property(
 * property="address",
 * type="string",
 * description="Dirección",
 * example="123 Main St, Anytown"
 * ),
 * @OA\Property(
 * property="email",
 * type="string",
 * format="email",
 * description="Correo electrónico",
 * example="jane.doe@example.com"
 * ),
 * @OA\Property(
 * property="linkedin_url",
 * type="string",
 * description="URL de LinkedIn",
 * example="https://www.linkedin.com/in/janedoe"
 * ),
 * @OA\Property(
 * property="facebook_url",
 * type="string",
 * description="URL de Facebook",
 * example="https://www.facebook.com/janedoe"
 * ),
 * @OA\Property(
 * property="indeed_url",
 * type="string",
 * description="URL de Indeed",
 * example="https://www.indeed.com/profiles/janedoe"
 * ),
 * @OA\Property(
 * property="created_at",
 * type="string",
 * format="date-time",
 * description="Fecha de creación",
 * readOnly="true"
 * ),
 * @OA\Property(
 * property="updated_at",
 * type="string",
 * format="date-time",
 * description="Fecha de actualización",
 * readOnly="true"
 * )
 * )
 */
class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'id_card',
        'phone_number',
        'address',
        'email',
        'linkedin_url',
        'facebook_url',
        'indeed_url'
    ];

    /**
     * Define la relación: Una persona tiene muchos estudios.
     */
    public function studies(): HasMany
    {
        return $this->hasMany(Study::class);
    }

    /**
     * Define la relación: Una persona tiene muchas experiencias laborales.
     */
    public function workExperiences(): HasMany
    {
        return $this->hasMany(WorkExperience::class);
    }
}
