<?php

namespace App\Http\Controllers;


use Session;
use App\Models\Ad;
use App\Models\Cart;
use App\Models\User;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Family;
use App\Models\Notice;
use App\Models\Wilaya;
use App\Models\Product;
use App\Models\Section;
use App\Models\Category;
use App\Models\Discount;
use App\Models\District;
use App\Models\Suppliers;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\CategorySubcategory;


class DatatablesController extends Controller
{
  public function categories()
  {

    $categories = Category::orderBy('created_at', 'DESC')->get();

    return datatables()
      ->of($categories)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
        $btn = '';

        $btn .= '<a class="dropdown-item-inline update" title="' . __('Edit') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bxs-edit me-2"></i></a>';

        $btn .= '<a class="dropdown-item-inline delete" title="' . __('Delete') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bxs-trash me-2"></i></a>';

        return $btn;
      })

      ->addColumn('subcategories', function ($row) {
        // return 10;
        return number_format($row->subcate()->count());

      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d', strtotime($row->created_at));

      })


      ->make(true);
  }

  public function subcategories(Request $request)
  {
    $subcategories = Subcategory::orderBy('created_at', 'DESC');

    if (!empty($request->category)) {
      $categorySubcategories = CategorySubcategory::where('category_id', $request->category)->pluck('subcategory_id')->toArray();
      $subcategories = $subcategories->whereIn('id', $categorySubcategories);
    }

    $subcategories = $subcategories->get();

    return datatables()
      ->of($subcategories)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
        $btn = '';

        $btn .= '<a class="dropdown-item-inline category" title="' . __('category') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-category"></i></a>';

        $btn .= '<a class="dropdown-item-inline update" title="' . __('Edit') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bxs-edit me-2"></i></a>';

        $btn .= '<a class="dropdown-item-inline delete" title="' . __('Delete') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bxs-trash me-2"></i></a>';

        return $btn;
      })

      ->addColumn('category', function ($row) {
        return $row->cate()->count();
      })
      ->addColumn('products', function ($row) {

        return $row->products()->count();
      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d', strtotime($row->created_at));

      })

      ->make(true);
  }


  public function families()
  {

    $families = Family::orderBy('created_at', 'DESC')->get();

    return datatables()
      ->of($families)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
        $btn = '';

        $btn .= '<a class="dropdown-item-inline update" title="' . __('Edit') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bxs-edit me-2"></i></a>';

        if (is_null($row->section())) {

          $btn .= '<a class="dropdown-item-inline delete" title="' . __('Delete') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bxs-trash me-2"></i></a>';

          $btn .= '<a class="dropdown-item-inline add_to_home" title="' . __('Add to Homepage') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bxs-plus-square me-2"></i></a>';

        } else {

          $btn .= '<a class="dropdown-item-inline remove_from_home" title="' . __('Remove from Homepage') . '" table_id="' . $row->section()->id . '" href="javascript:void(0);"><i class="bx bxs-x-square me-2"></i></a>';

        }

        return $btn;
      })

      ->addColumn('categories', function ($row) {

        return $row->categories()->count();

      })

      ->addColumn('is_published', function ($row) {

        if (is_null($row->section())) {
          return false;
        }
        return true;

      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d', strtotime($row->created_at));

      })


      ->make(true);
  }

  public function offers()
  {

    $offers = Offer::orderBy('created_at', 'DESC')->get();

    return datatables()
      ->of($offers)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
        $btn = '';

        $btn .= '<a class="dropdown-item-inline update" title="' . __('Edit') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bxs-edit me-2"></i></a>';

        if (is_null($row->section())) {

          $btn .= '<a class="dropdown-item-inline delete" title="' . __('Delete') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bxs-trash me-2"></i></a>';

          $btn .= '<a class="dropdown-item-inline add_to_home" title="' . __('Add to Homepage') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bxs-plus-square me-2"></i></a>';

        } else {

          $btn .= '<a class="dropdown-item-inline remove_from_home" title="' . __('Remove from Homepage') . '" table_id="' . $row->section()->id . '" href="javascript:void(0);"><i class="bx bxs-x-square me-2"></i></a>';

        }

        return $btn;
      })

      ->addColumn('categories', function ($row) {

        return $row->categories()->count();

      })

      ->addColumn('is_published', function ($row) {

        if (is_null($row->section())) {
          return false;
        }
        return true;

      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d', strtotime($row->created_at));

      })


      ->make(true);
  }

  public function products(Request $request)
  {

    $products = Product::orderBy('created_at', 'DESC');

    if (!empty($request->category)) {
      $category = Category::findOrFail($request->category);
      $category_subs = CategorySubcategory::where('category_id', $category->id)->pluck('subcategory_id')->toArray();
      // dd($category_subs);
      // $category_subs = $category->subcate()->pluck('id')->toArray();
      $products = $products->whereIn('subcategory_id', $category_subs);
    }


    if (!empty($request->subcategory)) {

      $products = $products->where('subcategory_id', $request->subcategory);
    }

    if (!empty($request->discount)) {
      if ($request->discount == "1") {
        $discounts = Discount::WhereRaw('? between start_date and end_date', Carbon::now()->toDateString())
          ->pluck('product_id')->toArray();
        $products = $products->whereIn('id', $discounts);
      }

      if ($request->discount == "2") {
        $discounts = Discount::WhereRaw('? between start_date and end_date', Carbon::now()->toDateString())
          ->pluck('product_id')->toArray();
        $products = $products->whereNotIn('id', $discounts);
      }

    }

    $products = $products->get();

    return datatables()
      ->of($products)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
        $btn = '';

        $btn .= '<a class="dropdown-item-inline update" title="' . __('Edit') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bxs-edit me-2"></i></a>';

        $btn .= '<a class="dropdown-item-inline delete" title="' . __('Delete') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bxs-trash me-2"></i></a>';

        /* if (is_null($row->discount())) {

          $btn .= '<a class="dropdown-item-inline add_discount" title="' . __('Add discount') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bxs-message-alt-add me-2"></i></a>';

        } else {

          $btn .= '<a class="dropdown-item-inline edit_discount" title="' . __('Edit discount') . '" table_id="' . $row->discount()->id . '" href="javascript:void(0);"><i class="bx bxs-message-alt-edit me-2"></i></a>';

          $btn .= '<a class="dropdown-item-inline delete_discount" title="' . __('Delete discount') . '" table_id="' . $row->discount()->id . '" href="javascript:void(0);"><i class="bx bxs-message-alt-x me-2"></i></a>';

        } */

        return $btn;
      })

      ->addColumn('name', function ($row) {

        return $row->unit_name;

      })

      ->addColumn('purchasing_price', function ($row) {

        return number_format($row->purchasing_price, 2, '.', ',') . ' Dzd';

      })

      ->addColumn('selling_price', function ($row) {

        return number_format($row->unit_price, 2, '.', ',') . ' Dzd';

      })

      ->addColumn('quantity', function ($row) {

        return $row->quantity;
      })

      /* ->addColumn('is_discounted', function ($row) {

        if (is_null($row->discount())) {
          return false;
        }
        return true;

      })

      ->addColumn('discount', function ($row) {

        if (is_null($row->discount())) {
          return '';
        }

        return $row->discount()->amount . '%';

      }) */

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d', strtotime($row->created_at));

      })


      ->make(true);
  }

  public function sections(Request $request)
  {

    $sections = Section::orderBy('rank', 'ASC')->get();

    return datatables()
      ->of($sections)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
        $btn = '';

        if ($row->deleteable == 1) {
          $btn .= '<a class="dropdown-item-inline delete" title="' . __('Remove') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-x me-2"></i></a>';
        }

        if ($row->moveable == 1) {
          $btn .= '<a class="dropdown-item-inline switch" title="' . __('Switch') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-refresh me-2"></i></a>';

          $btn .= '<a class="dropdown-item-inline insert" title="' . __('Insert') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-redo me-2"></i></a>';

        }

        return $btn;
      })

      ->addColumn('type', function ($row) {

        return $row->type;

      })

      ->addColumn('name', function ($row) {

        return $row->name();

      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d', strtotime($row->created_at));

      })


      ->make(true);
  }

  public function orders(Request $request)
  {


    $orders = Order::orderBy('created_at', 'DESC');

    if (!empty($request->status)) {
      if ($request->status == 'default') {
        $orders = $orders->whereNotIn('status', ['delivered', 'canceled']);
      } else {
        $orders = $orders->where('status', $request->status);
      }

    }

    $orders = $orders->get();

    return datatables()
      ->of($orders)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
        $btn = '';

        $btn .= '<a class="dropdown-item-inline note" title="' . __('Note') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-note me-2"></i></a>';

        $btn .= '<a class="dropdown-item-inline delete" title="' . __('Delete') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-trash me-2"></i></a>';

        $btn .= '<a class="dropdown-item-inline bank" title="' . __('Bank') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-money me-2"></i></a>';

        $btn .= '<a class="dropdown-item-inline invoiceSupplier" title="' . __('Invoice supplier') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bxs-file me-2"></i></a>';

        if ($row->status == 'pending') {

          $btn .= '<a class="dropdown-item-inline accept" title="' . __('Approve') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-check me-2"></i></a>';

          $btn .= '<a class="dropdown-item-inline refuse" title="' . __('Cancel') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-x me-2"></i></a>';

        }

        if ($row->status == 'accepted') {

          $btn .= '<a class="dropdown-item-inline ship" title="' . __('Ship') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bxs-truck me-2"></i></a>';

        }
        if ($row->status == 'ongoing') {
          $btn .= '<a class="dropdown-item-inline delivered" title="' . __('Delivered') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-package me-2"></i></a>';
        }

        if (!in_array($row->status, ['pending', 'canceled'])) {
          if (!is_null($row->invoice)) {
            $btn .= '<a class="dropdown-item-inline " target="_blank" title="' . __('whatsapp') . '"
              href="https://api.whatsapp.com/send?text=' .
              __('order N') . ': ' . $row->id . '%0A' .
              __('Total amount') . ': ' . number_format($row->invoice->total_amount, 2, '.', ',') . ' Dzd %0A' .
              __('Payment method') . ': ' . $row->payement_method . '%0A' .
              // __('Wilaya')      .': '.$row->wilayas->name.
              // __('Districts')   .': '.$row->district.
              __('Phone') . ': ' . $row->phone() . '%0A' .
              __('Invoice') . ': ' . asset($row->invoice->file) . '%0A' .
              __('Location') . ': ' . $row->address() . '"><i class="bx bxl-whatsapp"></i></a>';

            $btn .= '<a class="dropdown-item-inline invoice" title="' . __('Invoice') . '" table_id="' . $row->invoice->id . '" href="javascript:void(0);"><i class="bx bx-file me-2"></i></a>';

            if ($row->status == 'ongoing' && $row->invoice->is_paid == 'no') {

              // $btn .= '<a class="dropdown-item-inline payment" title="'.__('Payment').'" table_id="'.$row->id.'" href="javascript:void(0);"><i class="bx bx-money me-2"></i></a>';

            }

          }
        }

        $btn .= '<a class="dropdown-item-inline" title="' . __('Location') . '" href="' . $row->address() . '" target="_blank" ><i class="bx bx-map me-2"></i></a>';

        $btn .= '<a class="dropdown-item-inline" title="' . __('Cart') . '" href="' . url('order/' . $row->id . '/items') . '"><i class="bx bx-cart me-2"></i></a>';

        // $btn .= '<a class="dropdown-item-inline" title="'.__('Bank').'" href="'.url('order/'.$row->id.'/bank').'">Bank</a>';


        return $btn;
      })

      ->addColumn('user', function ($row) {

        return $row->user->fullname();

      })

      ->addColumn('client', function ($row) {

        return $row->client->name;

      })

      ->addColumn('phone', function ($row) {

        return $row->phone();

      })

      ->addColumn('status', function ($row) {

        return $row->status;

      })

      ->addColumn('driver', function ($row) {

        if (!is_null($row->delivery)) {
          return $row->delivery->driver->fullname();
        }

      })

      ->addColumn('purchase_amount', function ($row) {

        if (!is_null($row->invoice)) {
          return number_format($row->invoice->purchase_amount, 2, '.', ',') . ' Dzd';
        }

      })

      ->addColumn('tax_amount', function ($row) {

        if (!is_null($row->invoice)) {
          return number_format($row->invoice->tax_amount, 2, '.', ',') . ' Dzd';
        }

      })

      ->addColumn('total_amount', function ($row) {

        if (!is_null($row->invoice)) {
          return number_format($row->invoice->total_amount, 2, '.', ',') . ' Dzd';
        }

      })

      ->addColumn('ccp_account', function ($row) {


        return $row->ccp_acount;


      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d', strtotime($row->created_at));

      })


      ->make(true);
  }

  public function items(Request $request)
  {

    $cart = Cart::findOrFail($request->cart_id);
    $items = $cart->items()->orderBy('created_at', 'DESC')->get();

    return datatables()
      ->of($items)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
        $btn = '';

        $btn .= '<a class="dropdown-item-inline delete" title="' . __('Delete') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-trash me-2"></i></a>';

        $btn .= '<a class="dropdown-item-inline edit" title="' . __('Edit') . '" table_id="' . $row->id . '" quantity="' . $row->quantity . '"href="javascript:void(0);"><i class="bx bx-edit me-2"></i></a>';

        return $btn;
      })

      ->addColumn('product', function ($row) {

        return $row->name();

      })


      ->addColumn('price', function ($row) {

        return number_format($row->price(), 2, '.', ',');

      })

      ->addColumn('type', function ($row) {

        return $row->type;

      })

      ->addColumn('quantity', function ($row) {

        return $row->quantity;

      })

      ->addColumn('discount', function ($row) {

        return $row->discount . '%';

      })

      ->addColumn('amount', function ($row) {

        return number_format($row->amount, 2, '.', ',');

      })


      ->make(true);
  }

  public function drivers()
  {

    $drivers = Driver::orderBy('created_at', 'DESC')->get();

    return datatables()
      ->of($drivers)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
        $btn = '';

        $btn .= '<a class="dropdown-item-inline update" title="' . __('Edit') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bxs-edit me-2"></i></a>';

        $btn .= '<a class="dropdown-item-inline delete" title="' . __('Delete') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bxs-trash me-2"></i></a>';

        return $btn;
      })

      ->addColumn('name', function ($row) {

        return $row->fullname();

      })

      ->addColumn('phone', function ($row) {

        return $row->phone();

      })

      ->addColumn('status', function ($row) {

        return $row->status();

      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d', strtotime($row->created_at));

      })


      ->make(true);
  }

  public function users()
  {

    $users = User::where('role', 0)->whereIn('status', [0, 1])->get();

    return datatables()
      ->of($users)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
        $btn = '';

        if ($row->status == 1) {
          $btn .= '<a class="dropdown-item-inline delete" title="' . __('Block') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-x-circle me-2"></i></a>';
        } else {
          $btn .= '<a class="dropdown-item-inline restore" title="' . __('Activate') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-check-circle me-2"></i></a>';
        }




        return $btn;
      })

      ->addColumn('name', function ($row) {

        return $row->fullname();

      })

      ->addColumn('phone', function ($row) {

        return $row->phone();

      })

      ->addColumn('email', function ($row) {

        return $row->email;

      })

      ->addColumn('status', function ($row) {

        if ($row->status == 1) {
          return true;
        } else {
          return false;
        }

      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d', strtotime($row->created_at));

      })


      ->make(true);
  }
  public function suppliers()
  {

    $suppliers = Suppliers::all();

    return datatables()
      ->of($suppliers)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
        $btn = '';

        if ($row->status == 1) {
          $btn .= '<a class="dropdown-item-inline update" title="' . __('Edit') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-edit me-2"></i></a>';

          $btn .= '<a class="dropdown-item-inline delete" title="' . __('Block') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-x-circle me-2"></i></a>';

        } else {
          $btn .= '<a class="dropdown-item-inline restore" title="' . __('Activate') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-check-circle me-2"></i></a>';
        }
        return $btn;
      })

      ->addColumn('name', function ($row) {

        return $row->fullname;

      })
      ->addColumn('phone', function ($row) {

        return $row->phone;

      })

      ->addColumn('status', function ($row) {

        if ($row->status == 1) {
          return true;
        } else {
          return false;
        }

      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d', strtotime($row->created_at));

      })


      ->make(true);
  }

  public function notices()
  {

    $notices = Notice::orderBy('created_at', 'DESC')->get();

    return datatables()
      ->of($notices)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
        $btn = '';

        $btn .= '<a class="dropdown-item-inline view" title="' . __('View') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-show me-2"></i></a>';

        $btn .= '<a class="dropdown-item-inline delete" title="' . __('Delete') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-trash me-2"></i></a>';

        return $btn;
      })

      ->addColumn('title', function ($row) {

        if (Session::get('locale') == 'en') {
          return $row->title_en;
        }

        return $row->title_ar;
      })

      ->addColumn('type', function ($row) {

        return $row->type;

      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d', strtotime($row->created_at));

      })


      ->make(true);
  }

  public function ads()
  {

    $ads = Ad::orderBy('created_at', 'DESC')->get();

    return datatables()
      ->of($ads)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
        $btn = '';

        $btn .= '<a class="dropdown-item-inline update" title="' . __('Edit') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-edit me-2"></i></a>';

        $btn .= '<a class="dropdown-item-inline delete" title="' . __('Delete') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-trash me-2"></i></a>';

        return $btn;
      })

      ->addColumn('name', function ($row) {
        return $row->name;
      })


      ->addColumn('created_at', function ($row) {

        return date('Y-m-d', strtotime($row->created_at));

      })


      ->make(true);
  }

  public function wilayas(Request $request)
  {
    $wilayas = Wilaya::orderBy('created_at', 'DESC')->get();

    return datatables()
      ->of($wilayas)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
        $btn = '';

        $btn .= '<a class="dropdown-item-inline update" title="' . __('Edit') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-edit me-2"></i></a>';

        $btn .= '<a class="dropdown-item-inline delete" title="' . __('Delete') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-trash me-2"></i></a>';

        return $btn;
      })

      ->addColumn('name', function ($row) {
        return $row->name;
      })

      ->addColumn('delivery_pricce', function ($row) {
        return number_format($row->delivery_price, 2, '.', ',') . ' Dzd';
      })

      ->addColumn('district', function ($row) {

        return $row->district()->count();

      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d', strtotime($row->created_at));

      })

      ->make(true);
  }
  public function districts(Request $request)
  {
    $district = District::orderBy('created_at', 'DESC');
    // dd($request->wilaya);
    if (!empty($request->wilaya)) {
      $district->where('wilaya_id', $request->wilaya);
    }
    // dd($district);
    $district = $district->get();

    return datatables()
      ->of($district)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
        $btn = '';

        $btn .= '<a class="dropdown-item-inline update" title="' . __('Edit') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-edit me-2"></i></a>';

        $btn .= '<a class="dropdown-item-inline delete" title="' . __('Delete') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bx-trash me-2"></i></a>';

        return $btn;
      })

      ->addColumn('name', function ($row) {
        return $row->name;
      })

      ->addColumn('wilaya', function ($row) {
        return $row->wilayaDis->name;
      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d', strtotime($row->created_at));

      })

      ->make(true);
  }

  public function clients(Request $request)
  {

    $clients = Client::orderBy('created_at', 'DESC')->get();

    return datatables()
      ->of($clients)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
        $btn = '';

        $btn .= '<a class="dropdown-item-inline update" title="' . __('Edit') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bxs-edit me-2"></i></a>';

        $btn .= '<a class="dropdown-item-inline delete" title="' . __('Delete') . '" table_id="' . $row->id . '" href="javascript:void(0);"><i class="bx bxs-trash me-2"></i></a>';

        return $btn;
      })

      ->addColumn('name', function ($row) {

        return $row->name;

      })

      ->addColumn('phone', function ($row) {

        return $row->phone;

      })

      ->addColumn('district', function ($row) {

        return $row->district->name;

      })

      ->addColumn('wilaya', function ($row) {

        return $row->district->wilayaDis->name;

      })


      ->addColumn('created_at', function ($row) {

        return date('Y-m-d', strtotime($row->created_at));

      })


      ->make(true);
  }


}
