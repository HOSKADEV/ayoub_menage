<?php

namespace App\Http\Controllers\Suppliers;

use Exception;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PaginatedSupplierCollection;

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
      'phone' => 'nullable | numeric',
      'image' => 'sometimes|mimetypes:image/*'
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
      $supplier = Suppliers::create([
        'fullname' => $request->fullname,
        'phone' => $request->phone,
      ]);

      if($request->hasFile('image')){
        //$path = $request->image->store('/uploads/suppliers/images','upload');

        $file = $request->image;
        $name = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        $filename = 'suppliers/' . $supplier->id . '/' . md5(time().$name) . '.' . $extension;

        $url = $this->firestore($file->get(),$filename);

        $supplier->image = $url;
        $supplier->save();
      }

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

      if($request->hasFile('image')){
        //$path = $request->image->store('/uploads/suppliers/images','upload');

        $file = $request->image;
        $name = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        $filename = 'suppliers/' . $supplier->id . '/' . md5(time().$name) . '.' . $extension;

        $url = $this->firestore($file->get(),$filename);

        $supplier->image = $url;
        $supplier->save();
      }

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

  public function get(Request $request){  //paginated
    $validator = Validator::make($request->all(), [
      'search' => 'sometimes|string',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

    $suppliers = Suppliers::orderBy('created_at','DESC');

    if($request->has('search')){

      $suppliers = $suppliers->where('fullname', 'like', '%' . $request->search . '%');
    }

    $suppliers = $suppliers->paginate(10);

    return response()->json([
      'status' => 1,
      'message' => 'success',
      'data' => new PaginatedSupplierCollection($suppliers)
    ]);

  }catch(Exception $e){
    return response()->json([
      'status' => 0,
      'message' => $e->getMessage()
    ]
  );
  }

  }
}
