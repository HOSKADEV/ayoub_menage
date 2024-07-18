<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaginatedProductCollection;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SimilarProductController extends Controller
{
    public function index(Request $request)
    {
      // Get the category ans subcategory id test from the request parameters.
      $validator = Validator::make($request->all(),
      [
        'category_id' => 'required|exists:categories,id',
        'subcategory_id' => 'required|exists:subcategories,id',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]);
      }
      try
      {
        // create array for append products
        $productsArray = [];

        $products = Product::where('subcategory_id', $request->subcategory_id)->orderBy('created_at', 'desc')->take(10)->get();

        // foreach($products as $product)
        // {
        //   $productsArray[] = $product;
        // }

        // test array equel 10 product identical or similar
        if (count($products) == 10)
        {
          $products = $products->paginate(10);
          return response()->json([
            'status' => 1,
            'message' => 'success',
            'data' => new PaginatedProductCollection($products)
          ]);
        }

        $subcategory = Subcategory::where('category_id',$request->category_id)->get();
        // append array it is not equal 10 from category
        foreach ($subcategory as $ids)
        {
          $products = Product::where('subcategory_id', $ids->id)->orderBy('created_at', 'desc')->take(10)->get();
          // $category_id[] = $ids->id;
          foreach($products as $product)
          {
            $productsArray[] = $product;
          }
        }
        // in case not equal 10 random product and append to array
        $randomProducts = Product::inRandomOrder()->take(10)->get();

        foreach($randomProducts as $product)
        {
          $productsArray[] = $product;
        }
        //  select 10 products
        // $productsArray = array_slice($productsArray, 0, 10);

        // Define the number of items per page
        $perPage = 10;

        // Create a collection from the array
        $dataCollection = new Collection($productsArray);
        // Get the current page from the query parameters or default to 1
        $currentPage = request()->get('page', 1);

        // Manually slice the array to get the items for the current page
        $currentPageItems = $dataCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        // Create a LengthAwarePaginator instance
        $paginatedData = new LengthAwarePaginator($currentPageItems, count($dataCollection), $perPage, $currentPage);
        return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => new PaginatedProductCollection($paginatedData)
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
