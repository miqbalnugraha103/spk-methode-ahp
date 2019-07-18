<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Orders;
use App\Brand;
use App\QuoteLists;
use App\ProspectSales;
use Illuminate\Http\Request;
use Session;
use Auth;
use DB;
use Alert;
use Yajra\Datatables\Datatables;

class OrderController extends Controller
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
        return view('admin.order.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.brand.create');
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
            'brand' => 'required',
            'files' => 'required|mimes:jpg,png,jpeg'
        ]);

        $requestData = $request->all();

        Brand::create($requestData);

        Alert::success('Your data already created !', 'Success !');

        return redirect('admin/brand');
    }
    /**
     * transaction
     * 
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
     public function transaction($id)
    {
        $brand = Brand::findOrFail($id);

        return view('admin.brand.edit', compact('brand'));
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
        $brand = Brand::findOrFail($id);

        return view('admin.brand.show', compact('brand'));
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
        $brand = Brand::findOrFail($id);

        return view('admin.brand.edit', compact('brand'));
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
            'brand' => 'required'
        ]);

        $datas = $request['files'];
        if($datas !="" ){
            $path = 'files/brand';
            $name = rand(10000,99999).'.'.$datas->getClientOriginalExtension();
            $path = 'files/brand/' . $name;
            Image::make($datas->getRealPath())->resize(150, 80)->save($path);
            $request['image_brand'] = $name;
        }

        $requestData = $request->all();

        $brand = Brand::findOrFail($id);
        $brand->update($requestData);

        Alert::success('Your data already updated !', 'Success !');

        return redirect('admin/brand');
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
        Brand::destroy($id);

        Alert::success('Your data already deleted !', 'Success !');

        return redirect('admin/brand');
    }

    public function anyData()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $brand = Brand::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'brand', 'image_brand'])->orderby('id', 'ASC')->get();

        return Datatables::of($brand)

            ->addColumn('action', function ($brand) {
                return '<a href="brand/'.$brand->id.'/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-pencil-square-o"></i> Edit </a>
                        <a onclick="deleteData('.$brand->id.')" class="btn bg-red btn-xs waves-effect"><i class="fa fa-trash-o"></i> Delete </a>';
            })
             ->addColumn('image_brand', function ($brand) {
                return '<img src="'.url('/').'/files/brand/'.$brand->image_brand.'"/>';
            })
            ->rawColumns(['action','image_brand'])
            ->make(true);
    }
}
