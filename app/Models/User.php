<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 * schema="User",
 * title="User",
 * description="Modelo de un usuario completo",
 * @OA\Property(
 * property="id",
 * type="integer",
 * format="int64",
 * description="ID del usuario",
 * example=1
 * ),
 * @OA\Property(
 * property="name",
 * type="string",
 * description="Nombre del usuario",
 * example="Edgar"
 * ),
 * @OA\Property(
 * property="email",
 * type="string",
 * format="email",
 * description="Correo electrónico del usuario",
 * example="edgar@gmail.com"
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
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
