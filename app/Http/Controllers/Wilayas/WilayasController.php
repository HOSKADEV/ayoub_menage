<?php

namespace App\Http\Controllers\Wilayas;

use App\Http\Controllers\Controller;
use App\Http\Resources\wilayas\WilayaResource;
use App\Http\Resources\wilayas\WilayasCollection;
use App\Models\Wilaya;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WilayasController extends Controller
{
    public function index()
    {
      return view('content.wilayas.list');
    }

    public function create(Request $request)
    {
      // dd($request->all());
      $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'delivery_price' =>'required|numeric',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'status'=> 0,
          'message' => $validator->errors()->first()
        ]);
      }

      try
      {
        // $wilayas = Wilaya::create($request->all());

        $wilayas = new Wilaya();

        // start seach name location
        $locationName = $request->input('name');

        $client = new Client();
        $response = $client->get('https://nominatim.openstreetmap.org/search.php', [
          'query' => [
              'q' => $locationName,
              'format' => 'json',
              'addressdetails' => 1,
              'limit' => 1
          ]
      ]);

      $data = json_decode($response->getBody(), true);
      // dd($data);
      // Check if any results were found
      if (empty($data))
      {
        return response()->json([
          'status' => 0,
          'message' => "Location not found."
        ]);
      }
      else
      {
        $result = $data[0];
        $lat = $result['lat'];
        $lng = $result['lon'];
        $wilayas->name = $result['name'];
        $wilayas->display_name = $result['display_name'];
        $wilayas->delivery_price = $request->input('delivery_price');
        $wilayas->latitude = $lat;
        $wilayas->longitude = $lng;
        $wilayas->save();

        return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => new WilayaResource($wilayas)
        ]);
      }

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
      $validator = Validator::make($request->all(), [
        'wilaya_id' => 'required',
        // 'name' => 'required|string',
        // 'delivery_price' =>'required|numeric',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'status'=> 0,
          'message' => $validator->errors()->first()
        ]);
      }

      try
      {
        $wilayas = Wilaya::findOrFail($request->wilaya_id);
        $wilayas->update($request->all());
        // $wilayas = Wilaya::update($request->all());

        return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => new WilayaResource($wilayas)
        ]);
      }
      catch(Exception $e){
        return response()->json([
          'status' => 0,
          'message' => $e->getMessage()
        ]);
      }
    }

    public function delete(Request $request)
    {
      $validator = validator::make($request->all(), [
        'wilaya_id' => 'required'
      ]);

      if ($validator->fails())
      {
        return response()->json([
          'status'=> 0,
          'message' => $validator->errors()->first()
        ]);
      }

      try {
        $wilayas = Wilaya::findOrFail($request->wilaya_id);

        if (!$wilayas->district()->count() == 0)
        {
          return response()->json([
            'status' => 0,
            'message' => 'لا يمكنك حذف الولاية لحوزتها المقاطعة',
          ]);
        }
        // dd('supp');
        $wilayas->delete();

        return response()->json([
          'status' => 1,
          'message' => 'success',
        ]);

      }
      catch(Exception $e){
        return response()->json([
          'status' => 0,
          'message' => $e->getMessage()
        ]);
      }

    }

    public function get(Request $request)
    {
      $wilayas = WilayaResource::collection(Wilaya::all()) ;
        // dd($wilayas);
        return response()->json([
        'status' => 1,
        'message' =>'success',
        'data' => $wilayas
      ]);
    }
}
