<?php

namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;
use App\Models\Suppliers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class supplierController extends Controller
{

  public function index()
  {
    return view('content.suppliers.list');
  }
  public function search(Request $request)
  {
    $query = $request->input('query');

    $suppliers = Suppliers::where('fullname', 'LIKE', '%' . $query . '%')->get();
    return response()->json($suppliers);
  }

  public function create(Request $request)
  {
    $validator = Validator::make($request->all(),
    [
      'fullname' => 'required | string | unique:suppliers',
      'phone' => 'nullable | numeric'
    ]);

    if ($validator->fails())
    {
      return response()->json([
        'status'=> 0,
        'message' => $validator->errors()->first()
      ]);
    }

    try
    {
      $suppliers = Suppliers::create([
        'fullname' => $request->fullname,
        'phone' => $request->phone,
      ]);

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => $suppliers
      ]);
    }
    catch(Exception $e)
    {
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]);
    }
  }

  public function update(Request $request)
  {
    $validator = Validator::make($request->all(),
    [
      'supplier_id' => 'required|exists:suppliers,id'
    ]);

    if ($validator->fails())
    {
      return response()->json([
        'status'=> 0,
        'message' => $validator->errors()->first()
      ]);
    }

    try
    {
      $supplier = Suppliers::findOrFail($request->supplier_id);
      $supplier->update($request->all());

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => $supplier
      ]);
    }
    catch(Exception $e)
    {
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]);
    }
  }
}
