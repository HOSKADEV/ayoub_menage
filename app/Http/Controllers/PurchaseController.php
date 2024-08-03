<?php

namespace App\Http\Controllers;

use App\Models\PurchaseItem;
use Exception;
use App\Models\Purchase;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    public function index($id){
      $supplier = Suppliers::find($id);
      $products = $supplier->products;

      if($supplier){
        return view('content.purchases.list')->with('supplier',$supplier)->with('products', $products);
      }else{
        return redirect()->route('pages-misc-error');
      }
    }

    public function create(Request $request)
    {
      //dd($request->all());
      $validator = Validator::make($request->all(), [

        'supplier_id' => 'required|exists:suppliers,id',
        'paid_amount' => 'sometimes|numeric',
        'receipt' => 'sometimes|file',
        'items' => 'required|array',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|numeric',
        'items.*.price' => 'required|numeric'
      ]);

      if ($validator->fails()) {
        return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]);
      }
      try
      {
        DB::beginTransaction();

        $purchase = Purchase::create($request->except('receipt','items'));

        $items = $request->items;

        array_walk($items, function(&$item, $key, $purchase_id) {
          $item += [
            'purchase_id' =>  $purchase_id,
            'amount' => $item['price'] * $item['quantity']
          ];
        }, $purchase->id );


        //dd($items);

        PurchaseItem::insert($items);

        $purchase->refresh();
        $purchase->total();

        DB::commit();

        return response()->json([
          'status' => 1,
          'message' => 'success',
        ]);

      }
      catch (Exception $e)
      {
        DB::rollBack();
        return response()->json(
          [
            'status' => 0,
            'message' => $e->getMessage(),
          ]
        );
      }
    }
}
