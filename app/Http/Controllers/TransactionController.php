<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public  function  fundWallet(Request $request){

        $wallet = Wallet::where('unique_identifier', $request->wallet_identifier)->first();


        if ($wallet && $request->wallet_identifier == $wallet->unique_identifier){
            $wallet->current_balance += $request->amount;
            $wallet->save();
            return response()->json(['data'=>$wallet, 'message'=>'account funded']);
        }

        return response()->json(['message'=>'make sure the wallet exists and the account is owned by you.']);
    }

    public function sendToAnotherWallet(Request $request){

        $walletToCredit = Wallet::where('unique_identifier', $request->walletTo)->first();
        $walletToDebit = Wallet::where('unique_identifier', $request->walletFrom)->first();

        //to make sure the authenticated user ones the debitting account
        if ($walletToDebit->user_id != Auth::id()){
            return response()->json(['message'=>'You need to be the owner of this account to debit from it'], 403);
        }

        //check if adequate unique identifier for account is provided
        if (!$walletToDebit){
            return response()->json(['message'=>'From account does not exist'], 404);
        }

        //check if adequate unique identifier for account is provided
        if (!$walletToCredit){
            return response()->json(['message'=>'"To" account does not exist'], 404);
        }

        //make sure there is enough money in the wallet, and does not exceed minimum balance
        if ($request->amountToSend > $walletToDebit->current_balance ){
            return response()->json(['message'=>'You do not have enough balance for this operation'], 403);
        }

        if(($walletToDebit->current_balance - $request->amountToSend) < $walletToDebit->minimum_balance){
            return response()->json(['message'=>'You do not have enough balance for this operation: Your minimum balance show not be less than 1000']);
        }


        //perform the credit and debit operations
        $walletToDebit->current_balance -=  $request->amountToSend;
        $walletToCredit->current_balance +=  $request->amountToSend;
        $walletToDebit->save();
        $walletToCredit->save();

        $transaction = Transaction::create([
           'wallet_to_id' => $walletToCredit->id,
           'wallet_from_id' => $walletToDebit->id,
           'amount' => $request->amountToSend,
           'user_id' => Auth::id(),
        ]);

        $transaction->load('wallet1Involved', 'wallet2Involved');

        return response()->json(['data'=>$transaction, 'message'=>'transaction complete']);
    }
}
