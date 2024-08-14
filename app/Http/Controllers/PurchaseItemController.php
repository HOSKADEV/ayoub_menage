<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchaseItemController extends Controller
{
  public function index($id){
    $purchase = Purchase::findOrFail($id);
    $categories = Category::all();

    return view('content.purchases.items')
    ->with('purchase',$purchase)
    ->with('categories',$categories);
  }

   public function add(Request $request){
    $validator = Validator::make($request->all(), [
      'purchase_id' => 'required|exists:purchases,id',
      'product_id' => 'required|exists:products,id',
      'quantity' => 'required|integer|min:1',
      'price' => 'required|numeric',
      'name' => 'required'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status'=> 0,
        'message' => $validator->errors()->first()
      ]);
    }
    try{

      $purchase = Purchase::find($request->purchase_id);

      $item = $purchase->items()->where($request->only('product_id','price'))->first();

      if($item){
        $item->quantity += $request->quantity;
        $item->amount = $item->quantity * $item->price;
        $item->save();
      }else{
        $request->merge(['amount' => $request->price * $request->quantity]);
        PurchaseItem::create($request->all());
      }

      $purchase->total();

      return response()->json([
        'status' => 1,
        'message' => 'success',

      ]);

    }catch(Exception $e){
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
    );
    }
  }



  public function edit(Request $request){

    $validator = Validator::make($request->all(), [
      'item_id' => 'required|exists:purchase_items,id',
      'quantity' => 'required|integer|min:1',
      'price' => 'required|numeric',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status'=> 0,
        'message' => $validator->errors()->first()
      ]);
    }

    try{

        $item = PurchaseItem::findOrFail($request->item_id);

        $purchase = $item->purchase;

        $duplicate = $purchase->items()->where(['product_id' => $item->product_id, 'price' => $request->price])
                                      ->whereNot('id',$item->id)->first();

        if($duplicate){
          $item->quantity = $duplicate->quantity + $request->quantity;
          $duplicate->delete();
        }else{
          $item->quantity = $request->quantity;
        }
        $item->price = $request->price;
        $item->amount = $item->quantity * $item->price;
        $item->save();
        $purchase->total();

      return response()->json([
        'status' => 1,
        'message' => 'success',
      ]);

    }catch(Exception $e){
      DB::rollBack();
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
    );
    }

  }

  public function delete(Request $request){

    $validator = Validator::make($request->all(), [
      'item_id' => 'required|exists:purchase_items,id',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $item = PurchaseItem::findOrFail($request->item_id);
      $purchase = $item->purchase;

      $item->delete();
      $purchase->total();


      return response()->json([
        'status' => 1,
        'message' => 'success',
      ]);

    }catch(Exception $e){
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
    );
    }

  }
/*
  public function restore(Request $request){

    $validator = Validator::make($request->all(), [
      'item_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $item = Item::findOrFail($request->item_id);

      $item->restore();

      return response()->json([
        'status' => 1,
        'message' => 'success',
      ]);

    }catch(Exception $e){
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
    );
    }

  } */
}
