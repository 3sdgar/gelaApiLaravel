<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 * schema="Role",
 * title="Role",
 * description="Modelo de un rol de usuario",
 * @OA\Property(
 * property="id",
 * type="integer",
 * format="int64",
 * description="ID del rol",
 * example=1
 * ),
 * @OA\Property(
 * property="rol",
 * type="string",
 * description="Nombre del rol",
 * example="admin"
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

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rol',
    ];
}
