<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\ProspectSales;
use App\ProspectSalesHistory;
use App\Sales;
use App\StatusProgress;
use App\Brand;
use App\ProspectToBrand;
use App\TermCondition;
use Illuminate\Http\Request;
use App\User;
use Session;
use Auth;
use DB;
use Alert;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

class PitchingController extends Controller
{
    public function __construct()
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                $statusprogress = statusprogress::select('name_progress', 'id')->get();
                $salesprogress1 = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                    ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                    ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                    ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                    ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id'])
                    ->where('prospect_sales_history.status_id', '=', '1')
                    ->where('prospect_sales_history.status', '=', '1')
                    ->count();

                $salesprogress2 = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                    ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                    ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                    ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                    ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id'])
                    ->where('prospect_sales_history.status_id', '=', '2')
                    ->where('prospect_sales_history.status', '=', '1')
                    ->count();

                $salesprogress3 = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                    ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                    ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                    ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                    ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id'])
                    ->where('prospect_sales_history.status_id', '=', '3')
                    ->where('prospect_sales_history.status', '=', '1')
                    ->count();

                $salesprogress4 = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                    ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                    ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                    ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                    ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id'])
                    ->where('prospect_sales_history.status_id', '=', '4')
                    ->where('prospect_sales_history.status', '=', '1')
                    ->count();

                $salesprogress5 = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                    ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                    ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                    ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                    ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id'])
                    ->where('prospect_sales_history.status_id', '=', '5')
                    ->where('prospect_sales_history.status', '=', '1')
                    ->count();

            $chartDatas = ProspectSales::select([
                DB::raw('DATE(created_at) AS date'),
                DB::raw('COUNT(id) AS count'),
            ])
                ->whereBetween('created_at', [Carbon::now()->subDays(30), Carbon::now()])
                ->whereYear('created_at','>=','2017')
//                ->where('created_at','<=','2029')
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get()
                ->toArray();
//            print_r($chartDatas);die();
        }else {
            $statusprogress = statusprogress::select('name_progress', 'id')->get();
            $salesprogress1 = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id'])
                ->where('users.id', Auth::user()->id)
                ->where('prospect_sales_history.status_id', '=', '1')
                ->where('prospect_sales_history.status', '=', '1')
                ->count();

            $salesprogress2 = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id'])
                ->where('users.id', Auth::user()->id)
                ->where('prospect_sales_history.status_id', '=', '2')
                ->where('prospect_sales_history.status', '=', '1')
                ->count();

            $salesprogress3 = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id'])
                ->where('users.id', Auth::user()->id)
                ->where('prospect_sales_history.status_id', '=', '3')
                ->where('prospect_sales_history.status', '=', '1')
                ->count();

            $salesprogress4 = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id'])
                ->where('users.id', Auth::user()->id)
                ->where('prospect_sales_history.status_id', '=', '4')
                ->where('prospect_sales_history.status', '=', '1')
                ->count();

            $salesprogress5 = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id'])
                ->where('users.id', Auth::user()->id)
                ->where('prospect_sales_history.status_id', '=', '5')
                ->where('prospect_sales_history.status', '=', '1')
                ->count();


        }

        return view('admin.pitching.index', compact('statusprogress', 'salesprogress1', 'salesprogress2', 'salesprogress3', 'salesprogress4', 'salesprogress5'));
    }

    public function dataTable($id)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $statusprogress = statusprogress::select('name_progress', 'id')->get();
            $salesprogressadd = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'status_progress.id', 'prospect_sales_history.status_id'])
                ->where('prospect_sales_history.status_id', '=', $id)
                ->where('prospect_sales_history.status', '=', '1')
                ->get();

            $salesprogress1 = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id'])
                ->where('prospect_sales_history.status_id', '=', '1')
                ->where('prospect_sales_history.status', '=', '1')
                ->count();

            $salesprogress2 = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id'])
                ->where('prospect_sales_history.status_id', '=', '2')
                ->where('prospect_sales_history.status', '=', '1')
                ->count();

            $salesprogress3 = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id'])
                ->where('prospect_sales_history.status_id', '=', '3')
                ->where('prospect_sales_history.status', '=', '1')
                ->count();

            $salesprogress4 = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id'])
                ->where('prospect_sales_history.status_id', '=', '4')
                ->where('prospect_sales_history.status', '=', '1')
                ->count();

            $salesprogress5 = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id'])
                ->where('prospect_sales_history.status_id', '=', '5')
                ->where('prospect_sales_history.status', '=', '1')
                ->count();
        }else{
            $statusprogress = statusprogress::select('name_progress', 'id')->get();
            $salesprogressadd = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'status_progress.id', 'prospect_sales_history.status_id'])
                ->where('users.id', Auth::user()->id)
                ->where('prospect_sales_history.status_id', '=', $id)
                ->where('prospect_sales_history.status', '=', '1')
                ->get();

            $salesprogress1 = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id'])
                ->where('users.id', Auth::user()->id)
                ->where('prospect_sales_history.status_id', '=', '1')
                ->where('prospect_sales_history.status', '=', '1')
                ->count();

            $salesprogress2 = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id'])
                ->where('users.id', Auth::user()->id)
                ->where('prospect_sales_history.status_id', '=', '2')
                ->where('prospect_sales_history.status', '=', '1')
                ->count();

            $salesprogress3 = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id'])
                ->where('users.id', Auth::user()->id)
                ->where('prospect_sales_history.status_id', '=', '3')
                ->where('prospect_sales_history.status', '=', '1')
                ->count();

            $salesprogress4 = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id'])
                ->where('users.id', Auth::user()->id)
                ->where('prospect_sales_history.status_id', '=', '4')
                ->where('prospect_sales_history.status', '=', '1')
                ->count();

            $salesprogress5 = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id'])
                ->where('users.id', Auth::user()->id)
                ->where('prospect_sales_history.status_id', '=', '5')
                ->where('prospect_sales_history.status', '=', '1')
                ->count();
        }

        return view('admin.pitching.index', compact('id', 'salesprogress1', 'salesprogress2', 'salesprogress3', 'salesprogress4', 'salesprogress5', 'statusprogress', 'salesprogressadd'));
    }

    public function anyData($id = null)
    {
        DB::statement(DB::raw('set @rownum=0'));
        if($id == null) {
            if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                $salesprogress = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                    ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                    ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                    ->leftjoin('customer_profile as cp', 'cp.id', '=', 'prospect_sales.customer_profile_id')
                    ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id', 'prospect_sales.user_id', 'prospect_sales_history.prospect_id', 'prospect_sales_history.notes'])
                    ->where('prospect_sales_history.status', '=', '1')
                    ->get();
            } else {
                $salesprogress = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                    ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                    ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                    ->leftjoin('customer_profile as cp', 'cp.id', '=', 'prospect_sales.customer_profile_id')
                    ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id', 'prospect_sales.user_id', 'prospect_sales_history.prospect_id', 'prospect_sales_history.notes'])
                    ->where('users.id', Auth::user()->id)
                    ->where('prospect_sales_history.status', '=', '1')
                    ->get();
            }
        }else{
            if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                $salesprogress = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                    ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                    ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                    ->leftjoin('customer_profile as cp', 'cp.id', '=', 'prospect_sales.customer_profile_id')
                    ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id', 'prospect_sales.user_id', 'prospect_sales_history.prospect_id', 'prospect_sales_history.notes'])
                    ->where('prospect_sales_history.status_id', '=', $id)
                    ->where('prospect_sales_history.status', '=', '1')
                    ->get();
            } else {
                $salesprogress = ProspectSales::leftJoin('users', 'users.id', '=', 'prospect_sales.user_id')
                    ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                    ->leftJoin('prospect_sales_history', 'prospect_sales_history.prospect_id', '=', 'prospect_sales.id')
                    ->leftjoin('customer_profile as cp', 'cp.id', '=', 'prospect_sales.customer_profile_id')
                    ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'users.name as name_sales', 'prospect_sales.id', 'prospect_sales.user_id', 'prospect_sales_history.prospect_id', 'prospect_sales_history.notes'])
                    ->where('users.id', Auth::user()->id)
                    ->where('prospect_sales_history.status_id', '=', $id)
                    ->where('prospect_sales_history.status', '=', '1')
                    ->get();
            }
        }


        return Datatables::of($salesprogress)

            ->editColumn('action', function ($salesprogress){
                return '<a class="btn bg-blue-grey btn-xs waves-effect" href="sales-progress/detail/'.$salesprogress->prospect_id.'"><i class="fa fa-eye"></i> View</a>';
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    public function detail($id)
    {
        $prospectsales = ProspectSales::select(DB::raw('prospect_sales.id, prospect_sales.progress_notes, cp.company_name, cp.company_address, cp.company_phone, cp.name_pic'))
            ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
            ->where('prospect_sales.id', $id)
            ->first();

        $brandData = ProspectToBrand::leftJoin('brand', 'brand.id', '=', 'prospect_to_brand.brand_id')
        ->select('prospect_to_brand.brand_id', 'brand.brand')
        ->where('prospect_sales_id',$id)
        ->get();
        $TermCondition = new TermCondition();
        $getTerm = $TermCondition->getList();
        return view('admin.pitching.detail', compact('id', 'prospectsales', 'brandData', 'getTerm'));
    }

    public function detailDataTable($id)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $salesprogress = ProspectSalesHistory::leftJoin('prospect_sales', 'prospect_sales.id', '=', 'prospect_sales_history.prospect_id')
            ->leftJoin('users', 'users.id', '=', 'prospect_sales_history.user_id')
            ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales_history.status_id')
            ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
            ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
            ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'cp.company_address', 'cp.company_phone', 'prospect_sales.assignment_date', 'prospect_sales.notes', 'prospect_sales.progress_notes', 'users.name as name_sales', 'prospect_sales_history.status', 'status_progress.name_progress', 'prospect_sales_history.status_id'])
            ->where('prospect_sales_history.prospect_id', '=', $id)
            ->where('prospect_sales_history.status', '=', '1')
            ->get();

        return Datatables::of($salesprogress)

            ->editColumn('status', function ($salesprogress){
               
                if ($salesprogress->status_id == 5){
                    return '<p class="btn bg-light-green btn-xs waves-effect">Selesai</p>';
                } else {
                    return '<a class="btn bg-red btn-xs waves-effect">Dalam Proses</a>';
                }
            })
//
            ->rawColumns(['status'])
            ->make(true);
    }
}