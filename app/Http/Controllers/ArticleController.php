<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 * title="API de Curriculums",
 * version="1.0.0",
 * description="API para la gestión de Curriculums"
 * )
 * @OA\Tag(
 * name="Artículos",
 * description="Endpoints para la gestión de Curriculums"
 * )
 */
class ArticleController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/articles",
     * summary="Obtener la lista de artículos",
     * tags={"Artículos"},
     * @OA\Response(
     * response=200,
     * description="Operación exitosa",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/Article")
     * )
     * )
     * )
     */
    public function index()
    {
        $articles = Article::all();
        return response()->json($articles);
    }

    /**
     * @OA\Post(
     * path="/api/articles",
     * summary="Crear un nuevo artículo",
     * tags={"Artículos"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/ArticleRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Artículo creado exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/Article")
     * )
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'available_quantity' => 'required|integer',
        ]);
        
        $article = Article::create($validatedData);

        return response()->json($article, 201);
    }

    /**
     * @OA\Get(
     * path="/api/articles/{id}",
     * summary="Obtener un artículo por ID",
     * tags={"Artículos"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del artículo",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Operación exitosa",
     * @OA\JsonContent(ref="#/components/schemas/Article")
     * )
     * )
     */
    public function show(Article $article)
    {
        return response()->json($article);
    }

    /**
     * @OA\Put(
     * path="/api/articles/{id}",
     * summary="Actualizar un artículo",
     * tags={"Artículos"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del artículo a actualizar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/ArticleRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Artículo actualizado exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/Article")
     * )
     * )
     */
    public function update(Request $request, Article $article)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric',
            'available_quantity' => 'sometimes|required|integer',
        ]);
        
        $article->update($validatedData);

        return response()->json($article);
    }

    /**
     * @OA\Delete(
     * path="/api/articles/{id}",
     * summary="Eliminar un artículo",
     * tags={"Artículos"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del artículo a eliminar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=204,
     * description="Artículo eliminado exitosamente"
     * )
     * )
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return response()->json(null, 204);
    }
}
