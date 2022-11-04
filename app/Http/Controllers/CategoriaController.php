<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        try
        {
            $categorias = User::find(Auth::id())->categoria;
        }
        catch (\Exception $e)
        {
            return response()->json(['res' => false, 'message' => 'Hubo un error', 'error' => $e], 500);
        }
        return response()->json($categorias, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'nombre' => ['required', 'string'],
            'tipo' => ['required', 'int']
        ]);
        if ($validator->fails())
        {
            return response()->json(["res" => false, "validator" => $validator->messages()], 500);
        }
        $objCategoria = new Categoria();
        $objCategoria->user_id = Auth::id();
        $objCategoria->nombre = $request->json('nombre');
        $objCategoria->tipo = $request->json('tipo');
        try
        {
            $objCategoria->save();
        }
        catch (\Exception $e)
        {
            return response()->json(['res' => false, 'message' => 'Error al insertar categoria', 'error' => $e], 500);
        }
        return response()->json(['res' => true, 'Categoria' => $objCategoria], 200);
    }

    public function show($id)
    {
        try
        {
            $objCategoria = Categoria::find($id);
            if ($objCategoria == null)
            {
                return response()->json(['res' => false, 'message' => 'Error, usuario no encontrado'], 500);
            }
        }
        catch (\Exception $e)
        {
            return response()->json(['res'=>false,'message'=>'hubo un error', 'error' => $e], 500);
        }
        return response()->json($objCategoria, 200);
    }

    public function destroy($id)
    {
        $objCategoria = Categoria::find($id);
        if ($objCategoria == null) {
            return response()->json(['res' => false, 'message' => 'Error, categoria no encontrada'], 404);
        }
        try {
            $objCategoria->delete();
        } catch (\Exception $e) {
            return response()->json(['res' => false, 'message' => 'Error al eliminar la tablera', 'error' => $e], 500);
        }
        return response()->json(['res' => true, "message" => "Se eliminó la categoria", "tablera" => $objCategoria], 200);
    }
}
