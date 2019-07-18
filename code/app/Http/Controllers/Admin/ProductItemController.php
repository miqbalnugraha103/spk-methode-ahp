<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\ProductItem;
use App\Brand;
use App\Color;
use Illuminate\Http\Request;
use App\User;
use Session;
use Auth;
use DB;
use Alert;
use Yajra\Datatables\Datatables;
use Intervention\Image\Facades\Image;

class ProductItemController extends Controller
{
    public function __construct()
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.product-item.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $brand = Brand::pluck('brand', 'id')->prepend('Choose brand', '');
        $color = Color::select('color_name', 'id')->get();
        return view('admin.product-item.create', compact('brand', 'color'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'brand_id' => 'required',
            'product_code' => 'required',
            'color_id' => 'required',
            'name' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'files' => 'mimes:jpg,png,jpeg'
        ]);
        $requestData = $request->all();
        $datas = $request['files'];
        if($datas !="" ){
            $name = date('dmY').'_'.rand(10000,99999).'.'.$datas->getClientOriginalExtension();
            $path = 'files/product/' . $name;
            Image::make($datas->getRealPath())->resize(150, 80)->save($path);
            $requestData['image_name'] = $name;
        }
        if($request->diskon == '' || $request->diskon == 0){
            $requestData['product_code'] = $request->product_code;
            $requestData['slug'] = str_slug($request->name, '');
            $requestData['price'] = str_replace(",","",$request->price);
            $requestData['diskon'] = 0;
            $requestData['quantity'] = $request->quantity;
            $requestData['quality'] = $request->quality;
            $requestData['size'] = $request->size;
            $requestData['color_id'] = $request['color_id'];
        }else{
            $requestData['product_code'] = $request->product_code;
            $requestData['slug'] = str_slug($request->name, '-');
            $requestData['price'] = str_replace(",","",$request->price);
            $requestData['diskon'] = $request->diskon;
            $requestData['quantity'] = $request->quantity;
            $requestData['quality'] = $request->quality;
            $requestData['size'] = $request->size;
            $requestData['color_id'] = $request['color_id'];
        }
        ProductItem::create($requestData);

        Alert::success('Your data already created !', 'Success !');

        return redirect('admin/product');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $brand = Brand::select('brand', 'id')->get();
        $color = Color::select('color_name', 'id')->get();
        $productItem = ProductItem::findOrFail($id);

        return view('admin.product-item.show', compact('productItem', 'brand', 'color'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $brand = Brand::pluck('brand', 'id')->prepend('Choose brand', '');
        $color = Color::select('color_name', 'id')->get();
        $productItem = ProductItem::findOrFail($id);

        return view('admin.product-item.edit', compact('productItem', 'brand', 'color'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'brand_id' => 'required',
            'color_id' => 'required',
            'product_code' => 'required',
            'name' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'files' => 'mimes:jpg,png,jpeg'

        ]);

        $datas = $request['files'];
        if($datas !="" ) {
            $datas = $request['files'];
            $name = date('dmY').'_'.rand(10000, 99999) . '.' . $datas->getClientOriginalExtension();
            $path = 'files/product/' . $name;
            Image::make($datas->getRealPath())->resize(150, 80)->save($path);
            $request['image_name'] = $name;
        }

        $requestData = $request->all();
        $requestData['product_code'] = $request->product_code;
        $requestData['slug'] = str_slug($request->name, '');
        $requestData['price'] = str_replace(",", "", $request->price);
        $requestData['diskon'] = $request->diskon;
        $requestData['quantity'] = $request->quantity;
        $requestData['quality'] = $request->quality;
        $requestData['size'] = $request->size;
        $requestData['color_id'] = $request['color_id'];

        $productItem = ProductItem::findOrFail($id);
        $productItem->update($requestData);

        Alert::success('Your data already updated !', 'Success !');

        return redirect('admin/product');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
//        if(Auth::user()->role == User::ROLE_SUPERADMIN) {
            $product = ProductItem::findOrFail($id);
            if($product->image_name != '') {
                $image_path = app_path("../../files/product/" . $product->image_name);
                unlink($image_path);
            }

            ProductItem::destroy($id);

            Alert::success('Your data already deleted !', 'Success !');

            return redirect('admin/product');
//        }
    }

    public function anyData()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $productItem = ProductItem::leftJoin('brand as b', 'b.id', '=', 'products.brand_id')
        ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'products.id', 'b.brand', 'products.name as item', 'products.price' , 'products.diskon' , 'products.image_name as image'])
        ->orderby('item', 'ASC')
        ->get();

        return Datatables::of($productItem)

            ->addColumn('action', function ($productItem) {
                if(Auth::user()->role == User::ROLE_SUPERADMIN) {
                    return '<p><a class="btn bg-grey btn-xs waves-effect" href="product/view/' . $productItem->id . '"><i class="fa fa-eye"></i> View</a></p>
                        <p><a href="product/' . $productItem->id . '/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-pencil-square-o"></i> Edit </a></p>
                        <p><a onclick="deleteData(' . $productItem->id . ')" class="btn bg-red btn-xs waves-effect"><i class="fa fa-trash-o"></i> Delete </a></p>';
                }else{
                    return '<p><a class="btn bg-grey btn-xs waves-effect" href="product/view/' . $productItem->id . '"><i class="fa fa-eye"></i> View</a></p>
                        <p><a href="product/' . $productItem->id . '/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-pencil-square-o"></i> Edit </a></p>';
                }
            })
            ->addColumn('price', function ($productItem) {
                return number_format($productItem->price, 2);
            })
            ->addColumn('diskon', function ($productItem) {
                if($productItem->diskon == '')
                {
                    return 0;
                }else{
                    return $productItem->diskon;
                }
            })
             ->addColumn('image', function ($productItem) {
                return '<img src="'.url('/').'/files/product/'.$productItem->image.'"/>';
            })
            ->rawColumns(['action', 'price', 'diskon', 'image'])
            ->make(true);
    }
}
