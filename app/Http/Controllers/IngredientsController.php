<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class IngredientsController extends Controller
{
    public function getIngredients(Request $request): \Illuminate\Http\JsonResponse
    {
        $ingredients = Ingredient::all();

        return response()->json([
            'success' => true,
            'message' => 'successfully',
            'data' => $ingredients
        ], Response::HTTP_OK);
    }

    public function getIngredient(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $ingredient = Ingredient::find($id);

        if(!$ingredient) {
            return response()->json([
                'success' => false,
                'message' => 'ingredient not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'message' => 'successfully',
            'data' => $ingredient
        ], Response::HTTP_OK);
    }

    public function createIngredient(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:ingredients',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'success' => false,
                'message' => 'invalid inputs',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $ingredient = new Ingredient();
        $ingredient->name = $request->name;
        $ingredient->save();

        return response()->json([
            'success' => true,
            'message' => 'successfully',
            'data' => $ingredient
        ]);
    }

    public function updateIngredient(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'success' => false,
                'message' => 'invalid inputs',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $ingredient = Ingredient::find($id);

        if(!$ingredient) {
            return response()->json([
                'success' => false,
                'message' => 'ingredient not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $ingredient->name = $request->name;
        $ingredient->save();

        return response()->json([
            'success' => true,
            'message' => 'successfully',
            'data' => $ingredient
        ]);
    }

    public function deleteIngredient(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $ingredient = Ingredient::find($id);

        if(!$ingredient) {
            return response()->json([
                'success' => false,
                'message' => 'ingredient not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $ingredient->delete();

        return response()->json([
            'success' => true,
            'message' => 'successfully',
            'data' => $ingredient
        ], Response::HTTP_OK);
    }
}
