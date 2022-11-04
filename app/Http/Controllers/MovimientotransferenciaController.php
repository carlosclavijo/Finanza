<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use App\Models\Movimiento;
use App\Models\MovimientoTransferencia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MovimientotransferenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'cuenta_id' => ['required', 'int']
        ]);
        if ($validator->fails())
        {
            return response()->json(["res" => false, "validator" => $validator->messages()], 500);
        }
        $objMovimientotransferencia = new MovimientoTransferencia();
        $objMovimiento = Movimiento::latest()->first();
        $objMovimientotransferencia->movimiento_id = $objMovimiento->id;
        $objMovimientotransferencia->cuenta_id = $request->json('cuenta_id');
        try
        {
            $objMovimientotransferencia->save();
        }
        catch (\Exception $e)
        {
            return response()->json(['res' => false, 'message' => 'Error al insertar Movimiento', 'error' => $e], 500);
        }
        $objUser = User::find(Auth::id());
        $objCuenta = Cuenta::find($request->json('cuenta_id'));
        $objCuenta->saldo = $objCuenta->saldo + $objMovimiento->monto;
        $objUser->saldo = $objUser->saldo + $objMovimiento->monto;
        $objCuenta->save();
        $objUser->save();
        $objMovimientoRecibo = new Movimiento();
        $objMovimientoRecibo->user_id = Auth::id();
        $objMovimientoRecibo->monto = $objMovimiento->monto;
        $objMovimientoRecibo->tipo = 0;
        $objMovimientoRecibo->categoria_id = $objMovimiento->categoria_id;
        $objMovimientoRecibo->cuenta_id = $objMovimientotransferencia->cuenta_id;
        $objMovimientoRecibo->save();
        return response()->json(['res' => true, 'Movimiento' => $objMovimientotransferencia], 200);
    }
}
