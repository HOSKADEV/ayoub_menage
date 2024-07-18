<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategorySubcategory\CategorySubcategoryCollection;
use App\Http\Resources\CategorySubcategory\PaginatedCategorySubcategoryCollection;
use App\Http\Resources\PaginatedSubcategoryCollection;
use App\Http\Resources\SubcategoryCollection;
use App\Http\Resources\SubcategoryResource;
use App\Models\Category;
use App\Models\CategorySubcategory;
use App\Models\Subcategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SubcategoryController extends Controller
{

  public function index()
  {
    $categories = Category::all();
    return view('content.subcategories.list')
    ->with('categories',$categories);
  }

  public function create(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'image' => 'required',
      // 'category_id' => 'required|exists:categories,id',
      'name' => 'required|string',
      'categories' => 'required|array',
      'categories.*' => 'distinct'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status'=> 0,
        'message' => $validator->errors()->first()
      ]);
    }
    try{

      $subcategory = Subcategory::create($request->except('image'));
      $categories_ids = $request->categories;
      $firstId = reset($categories_ids);
      // dd($firstId);
      $subcategory->category_id = $firstId;
      $subcategory->update();

      foreach ($request->categories as $cate_id)
      {
        $categorySubcategory = new CategorySubcategory();
        $categorySubcategory->category_id = $cate_id;
        $categorySubcategory->subcategory_id = $subcategory->id;
        $categorySubcategory->save();
      }

      if($request->hasFile('image'))
      {
        //$path = $request->image->store('/uploads/categories/images','upload');
        $file = $request->image;
        $name = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        $filename = 'subcategory/' . $subcategory->id . '/' . md5(time().$name) . '.' . $extension;

        $url = $this->firestore($file->get(),$filename);

        $subcategory->image = $url;
        $subcategory->save();
      }
      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new SubcategoryResource($subcategory)
      ]);

    }
    catch(Exception $e){
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]);
    }
  }

  public function update(Request $request){

    $validator = Validator::make($request->all(), [
      'subcategory_id' => 'required',
      // 'category_id' => 'sometimes|exists:categories,id',
      'name' => 'sometimes|string',
      // 'categories' => 'required|array',
      // 'categories.*' => 'distinct'
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $subcategory = Subcategory::findOrFail($request->subcategory_id);

      DB::beginTransaction();

      $subcategory->update($request->except('subcategory_id', 'image' ));

      if($request->has('categories'))
      {
        foreach($subcategory->categorySubcategory as $category_subcategory)
        {
          // $category_subcategory->delete();
          $category_subcategory->forceDelete();
        }
        foreach($request->categories as $category)
        {
          $categorySubcategory = new CategorySubcategory();
          $categorySubcategory->category_id = $category;
          $categorySubcategory->subcategory_id = $subcategory->id;
          $categorySubcategory->save();
        }
      }

      if($request->hasFile('image'))
      {
        //$path = $request->image->store('/uploads/categories/images','upload');
        $file = $request->image;
        $name = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        $filename = 'subcategory/' . $subcategory->id . '/' . md5(time().$name) . '.' . $extension;

        $url = $this->firestore($file->get(),$filename);

        $subcategory->image = $url;
        $subcategory->save();
      }
      DB::commit();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new SubcategoryResource($subcategory)
      ]);

    }catch(Exception $e){
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
    );
    }

  }

  public function delete(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'subcategory_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $subcategory = Subcategory::findOrFail($request->subcategory_id);

      $categorySubcategories = CategorySubcategory::where('subcategory_id',$request->subcategory_id)->get();
      foreach($categorySubcategories as $categorySubcategory)
      {
        $categorySubcategory->delete();
      }
      // dd($categorySubcategory);
      $subcategory->delete();

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

  public function restore(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'subcategory_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $subcategory = Subcategory::withTrashed()->findOrFail($request->subcategory_id);

      $subcategory->restore();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new SubcategoryResource($subcategory)
      ]);

    }catch(Exception $e){
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
    );
    }

  }

  public function get(Request $request) //paginated
  {
    $validator = Validator::make($request->all(), [
      'category_id' => 'sometimes',
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

      $subcategories = Subcategory::orderBy('created_at','DESC');

      if($request->has('category_id'))
      {
        // $category = Category::findOrFail($request->category_id);
        // $category_subs = $category->subcategories()->pluck('id')->toArray();
        $category_subs = CategorySubcategory::where('category_id',$request->category_id)->pluck('subcategory_id')->toArray();
        $subcategories = $subcategories->whereIn('id',$category_subs);
      }

      if($request->has('search'))
      {
        $subcategories = $subcategories->where('name', 'like', '%' . $request->search . '%');
      }

      if($request->has('all'))
      {
        $subcategories = $subcategories->get();
        return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => new SubcategoryCollection($subcategories)
        ]);
      }
      $subcategories = $subcategories->paginate(10);

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new PaginatedSubcategoryCollection($subcategories)
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

  public function gatCategorySubcategory(Request $request)
  {
    $categorySubcategory = CategorySubcategory::all();
    $categorySubcategory = $categorySubcategory->paginate(10);

    return response()->json([
      'status' => 1,
      'data' => new PaginatedCategorySubcategoryCollection($categorySubcategory)
    ]);
  }

  public function showCategory(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'subcategory_id' => 'sometimes',

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
      // $categorySubcategory = CategorySubcategory::where('subcategory_id', $request->subcategory_id)->get();
      $categories = Category::orderBy('created_at','DESC');
      $categorySubcategory = CategorySubcategory::where('subcategory_id', $request->subcategory_id)->pluck('category_id')->toArray();
      $categories = $categories->whereIn('id',$categorySubcategory);
      $categories = $categories->get();
      // dd($categories);
      return response()->json([
          'status' => 1,
          'data' => $categories
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
