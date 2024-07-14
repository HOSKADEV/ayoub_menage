<?php

namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;
use App\Models\Suppliers;
use Illuminate\Http\Request;

class SuooliersController extends Controller
{
    public function search(Request $request)
    {
      $query = $request->input('query');

      $suppliers = Suppliers::where('fullname', 'LIKE', '%' . $query . '%')->get();
      return response()->json($suppliers);
    }
}
