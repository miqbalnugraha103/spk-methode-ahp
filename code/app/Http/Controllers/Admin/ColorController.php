<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Color;
use App\User;
use Session;
use Auth;
use DB;
use Alert;
use Yajra\Datatables\Datatables;
use Intervention\Image\Facades\Image;

class ColorController extends Controller
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
        return view('admin.color.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.color.create');
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
            'color_name' => 'required',
        ]);

        $requestData = $request->all();
        dd($requestData);die();
//        Color::create($requestData);

        Alert::success('Your data already created !', 'Success !');

        return redirect('admin/color');
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
        $color = Color::findOrFail($id);

        return view('admin.color.show', compact('color'));
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
        $color = Color::findOrFail($id);

        return view('admin.color.edit', compact('color'));
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
            'color_name' => 'required',
        ]);

        $requestData = $request->all();

        $color = Color::findOrFail($id);
        $color->update($requestData);

        Alert::success('Your data already updated !', 'Success !');

        return redirect('admin/color');
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
        Color::destroy($id);

        Alert::success('Your data already deleted !', 'Success !');

        return redirect('admin/color');
    }

    public function anyData()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $color = Color::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'color_name'])->orderBy('color_name', 'asc')->get();

        return Datatables::of($color)

            ->addColumn('action', function ($color) {

                if(Auth::user()->role == User::ROLE_SUPERADMIN) {
                    return '<a href="color/' . $color->id . '/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-pencil-square-o"></i> Edit </a>
                        <a onclick="deleteData(' . $color->id . ')" class="btn bg-red btn-xs waves-effect"><i class="fa fa-trash-o"></i> Delete </a>';
                }else{
                    return '<a href="color/' . $color->id . '/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-pencil-square-o"></i> Edit </a>';
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
