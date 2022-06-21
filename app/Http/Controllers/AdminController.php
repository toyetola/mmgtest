<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{


    public  function  getUsers(Request $request){

        try{
            if (Auth::user()->role != "admin"){
                return response()->json(['message'=>'you need to be an admin', 'data'=>'error'], 403);
            }
            $users = User::all();
            return response()->json(['message'=>'users loaded', 'data'=>$users], 200);
        }catch (Exception $exception){
            return response()->json(['message'=>$exception->getMessage(), 'data'=>'error'], 500);
        }


    }

    public function getUser(Request $request, $id){

        try{
            if (Auth::user()->role != "admin"){
                return response()->json(['message'=>'you need to be an admin', 'data'=>'error'], 403);
            }

            $user = User::findOrFail($id);
            $user->load('wallets');
            $transactions = Transaction::where('wallet_to_id', $user->id)->orWhere('wallet_from_id', $user->id)->get();
            $user['transactions'] = $transactions;
            return response()->json(['message'=>'users loaded', 'data'=>$user], 200);
        }catch (Exception $exception){
            return response()->json(['message'=>$exception->getMessage(), 'data'=>'error'], 500);
        }


    }

    public function getWallets(){

        try{
            if (Auth::user()->role != "admin"){
                return response()->json(['message'=>'you need to be an admin', 'data'=>'error'], 403);
            }

            $wallets = Wallet::all();
            return response()->json(['message'=>'wallets fetched', 'data'=>$wallets], 200);
        }catch (Exception $exception){
            return response()->json(['message'=>$exception->getMessage(), 'data'=>'error'], 500);
        }

    }

    public function getWalletDetail($id){

        try{

            if (Auth::user()->role != "admin"){
                return response()->json(['message'=>'you need to be an admin', 'data'=>'error'], 403);
            }

            $wallets = Wallet::where('id', $id)->with('transactions', 'user')->first();
            return response()->json(['message'=>'wallets fetched', 'data'=>$wallets], 200);
        }catch (Exception $exception){
            return response()->json(['message'=>$exception->getMessage(), 'data'=>'error'], 500);
        }

    }


    public function stats(){

        if (Auth::user()->role != "admin"){
            return response()->json(['message'=>'you need to be an admin', 'data'=>'error'], 403);
        }

        $countUsers = User::all()->count();
        $countWallets = Wallet::all()->count();
        $summationOfWalletAmounts = Wallet::all()->sum('current_balance');
        $transactionVolume = Transaction::all()->count();

        $stats = [];
        $stats['usersCount'] = $countUsers;
        $stats['walletsCount'] = $countWallets;
        $stats['transactionVolume'] = $transactionVolume;
        $stats['summationOfWalletAmount'] = $summationOfWalletAmounts;

        return response()->json(['message'=>'stats fetched', 'data'=>$stats], 200);
    }


}
