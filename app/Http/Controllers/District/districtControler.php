<?php

namespace App\Http\Controllers\District;

use App\Http\Controllers\Controller;
use App\Http\Resources\Districts\DistrictResource;
use App\Models\District;
use App\Models\Wilaya;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;

class districtControler extends Controller
{
  public function index()
  {
    $wilayas = Wilaya::all();
    return view('content.districts.list', compact('wilayas'));
  }

  public function create(Request $request)
  {
      $validator = Validator::make($request->all(),
      [
        'name' => 'required|string',
        'wilaya_id' => 'required|exists:wilayas,id',
      ]);

      if ($validator->fails())
      {
        return response()->json([
          'status' => 0,
          'message'=> $validator->errors()->first()
        ]);
      }
      try
      {
        $district = District::create($request->all());
        /* $district = new District();

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

        if (empty($data))
        {
          return response()->json([
            'status' => 0,
            'message' => "هذه منطقة غير موجدة علي خريطة"
          ]);
        }
        else
        {
          $result = $data[0];
          $lat = $result['lat'];
          $lng = $result['lon'];

          $district->wilaya_id = $request->wilaya_id;
          $district->name = $result['name'];
          $district->display_name = $result['display_name'];
          $district->longitude = $lng;
          $district->latitude = $lat;
          $district->save();
        } */
        return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => new DistrictResource($district)
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
      'district_id' => 'required|exists:districts,id'
    ]);

    if ($validator->fails())
    {
      return response()->json([
        'status'=>0,
        'message'=> $validator->errors()->first()
      ]);
    }

    try
    {
      $district = District::findOrFail($request->district_id);
      $district->update($request->all());

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new DistrictResource($district)
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

  public function delete(Request $request)
  {
    $validator = Validator::make($request->all(),
    [
      'district_id' => 'required|exists:districts,id'
    ]);

    if ($validator->fails())
    {
      return response()->json([
        'status'=>0,
        'message'=> $validator->errors()->first()
      ]);
    }

    try
    {
      $district = District::findOrFail($request->district_id);
      $district->delete();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new DistrictResource($district)
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

  public function get(Request $request) //paginated
  {
    $validator = Validator::make($request->all(), [
      'wilaya_id' => 'sometimes|exists:wilayas,id',
      'search' => 'sometimes|string',

    ]);

    if ($validator->fails())
    {
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try
    {

      $districts = District::orderBy('created_at','DESC');

      if($request->has('wilaya_id'))
      {
        $districts = $districts->where('wilaya_id',$request->wilaya_id);
      }

      if($request->has('search'))
      {
        $districts = $districts->where('name', 'like', '%' . $request->search . '%');
      }

      $districts = $request->has('all') ? $districts->get() : $districts->paginate(10);

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => $districts
      ]);

    }
    catch(Exception $e)
    {
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
        ]
      );
    }

  }


}
