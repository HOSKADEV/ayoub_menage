<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Client;
use App\Models\Wilaya;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\ClientResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PaginatedClientCollection;

class ClientController extends Controller
{
  public function index(){
    $wilayas = Wilaya::all();
    return view('content.clients.list')->with('wilayas',$wilayas);
  }
  public function create(Request $request){
    $validator = Validator::make($request->all(), [
      'district_id' => 'required|exists:districts,id',
      'name' => 'required|string',
      'phone' => 'required|min:10|unique:clients,phone',
      'image' => 'sometimes|mimetypes:image/*',
      'longitude' => [Rule::requiredIf($request->is('api/*')), 'numeric'],
      'latitude' => [Rule::requiredIf($request->is('api/*')), 'numeric'],
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status'=> 0,
        'message' => $validator->errors()->first()
      ]);
    }
    try{


      $client = Client::create($validator->validated());

      if($request->hasFile('image')){
        //$path = $request->image->store('/uploads/clients/images','upload');

        $file = $request->image;
        $name = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        $filename = 'clients/' . $client->id . '/' . md5(time().$name) . '.' . $extension;

        $url = $this->firestore($file->get(),$filename);

        $client->image = $url;
        $client->save();
      }

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new ClientResource($client)
      ]);

    }catch(Exception $e){
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
    );
    }
  }

  public function update(Request $request){

    $validator = Validator::make($request->all(), [
      'client_id' => 'required',
      'district_id' => 'sometimes|exists:districts,id',
      'name' => 'sometimes|string',
      'phone' => 'sometimes|min:10|unique:clients,phone',
      'image' => 'sometimes|mimetypes:image/*',
      'longitude' => 'sometimes|numeric',
      'latitude' => 'sometimes|numeric'
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $client = Client::findOrFail($request->client_id);

      $client->update($request->except('client_id'));


      if($request->hasFile('image')){
        //$path = $request->image->store('/uploads/clients/images','upload');

        $file = $request->image;
        $name = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        $filename = 'clients/' . $client->id . '/' . md5(time().$name) . '.' . $extension;

        $url = $this->firestore($file->get(),$filename);

        $client->image = $url;
        $client->save();
      }

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new ClientResource($client)
      ]);

    }catch(Exception $e){
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
    );
    }

  }

  public function delete(Request $request){

    $validator = Validator::make($request->all(), [
      'client_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $client = Client::findOrFail($request->client_id);

      $client->delete();

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

  public function restore(Request $request){

    $validator = Validator::make($request->all(), [
      'client_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $client = Client::withTrashed()->findOrFail($request->client_id);

      $client->restore();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new ClientResource($client)
      ]);

    }catch(Exception $e){
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
    );
    }

  }

  public function get(Request $request){  //paginated
    $validator = Validator::make($request->all(), [
      'search' => 'sometimes|string',
      'wilaya_id' => 'sometimes|exists:wilayas,id',
      'district_id' => 'sometimes|prohibits:wilaya_id|exists:districts,id'
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

    $clients = Client::orderBy('created_at','DESC');

    if($request->has('district_id')){

      $clients = $clients->where('district_id', $request->district_id);
    }

    if($request->has('wilaya_id')){
      $districts = Wilaya::find($request->wilaya_id)->district()->pluck('id')->toArray();
      $clients = $clients->whereIn('district_id', $districts);
    }

    if($request->has('search')){

      $clients = $clients->where('name', 'like', '%' . $request->search . '%');
    }

    $clients = $clients->paginate(10);

    return response()->json([
      'status' => 1,
      'message' => 'success',
      'data' => new PaginatedClientCollection($clients)
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
