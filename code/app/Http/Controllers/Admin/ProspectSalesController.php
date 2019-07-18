<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\ProspectSales;
use App\CustomerProfile;
use App\ProspectSalesHistory;
use App\Sales;
use App\Brand;
Use App\ProspectToBrand;
use App\StatusProgress;
use App\TermCondition;
use Illuminate\Http\Request;
Use App\User;
use Session;
use Auth;
use DB;
use Alert;
use Yajra\Datatables\Datatables;

class ProspectSalesController extends Controller
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
        return view('admin.prospect-sales.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if(Auth::user()->role ==  \App\User::ROLE_SUPERADMIN || Auth::user()->role ==  \App\User::ROLE_ADMIN) {
            if (Auth::user()->role != User::ROLE_SUPERADMIN || Auth::user()->role != User::ROLE_ADMIN) {
                $sales = User::whereNotIn('role', [1, 2])->pluck('name', 'id')->prepend('Choose Sales', '');
            }
            $status = StatusProgress::pluck('name_progress', 'id')->prepend('Choose Status', '1');
            $custProfile = CustomerProfile::pluck('company_name', 'id')->prepend('Choose Company', '');
            /*tambahan iqbal*/
            $prospectBrand = new Brand();
            $brandId = $prospectBrand->getList();
            /*end tambahan iqbal*/
            return view('admin.prospect-sales.create', compact('sales', 'status', 'custProfile', 'brandId'));
        }else {
            Alert::info('No Access !', 'Warning');
            return redirect('admin/prospect');
        }
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
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN){
            $this->validate($request, [
                'customer_profile_id' => 'required',
                'user_id' => 'required',
                'brand' => 'required'
            ]);
        }else{
            $this->validate($request, [
                'customer_profile_id' => 'required',
                'user_id' => 'required',
                'brand' => 'required'
            ]);
        }
        $requestData = $request->all();
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $requestData['user_id'] = $request->user_id;
        }else{
            $requestData['user_id'] = Auth::user()->id;
        }
        $requestData['status_id'] = 1;
        $requestData['assignment_date'] = date('d-m-Y H:i:s');
        $requestData['created_at'] = date('Y-m-d H:i:s');
        $requestData['updated_at'] = date('Y-m-d H:i:s');
        unset($requestData['_token']);
        unset($requestData['brand']);

        $id = ProspectSales::insertGetId($requestData);

        $history['prospect_id'] = $id;

        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $history['user_id'] = $request->user_id;
        }else{
            $history['user_id'] = Auth::user()->id;
        }
        $history['status_id'] = 1;
        $history['status'] = 1;
        $history['assignment_date'] = date('d-m-Y H:i:s');
        $history['created_at'] = date('Y-m-d H:i:s');
        $history['updated_at'] = date('Y-m-d H:i:s');

        ProspectSalesHistory::insert($history);
        if($request->brand) {
            foreach ($request->brand as $key => $value) {
                 $data = [
                    'prospect_sales_id' => $id,
                    'brand_id' =>  $value,
                    'created_at' =>date('Y-m-d H:s:i')
                ];
            $ids = ProspectToBrand::insert($data);
            } 
        }

        Alert::success('Your data already created !', 'Success !');

        return redirect('admin/prospect');
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
        $prospectsales = ProspectSales::select(DB::raw('prospect_sales.id, prospect_sales.progress_notes, cp.company_name, cp.company_address, cp.company_phone, cp.name_pic'))
            ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
            ->where('prospect_sales.id', $id)
            ->first();
        $TermCondition = new TermCondition();
        $getTerm = $TermCondition->getList();

        $brandData = ProspectToBrand::leftJoin('brand', 'brand.id', '=', 'prospect_to_brand.brand_id')
            ->select('prospect_to_brand.brand_id', 'brand.brand')
            ->where('prospect_sales_id',$id)
            ->get();
        return view('admin.prospect-sales.show', compact('id', 'prospectsales', 'getTerm', 'brandData'));
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
        $custProfile = CustomerProfile::pluck('company_name', 'id')->prepend('Choose Company', '');
        $StatusProgress         = new StatusProgress();
        $status                 = $StatusProgress->selectForStatus();
        $ProspectSale         = new ProspectSales();
        $prospectsales          = $ProspectSale->getProspectSales($id);
        $ProspectSalesHistory   = new ProspectSalesHistory();
        $prospectHistory        = $ProspectSalesHistory->where('prospect_id', $id)->get();
        foreach ($prospectHistory as $history) {
            $history_array[] = $history->user_id;
        }
        $prospectsalesGet       = new ProspectSales();
        $sales                  = $prospectsalesGet->getForSales($prospectsales->user_id);
//        dd($sales);

        $salesperson = User::leftJoin('prospect_sales_history', 'prospect_sales_history.user_id', '=', 'users.id')
            ->select('users.id', 'users.name as name_sales', 'prospect_sales_history.user_id')
            ->where('users.id', '!=', 'prospect_sales_history.user_id')
            ->whereNotIn('users.id', $history_array)
            ->whereNotIn('users.role', [1,2])
            ->pluck('name_sales', 'id')->prepend('Choose Sales', '');
        $salesonduty = ProspectSalesHistory::leftJoin('users as u', 'u.id', '=', 'prospect_sales_history.user_id')
            ->select('u.id', 'u.name as name_sales')
            ->where('prospect_sales_history.prospect_id', '=', $id)
            ->pluck('name_sales', 'id')->prepend('Choose Sales', '');

        /*tambahan iqbal*/
        $prospectBrand = new Brand();
        $brandId = $prospectBrand->getList();

        $brandDataGet = new ProspectToBrand();
        $brandData = $brandDataGet->getByProspectId($id);
        /*end tambahan iqbal*/

        return view('admin.prospect-sales.edit', compact('custProfile','prospectsales', 'sales', 'salesperson', 'salesonduty', 'status', 'brand', 'brandId', 'brandData', 'id'));
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
        $requestData = $request->all();
        $prospectsales = ProspectSales::findOrFail($id);
        $prospectsales->update($requestData);

        Alert::success('Your data already updated !', 'Success !');

        return redirect('admin/prospect');
    }

    public function getCustomerProfile(Request $request)
    {
        $customer_profile_id = $request->customer_profile_id;
        if($customer_profile_id == '') {
            $data['company_name'] = '';
            $data['name_pic'] = '-';
            $data['company_address'] = '-';
            $data['company_phone'] = '-';
        }else{
            $custProfile = CustomerProfile::FindOrFail($customer_profile_id);

            $data['company_name'] = $custProfile->company_name;
            $data['name_pic'] = $custProfile->name_pic;
            $data['company_address'] = $custProfile->company_address;
            $data['company_phone'] = $custProfile->company_phone;
        }
        return response($data);
    }

    public function updateCompany($id, Request $request)
    {
        $requestData = $request->all();
        $prospectsales = ProspectSales::findOrFail($id);
        $ProspectToBrand = new ProspectToBrand;
        $ProspectToBrand->deleteProspectId($request->id);
        
        $prospectsales->update($requestData);
        if($request->brand) {
            foreach ($request->brand as $key => $value) {
                 $data = [
                    'prospect_sales_id' => $id,
                    'brand_id' =>  $value,
                    'updated_at' =>date('Y-m-d H:s:i')
                ];
            $ids = ProspectToBrand::insert($data);
            } 
        }
        Alert::success('Company data already updated !', 'Success !');

        return redirect('admin/prospect/'.$id.'/edit');
    }

    public function updateAssignment($id, Request $request)
    {
        $requestData = $request->all();
        $requestData['user_id'] = $request->sales_person_id;

        // $old_data = ProspectSalesHistory::where('prospect_id', $id)->Where('status_id', 1)->firstOrFail();

        // $old_data_update = ProspectSalesHistory::where('prospect_sales_history.prospect_id', $id)->Where('prospect_sales_history.status_id', 1)->update(['assignment_date' => $request->assignment_date, 'updated_at' => date('Y-m-d H:i:s')]);

        $old_data_update = ProspectSalesHistory::where('prospect_sales_history.prospect_id', $id)->Where('prospect_sales_history.status_id', 1)->update(['updated_at' => date('Y-m-d H:i:s')]);
    
        $history['prospect_id'] = $id;
        $history['user_id'] = $request->sales_person_id;
        $history['assignment_date'] = $request->assignment_date;
        $history['status_id'] = 1;
        $history['status'] = 1;
        $history['updated_at'] = date('Y-m-d H:i:s');
        $history['created_at'] = date('Y-m-d H:i:s');

        $prospectsales = ProspectSales::findOrFail($id);

        ProspectSalesHistory::where('user_id')->insert($history);
        $prospectsales->update($requestData);
        Alert::success('Your data already updated !', 'Success !');
        
        return redirect('admin/prospect/'.$id.'/edit');
    }

     public function updateProgress($id, Request $request)
    {
//        $requestData = $request->all();
        $requestData['user_id'] = $request->sales_person_id;

        $old_data = ProspectSalesHistory::where('prospect_id', $id)->get();

        $history['prospect_id']     = $id;
        $history['user_id']         = $request->sales_person_id;
        $history['status_id']       = $request->status_id;
        $history['assignment_date'] = $request->assignment_date;
        $history['notes']           = $request->notes;
        $history['status']          = 1;
        $history['updated_at']      = date('Y-m-d H:i:s');
        $history['created_at']      = date('Y-m-d H:i:s');

        $prospectsales = ProspectSales::findOrFail($id);

        if(count($old_data) > 0)
        {

            ProspectSalesHistory::where('prospect_id', $id)->Where('status_id', 1)->Where('user_id', $request->sales_person_id)->update(['assignment_date' => $request->assignment_date, 'updated_at' => date('Y-m-d H:i:s')]);

            ProspectSalesHistory::where('prospect_id', $id)->update(['status' => 0, 'updated_at' => date('Y-m-d H:i:s')]);
            ProspectSalesHistory::insert($history);

        }

        $prospectsales->update($requestData);

        Alert::success('Your data already updated !', 'Success !');

        return redirect('admin/prospect/'.$id.'/edit');
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
            ProspectSales::destroy($id);
            ProspectToBrand::where('prospect_sales_id', $id)->delete();
            ProspectSalesHistory::where('prospect_id', $id)->delete();

            Alert::success('Your data already deleted !', 'Success !');
        }
        return redirect('admin/prospect');
    }

    public function anyData()
    {
        DB::statement(DB::raw('set @rownum=0'));
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $prospectsales = ProspectSales::leftJoin('users', 'prospect_sales.user_id', '=', 'users.id')
                ->leftJoin('customer_profile', 'customer_profile.id', '=', 'prospect_sales.customer_profile_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'prospect_sales.id', 'users.name as name_sales', 'prospect_sales.customer_profile_id', 'prospect_sales.assignment_date',
                    'prospect_sales.user_id', 'customer_profile.company_name', 'customer_profile.name_pic', 'customer_profile.company_address', 'customer_profile.company_phone'])
                ->orderby('prospect_sales.assignment_date', 'ASC')
                ->get();
        }else{
            $prospectsales = ProspectSales::leftJoin('users', 'prospect_sales.user_id', '=', 'users.id')
                ->leftJoin('customer_profile', 'customer_profile.id', '=', 'prospect_sales.customer_profile_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'prospect_sales.id', 'users.name as name_sales', 'prospect_sales.customer_profile_id', 'prospect_sales.assignment_date',
                    'prospect_sales.user_id', 'customer_profile.company_name', 'customer_profile.name_pic', 'customer_profile.company_address', 'customer_profile.company_phone'])
                ->where('users.id', Auth::user()->id)
                ->orderby('prospect_sales.assignment_date', 'ASC')
                ->get();
        }

        return Datatables::of($prospectsales)

            ->addColumn('action', function ($prospectsales) {
                if(Auth::user()->role == User::ROLE_SUPERADMIN) {
                    return '<a class="btn bg-grey btn-xs waves-effect" href="prospect/history/' . $prospectsales->id . '"><i class="fa fa-eye"></i> View</a>
                        <a href="prospect/' . $prospectsales->id . '/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-pencil-square-o"></i> Edit</a>
                        <a onclick="deleteData(' . $prospectsales->id . ')" class="btn bg-red btn-xs waves-effect"><i class="fa fa-trash-o"></i> Delete</a>';
                }elseif(Auth::user()->role == User::ROLE_ADMIN) {
                    return '<a class="btn bg-grey btn-xs waves-effect" href="prospect/history/' . $prospectsales->id . '"><i class="fa fa-eye"></i> View</a>
                        <a href="prospect/' . $prospectsales->id . '/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-pencil-square-o"></i> Edit</a>';
                }else{
                    return '<a class="btn bg-grey btn-xs waves-effect" href="prospect/history/' . $prospectsales->id . '"><i class="fa fa-eye"></i> View</a>';
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function assignment($id)
    {

        DB::statement(DB::raw('set @rownum=0'));
        $historysales = ProspectSalesHistory::leftJoin('prospect_sales', 'prospect_sales.id', '=', 'prospect_sales_history.prospect_id')
            ->leftJoin('customer_profile', 'customer_profile.id', '=', 'prospect_sales.customer_profile_id')
            ->leftJoin('users', 'users.id', '=', 'prospect_sales_history.user_id')
            ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales_history.status_id')
            ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'customer_profile.company_name', 'customer_profile.company_address', 'customer_profile.company_phone',
                'prospect_sales.progress_notes', 'prospect_sales_history.assignment_date', 'prospect_sales_history.status_id', 'prospect_sales.user_id', 'prospect_sales_history.notes',
                'users.name as name_sales', 'users.id', 'status_progress.name_progress', 'prospect_sales_history.prospect_id', 'prospect_sales_history.status'])
            ->where('prospect_sales_history.prospect_id', '=', $id)
            ->where('prospect_sales_history.status_id', '=', 1)
//            ->orderBy('prospect_sales_history.assignment_date', 'DESC')
            ->orderBy('prospect_sales_history.assignment_date', 'DESC')
            ->get();

        return Datatables::of($historysales)

            ->editColumn('assignment_date', function ($historysales){

                if ($historysales->user_id == $historysales->id){
                    return $historysales->assignment_date;
                } else {
                    return $historysales->assignment_date;
                }

            })
            ->addColumn('status', function ($historysales){

                if ($historysales->user_id == $historysales->id){
                    return '<a class="btn bg-light-green btn-xs waves-effect" id="status_update">On Duty</a>';
                } else {
                    return '<a class="btn bg-red btn-xs waves-effect" id="status_update">Inactive</a>';
                }

            })

           // ->addColumn('action', function ($historysales) {
           //      if ($historysales->status == 1){
           //          return '<a onclick="deleteData('.$historysales->id.')" class="btn bg-red btn-xs waves-effect"><i class="fa fa-trash-o"></i> Delete</a>';
           //      }
           // })

            ->rawColumns(['status','assignment_date'])
            ->make(true);
    }

    public function progress($id)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $historysales = ProspectSalesHistory::leftJoin('prospect_sales', 'prospect_sales.id', '=', 'prospect_sales_history.prospect_id')
            ->leftJoin('customer_profile', 'customer_profile.id', '=', 'prospect_sales.customer_profile_id')
            ->leftJoin('users', 'users.id', '=', 'prospect_sales_history.user_id')
            ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales_history.status_id')
            ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'customer_profile.company_name', 'customer_profile.company_address', 'customer_profile.company_phone',
                'prospect_sales.progress_notes', 'prospect_sales_history.assignment_date', 'prospect_sales_history.status_id', 'prospect_sales_history.user_id', 'prospect_sales_history.notes', 'prospect_sales_history.created_at',
                'users.name as name_sales', 'users.id as user_id', 'status_progress.name_progress', 'prospect_sales_history.prospect_id', 'prospect_sales_history.status'])
            ->where('prospect_sales_history.prospect_id', '=', $id)
            ->where('prospect_sales_history.status_id', '<>', 1)
            ->orderBy('prospect_sales_history.status', 'DESC')
            ->orderBy('prospect_sales_history.assignment_date', 'DESC')
            ->get();

        return Datatables::of($historysales)
            ->addColumn('created_at', function ($historysales){
                    return [ 'display' => e(
                         $historysales->created_at->format('d-m-Y H:i')
                      ),
                      'timestamp' => $historysales->created_at->timestamp
                    ];
            })
            ->editColumn('name_progress', function ($historysales){

                if ($historysales->status == 1){
                    return '<p class="btn bg-light-green btn-xs waves-effect">'.$historysales->name_progress.'</p>';
                } else {
                    return '<p class="btn bg-red btn-xs waves-effect">'.$historysales->name_progress.'</p>';
                }

            })
            ->order(function ($historysales) {
                if (request()->has('assignment_date')) {
                    $historysales->orderBy('assignment_date', 'DESC');
                }
            })

//            ->addColumn('action', function ($prospectsales) {
//                return '<a href="prospect-sales/'.$prospectsales->id.'/edit" class="btn bg-cyan waves-effect"><i class="fa fa-pencil-square-o"></i> Edit</a>
//                        <a onclick="deleteData('.$prospectsales->id.')" class="btn bg-red waves-effect"><i class="fa fa-trash-o"></i> Delete</a>';
//            })

            ->rawColumns(['created_at', 'name_progress'])
            ->make(true);
    }
}
