<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\settings\SettingResource;
use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class settingController extends Controller
{
    public function index()
    {
      $settings = Setting::first();
      return view('content.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'setting_id' =>'required',
        'price_max'  =>'required|numeric',
        'bank_account_bankily' =>'required|numeric',
        'bank_account_sedad'   =>'required|numeric',
        'bank_account_bimbank' =>'required|numeric',
        'bank_account_masrfy'  =>'required|numeric',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'status'=> 0,
          'message' => $validator->errors()->first()
        ]);
      }

      try
      {
        $settings = Setting::findOrFail($request->setting_id);
        $settings->update($request->all());

        return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => new SettingResource($settings)
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

    public function get()
    {
      try
      {
        $settings = Setting::first();
        // dd($settings);
        return response()->json([
        'status' => 1,
          'data' => new SettingResource($settings)
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
