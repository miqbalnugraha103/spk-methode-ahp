<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\ProspectSalesHistory;
use App\ProspectSales;
use App\Sales;
use App\StatusProgress;
use App\Brand;
use App\ProspectToBrand;
use App\TermCondition;
use Illuminate\Http\Request;
use Session;
use Auth;
use DB;
use Alert;
use Yajra\Datatables\Datatables;

class ProspectSalesHistoryController extends Controller
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
    public function index($id)
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
        return view('admin.prospect-history.index', compact('id', 'prospectsales', 'getTerm', 'brandData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */

    public function anyData($id)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $historysales = ProspectSalesHistory::leftJoin('prospect_sales', 'prospect_sales.id', '=', 'prospect_sales_history.prospect_id')
            ->leftJoin('users', 'users.id', '=', 'prospect_sales_history.user_id')
            ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales_history.status_id')
            ->leftjoin('customer_profile as cp','cp.id', '=', 'prospect_sales.customer_profile_id')
            ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'cp.company_name', 'cp.company_address', 'cp.company_phone', 'prospect_sales.progress_notes', 'prospect_sales_history.assignment_date', 'prospect_sales_history.status_id', 'prospect_sales_history.user_id', 'prospect_sales_history.notes',
                'users.name as name_sales', 'status_progress.name_progress', 'prospect_sales_history.prospect_id', 'prospect_sales_history.status', 'prospect_sales_history.created_at'])
            ->where('prospect_sales_history.prospect_id', '=', $id)
            ->where('prospect_sales_history.status_id', '<>', 1)
            ->orderby('prospect_sales_history.created_at', 'DESC')
            ->get();

        return Datatables::of($historysales)
            ->addColumn('created_at', function ($historysales){
                    return [ 'display' => e(
                         $historysales->created_at->format('d-m-Y H:i')
                      ),
                      'timestamp' => $historysales->created_at->timestamp
                    ];
            })
            ->editColumn('status', function ($historysales){

                if ($historysales->status == 1){
                    return '<p class="btn bg-light-green btn-xs waves-effect" style="pointer-events: none">'.$historysales->name_progress.'</p>';
                } else {
                    return '<p class="btn bg-red btn-xs waves-effect" style="pointer-events: none">'.$historysales->name_progress.'</p>';
                }

            })

            ->rawColumns(['status'])
            ->make(true);
    }
}
