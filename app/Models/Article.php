<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 * schema="ArticleRequest",
 * title="Article Request",
 * description="Cuerpo de la petición para crear/actualizar un artículo",
 * @OA\Property(property="name", type="string", example="Camiseta de Algodón"),
 * @OA\Property(property="type", type="string", example="Ropa"),
 * @OA\Property(property="description", type="string", example="Una camiseta suave y cómoda."),
 * @OA\Property(property="price", type="number", format="float", example=25.50),
 * @OA\Property(property="available_quantity", type="integer", example=100)
 * )
 *
 * @OA\Schema(
 * schema="Article",
 * title="Article",
 * description="Modelo de un artículo completo",
 * @OA\Property(property="id", type="integer", readOnly="true", example=1),
 * @OA\Property(property="name", type="string", example="Camiseta de Algodón"),
 * @OA\Property(property="type", type="string", example="Ropa"),
 * @OA\Property(property="description", type="string", example="Una camiseta suave y cómoda."),
 * @OA\Property(property="price", type="number", format="float", example=25.50),
 * @OA\Property(property="available_quantity", type="integer", example=100),
 * @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-29T12:00:00Z"),
 * @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-29T12:00:00Z")
 * )
 */

class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'description',
        'price',
        'available_quantity',
    ];
}
