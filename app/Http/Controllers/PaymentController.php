<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Client;
use App\Models\Payment;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function index($id){
      if(Route::currentRouteName()=='supplier_payments'){
        $payable = Suppliers::findOrFail($id);
      }elseif(Route::currentRouteName()=='client_payments'){
        $payable = Client::findOrFail($id);
      }

      return view('content.payments.list')->with('payable',$payable);
      //dd(get_class($payable));
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payable_id' => 'required|integer',
            'payable_type' => 'required',
            'amount' => 'required|numeric',
            'receipt' => 'sometimes|file',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        //dd($request->all());

        try {
            $payment = Payment::create($request->all());

            if ($request->hasFile('receipt')) {
              $path = $request->receipt->store('/uploads/receipts','upload');
              $payment->receipt = $path;
              $payment->save();
            }

            return response()->json([
                'status' => 1,
                'message' => 'success',
                'data' => $payment
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|integer|exists:payments,id',
            'is_paid' => 'sometimes|in:yes,no',
            'amount' => 'sometimes|numeric',
            'receipt' => 'sometimes|file',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        try {
            $payment = Payment::findOrFail($request->payment_id);

            if($request->is_paid == 'yes' && $payment->is_paid == 'no'){
              $request->merge(['paid_at' => now()->toDateTimeString()]);
            }


            $payment->update($request->except('payment_id'));

            if ($request->hasFile('receipt')) {
              $path = $request->receipt->store('/uploads/receipts','upload');
              $payment->receipt = $path;
              $payment->save();
            }

            return response()->json([
                'status' => 1,
                'message' => 'success',
                'data' => $payment
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'payment_id' => 'required|integer|exists:payments,id',
      ]);
        try {
            $payment = Payment::findOrFail($request->payment_id);
            $payment->delete();

            return response()->json([
                'status' => 1,
                'message' => 'success',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function restore(Request $request)
    {
        try {
            $payment = Payment::withTrashed()->findOrFail($request->payment_id);
            $payment->restore();

            return response()->json([
                'status' => 1,
                'message' => 'success',
                'data' => $payment
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function multi_pay(Request $request){
      $validator = Validator::make($request->all(), [
        'payments' => 'required|array|min:1',
        'payments.*' => 'exists:payments,id',
      ]);

      if ($validator->fails()) {
          return response()->json([
              'status' => 0,
              'message' => $validator->errors()->first()
          ]);
      }
        try {

          DB::table('payments')->whereIn('id',$request->payments)
          ->update(['is_paid' => 'yes' ,'paid_at' => now()->toDateTimeString()]);


            return response()->json([
                'status' => 1,
                'message' => 'success',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ]);
        }
    }
}
