<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\CustomerProfile;
use App\User;
use Session;
use Auth;
use DB;
use Alert;
use Yajra\Datatables\Datatables;
use Intervention\Image\Facades\Image;

class CustomerProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.customer-profile.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.customer-profile.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'company_name' => 'required',
            'name_pic' => 'required',
        ]);

        $requestData = $request->all();

        CustomerProfile::create($requestData);

        Alert::success('Your data already created !', 'Success !');

        return redirect('admin/customer-profile');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $custProfile = CustomerProfile::FindOrFail($id);
        return view('.admin.customer-profile.edit', compact('custProfile'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'company_name' => 'required',
            'name_pic' => 'required',
        ]);

        $requestData = $request->all();

        $CustomerProfile = CustomerProfile::FindOrFail($id);
        $CustomerProfile->update($requestData);

        Alert::success('Your data already updated !', 'Success !');

        return redirect('admin/customer-profile');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CustomerProfile::destroy($id);
        Alert::success('Your data already deleted !', 'Success !');
        return redirect('admin/customer-profile');
    }

    public function anyData()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $custProfile = CustomerProfile::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'id', 'company_name', 'name_pic', 'company_phone'])->orderby('id', 'ASC')->get();

        return Datatables::of($custProfile)

            ->addColumn('action', function ($custProfile) {

                if(Auth::user()->role == User::ROLE_SUPERADMIN) {
                    return '<a href="customer-profile/' . $custProfile->id . '/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-pencil-square-o"></i> Edit </a>
                        <a onclick="deleteData(' . $custProfile->id . ')" class="btn bg-red btn-xs waves-effect"><i class="fa fa-trash-o"></i> Delete </a>';
                }else{
                    return '<a href="customer-profile/' . $custProfile->id . '/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-pencil-square-o"></i> Edit </a>';
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
