<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CuentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        try
        {
            $cuentas = User::find(Auth::id())->cuenta;
        }
        catch (\Exception $e)
        {
            return response()->json(['res' => false, 'message' => 'Hubo un error', 'error' => $e], 500);
        }
        return response()->json($cuentas, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'nombre' => ['required', 'string']
        ]);
        if ($validator->fails())
        {
            return response()->json(["res" => false, "validator" => $validator->messages()], 500);
        }
        $objCuenta = new Cuenta();
        $objCuenta->user_id = Auth::id();
        $objCuenta->nombre = $request->json('nombre');
        $objCuenta->saldo = 0;
        try
        {
            $objCuenta->save();
        }
        catch (\Exception $e)
        {
            return response()->json(['res' => false, 'message' => 'Error al insertar categoria', 'error' => $e], 500);
        }
        return response()->json(['res' => true, 'Categoria' => $objCuenta], 200);
    }

    public function show($id)
    {
        try
        {
            $objCuenta = Cuenta::find($id);
            if ($objCuenta == null)
            {
                return response()->json(['res' => false, 'message' => 'Error, usuario no encontrado'], 500);
            }
        }
        catch (\Exception $e)
        {
            return response()->json(['res'=>false,'message'=>'hubo un error', 'error' => $e], 500);
        }
        return response()->json($objCuenta, 200);
    }

    public function destroy($id)
    {
        $objCuenta = Cuenta::find($id);
        if ($objCuenta == null) {
            return response()->json(['res' => false, 'message' => 'Error, cuenta no encontrada'], 404);
        }
        try {
            $objCuenta->delete();
        } catch (\Exception $e) {
            return response()->json(['res' => false, 'message' => 'Error al eliminar la tablera', 'error' => $e], 500);
        }
        return response()->json(['res' => true, "message" => "Se eliminó la cuenta", "tablera" => $objCuenta], 200);
    }
}
