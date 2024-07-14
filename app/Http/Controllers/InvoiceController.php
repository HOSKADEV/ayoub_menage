<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Order;
use App\Models\Product;
use App\Models\Suppliers;
use Exception;
// use File;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File ;
use Illuminate\Support\Facades\Validator;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice as Bill;
use LaravelDaily\Invoices\Classes\InvoiceItem;
class InvoiceController extends Controller
{
    public function update(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'invoice_id' => 'required|exists:invoices,id',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]);
      }

      try
      {

        $invoice = Invoice::find($request->invoice_id);

        if(!is_null($invoice->file)){
          File::delete(url($invoice->file));
        }

        $invoice->pdf();
        $invoice->refresh();

        return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => url($invoice->file)
        ]);

      }
      catch (Exception $e)
      {
        return response()->json(
          [
            'status' => 0,
            'message' => $e->getMessage()
          ]
        );
      }

    }

    public function updateSupplier(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'order_id' => 'required|exists:orders,id',
      ]);
      if ($validator->fails())
      {
        return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]);
      }

      try
      {
        $order    = Order::find($request->order_id);
        $cart     = Cart::find($order->cart_id);
        $itemsIds = Item::where('cart_id', $cart->id)->pluck('product_id')->toArray();

        $products     = Product::whereIn('id', $itemsIds);
        $suppliersIds = Product::whereIn('id', $itemsIds)->pluck('supplier_id')->toArray();

        $suppliers = Suppliers::whereIn('id',$suppliersIds)->get();

        // dd($suppliers);
        if(!is_null($order->file)){
          File::delete(url($order->file));
        }

        $seller = new Party([
            'name'          => __('Maurizon'),
        ]);

        $buyer = new Party([
          'name' => $cart->user->name,
        ]);

          $items = [];
          $products = $products->get();

          foreach($products as $product)
          {
            // dd($product->supplier->fullname);
            array_push($items,
              (new InvoiceItem())
              ->title($product->unit_name)
              ->description($product->supplier->fullname)
              ->pricePerUnit($product->purchasing_price)
              ->units($product->code_supplier)
            );
          }


          $filename ='supplier-00'. $suppliers[0]->id.'-00'.$request->order_id.'-'. Carbon::now()->toDateString();

          $suppliers = Bill::make(__('receipt'))
          ->series('ID')
          // ability to include translated invoice status
          // in case it was paid
          // ->status(__('invoices::invoice.paid'))
          ->sequence($suppliers[0]->id)
          ->serialNumberFormat('{SEQUENCE}/{SERIES}')
          ->seller($seller)
          ->buyer($buyer)
          ->date($suppliers[0]->created_at)
          ->dateFormat('Y-m-d')
          // ->payUntilDays(14)
          ->currencySymbol('Mur')
          ->currencyCode('Mur')
          ->currencyFormat('{SYMBOL}{VALUE}')
          ->currencyThousandsSeparator('.')
          ->currencyDecimalPoint(',')
          ->filename($filename)
          ->addItems($items)
          // ->notes($notes)
          ->logo(public_path('logo.png'))
          // You can additionally save generated invoice to configured disk
          ->save('suppliers');

          // And return invoice itself to browser or have a different view
          $suppliers->stream();

          $order->file = 'uploads/supplier/'.$filename.'.pdf';

          $order->save();
          $order->refresh();

          return response()->json([
            'status' => 1,
            'message' => 'success',
            'data' => url($order->file),
          ]);
      }
      catch (Exception $e)
      {
        return response()->json(
          [
            'status' => 0,
            'message' => $e->getMessage()
          ]
        );
      }
    }

}
