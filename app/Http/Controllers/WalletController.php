<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            if (Auth::user()->role == "customer"){
                $wallets = Wallet::where('user_id', Auth::id())->get();
            }else if(Auth::user()->role == "admin"){
                $wallets = Wallet::all();
            }else{
                return response('you are not authorised', 403);
            }

            return response($wallets, 200);
        }catch(Exception $e){
            return response()->json(['message'=>$e->getMessage()]);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
//            $validator = Validator::make($request->all(),[
//                'name' => 'required|string|unique:wallets'
//            ]);

//            if($validator->fails()){
//                return response()->json(['errors'=>$validator->errors(), 'message'=>'Error']);
//            }
            $nameExist = Wallet::where('name', $request->name)->where('user_id', Auth::id())->first();
            $typeExist = Wallet::where('type', $request->type)->where('user_id', Auth::id())->first();

            if($nameExist){
                return response()->json(['message'=>'You already created a wallet with this name']);
            }

            if($typeExist){
                return response()->json(['message'=>'You already created a wallet with this type']);
            }

            $wallet = Wallet::create([
                'name' =>  ucfirst($request->name),
                'type' => $request->type,
                'user_id' => Auth::id(),
                'unique_identifier'=> Str::random(10)
            ]);

            return response()->json(['message'=>'wallet created', 'data'=>$wallet], 201);
        }catch(Exception $e){
            return response()->json(['message'=>'']);
        }


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $wallet = Wallet::findOrFail($id);

            if (Auth::id() != $wallet->user_id){
                return response()->json(['message'=>'unauthorized'], 401);
            }
            if ($wallet){
                $wallet->name = $request->name;
                $wallet->type = $request->type;
                $wallet->user_id = Auth::id();
                $wallet->save();
            }
        }catch(Exception $e){
            return response()->json(['message'=>'']);
        }

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $wallet = Wallet::findOrFail($id);
        return response()->json(["data"=>$wallet], 200);
    }
}
