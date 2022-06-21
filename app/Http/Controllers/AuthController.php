<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        if($request->phone){
            $credentials = $request->only('phone', 'password');
        }else{
            $credentials = $request->only('email', 'password');
        }


        if (Auth::attempt($credentials)) {
            // Authentication passed...
            $token = Auth::user()->createToken('Wallet App Client Grant')->accessToken;
            return response()->json(["message"=>"you are logged in", "data"=>$token], 200);
        }

        return response()->json(["message"=>"Not logged in,  please check your credentials and try again"], 401);
    }


    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'string|max:14|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $request['password']=Hash::make($request['password']);
        $user = User::create($request->toArray());
//        $token = $user->createToken('Wallet App Client Grant')->accessToken;
//        $response = ['token' => $token];
        return response()->json(["message"=>"successfully registered; please login"], 201);
    }


    public function logout(Request $request){

        $accessToken = auth()->user()->token();
        $token= $request->user()->tokens->find($accessToken);
        $token->revoke();
        return response(['message' => 'You have been successfully logged out.'], 200);
    }

}
