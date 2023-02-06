<?php

namespace App\Http\Controllers;

use App\Models\Pizza;
use App\Models\Ingredient;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PizzaController extends Controller
{
    public function getPizzas(Request $request): \Illuminate\Http\JsonResponse
    {
        $pizzas = Pizza::all();

        return response()->json([
            'success' => true,
            'message' => 'successfully',
            'data' => $pizzas
        ], Response::HTTP_OK);
    }

    public function getPizza(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $pizza = Pizza::find($id);

        if(!$pizza) {
            return response()->json([
                'success' => false,
                'message' => 'pizza not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'message' => 'successfully',
            'data' => $pizza
        ], Response::HTTP_OK);
    }

    public function addIngredientToPizza(Request $request, int $pizzaId, int $ingredientId): \Illuminate\Http\JsonResponse
    {
        $pizza = Pizza::find($pizzaId);

        if(!$pizza) {
            return response()->json([
                'success' => false,
                'message' => 'pizza not found'
            ], Response::HTTP_NOT_FOUND);
        }

        
        $ingredient = Ingredient::find($ingredientId);

        if(!$ingredient) {
            return response()->json([
                'success' => false,
                'message' => 'ingredient not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if($pizza->ingredients->contains($ingredient)) {
            return response()->json([
                'success' => false,
                'message' => 'pizza have already this ingredient'
            ], Response::HTTP_NOT_FOUND);
        }

        $pizza->ingredients()->attach($ingredient);

        return response()->json([
            'success' => true,
            'message' => 'ingredient add to pizza successfully',
        ], Response::HTTP_OK);

    }

    public function deleteIngredientToPizza(Request $request, int $pizzaId, int $ingredientId): \Illuminate\Http\JsonResponse
    {
        $pizza = Pizza::find($pizzaId);

        if(!$pizza) {
            return response()->json([
                'success' => false,
                'message' => 'pizza not found'
            ], Response::HTTP_NOT_FOUND);
        }

        
        $ingredient = Ingredient::find($ingredientId);

        if(!$ingredient) {
            return response()->json([
                'success' => false,
                'message' => 'ingredient not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if(!$pizza->ingredients->contains($ingredient)) {
            return response()->json([
                'success' => false,
                'message' => 'pizza havn\'t this ingredient'
            ], Response::HTTP_NOT_FOUND);
        }

        $pizza->ingredients()->detach($ingredient);

        return response()->json([
            'success' => true,
            'message' => 'ingredient remove from pizza successfully',
        ], Response::HTTP_OK);

    }

    public function createPizza(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:pizzas',
            'price' => 'required|integer',
            'image' => 'required|base64image|base64mimes:jpg,png|base64max:1024',
            'ingredients' => 'array'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'success' => false,
                'message' => 'invalid inputs',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $image_parts = explode(";base64,", $request->image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $uniqid = uniqid();
        $file = 'pizza_pictures/' . $uniqid . '.' . $image_type;

        $status = Storage::disk('public')->put($file , $image_base64);

        if(!$status)
        {
            return response()->json([
                'success' => false,
                'message' => 'An error occur when storing image',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $pizza = new Pizza();
        $pizza->name = $request->name;
        $pizza->price = $request->price;
        $pizza->image_url = $file;
        $pizza->save();
        if($request->ingredients)
        {
            $ingredients = Ingredient::all()->whereIn('id', $request->input('ingredients'));
            $pizza->ingredients()->attach($ingredients);
        }

        return response()->json([
            'success' => true,
            'message' => 'successfully',
            'data' => $pizza
        ]);
    }

    public function updatePizza(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'price' => 'integer',
            'image' => 'string',
            'ingredients' => 'array'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'success' => false,
                'message' => 'invalid inputs',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $pizza = Pizza::find($id);

        if(!$pizza) {
            return response()->json([
                'success' => false,
                'message' => 'pizza not found'
            ], Response::HTTP_NOT_FOUND);
        }


        if($request->has('name'))
        {
            $pizza->name = $request->name;
        }

        if($request->has('price'))
        {
            $pizza->price = $request->price;
        }

        if($request->has('image'))
        {
            $image_parts = explode(";base64,", $request->image);
            $image_base64 = base64_decode($image_parts[1]);
            $status = Storage::disk('public')->put($pizza->image_url, $image_base64);
            if(!$status)
            {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occur when storing image',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        if($request->has('ingredients'))
        {
            $ingredients = Ingredient::findMany($request->ingredients);
            $pizza->ingredients()->detach();
            $pizza->ingredients()->attach($ingredients);
        }

        $pizza->save();

        return response()->json([
            'success' => true,
            'message' => 'successfully',
            'data' => $pizza
        ]);
    }

    public function deletePizza(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $pizza = Pizza::find($id);

        if(!$pizza) {
            return response()->json([
                'success' => false,
                'message' => 'pizza not found'
            ], Response::HTTP_NOT_FOUND);
        }

        Storage::disk('public')->delete($pizza->image_url);

        $pizza->delete();

        return response()->json([
            'success' => true,
            'message' => 'successfully',
            'data' => $pizza
        ], Response::HTTP_OK);
    }

}
