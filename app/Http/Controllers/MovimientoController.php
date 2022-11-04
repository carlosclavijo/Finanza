<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Cuenta;
use App\Models\Movimiento;
use App\Models\Movimientotransferencia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MovimientoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'monto' => ['required', 'int'],
            'categoria_id' => ['required', 'int'],
            'cuenta_id' => ['required', 'int']
        ]);
        if ($validator->fails())
        {
            return response()->json(["res" => false, "validator" => $validator->messages()], 500);
        }
        $objMovimiento = new Movimiento();
        $objMovimiento->user_id = Auth::id();
        $objMovimiento->monto = $request->json('monto');
        $objMovimiento->categoria_id = $request->json('categoria_id');
        $objMovimiento->cuenta_id = $request->json('cuenta_id');
        $objUser = User::find(Auth::id());
        $objCategoria = Categoria::find($objMovimiento->categoria_id);
        $objCuenta = Cuenta::find($objMovimiento->cuenta_id);
        $objMovimiento->tipo = $objCategoria->tipo;
        if ($objCategoria->tipo == 1 && $objMovimiento->monto > $objCuenta->saldo)
        {
            return response()->json('No se puede extraer un monto menor al saldo');
        }
        try
        {
            $objMovimiento->save();
        }
        catch (\Exception $e)
        {
            return response()->json(['res' => false, 'message' => 'Error al insertar categoria', 'error' => $e], 500);
        }
        if ($objCategoria->tipo == 0)
        {
            $objCuenta->saldo = $objCuenta->saldo + $objMovimiento->monto;
            $objUser->saldo = $objUser->saldo + $objMovimiento->monto;
            $objCuenta->save();
            $objUser->save();
        }
        else if ($objCategoria->tipo == 1)
        {
            $objCuenta->saldo = $objCuenta->saldo - $objMovimiento->monto;
            $objUser->saldo = $objUser->saldo - $objMovimiento->monto;
            $objCuenta->save();
            $objUser->save();
        }
        return response()->json(['res' => true, 'Movimiento' => $objMovimiento], 200);
    }

    public function index($id)
    {
        try
        {
            $movimientos = Cuenta::find($id)->movimiento;
        }
        catch (\Exception $e)
        {
            return response()->json(['res' => false, 'message' => 'Hubo un error', 'error' => $e], 500);
        }
        $aux = false;
        $origen = 0;
        foreach ($movimientos as $m)
        {
            $m->cuenta = Cuenta::find($m->cuenta_id)->nombre;
            $c = Categoria::find($m->categoria_id);
            $m->categoria = $c->nombre;
            if ($m->tipo == 0)
            {
                $m->tipoNombre = "Ingreso";
            } else {
                $m->tipoNombre = "Egreso";
            }
            $destino = Movimientotransferencia::all()->where('movimiento_id', $m->id);
            if (count($destino) > 0) {
                $destino = $destino->first()->cuenta_id;
                $m->destino = Cuenta::find($destino)->nombre;
            }
        }
        return response()->json($movimientos, 200);
    }
}
