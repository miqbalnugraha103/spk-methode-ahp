<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\ProspectSalesHistory;
use App\Sales;
use Illuminate\Http\Request;
use Session;
use Auth;
use DB;
use Alert;
use Yajra\Datatables\Datatables;

class SalesHistoryController extends Controller
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
        return view('admin.sales-history.index', compact('id'));
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
            ->leftJoin('sales_person', 'sales_person.id', '=', 'prospect_sales_history.sales_person_id')
            ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'prospect_sales.company_name', 'prospect_sales_history.status'])
            ->where('prospect_sales_history.sales_person_id', '=', $id)
            ->groupBy('prospect_sales_history.prospect_id')
            ->get();

        return Datatables::of($historysales)

            ->editColumn('status', function ($historysales){
                if ($historysales->status == 1){
                    return '<p class="btn bg-light-green btn-xs waves-effect">'.$historysales->name_progress.'</p>';
                } else {
                    return '<a class="btn bg-red btn-xs waves-effect">On Duty</a>';
                }

            })
//            ->addColumn('action', function ($prospectsales) {
//                return '<a href="prospect-sales/'.$prospectsales->id.'/edit" class="btn bg-cyan waves-effect"><i class="fa fa-pencil-square-o"></i> Edit</a>
//                        <a onclick="deleteData('.$prospectsales->id.')" class="btn bg-red waves-effect"><i class="fa fa-trash-o"></i> Delete</a>';
//            })

            ->rawColumns(['status'])
            ->make(true);
    }
}
