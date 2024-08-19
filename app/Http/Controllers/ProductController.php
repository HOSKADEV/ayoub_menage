<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Suppliers;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Models\Products_media;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Session ;
use App\Http\Resources\ProductCollection;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProdectsMediaResource;
use App\Http\Resources\ProductWithMediaResource;
use App\Http\Resources\PaginatedProductCollection;
use App\Http\Resources\PaginatedProductWithMediaCollection;

class ProductController extends Controller
{

  public function index()
  {
    $settings = Setting::first();
    $categories = Category::all();
    $suppliers  = Suppliers::where('status', 1)->get();
    return view('content.products.list', compact('suppliers','settings'))
    ->with('categories',$categories);
  }

  public function details()
  {
    $products = Product::all();
    return new ProductWithMediaResource($products);
  }

  public function create(Request $request)
  {
      $validator = Validator::make($request->all(), [
        // 'images'  => 'sometimes|mimetypes:image/*',
        // 'filesvideos'  => 'required|mimes:mp4,mov,avi|max:10240',
        // 'code_bar'        => 'required',
        'unit_name'   => 'required|string',
        'pack_name'   => 'sometimes|string',
        'supplier_id' => 'required',
        'code_supplier'   => 'sometimes',
        'purchasing_price'  => 'required|numeric',
        'unit_price'  => 'required|numeric',
        'unit_type'   => 'required|in:1,2,3',
        'quantity'  => 'required|integer',
        'subcategory_id'    => 'required|exists:subcategories,id',
        //'pack_price'  => 'required_with:pack_units|nullable|numeric',
        //'pack_units'  => 'required_with:pack_price|nullable|integer',
        'status'      => 'required|in:1,2',
        //'description'   => 'sometimes|string',
      ]);

      if ($validator->fails())
      {
        return response()->json([
          'status'=> 0,
          'message' => $validator->errors()->first()
        ]);
      }

    try{
      // $product = Product::create($request->except('image'));

      // insert data products
      $product = Product::create(
        [
          'subcategory_id' => $request->input('subcategory_id'),
          'supplier_id' => $request->input('supplier_id'),
          'unit_name' => $request->input('unit_name'),
          'pack_name' => $request->input('pack_name'),
          'purchasing_price' => $request->input('purchasing_price'),
          'unit_price' => $request->input('unit_price'),
          'pack_price' => $request->input('pack_price'),
          'pack_units' => $request->input('pack_units'),
          'unit_type' => $request->input('unit_type'),
          'quantity' => $request->input('quantity'),
          'status' => $request->input('status'),
          'description' => $request->input('description'),
          'code_supplier' => $request->input('code_supplier'),
          // 'code_bar' => $request->input('code_bar'),
        ]
      );

      $dataImage  = [];
      $datavideos = [];
      if ($request->imagesInc > 0)
      {
          for ($x = 0; $x < $request->imagesInc; $x++) {
            if ($request->hasFile('files' . $x))
            {
                $file = $request->file('files' . $x);
                $name = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();

                $filename = 'products/images/' . $product->id . '/' . md5(time().$name) . '.' . $extension;
                $url = $this->firestore($file->get(),$filename);
                $check =  Products_media::where('products_id', $product->id)->first();
                if ($check)
                {
                  $productsMedia = new  Products_media();
                  $productsMedia->images = $url;
                  $productsMedia->products_id = $product->id;
                  $productsMedia->save();

                }
                else
                {
                  $productsMedia = Products_media::create([
                    'products_id' => $product->id,
                    'images' => $url,
                  ]);
                }

                $dataImage[] =  $url;
            }
          }
      }
      if ($request->videosInc > 0)
      {
        for ($x = 0; $x < $request->videosInc; $x++) {
          if ($request->hasFile('filesvideos' . $x))
          {
              $file = $request->file('filesvideos' . $x);
              $name = $file->getClientOriginalName();
              $extension = $file->getClientOriginalExtension();

              $filename = 'products/videos/' . $product->id . '/' . md5(time().$name) . '.' . $extension;
              $url = $this->firestore($file->get(),$filename);

              $check =  Products_media::where('products_id', $product->id)->first();
              if ($check)
              {
                $productsMedia = new  Products_media();
                $productsMedia->videos = $url;
                $productsMedia->products_id = $product->id;
                $productsMedia->save();

              }
              else
              {
                $productsMedia = Products_media::create([
                  'products_id' => $product->id,
                  'videos' => $url,
                ]);
              }
              $datavideos[] =  $url;
          }
        }
      }

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'images' => $dataImage,
        'videos' => $datavideos,
        'data' => new ProductResource($product),
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

  public function update(Request $request){

    $validator = Validator::make($request->all(), [
      'product_id' => 'required|exists:products,id',
      'unit_name'  => 'sometimes|string',
      'pack_name'  => 'sometimes|string',
      'unit_price' => 'sometimes|numeric',
      'unit_type'  => 'sometimes|in:1,2,3',
      //'pack_price' => 'required_with:pack_units|nullable|numeric',
      //'pack_units' => 'required_with:pack_price|nullable|integer',
      'quantity'  => 'sometimes|integer',
      'status' => 'sometimes|in:1,2',
      // 'image'      => 'sometimes|mimetypes:image/*',
      // 'description'  => 'required|string',
      // 'video' => 'required|mimes:mp4,mov,avi|max:10240',
      // 'purchasing_price' => 'required|numeric',
      // 'name_supplier' => 'required|string',

    ]);
    // dd(  $validator);
    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{
      // return  product id
      $product = Product::findOrFail($request->product_id);

      if ($request->input('unit_name'))
      {

       // updated data products
        $product->subcategory_id = $request->input('subcategory_id');
        $product->supplier_id = $request->input('supplier_id');
        $product->unit_name   = $request->input('unit_name');
        $product->pack_name   = $request->input('pack_name');
        $product->purchasing_price = $request->input('purchasing_price');
        $product->unit_price  = $request->input('unit_price');
        $product->pack_price  = $request->input('pack_price');
        $product->unit_type   = $request->input('unit_type');
        $product->quantity   = $request->input('quantity');
        $product->status      = $request->input('status');
        $product->description = $request->input('description');
        $product->code_supplier = $request->input('code_supplier');
        // $product->code_bar = $request->input('code_bar');
        $product->update();
      }

      $dataImage  = [];
      $datavideos = [];
      if ($request->imagesInc > 0)
      {
        for ($x = 0; $x < $request->imagesInc; $x++) {
          if ($request->hasFile('files' . $x))
          {
              $file = $request->file('files' . $x);
              $name = $file->getClientOriginalName();
              $extension = $file->getClientOriginalExtension();

              $filename = 'products/images/' . $request->product_id . '/' . md5(time().$name) . '.' . $extension;
              $url = $this->firestore($file->get(),$filename);
                $productsMedia = Products_media::create([
                  'products_id' => $request->product_id,
                  'images' => $url,
                ]);
              $dataImage[] =  $url;
          }
        }
      }
      if ($request->videosInc > 0)
      {
        for ($x = 0; $x < $request->videosInc; $x++) {
          if ($request->hasFile('filesvideos' . $x))
          {
            $file = $request->file('filesvideos' . $x);
            $name = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $filename = 'products/videos/' . $request->product_id . '/' . md5(time().$name) . '.' . $extension;
            $url = $this->firestore($file->get(),$filename);

            $productsMedia = Products_media::create([
              'products_id' => $request->product_id,
              'videos' => $url,
            ]);

              $datavideos[] =  $url;
          }
        }
      }
      $product->save();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'images' => $dataImage,
        'videos' => $datavideos,
        'data' => new ProductResource($product)
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

  public function delete(Request $request){

    $validator = Validator::make($request->all(), [
      'product_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $product = Product::findOrFail($request->product_id);

      $productMedia = Products_media::where('products_id',$request->product_id)->get();

      foreach($productMedia as $pm)
      {
        $pm->delete();
      }

      $product->delete();

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

  public function deletedMedia(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'product_media_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    $productMedia = Products_media::findOrFail($request->product_media_id);
    $productMedia->delete();

      return response()->json([
        'status' => 1,
        'message' => 'success',
      ]);
  }

  public function restore(Request $request){

    $validator = Validator::make($request->all(), [
      'product_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $product = Product::withTrashed()->findOrFail($request->product_id);

      $product->restore();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new ProductResource($product)
      ]);

    }catch(Exception $e){
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
    );
    }

  }

  public function get(Request $request)
  {  //paginated
    $validator = Validator::make($request->all(), [
      'supplier_id' => 'sometimes|exists:suppliers,id',
      'category_id' => 'sometimes|missing_with:subcategory_id|exists:categories,id',
      'subcategory_id' => 'sometimes|exists:subcategories,id',
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


      if(!is_null($request->bearerToken())){
        Session::put('user_id', $this->get_user_from_token($request->bearerToken())->id);
      }

      $products = Product::where('status','available')->orderBy('created_at','DESC');

      if($request->has('supplier_id')){

        $products = $products->where('supplier_id',$request->supplier_id);
      }

      if($request->has('category_id')){

        $category = Category::findOrFail($request->category_id);
        $category_subs = $category->subcategories()->pluck('id')->toArray();
        $products = $products->whereIn('subcategory_id',$category_subs);
      }

      if($request->has('subcategory_id')){

        $subcategory = Subcategory::findOrFail($request->subcategory_id);
        $sub_products = $subcategory->products()->pluck('id')->toArray();
        $products = $products->whereIn('id',$sub_products);
      }

      if($request->has('search')){

        $products = $products->where('unit_name', 'like', '%' . $request->search . '%');
                              //->orWhere('pack_name', 'like', '%' . $request->search . '%');
      }

      if($request->has('all')){
        $products = $products->get();
        return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => $products
        ]);

      }
      $products = $products->paginate(10);


      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new PaginatedProductCollection($products)
      ]);
  }

    catch(Exception $e){
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
    );
    }
  }
  public function getDetails(Request $request)
  {  //paginated
    $validator = Validator::make($request->all(), [
      'category_id' => 'sometimes|missing_with:subcategory_id|exists:categories,id',
      'subcategory_id' => 'sometimes|exists:subcategories,id',
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


      if(!is_null($request->bearerToken())){
        Session::put('user_id', $this->get_user_from_token($request->bearerToken())->id);
      }

      $products = Product::where('status','available')->orderBy('created_at','DESC');

      if($request->has('category_id')){

        $category = Category::findOrFail($request->category_id);
        $category_subs = $category->subcategories()->pluck('id')->toArray();
        $products = $products->whereIn('subcategory_id',$category_subs);
      }

      if($request->has('subcategory_id')){

        $subcategory = Subcategory::findOrFail($request->subcategory_id);
        $sub_products = $subcategory->products()->pluck('id')->toArray();
        $products = $products->whereIn('id',$sub_products);
      }

      if($request->has('search')){

        $products = $products->where('unit_name', 'like', '%' . $request->search . '%');
                              //->orWhere('pack_name', 'like', '%' . $request->search . '%');
      }

      if($request->has('all')){
        $products = $products->get();
        return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => $products
        ]);

      }
      $products = $products->paginate(10);


      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new PaginatedProductWithMediaCollection($products)
      ]);
    }

    catch(Exception $e){
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
    );
    }
  }
}
