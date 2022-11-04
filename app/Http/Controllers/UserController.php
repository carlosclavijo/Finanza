<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['login', 'store']]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'required', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        if ($validator->fails())
        {
            return response()->json(["res" => false, "validator" => $validator->messages()], 500);
        }
        $objUser = new User();
        $objUser->name = $request->json('name');
        $objUser->email = $request->json('email');
        $objUser->password = bcrypt($request->json('password'));
        $objUser->saldo = 0;
        try
        {
            $objUser->save();
            $accessToken = $objUser->createToken('authToken')->accessToken;
        }
        catch (\Exception $e)
        {
            return response()->json(['res' => false, 'message' => 'Error al insertar usuario, email Posiblemente ya registrado', "error" => $e], 500);
        }
        return response()->json(['res' => true, 'Usuario' => $objUser, 'access_token' => $accessToken->token ], 200);
    }

    public function show()
    {
        try
        {
            $objUser = User::find(Auth::id());
            if ($objUser == null)
            {
                return response()->json(['res' => false, 'message' => 'Error, usuario no encontrado'], 500);
            }
        }
        catch (\Exception $e)
        {
            return response()->json(['res'=>false,'message'=>'hubo un error', 'error' => $e], 500);
        }
        return response()->json($objUser, 200);
    }

    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);
        if (Auth::attempt($credentials)) {
            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            return response()->json([
                "access_token" => $tokenResult->plainTextToken,
                "user" => $user
                //"id" => Auth::id()
            ]);
        } else {
            return response()->json([
                "message" => "Unauthenticated."
            ], 401);
        }
    }
}
