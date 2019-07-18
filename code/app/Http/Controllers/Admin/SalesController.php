<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Sales;
use App\ProspectSalesHistory;
use Illuminate\Http\Request;
use App\User;
use Session;
use Auth;
use DB;
use Alert;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\Datatables;

class SalesController extends Controller
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
        $allSalesCount = User::where('role', '=', 3)->count();
        $newSalesCount = User::where('role', '=', 3)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->count();
        return view('admin.sales.index', compact('allSalesCount', 'newSalesCount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {

        return view('admin.sales.create');
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
            'name'      => 'required',
            'username'  => 'required|unique:users,username',
            'email'     => 'required|email|regex:/^.+@.+$/i|unique:users,email',
            'password'  => 'required|min:6'
        ]);

        $request['password'] = Hash::make($request['password']);
        $request['created_by'] = Auth::user()->id;
        $request['role'] = 3;
        $requestData = $request->all();

        $username = User::where('username', '=', $request->username)->count();

        $user = User::where('email', '=', $request['email'])->count();

        if($user == 0 && $username == 0)
        {
            $requestData['username'] = str_replace(" ", "_", $request->username);
            User::create($requestData);
            Alert::success('Your data already added !', 'Success !');
            return redirect('admin/sales');

        } else {

            Alert::error('Username or Email already taken !', 'error')->autoclose(2500);
            return back()->withInput($request->input());
        }
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
        $sales = Sales::findOrFail($id);

        return view('admin.sales.show', compact('sales'));
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
        $sales = User::findOrFail($id);


        return view('admin.sales.edit', compact('sales'));
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
            'name'      => 'required',
            'username'  => 'required|unique:users,username,'.$id,
            'email'     => 'required|email|regex:/^.+@.+$/i|unique:users,email,'.$id,
            'password'  => 'required|min:6'
        ]);
        $request['password'] = Hash::make($request['password']);
        $request['created_by'] = Auth::user()->id;
        $requestData = $request->all();

        $username = User::where('username', '=', $request->username)->where('id', '!=', $id)->count();
        $user = User::where('email', '=', $request->email)->where('id', '!=', $id)->count();

        if($user == 0 && $username == 0)
        {
            $requestData['username'] = str_replace(" ", "_", $request->username);
            $user = User::findOrFail($id);
            $user->update($requestData);
            Alert::success('Your data already updated !', 'Success !');
            return redirect('admin/sales');
        }
        else
        {
            Alert::error('Username or Email already taken!', 'error')->autoclose(2500);
            return back()->withInput($request->input());
        }
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
        if(Auth::user()->role == User::ROLE_SUPERADMIN) {
            User::destroy($id);

            Alert::success('Your data already deleted !', 'Success !');

            return redirect('admin/sales');
        }
    }

    public function detail($id)
    {
        $sales = User::findOrFail($id);
        return view('admin.sales.detail', compact('sales'));
    }

    public function anyData()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $sales = User::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'users.*'])->where('role', '!=', User::ROLE_SUPERADMIN)->where('role', 3)->orderBy('users.name')->get();

        return Datatables::of($sales)
            
            // ->editColumn('name_sales', function ($sales){
            //     return '<a class="btn bg-blue-grey waves-effect" href="sales/assignment/'.$sales->id.'">'.$sales->name_sales.'</a>';
            // })

            ->addColumn('action', function ($sales) {
                if(Auth::user()->role == User::ROLE_SUPERADMIN) {
                    return '<a class="btn bg-grey btn-xs waves-effect" href="sales/sales-detail/' . $sales->id . '"><i class="fa fa-eye"></i> View</a>
                        <a href="sales/' . $sales->id . '/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-pencil-square-o"></i> Edit </a>
                        <a onclick="deleteData(' . $sales->id . ')" class="btn bg-red btn-xs waves-effect"><i class="fa fa-trash-o"></i> Delete </a>';
                }else{
                    return '<a class="btn bg-grey btn-xs waves-effect" href="sales/sales-detail/' . $sales->id . '"><i class="fa fa-eye"></i> View</a>
                        <a href="sales/' . $sales->id . '/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-pencil-square-o"></i> Edit </a>';
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function dataSalesDetail($id)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $historysales = ProspectSalesHistory::leftJoin('prospect_sales', 'prospect_sales.id', '=', 'prospect_sales_history.prospect_id')
            ->leftJoin('users', 'users.id', '=', 'prospect_sales_history.user_id')
            ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales_history.status_id')
            ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'prospect_sales.company_name', 'prospect_sales_history.assignment_date', 'prospect_sales_history.status_id', 'prospect_sales_history.user_id', 'status_progress.name_progress', 'prospect_sales_history.status'])
            ->where('prospect_sales_history.user_id', '=', $id)
            ->orderby('prospect_sales_history.assignment_date', 'DESC')
            ->orderby('prospect_sales.company_name', 'ASC')
            ->get();

        return Datatables::of($historysales)

            ->editColumn('name_progress', function ($historysales){

                if ($historysales->status == 1){
                    return '<p class="btn bg-light-green btn-xs waves-effect">'.$historysales->name_progress.'</p>';
                } else {
                    return '<p class="btn bg-red btn-xs waves-effect">'.$historysales->name_progress.'</p>';
                }

            })

//            ->addColumn('action', function ($prospectsales) {
//                return '<a href="prospect-sales/'.$prospectsales->id.'/edit" class="btn bg-cyan waves-effect"><i class="fa fa-pencil-square-o"></i> Edit</a>
//                        <a onclick="deleteData('.$prospectsales->id.')" class="btn bg-red waves-effect"><i class="fa fa-trash-o"></i> Delete</a>';
//            })

            ->rawColumns(['name_progress'])
            ->make(true);
    }
}
