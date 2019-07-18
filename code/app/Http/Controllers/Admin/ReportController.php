<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use App\ProspectSales;
use App\Sales;
use App\ProspectSalesHistory;
use App\ProspectToBrand;
use App\QuoteLists;
use App\QuoteListsDetail;
use App\PurchaseOrderLists;
use App\PurchaseOrderListsDetail;
use App\InvoiceLists;
use App\InvoiceListsDetail;
use App\InvoiceListsPayment;
use App\DeliveryOrderLists;
use App\DeliveryOrderListsDetail;
use App\DeliveryOrderListsTransaction;
use Illuminate\Http\Request;
use App\User;
use Session;
use Auth;
use DB;
use Alert;
use Yajra\Datatables\Datatables;
use Excel;


class ReportController extends Controller
{
    protected $paginate_number = 30;

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
     * @return \Illuminate\Http\Response
     */
    public function showBySales()
    {
        return view('admin.report.by-sales');
    }

    public function anyDataBySales()
    {
        DB::statement(DB::raw('set @rownum=0'));

        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $quotelist = QuoteLists::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'u.name as name_sales', 'cp.company_name', 'quote_lists.quote_list_code',
                'ql.quote_list_code as quote_code', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.quote_list_code as quote_code',
                'po.purchase_order_list_code as po_code', 'il.invoice_list_code as invoice_code', 'do.delivery_order_list_code as do_code', 'quote_lists.created_at as date_quote', 'po.created_at as date_po',
                'il.created_at as date_invoice', 'do.created_at as date_do', 'stp.name_progress'])
                ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                // ->groupBy('quote_lists.quote_list_code')
                ->where('quote_lists.is_active', '=', '1')
                ->orderby('u.name', 'ASC')
                ->get();
        }else{
            $quotelist = QuoteLists::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'u.name as name_sales', 'cp.company_name', 'quote_lists.quote_list_code', 'ql.quote_list_code as quote_code', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.quote_list_code as quote_code', 'po.purchase_order_list_code as po_code', 'il.invoice_list_code as invoice_code', 'do.delivery_order_list_code as do_code', 'quote_lists.created_at as date_quote', 'po.created_at as date_po', 'il.created_at as date_invoice', 'do.created_at as date_do', 'stp.name_progress'])
                ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                ->where('quote_lists.sales_person_id', '=', Auth::user()->id)
                ->where('quote_lists.is_active', '=', '1')
                ->orderby('u.name', 'ASC')
                ->get();
        }

        return Datatables::of($quotelist)
            ->addColumn('action', function ($quotelist) {
                if($quotelist->file == '')
                {
                    return '<p><a class="btn bg-grey btn-xs waves-effect" href="quote-list/view/'.$quotelist->id.'"><i class="fa fa-eye"></i> View</a></p>
                            <p><a href="quote-list/'.$quotelist->id.'/create_requote" class="btn bg-orange btn-xs waves-effect"><i class="fa fa-handshake-o"></i> Requote </a></p>';
                }else{
                    return '<p><a class="btn bg-grey btn-xs waves-effect" href="quote-list/view/'.$quotelist->id.'"><i class="fa fa-eye"></i> View</a></p>
                        <p><a href="quote-list/'.$quotelist->id.'/create_requote" class="btn bg-orange btn-xs waves-effect"><i class="fa fa-handshake-o"></i> Requote </a></p>';
                }
            })
            ->addColumn('quote_code', function ($quotelist) {
                if($quotelist->quote_code == '')
                {
                    return '-';
                }else{
                    return $quotelist->quote_code;
                }
            })
            ->addColumn('fix_po', function ($quotelist) {
                if($quotelist->fix_po == 0 && $quotelist->fix_invoice == 0 && $quotelist->fix_do == 0)
                {
                    return '<p><span class="label bg-purple">Quote already created&nbsp;-&nbsp;'.$quotelist->quote_code.'&nbsp;-&nbsp;'.date('d-F-Y H:s:i', strtotime($quotelist->date_quote)).'</span></p>';
                }
                elseif($quotelist->fix_po == 1 && $quotelist->fix_invoice == 0 && $quotelist->fix_do == 0)
                {
                    return '<p><span class="label bg-purple">Quote already created&nbsp;-&nbsp;'.$quotelist->quote_code.'&nbsp;-&nbsp;'.date('d-F-Y H:s:i', strtotime($quotelist->date_quote)).'</span></p>
                            <p><span class="label label-info">PO already created&nbsp;-&nbsp;'.$quotelist->po_code.'&nbsp;-&nbsp;'.date('d-F-Y H:s:i', strtotime($quotelist->date_po)).'</span></p>';
                }
                elseif($quotelist->fix_po == 1 && $quotelist->fix_invoice == 1 && $quotelist->fix_do == 0)
                {
                    return '<p><span class="label bg-purple">Quote already created&nbsp;-&nbsp;'.$quotelist->quote_code.'&nbsp;-&nbsp;'.date('d-F-Y H:s:i', strtotime($quotelist->date_quote)).'</span></p>
                            <p><span class="label label-info">PO already created&nbsp;-&nbsp;'.$quotelist->po_code.'&nbsp;-&nbsp;'.date('d-F-Y H:s:i', strtotime($quotelist->date_po)).'</span></p>
                            <p><span class="label label-primary">Invoice already created&nbsp;-&nbsp;'.$quotelist->invoice_code.'&nbsp;-&nbsp;'.date('d-F-Y H:s:i', strtotime($quotelist->date_invoice)).'</span></p>';
                }
                elseif($quotelist->fix_po == 1 && $quotelist->fix_invoice == 1 && $quotelist->fix_do == 1)
                {
                    return '<p><span class="label bg-purple">Quote already created&nbsp;-&nbsp;'.$quotelist->quote_code.'&nbsp;-&nbsp;'.date('d-F-Y H:s:i', strtotime($quotelist->date_quote)).'</span></p>
                            <p><span class="label label-info">PO already created&nbsp;-&nbsp;'.$quotelist->po_code.'&nbsp;-&nbsp;'.date('d-F-Y H:s:i', strtotime($quotelist->date_po)).'</span></p>
                            <p><span class="label bg-primary">Invoice already created&nbsp;-&nbsp;'.$quotelist->invoice_code.'&nbsp;-&nbsp;'.date('d-F-Y H:s:i', strtotime($quotelist->date_invoice)).'</span></p>
                            <p><span class="label label-success">DO already created&nbsp;-&nbsp;'.$quotelist->do_code.'&nbsp;-&nbsp;'.date('d-F-Y H:s:i', strtotime($quotelist->date_do)).'</span></p>';
                }
            })
            ->rawColumns(['action', 'quote_code','fix_po', 'quote_list_code'])
            ->make(true);
    }





    public function pdfTransaction(Request $request)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $quotelist = QuoteLists::select('u.name as name_sales', 'cp.company_name', 'quote_lists.quote_list_code',
                'ql.quote_list_code as quote_code', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.quote_list_code as quote_code',
                'po.purchase_order_list_code as po_code', 'il.invoice_list_code as invoice_code', 'do.delivery_order_list_code as do_code', 'quote_lists.created_at as date_quote', 'po.created_at as date_po',
                'il.created_at as date_invoice', 'do.created_at as date_do', 'stp.name_progress')
                ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                ->where('quote_lists.is_active', '=', '1')
                ->where(function ($q) use ($request){
                    $q->where('u.name', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('cp.company_name', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('stp.name_progress', 'LIKE', '%' . $request->search . "%");
                })
                ->orderby('u.name', 'ASC')
                ->get();
        }else{
            $quotelist = QuoteLists::select('u.name as name_sales', 'cp.company_name', 'quote_lists.quote_list_code',
                'ql.quote_list_code as quote_code', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.quote_list_code as quote_code',
                'po.purchase_order_list_code as po_code', 'il.invoice_list_code as invoice_code', 'do.delivery_order_list_code as do_code', 'quote_lists.created_at as date_quote', 'po.created_at as date_po',
                'il.created_at as date_invoice', 'do.created_at as date_do', 'stp.name_progress')
                ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                ->where('quote_lists.sales_person_id', '=', Auth::user()->id)
                ->where('quote_lists.is_active', '=', '1')
                ->where(function ($q) use ($request){
                    $q->where('u.name', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('cp.company_name', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('stp.name_progress', 'LIKE', '%' . $request->search . "%");
                })
                ->orderby('u.name', 'ASC')
                ->get();
        }

        $pdf = \PDF::loadView('admin.report.by-sales-transaction-pdf', compact('quotelist'));
        return $pdf->stream();
    }



    public function printTransaction(Request $request)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $quotelist = QuoteLists::select('u.name as name_sales', 'cp.company_name', 'quote_lists.quote_list_code',
                'ql.quote_list_code as quote_code', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.quote_list_code as quote_code',
                'po.purchase_order_list_code as po_code', 'il.invoice_list_code as invoice_code', 'do.delivery_order_list_code as do_code', 'quote_lists.created_at as date_quote', 'po.created_at as date_po',
                'il.created_at as date_invoice', 'do.created_at as date_do', 'stp.name_progress')
                ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                ->where('quote_lists.is_active', '=', '1')
                ->where(function ($q) use ($request){
                    $q->where('u.name', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('cp.company_name', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('stp.name_progress', 'LIKE', '%' . $request->search . "%");
                })
                ->orderby('u.name', 'ASC')
                ->get();
        }else{
            $quotelist = QuoteLists::select('u.name as name_sales', 'cp.company_name', 'quote_lists.quote_list_code',
                'ql.quote_list_code as quote_code', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.quote_list_code as quote_code',
                'po.purchase_order_list_code as po_code', 'il.invoice_list_code as invoice_code', 'do.delivery_order_list_code as do_code', 'quote_lists.created_at as date_quote', 'po.created_at as date_po',
                'il.created_at as date_invoice', 'do.created_at as date_do', 'stp.name_progress')
                ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                ->where('quote_lists.sales_person_id', '=', Auth::user()->id)
                ->where('quote_lists.is_active', '=', '1')
                ->where(function ($q) use ($request){
                    $q->where('u.name', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('cp.company_name', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('stp.name_progress', 'LIKE', '%' . $request->search . "%");
                })
                ->orderby('u.name', 'ASC')
                ->get();
        }
        return view('admin.report.by-sales-transaction-print', compact('quotelist'));
    }

























    public function showByActivity()
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $quotelist = QuoteLists::select('u.name as name_sales', 'ps.id as prospect_id', 'ps.status_id', 'cp.company_name', 'quote_lists.quote_list_code',
                'ql.quote_list_code as quote_code', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.quote_list_code as quote_code',
                'po.purchase_order_list_code as po_code', 'il.invoice_list_code as invoice_code', 'do.delivery_order_list_code as do_code', 'quote_lists.created_at as date_quote',
                'po.created_at as date_po', 'il.created_at as date_invoice', 'do.created_at as date_do', 'stp.name_progress')
                ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                // ->groupBy('quote_lists.quote_list_code')
                ->where('quote_lists.is_active', '=', '1')
                ->orderby('u.name', 'ASC')
                ->paginate($this->paginate_number);
        }else{
            $quotelist = QuoteLists::select('u.name as name_sales', 'ps.id as prospect_id', 'ps.status_id', 'cp.company_name', 'quote_lists.quote_list_code',
                'ql.quote_list_code as quote_code', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.quote_list_code as quote_code',
                'po.purchase_order_list_code as po_code', 'il.invoice_list_code as invoice_code', 'do.delivery_order_list_code as do_code', 'quote_lists.created_at as date_quote',
                'po.created_at as date_po', 'il.created_at as date_invoice', 'do.created_at as date_do', 'stp.name_progress')
                ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                ->where('quote_lists.sales_person_id', '=', Auth::user()->id)
                ->where('quote_lists.is_active', '=', '1')
                ->orderby('u.name', 'ASC')
                ->paginate($this->paginate_number);
        }
            $prospectHistory = ProspectSalesHistory::select('prospect_sales_history.prospect_id', 'prospect_sales_history.user_id', 'prospect_sales_history.status_id',
                'prospect_sales_history.assignment_date', 'prospect_sales_history.status', 'stp.name_progress')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'prospect_sales_history.status_id')
                // ->groupBy('prospect_sales_history.assignment_date')
                ->orderby('prospect_sales_history.status', 'DESC')
                ->orderby('prospect_sales_history.assignment_date', 'DESC')
                ->get();

        return view('admin.report.by-sales-activity', compact('quotelist', 'prospectHistory'));
    }

    public function searchActivity(Request $request)
    {
        if($request->ajax())
        {
            $output="";
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    if($request->search != '') {
                        $quotelist = QuoteLists::select('u.name as name_sales', 'ps.id as prospect_id', 'ps.status_id', 'cp.company_name', 'quote_lists.quote_list_code',
                            'ql.quote_list_code as quote_code', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.quote_list_code as quote_code',
                            'po.purchase_order_list_code as po_code', 'il.invoice_list_code as invoice_code', 'do.delivery_order_list_code as do_code', 'quote_lists.created_at as date_quote',
                            'po.created_at as date_po', 'il.created_at as date_invoice', 'do.created_at as date_do', 'stp.name_progress')
                            ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                            ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                            ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                            ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                            ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                            ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                            ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                            ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                            // ->groupBy('quote_lists.quote_list_code')
                            ->where(function ($q) use ($request){
                                $q->where('u.name', 'LIKE', '%' . $request->search . "%")
                                    ->orWhere('cp.company_name', 'LIKE', '%' . $request->search . "%")
                                    ->orWhere('stp.name_progress', 'LIKE', '%' . $request->search . "%");
                            })
                            ->where('quote_lists.is_active', '=', '1')
                            ->orderby('u.name', 'ASC')
                            ->get();
                    } else {
                        $quotelist = QuoteLists::select('u.name as name_sales', 'ps.id as prospect_id', 'ps.status_id', 'cp.company_name', 'quote_lists.quote_list_code',
                            'ql.quote_list_code as quote_code', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.quote_list_code as quote_code',
                            'po.purchase_order_list_code as po_code', 'il.invoice_list_code as invoice_code', 'do.delivery_order_list_code as do_code', 'quote_lists.created_at as date_quote',
                            'po.created_at as date_po', 'il.created_at as date_invoice', 'do.created_at as date_do', 'stp.name_progress')
                            ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                            ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                            ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                            ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                            ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                            ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                            ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                            ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                            ->where('quote_lists.is_active', '=', '1')
                            ->orderby('u.name', 'ASC')
                            ->paginate($this->paginate_number);
                    }
            }else{
                if($request->search != '') {
                    $quotelist = QuoteLists::select('u.name as name_sales', 'ps.id as prospect_id', 'ps.status_id', 'cp.company_name', 'quote_lists.quote_list_code',
                        'ql.quote_list_code as quote_code', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.quote_list_code as quote_code',
                        'po.purchase_order_list_code as po_code', 'il.invoice_list_code as invoice_code', 'do.delivery_order_list_code as do_code', 'quote_lists.created_at as date_quote',
                        'po.created_at as date_po', 'il.created_at as date_invoice', 'do.created_at as date_do', 'stp.name_progress')
                        ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                        ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                        ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                        ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                        ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                        ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                        ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                        // ->groupBy('quote_lists.quote_list_code')
                        ->where('quote_lists.sales_person_id', '=', Auth::user()->id)

                        ->where(function ($q) use ($request){
                            $q->where('u.name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('cp.company_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('stp.name_progress', 'LIKE', '%' . $request->search . "%");
                        })
                        ->where('quote_lists.is_active', '=', '1')
                        ->orderby('u.name', 'ASC')
                        ->get();
                } else {
                    $quotelist = QuoteLists::select('u.name as name_sales', 'ps.id as prospect_id', 'ps.status_id', 'cp.company_name', 'quote_lists.quote_list_code',
                        'ql.quote_list_code as quote_code', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.quote_list_code as quote_code',
                        'po.purchase_order_list_code as po_code', 'il.invoice_list_code as invoice_code', 'do.delivery_order_list_code as do_code', 'quote_lists.created_at as date_quote',
                        'po.created_at as date_po', 'il.created_at as date_invoice', 'do.created_at as date_do', 'stp.name_progress')
                        ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                        ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                        ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                        ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                        ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                        ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                        ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                        ->where('quote_lists.sales_person_id', '=', Auth::user()->id)
                        ->where('quote_lists.is_active', '=', '1')
                        ->orderby('u.name', 'ASC')
                        ->paginate($this->paginate_number);
                }
            }

            $prospectHistory = ProspectSalesHistory::select('prospect_sales_history.prospect_id', 'prospect_sales_history.user_id', 'prospect_sales_history.status_id', 'prospect_sales_history.assignment_date', 'prospect_sales_history.status', 'stp.name_progress')
            ->leftJoin('status_progress as stp', 'stp.id', '=', 'prospect_sales_history.status_id')
            // ->groupBy('prospect_sales_history.assignment_date')
            ->orderby('prospect_sales_history.status', 'DESC')
            ->orderby('prospect_sales_history.assignment_date', 'DESC')
            ->get();

            if($quotelist)
            {
                foreach ($quotelist as $no => $quote) {
                    $output.='<tr>'.
                        '<td>'.++$no.'</td>'.
                        '<td>'.$quote->name_sales.'</td>'.
                        '<td>'.$quote->company_name.'</td><td>';
                            foreach($prospectHistory as $history){
                                if($quote->prospect_id == $history->prospect_id && $history->status_id == $quote->status_id && $history->status == 1 ){
                                $output.= '<p><span class="label label-success">'.$history->name_progress.'&nbsp;-&nbsp;'.date("d-F-Y H:s:i", strtotime($history->assignment_date)).'</span><p>';
                                }
                                if($quote->prospect_id == $history->prospect_id && $history->status != 1 ){
                                    $output.= $history->name_progress.'&nbsp;-&nbsp;'.date('d-F-Y H:s:i', strtotime($history->assignment_date)).'<br>';
                                }
                            }
                        $output.= '</td><td>'.$quote->name_progress.'</td>'.
                        '</tr>'; 
                }
                return Response($output);
            }
        }
    }

    public function pdfActivityAll(Request $request)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $quotelist = QuoteLists::select('u.name as name_sales', 'ps.id as prospect_id', 'ps.status_id', 'cp.company_name', 'quote_lists.quote_list_code',
                'ql.quote_list_code as quote_code', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.quote_list_code as quote_code',
                'po.purchase_order_list_code as po_code', 'il.invoice_list_code as invoice_code', 'do.delivery_order_list_code as do_code', 'quote_lists.created_at as date_quote',
                'po.created_at as date_po', 'il.created_at as date_invoice', 'do.created_at as date_do', 'stp.name_progress')
                ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                // ->groupBy('quote_lists.quote_list_code')
                ->where('quote_lists.is_active', '=', '1')
                ->orderby('u.name', 'ASC')
                ->get();

            $prospectHistory = ProspectSalesHistory::select('prospect_sales_history.prospect_id', 'prospect_sales_history.user_id', 'prospect_sales_history.status_id', 'prospect_sales_history.assignment_date', 'prospect_sales_history.status', 'stp.name_progress')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'prospect_sales_history.status_id')
                // ->groupBy('prospect_sales_history.assignment_date')
                ->orderby('prospect_sales_history.status', 'DESC')
                ->orderby('prospect_sales_history.assignment_date', 'DESC')
                ->get();
        }else{
            $quotelist = QuoteLists::select('u.name as name_sales', 'ps.id as prospect_id', 'ps.status_id', 'cp.company_name', 'quote_lists.quote_list_code',
                'ql.quote_list_code as quote_code', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.quote_list_code as quote_code',
                'po.purchase_order_list_code as po_code', 'il.invoice_list_code as invoice_code', 'do.delivery_order_list_code as do_code', 'quote_lists.created_at as date_quote',
                'po.created_at as date_po', 'il.created_at as date_invoice', 'do.created_at as date_do', 'stp.name_progress')
                ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                // ->groupBy('quote_lists.quote_list_code')
                ->where('quote_lists.sales_person_id', '=', Auth::user()->id)
                ->where('quote_lists.is_active', '=', '1')
                ->orderby('u.name', 'ASC')
                ->get();

            $prospectHistory = ProspectSalesHistory::select('prospect_sales_history.prospect_id', 'prospect_sales_history.user_id', 'prospect_sales_history.status_id', 'prospect_sales_history.assignment_date', 'prospect_sales_history.status', 'stp.name_progress')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'prospect_sales_history.status_id')
                // ->groupBy('prospect_sales_history.assignment_date')
                ->orderby('prospect_sales_history.status', 'DESC')
                ->orderby('prospect_sales_history.assignment_date', 'DESC')
                ->get();
        }
        $pdf = \PDF::loadView('admin.report.by-sales-activity-pdf', compact('quotelist', 'prospectHistory'));
        return $pdf->stream();
    }

    public function pdfActivityGetId($data,Request $request)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $quotelist = QuoteLists::select('u.name as name_sales', 'ps.id as prospect_id', 'ps.status_id', 'cp.company_name', 'quote_lists.quote_list_code',
                'ql.quote_list_code as quote_code', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.quote_list_code as quote_code',
                'po.purchase_order_list_code as po_code', 'il.invoice_list_code as invoice_code', 'do.delivery_order_list_code as do_code', 'quote_lists.created_at as date_quote',
                'po.created_at as date_po', 'il.created_at as date_invoice', 'do.created_at as date_do', 'stp.name_progress')
                ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                // ->groupBy('quote_lists.quote_list_code')
                ->where(function ($q) use ($data){
                    $q->where('u.name', 'LIKE', '%' . $data . "%")
                        ->orWhere('cp.company_name', 'LIKE', '%' . $data . "%")
                        ->orWhere('stp.name_progress', 'LIKE', '%' . $data . "%");
                })
                ->where('quote_lists.is_active', '=', '1')
                ->orderby('u.name', 'ASC')
                ->get();

            $prospectHistory = ProspectSalesHistory::select('prospect_sales_history.prospect_id', 'prospect_sales_history.user_id', 'prospect_sales_history.status_id', 'prospect_sales_history.assignment_date', 'prospect_sales_history.status', 'stp.name_progress')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'prospect_sales_history.status_id')
                // ->groupBy('prospect_sales_history.assignment_date')
                ->orderby('prospect_sales_history.status', 'DESC')
                ->orderby('prospect_sales_history.assignment_date', 'DESC')
                ->get();
        }else{
            $quotelist = QuoteLists::select('u.name as name_sales', 'ps.id as prospect_id', 'ps.status_id', 'cp.company_name', 'quote_lists.quote_list_code',
                'ql.quote_list_code as quote_code', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.quote_list_code as quote_code',
                'po.purchase_order_list_code as po_code', 'il.invoice_list_code as invoice_code', 'do.delivery_order_list_code as do_code', 'quote_lists.created_at as date_quote',
                'po.created_at as date_po', 'il.created_at as date_invoice', 'do.created_at as date_do', 'stp.name_progress')
                ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                // ->groupBy('quote_lists.quote_list_code')
                ->where('quote_lists.sales_person_id', '=', Auth::user()->id)
                ->where(function ($q) use ($data){
                    $q->where('u.name', 'LIKE', '%' . $data . "%")
                        ->orWhere('cp.company_name', 'LIKE', '%' . $data . "%")
                        ->orWhere('stp.name_progress', 'LIKE', '%' . $data . "%");
                })
                ->where('quote_lists.is_active', '=', '1')
                ->orderby('u.name', 'ASC')
                ->get();

            $prospectHistory = ProspectSalesHistory::select('prospect_sales_history.prospect_id', 'prospect_sales_history.user_id', 'prospect_sales_history.status_id', 'prospect_sales_history.assignment_date', 'prospect_sales_history.status', 'stp.name_progress')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'prospect_sales_history.status_id')
                // ->groupBy('prospect_sales_history.assignment_date')
                ->orderby('prospect_sales_history.status', 'DESC')
                ->orderby('prospect_sales_history.assignment_date', 'DESC')
                ->get();
        }

        $pdf = \PDF::loadView('admin.report.by-sales-activity-pdf', compact('quotelist', 'prospectHistory'));
        return $pdf->stream();
    }

    public function printActivityAll(Request $request)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $quotelist = QuoteLists::select('u.name as name_sales', 'ps.id as prospect_id', 'ps.status_id', 'cp.company_name', 'quote_lists.quote_list_code',
                'ql.quote_list_code as quote_code', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.quote_list_code as quote_code',
                'po.purchase_order_list_code as po_code', 'il.invoice_list_code as invoice_code', 'do.delivery_order_list_code as do_code', 'quote_lists.created_at as date_quote', 'po.created_at as date_po',
                'il.created_at as date_invoice', 'do.created_at as date_do', 'stp.name_progress')
                ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                // ->groupBy('quote_lists.quote_list_code')
                ->where('quote_lists.is_active', '=', '1')
                ->orderby('u.name', 'ASC')
                ->get();

            $prospectHistory = ProspectSalesHistory::select('prospect_sales_history.prospect_id', 'prospect_sales_history.user_id', 'prospect_sales_history.status_id', 'prospect_sales_history.assignment_date', 'prospect_sales_history.status', 'stp.name_progress')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'prospect_sales_history.status_id')
                // ->groupBy('prospect_sales_history.assignment_date')
                ->orderby('prospect_sales_history.status', 'DESC')
                ->orderby('prospect_sales_history.assignment_date', 'DESC')
                ->get();
        }else{
            $quotelist = QuoteLists::select('u.name as name_sales', 'ps.id as prospect_id', 'ps.status_id', 'cp.company_name', 'quote_lists.quote_list_code',
                'ql.quote_list_code as quote_code', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.quote_list_code as quote_code',
                'po.purchase_order_list_code as po_code', 'il.invoice_list_code as invoice_code', 'do.delivery_order_list_code as do_code', 'quote_lists.created_at as date_quote',
                'po.created_at as date_po', 'il.created_at as date_invoice', 'do.created_at as date_do', 'stp.name_progress')
                ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                // ->groupBy('quote_lists.quote_list_code')
                ->where('quote_lists.sales_person_id', '=', Auth::user()->id)
                ->where('quote_lists.is_active', '=', '1')
                ->orderby('u.name', 'ASC')
                ->get();

            $prospectHistory = ProspectSalesHistory::select('prospect_sales_history.prospect_id', 'prospect_sales_history.user_id', 'prospect_sales_history.status_id', 'prospect_sales_history.assignment_date', 'prospect_sales_history.status', 'stp.name_progress')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'prospect_sales_history.status_id')
                // ->groupBy('prospect_sales_history.assignment_date')
                ->orderby('prospect_sales_history.status', 'DESC')
                ->orderby('prospect_sales_history.assignment_date', 'DESC')
                ->get();
        }
        return view('admin.report.by-sales-activity-print', compact('quotelist', 'prospectHistory'));
    }

    public function excelActivityAll($format, Request $request)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $quotelist = QuoteLists::select('u.name as name_sales', 'cp.company_name', 'stp.name_progress as activity_status',
                'quote_lists.created_at as date_activity')
                ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                // ->groupBy('quote_lists.quote_list_code')
                ->where('quote_lists.is_active', '=', '1')
                ->orderby('u.name', 'ASC')
                ->get()
                ->toArray();

            $prospectHistory = ProspectSalesHistory::select('prospect_sales_history.prospect_id', 'prospect_sales_history.user_id', 'prospect_sales_history.status_id', 'prospect_sales_history.assignment_date', 'prospect_sales_history.status', 'stp.name_progress')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'prospect_sales_history.status_id')
                // ->groupBy('prospect_sales_history.assignment_date')
                ->orderby('prospect_sales_history.status', 'DESC')
                ->orderby('prospect_sales_history.assignment_date', 'DESC')
                ->get();
        }else{
            $quotelist = QuoteLists::select('u.name as name_sales', 'ps.id as prospect_id', 'ps.status_id', 'cp.company_name', 'quote_lists.quote_list_code',
                'ql.quote_list_code as quote_code', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.quote_list_code as quote_code',
                'po.purchase_order_list_code as po_code', 'il.invoice_list_code as invoice_code', 'do.delivery_order_list_code as do_code', 'quote_lists.created_at as date_quote',
                'po.created_at as date_po', 'il.created_at as date_invoice', 'do.created_at as date_do', 'stp.name_progress')
                ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                // ->groupBy('quote_lists.quote_list_code')
                ->where('quote_lists.sales_person_id', '=', Auth::user()->id)
                ->where('quote_lists.is_active', '=', '1')
                ->orderby('u.name', 'ASC')
                ->get()
                ->toArray();

            $prospectHistory = ProspectSalesHistory::select('prospect_sales_history.prospect_id', 'prospect_sales_history.user_id', 'prospect_sales_history.status_id', 'prospect_sales_history.assignment_date', 'prospect_sales_history.status', 'stp.name_progress')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'prospect_sales_history.status_id')
                // ->groupBy('prospect_sales_history.assignment_date')
                ->orderby('prospect_sales_history.status', 'DESC')
                ->orderby('prospect_sales_history.assignment_date', 'DESC')
                ->get();
        }
        return Excel::create('by-sales'.date('Ymd'), function($excel) use ($quotelist) {
            $excel->sheet('bySales', function($sheet) use ($quotelist)
            {
                $sheet->fromArray($quotelist);
            });
        })->download($format);
    }

    public function printActivityGetId($data,Request $request)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $quotelist = QuoteLists::select('u.name as name_sales', 'ps.id as prospect_id', 'ps.status_id', 'cp.company_name', 'quote_lists.quote_list_code',
                'ql.quote_list_code as quote_code', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.quote_list_code as quote_code',
                'po.purchase_order_list_code as po_code', 'il.invoice_list_code as invoice_code', 'do.delivery_order_list_code as do_code', 'quote_lists.created_at as date_quote',
                'po.created_at as date_po', 'il.created_at as date_invoice', 'do.created_at as date_do', 'stp.name_progress')
                ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                // ->groupBy('quote_lists.quote_list_code')
                ->where(function ($q) use ($data){
                    $q->where('u.name', 'LIKE', '%' . $data . "%")
                        ->orWhere('cp.company_name', 'LIKE', '%' . $data . "%")
                        ->orWhere('stp.name_progress', 'LIKE', '%' . $data . "%");
                })
                ->where('quote_lists.is_active', '=', '1')
                ->orderby('u.name', 'ASC')
                ->get();

            $prospectHistory = ProspectSalesHistory::select('prospect_sales_history.prospect_id', 'prospect_sales_history.user_id', 'prospect_sales_history.status_id', 'prospect_sales_history.assignment_date', 'prospect_sales_history.status', 'stp.name_progress')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'prospect_sales_history.status_id')
                // ->groupBy('prospect_sales_history.assignment_date')
                ->orderby('prospect_sales_history.status', 'DESC')
                ->orderby('prospect_sales_history.assignment_date', 'DESC')
                ->get();
        }else{
            $quotelist = QuoteLists::select('u.name as name_sales', 'ps.id as prospect_id', 'ps.status_id', 'cp.company_name', 'quote_lists.quote_list_code',
                'ql.quote_list_code as quote_code', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.quote_list_code as quote_code',
                'po.purchase_order_list_code as po_code', 'il.invoice_list_code as invoice_code', 'do.delivery_order_list_code as do_code', 'quote_lists.created_at as date_quote',
                'po.created_at as date_po', 'il.created_at as date_invoice', 'do.created_at as date_do', 'stp.name_progress')
                ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('delivery_order_lists as do', 'do.purchase_order_list_code_id', '=', 'po.id')
                ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'ps.status_id')
                // ->groupBy('quote_lists.quote_list_code')
                ->where('quote_lists.sales_person_id', '=', Auth::user()->id)
                ->where(function ($q) use ($data){
                    $q->where('u.name', 'LIKE', '%' . $data . "%")
                        ->orWhere('cp.company_name', 'LIKE', '%' . $data . "%")
                        ->orWhere('stp.name_progress', 'LIKE', '%' . $data . "%");
                })
                ->where('quote_lists.is_active', '=', '1')
                ->orderby('u.name', 'ASC')
                ->get();

            $prospectHistory = ProspectSalesHistory::select('prospect_sales_history.prospect_id', 'prospect_sales_history.user_id', 'prospect_sales_history.status_id', 'prospect_sales_history.assignment_date', 'prospect_sales_history.status', 'stp.name_progress')
                ->leftJoin('status_progress as stp', 'stp.id', '=', 'prospect_sales_history.status_id')
                // ->groupBy('prospect_sales_history.assignment_date')
                ->orderby('prospect_sales_history.status', 'DESC')
                ->orderby('prospect_sales_history.assignment_date', 'DESC')
                ->get();
        }

        return view('admin.report.by-sales-activity-print', compact('quotelist', 'prospectHistory'));
    }

    public function showByBrand()
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', 1)
                ->paginate($this->paginate_number);
        }else{
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.sales_person_id', '=', Auth::user()->id)
                ->where('ql.is_active', 1)
                ->paginate($this->paginate_number);
        }
        return view('admin.report.by-brand', compact('historybrand'));
    }

    public function searchBrand(Request $request)
    {
        if($request->ajax())
        {
            $output="";
            if($request->search == '' && $request->start_date == '' && $request->end_date == '' )
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->paginate($this->paginate_number);
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->where('ql.is_active', '=', 1)
                        ->paginate($this->paginate_number);
                }
            }elseif($request->search != '' && $request->start_date == '' && $request->end_date == '' )
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where(function ($q) use ($request){
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                              ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                              ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                              ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                              ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                              ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                              ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                        })
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->where(function ($q) use ($request){
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                              ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                              ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                              ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                              ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                              ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                              ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                        })
                        ->get();
                }
            }elseif($request->search != '' && $request->start_date != '' && $request->end_date == '' )
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, date('Y-m-d')))
                        ->where(function ($q) use ($request){
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                        })
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, date('Y-m-d')))
                        ->where(function ($q) use ($request){
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                        })
                        ->get();
                }
            }elseif($request->search != '' && $request->start_date != '' && $request->end_date != '' )
            {

                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, $request->end_date))
                        ->where(function ($q) use ($request){
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                        })

                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, $request->end_date))
                        ->where(function ($q) use ($request){
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                        })
                        ->get();
                }
            }elseif($request->search == '' && $request->start_date != '' && $request->end_date == '' )
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, date('Y-m-d')))
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, date('Y-m-d')))
                        ->get();
                }
            }elseif($request->search == '' && $request->start_date != '' && $request->end_date != '')
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, $request->end_date))
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, $request->end_date))
                        ->get();
                }
            }elseif($request->search != '' && $request->start_date == '' && $request->end_date != '' )
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->whereBetween('quote_list_detail.created_at', array('1000-01-01', $request->end_date))
                        ->where(function ($q) use ($request){
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                        })
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->whereBetween('quote_list_detail.created_at', array('1000-01-01', $request->end_date))
                        ->where(function ($q) use ($request){
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                        })
                        ->get();
                }
            }elseif($request->search == '' && $request->start_date == '' && $request->end_date != '' )
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->whereBetween('quote_list_detail.created_at', array('1000-01-01', $request->end_date))
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->whereBetween('quote_list_detail.created_at', array('1000-01-01', $request->end_date))
                        ->get();
                }
            }
            if($historybrand)
            {
                foreach ($historybrand as $no => $brand) {
                    $output.='<tr>'.
                        '<td>'.++$no.'</td>'.
                        '<td>'.$brand->brand.'</td>'.
                        '<td>'.$brand->name_product.'</td>'.
                        '<td align="center">'.$brand->qty.'</td>'.
                        '<td align="right">'.number_format($brand->price,2).'</td>';
                        if($brand->diskon_nominal == 0 ||$brand->diskon_nominal == ''){
                            $output.= '<td align="right">0</td>'; 
                        }else{
                            $output.= '<td align="right">'.number_format($brand->diskon_nominal,2).'</td>';
                        }
                        $output.='<td align="right">'.number_format($brand->net_price,2).'</td>'.
                        '<td>'.$brand->created_at.'</td>'.
                        '</tr>'; 
                }
                return Response($output);
            }
        }
    }

    public function startDateBrand(Request $request)
    {
        if($request->ajax())
        {
            $output="";
            if($request->start_date == '' && $request->search == '' && $request->end_date == '' )
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->get();
                }
            }elseif($request->start_date != '' && $request->search == '' && $request->end_date == '' )
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, date('Y-m-d')))
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, date('Y-m-d')))
                        ->get();
                }
            }elseif($request->start_date != '' && $request->search != '' && $request->end_date == '' )
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, date('Y-m-d')))
                        ->where(function ($q) use ($request){
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                        })
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, date('Y-m-d')))
                        ->where(function ($q) use ($request){
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                        })
                        ->get();
                }
            }elseif($request->start_date != '' && $request->search != ''&& $request->end_date != '' )
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, $request->end_date))
                        ->where(function ($q) use ($request){
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                        })
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, $request->end_date))
                        ->where(function ($q) use ($request){
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                        })
                        ->get();
                }
            }elseif($request->start_date == '' && $request->search != '' && $request->end_date != '' )
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->whereBetween('quote_list_detail.created_at', array('1000-01-01', $request->end_date))
                        ->where(function ($q) use ($request){
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                        })
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->whereBetween('quote_list_detail.created_at', array('1000-01-01', $request->end_date))
                        ->where(function ($q) use ($request){
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                        })
                        ->get();
                }
            }elseif($request->start_date == '' && $request->search == '' && $request->end_date != '' )
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->whereBetween('quote_list_detail.created_at', array('1000-01-01', $request->end_date))
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->whereBetween('quote_list_detail.created_at', array('1000-01-01', $request->end_date))
                        ->get();
                }
            }elseif($request->start_date == '' && $request->search != '' && $request->end_date == '' )
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where(function ($q) use ($request){
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                        })
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->where(function ($q) use ($request){
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                        })
                        ->get();
                }
            }elseif($request->start_date != '' && $request->search == ''&& $request->end_date != '')
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, $request->end_date))
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, $request->end_date))
                        ->get();
                }
            }
            if($historybrand)
            {
                foreach ($historybrand as $no => $brand) {
                    $output.='<tr>'.
                        '<td>'.++$no.'</td>'.
                        '<td>'.$brand->brand.'</td>'.
                        '<td>'.$brand->name_product.'</td>'.
                        '<td align="center">'.$brand->qty.'</td>'.
                        '<td align="right">'.number_format($brand->price,2).'</td>';
                        if($brand->diskon_nominal == 0 ||$brand->diskon_nominal == ''){
                            $output.= '<td align="right">0</td>'; 
                        }else{
                            $output.= '<td align="right">'.number_format($brand->diskon_nominal,2).'</td>';
                        }
                        $output.='<td align="right">'.number_format($brand->net_price,2).'</td>'.
                        '<td>'.$brand->created_at.'</td>'.
                        '</tr>'; 
                }
                return Response($output);
            }
        }
    }

    public function endDateBrand(Request $request)
    {
        if($request->ajax())
        {
            $output="";
            if($request->end_date == '' && $request->start_date == '' && $request->search == '')
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->get();
                }
            }elseif($request->end_date != '' && $request->start_date == '' && $request->search == '')
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->whereBetween('quote_list_detail.created_at', array('1000-01-01', $request->end_date))
                        ->where('ql.is_active', '=', 1)
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->whereBetween('quote_list_detail.created_at', array('1000-01-01', $request->end_date))
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->get();
                }
            }elseif($request->end_date != '' && $request->start_date == '' && $request->search != '')
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->whereBetween('quote_list_detail.created_at', array('1000-01-01', $request->end_date))
                        ->where(function ($q) use ($request) {
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                                })
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->whereBetween('quote_list_detail.created_at', array('1000-01-01', $request->end_date))
                        ->where(function ($q) use ($request) {
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                                })
                        ->get();
                }
            }elseif($request->end_date != '' && $request->start_date != '' && $request->search != '')
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, $request->end_date))
                        ->where(function ($q) use ($request) {
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                                })
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, $request->end_date))
                        ->where(function ($q) use ($request) {
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                                })
                        ->get();
                }
            }elseif($request->end_date == '' && $request->start_date != '' && $request->search == '')
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, date('Y-m-d')))
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, date('Y-m-d')))
                        ->get();
                }
            }elseif($request->end_date == '' && $request->start_date != '' && $request->search != '')
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, date('Y-m-d')))
                        ->where(function ($q) use ($request) {
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                        })
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, date('Y-m-d')))
                        ->where(function ($q) use ($request) {
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                        })
                        ->get();
                }
            }elseif($request->end_date == '' && $request->start_date == '' && $request->search != '')
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where(function ($q) use ($request) {
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                        })
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->where(function ($q) use ($request) {
                            $q->where('quote_list_detail.brand', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $request->search . "%")
                                ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $request->search . "%");
                        })
                        ->get();
                }
            }elseif($request->end_date != '' && $request->start_date != '' && $request->search == '')
            {
                if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, $request->end_date))
                        ->get();
                }else{
                    $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                        ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                            'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                        ->where('ql.is_active', '=', 1)
                        ->where('ql.sales_person_id', '=', Auth::user()->id)
                        ->whereBetween('quote_list_detail.created_at', array($request->start_date, $request->end_date))
                        ->get();
                }
            }
            if($historybrand)
            {
                foreach ($historybrand as $no => $brand) {
                    $output.='<tr>'.
                        '<td>'.++$no.'</td>'.
                        '<td>'.$brand->brand.'</td>'.
                        '<td>'.$brand->name_product.'</td>'.
                        '<td align="center">'.$brand->qty.'</td>'.
                        '<td align="right">'.number_format($brand->price,2).'</td>';
                        if($brand->diskon_nominal == 0 ||$brand->diskon_nominal == ''){
                            $output.= '<td align="right">0</td>'; 
                        }else{
                            $output.= '<td align="right">'.number_format($brand->diskon_nominal,2).'</td>';
                        }
                        $output.='<td align="right">'.number_format($brand->net_price,2).'</td>'.
                        '<td>'.$brand->created_at.'</td>'.
                        '</tr>'; 
                }
                return Response($output);
            }
        }
    }

    public function pdfBrandAll()
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->get();
        }else{
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->where('ql.sales_person_id', '=', Auth::user()->id)
                ->get();
        }

        $pdf = \PDF::loadView('admin.report.by-brand-pdf', compact('historybrand'));
        return $pdf->stream();
    }

    public function pdfBrandStartDate($start_date)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->whereBetween('quote_list_detail.created_at', array($start_date, date('Y-m-d')))
                ->get();
        }else{
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->where('ql.sales_person_id', '=', Auth::user()->id)
                ->whereBetween('quote_list_detail.created_at', array($start_date, date('Y-m-d')))
                ->get();
        }

        $pdf = \PDF::loadView('admin.report.by-brand-pdf', compact('historybrand'));
        return $pdf->stream();
    }

    public function pdfBrandStartEndDate($start_date, $end_date)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->whereBetween('quote_list_detail.created_at', array($start_date, $end_date))
                ->get();
        }else{
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->where('ql.sales_person_id', '=', Auth::user()->id)
                ->whereBetween('quote_list_detail.created_at', array($start_date, $end_date))
                ->get();
        }

        $pdf = \PDF::loadView('admin.report.by-brand-pdf', compact('historybrand'));
        return $pdf->stream();
    }

    public function pdfBrandStartEndDateSearch($start_date, $end_date, $search)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->whereBetween('quote_list_detail.created_at', array($start_date, $end_date))
                ->where(function ($q) use ($search) {
                    $q->where('quote_list_detail.brand', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $search . "%");
                })
                ->get();
        }else{
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->where('ql.sales_person_id', '=', Auth::user()->id)
                ->whereBetween('quote_list_detail.created_at', array($start_date, $end_date))
                ->where(function ($q) use ($search) {
                    $q->where('quote_list_detail.brand', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $search . "%");
                })
                ->get();
        }

        $pdf = \PDF::loadView('admin.report.by-brand-pdf', compact('historybrand'));
        return $pdf->stream();
    }

    public function pdfBrandSearch($search)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->where(function ($q) use ($search) {
                    $q->where('quote_list_detail.brand', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $search . "%");
                })
                ->get();
        }else{
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->where('ql.sales_person_id', '=', Auth::user()->id)
                ->where(function ($q) use ($search) {
                    $q->where('quote_list_detail.brand', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $search . "%");
                })
                ->get();
        }

        $pdf = \PDF::loadView('admin.report.by-brand-pdf', compact('historybrand'));
        return $pdf->stream();
    }

    public function pdfBrandEndDateSearch($end_date, $search)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->whereBetween('quote_list_detail.created_at', array('1000-01-01', $end_date))
                ->where(function ($q) use ($search) {
                    $q->where('quote_list_detail.brand', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $search . "%");
                })
                ->get();
        }else{
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->where('ql.sales_person_id', '=', Auth::user()->id)
                ->whereBetween('quote_list_detail.created_at', array('1000-01-01', $end_date))
                ->where(function ($q) use ($search) {
                    $q->where('quote_list_detail.brand', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $search . "%");
                })
                ->get();
        }

        $pdf = \PDF::loadView('admin.report.by-brand-pdf', compact('historybrand'));
        return $pdf->stream();
    }

    public function pdfBrandStartDateSearch($start_date, $search)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->whereBetween('quote_list_detail.created_at', array($start_date, date('Y-m-d')))
                ->where(function ($q) use ($search) {
                    $q->where('quote_list_detail.brand', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $search . "%");
                })
                ->get();
        }else{
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->where('ql.sales_person_id', '=', Auth::user()->id)
                ->whereBetween('quote_list_detail.created_at', array($start_date, date('Y-m-d')))
                ->where(function ($q) use ($search) {
                    $q->where('quote_list_detail.brand', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $search . "%");
                })
                ->get();
        }
        $pdf = \PDF::loadView('admin.report.by-brand-pdf', compact('historybrand'));
        return $pdf->stream();
    }

    public function pdfBrandEndDate($end_date)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->whereBetween('quote_list_detail.created_at', array('1000-01-01', $end_date))
                ->get();
        }else{
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->where('ql.sales_person_id', '=', Auth::user()->id)
                ->whereBetween('quote_list_detail.created_at', array('1000-01-01', $end_date))
                ->get();
        }
        $pdf = \PDF::loadView('admin.report.by-brand-pdf', compact('historybrand'));
        return $pdf->stream();
    }



    public function printBrandAll()
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->get();
        }else{
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->where('ql.sales_person_id', '=', Auth::user()->id)
                ->get();
        }
        return view('admin.report.by-brand-print', compact('historybrand'));
    }

    public function excelBrandAll($format)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('quote_list_detail.brand', 'quote_list_detail.product_name as product', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at as product_date')
                ->where('ql.is_active', '=', 1)
                ->get()
                ->toArray();
        }else{
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('quote_list_detail.brand', 'quote_list_detail.product_name as product', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at as product_date')
                ->where('ql.is_active', '=', 1)
                ->where('ql.sales_person_id', '=', Auth::user()->id)
                ->get()
                ->toArray();
        }
        return Excel::create('by-brand'.date('Ymd'), function($excel) use ($historybrand) {
            $excel->sheet('byBrand', function($sheet) use ($historybrand)
            {
                $sheet->fromArray($historybrand);
            });
        })->download($format);
    }

    public function printBrandStartDate($start_date)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->whereBetween('quote_list_detail.created_at', array($start_date, date('Y-m-d')))
                ->get();
        }else{
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->where('ql.sales_person_id', '=', Auth::user()->id)
                ->whereBetween('quote_list_detail.created_at', array($start_date, date('Y-m-d')))
                ->get();
        }

        return view('admin.report.by-brand-print', compact('historybrand'));
    }

    public function printBrandStartEndDate($start_date, $end_date)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->whereBetween('quote_list_detail.created_at', array($start_date, $end_date))
                ->get();
        }else{
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->where('ql.sales_person_id', '=', Auth::user()->id)
                ->whereBetween('quote_list_detail.created_at', array($start_date, $end_date))
                ->get();
        }

        return view('admin.report.by-brand-print', compact('historybrand'));
    }

    public function printBrandStartEndDateSearch($start_date, $end_date, $search)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->whereBetween('quote_list_detail.created_at', array($start_date, $end_date))
                ->where(function ($q) use ($search) {
                    $q->where('quote_list_detail.brand', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $search . "%");
                })
                ->get();
        }else{
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->where('ql.sales_person_id', '=', Auth::user()->id)
                ->whereBetween('quote_list_detail.created_at', array($start_date, $end_date))
                ->where(function ($q) use ($search) {
                    $q->where('quote_list_detail.brand', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $search . "%");
                })
                ->get();
        }

        return view('admin.report.by-brand-print', compact('historybrand'));
    }

    public function printBrandSearch($search)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->where(function ($q) use ($search) {
                    $q->where('quote_list_detail.brand', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $search . "%");
                })
                ->get();
        }else{
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->where('ql.sales_person_id', '=', Auth::user()->id)
                ->where(function ($q) use ($search) {
                    $q->where('quote_list_detail.brand', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $search . "%");
                })
                ->get();
        }

        return view('admin.report.by-brand-print', compact('historybrand'));
    }

    public function printBrandEndDateSearch($end_date, $search)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->whereBetween('quote_list_detail.created_at', array('1000-01-01', $end_date))
                ->where(function ($q) use ($search) {
                    $q->where('quote_list_detail.brand', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $search . "%");
                })
                ->get();
        }else{
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->where('ql.sales_person_id', '=', Auth::user()->id)
                ->whereBetween('quote_list_detail.created_at', array('1000-01-01', $end_date))
                ->where(function ($q) use ($search) {
                    $q->where('quote_list_detail.brand', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $search . "%");
                })
                ->get();
        }

        return view('admin.report.by-brand-print', compact('historybrand'));
    }

    public function printBrandStartDateSearch($start_date, $search)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->whereBetween('quote_list_detail.created_at', array($start_date, date('Y-m-d')))
                ->where(function ($q) use ($search) {
                    $q->where('quote_list_detail.brand', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $search . "%");
                })
                ->get();
        }else{
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->where('ql.sales_person_id', '=', Auth::user()->id)
                ->whereBetween('quote_list_detail.created_at', array($start_date, date('Y-m-d')))
                ->where(function ($q) use ($search) {
                    $q->where('quote_list_detail.brand', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.product_name', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.qty', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.diskon_nominal', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.net_price', 'LIKE', '%' . $search . "%")
                        ->orWhere('quote_list_detail.created_at', 'LIKE', '%' . $search . "%");
                })
                ->get();
        }

        return view('admin.report.by-brand-print', compact('historybrand'));
    }

    public function printBrandEndDate($end_date)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->whereBetween('quote_list_detail.created_at', array('1000-01-01', $end_date))
                ->get();
        }else{
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select('ql.is_active', 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product')
                ->where('ql.is_active', '=', 1)
                ->where('ql.sales_person_id', '=', Auth::user()->id)
                ->whereBetween('quote_list_detail.created_at', array('1000-01-01', $end_date))
                ->get();
        }

        return view('admin.report.by-brand-print', compact('historybrand'));
    }

    public function anyDataByBrand()
    {
        DB::statement(DB::raw('set @rownum=0'));
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product'])
                ->get();
        }else{
            $historybrand = QuoteListsDetail::leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_list_detail.quote_list_id')
                ->leftJoin('users as u', 'u.id', '=', 'ql.sales_person_id')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_list_detail.prospect_sales_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'quote_list_detail.brand', 'quote_list_detail.qty', 'quote_list_detail.price', 'quote_list_detail.diskon_nominal',
                    'quote_list_detail.net_price', 'quote_list_detail.created_at', 'quote_list_detail.product_name as name_product'])
                ->where('ql.sales_person_id', '=', Auth::user()->id)
                ->get();
        }

        return Datatables::of($historybrand)

            ->addColumn('price', function ($historybrand)
            {
                return number_format($historybrand->price,2);
            })
            ->addColumn('diskon_nominal', function ($historybrand)
            {
                if($historybrand->diskon_nominal == 0 || $historybrand->diskon_nominal == ''){
                    return '0';
                }else{
                    return number_format($historybrand->diskon_nominal,2);
                }
            })
            ->addColumn('net_price', function ($historybrand)
            {
                return number_format($historybrand->net_price,2);
            })
            ->rawColumns(['price', 'diskon_nominal', 'net_price'])
            ->make(true);
    }

    public function showProspectSales()
    {
        return view('admin.report.prospect-sales');
    }
    public function anyDataProspectSales()
    {
        DB::statement(DB::raw('set @rownum=0'));
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $prospectsales = ProspectSales::leftJoin('users', 'prospect_sales.sales_person_id', '=', 'users.id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'users.name as name_sales ', 'prospect_sales.*'])
                ->orderby('prospect_sales.assignment_date', 'ASC')
                ->get();
        }else{
            $prospectsales = ProspectSales::leftJoin('users', 'prospect_sales.sales_person_id', '=', 'users.id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales.status_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'users.name as name_sales ', 'prospect_sales.*'])
                ->orderby('prospect_sales.assignment_date', 'ASC')
                ->where('prospect_sales.user_id', '=', Auth::user()->id)
                ->get();
        }

        return Datatables::of($prospectsales)

            ->addColumn('action', function ($prospectsales) {
                return '<a class="btn bg-blue-grey btn-xs waves-effect" href="prospect-sales-detail/'.$prospectsales->id.'"><i class="fa fa-eye"></i> View</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    public function showProspectSalesHistory($id)
    {
        
        $brandData = ProspectToBrand::leftJoin('brand', 'brand.id', '=', 'prospect_to_brand.brand_id')
        ->select('prospect_to_brand.brand_id', 'brand.brand')
        ->where('prospect_sales_id',$id)
        ->get();

        $prospectsales = ProspectSales::findOrFail($id);
        return view('admin.report.prospect-sales-history', compact('id', 'prospectsales', 'brandData'));
    }
    
    public function anyDataProspectSalesHistory($id)
    {
        DB::statement(DB::raw('set @rownum=0'));
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $historysales = ProspectSalesHistory::leftJoin('prospect_sales', 'prospect_sales.id', '=', 'prospect_sales_history.prospect_id')
                ->leftJoin('users', 'users.id', '=', 'prospect_sales_history.user_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales_history.status_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'prospect_sales.company_name', 'prospect_sales.company_address', 'prospect_sales.company_phone', 'prospect_sales.progress_notes', 'prospect_sales_history.assignment_date', 'prospect_sales_history.status_id', 'prospect_sales_history.sales_person_id', 'prospect_sales_history.notes',
                    'users.name as name_sales', 'status_progress.name_progress', 'prospect_sales_history.prospect_id', 'prospect_sales_history.status', 'prospect_sales_history.created_at'])
                ->where('prospect_sales_history.prospect_id', '=', $id)
                ->where('prospect_sales_history.status_id', '<>', 1)
                ->orderby('prospect_sales_history.created_at', 'DESC')
                ->get();
        }else{
            $historysales = ProspectSalesHistory::leftJoin('prospect_sales', 'prospect_sales.id', '=', 'prospect_sales_history.prospect_id')
                ->leftJoin('users', 'users.id', '=', 'prospect_sales_history.user_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales_history.status_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'prospect_sales.company_name', 'prospect_sales.company_address', 'prospect_sales.company_phone', 'prospect_sales.progress_notes', 'prospect_sales_history.assignment_date', 'prospect_sales_history.status_id', 'prospect_sales_history.sales_person_id', 'prospect_sales_history.notes',
                    'users.name as name_sales', 'status_progress.name_progress', 'prospect_sales_history.prospect_id', 'prospect_sales_history.status', 'prospect_sales_history.created_at'])
                ->where('prospect_sales_history.prospect_id', '=', $id)
                ->where('prospect_sales_history.status_id', '<>', 1)
                ->where('prospect_sales_history.user_id', '=', Auth::user()->id)
                ->orderby('prospect_sales_history.created_at', 'DESC')
                ->get();
        }

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
                    return '<p class="btn bg-light-green btn-xs waves-effect">'.$historysales->name_progress.'</p>';
                } else {
                    return '<p class="btn bg-red btn-xs waves-effect">'.$historysales->name_progress.'</p>';
                }

            })

            ->rawColumns(['status'])
            ->make(true);
    }

    public function ProspectSalesHistoryPdf($id)
    {
//        $prospectSales = ProspectSales::findOrFail($id);

        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $prospectSales = ProspectSales::select('prospect_sales.company_name', 'prospect_sales.company_address', 'prospect_sales.name_pic', 'prospect_sales.company_phone', 'prospect_sales.sales_person_id', 'prospect_sales.assignment_date', 'prospect_sales.progress_notes', 'prospect_sales.status_id', 'status.name_progress', 'u.name as name_sales', 'prospect_sales.created_at')
                ->leftJoin('users as u', 'u.id', '=', 'prospect_sales.sales_person_id')
                ->leftJoin('status_progress as status', 'status.id', '=', 'prospect_sales.status_id')
                ->where('prospect_sales.id', $id)
                ->first();

            $historysales = ProspectSalesHistory::leftJoin('prospect_sales', 'prospect_sales.id', '=', 'prospect_sales_history.prospect_id')
                ->leftJoin('users', 'users.id', '=', 'prospect_sales_history.user_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales_history.status_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'prospect_sales.company_name', 'prospect_sales.company_address', 'prospect_sales.company_phone', 'prospect_sales.progress_notes', 'prospect_sales_history.assignment_date', 'prospect_sales_history.status_id', 'prospect_sales_history.sales_person_id', 'prospect_sales_history.notes',
                    'users.name as name_sales', 'status_progress.name_progress', 'prospect_sales_history.prospect_id', 'prospect_sales_history.status', 'prospect_sales_history.created_at'])
                ->where('prospect_sales_history.prospect_id', '=', $id)
                ->where('prospect_sales_history.status_id', '<>', 1)
                ->orderby('prospect_sales_history.created_at', 'DESC')
                ->get();
        }else{
            $prospectSales = ProspectSales::select('prospect_sales.company_name', 'prospect_sales.company_address', 'prospect_sales.name_pic', 'prospect_sales.company_phone', 'prospect_sales.sales_person_id', 'prospect_sales.assignment_date', 'prospect_sales.progress_notes', 'prospect_sales.status_id', 'status.name_progress', 'u.name as name_sales', 'prospect_sales.created_at')
                ->leftJoin('users as u', 'u.id', '=', 'prospect_sales.sales_person_id')
                ->leftJoin('status_progress as status', 'status.id', '=', 'prospect_sales.status_id')
                ->where('prospect_sales.id', $id)
                ->where('prospect_sales.user_id', '=', Auth::user()->id)
                ->first();

            $historysales = ProspectSalesHistory::leftJoin('prospect_sales', 'prospect_sales.id', '=', 'prospect_sales_history.prospect_id')
                ->leftJoin('users', 'users.id', '=', 'prospect_sales_history.user_id')
                ->leftJoin('status_progress', 'status_progress.id', '=', 'prospect_sales_history.status_id')
                ->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'prospect_sales.company_name', 'prospect_sales.company_address', 'prospect_sales.company_phone', 'prospect_sales.progress_notes', 'prospect_sales_history.assignment_date', 'prospect_sales_history.status_id', 'prospect_sales_history.sales_person_id', 'prospect_sales_history.notes',
                    'users.name as name_sales', 'status_progress.name_progress', 'prospect_sales_history.prospect_id', 'prospect_sales_history.status', 'prospect_sales_history.created_at'])
                ->where('prospect_sales_history.prospect_id', '=', $id)
                ->where('prospect_sales_history.status_id', '<>', 1)
                ->where('prospect_sales_history.user_id', '=', Auth::user()->id)
                ->orderby('prospect_sales_history.created_at', 'DESC')
                ->get();
        }
        
        $brandData = ProspectToBrand::leftJoin('brand', 'brand.id', '=', 'prospect_to_brand.brand_id')
        ->select('prospect_to_brand.brand_id', 'brand.brand')
        ->where('prospect_sales_id',$id)
        ->get();
        
        $pdf = \PDF::loadView('admin.report.prospect-sales-history-pdf', compact('brandData', 'prospectSales', 'historysales'));
        return $pdf->stream();
    }
    
    public function showQuote()
    {
        return view('admin.report.quote-list');
    }
    public function anyDataQuote()
    {
        DB::statement(DB::raw('set @rownum=0'));

        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $quotelist = QuoteLists::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'quote_lists.id', 'quote_lists.prospect_sales_id', 'quote_lists.sales_person_id', 'cp.company_name', 'u.name as name_sales', 'quote_lists.quote_list_code', 'quote_lists.requote_list_code', 'ql.quote_list_code as quote_code', 'quote_lists.file', 'quote_lists.date_out', 'quote_lists.fix_data'])
                ->leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                ->where('quote_lists.is_active', '=', '1')
                ->orderby('quote_lists.quote_list_code', 'ASC')
                ->get();
        }else{
            $quotelist = QuoteLists::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'quote_lists.id', 'quote_lists.prospect_sales_id', 'quote_lists.sales_person_id', 'cp.company_name', 'u.name as name_sales', 'quote_lists.quote_list_code', 'quote_lists.requote_list_code', 'ql.quote_list_code as quote_code', 'quote_lists.file', 'quote_lists.date_out', 'quote_lists.fix_data'])
                ->leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
                ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
                ->where('quote_lists.is_active', '=', '1')
                ->where('quote_lists.sales_person_id', '=', Auth::user()->id)
                ->orderby('quote_lists.quote_list_code', 'ASC')
                ->get();
        }

        return Datatables::of($quotelist)
            ->addColumn('action', function ($quotelist) {
                return '<p><a class="btn bg-blue-grey btn-xs waves-effect" href="quote-list-detail/'.$quotelist->id.'"><i class="fa fa-eye"></i> View</a>';
            })
            ->addColumn('quote_code', function ($quotelist) {
                if($quotelist->quote_code != ''){
                    return $quotelist->quote_code;
                }else{
                    return '-';
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    public function detailQuote($id)
    {
        $quotelist = QuoteLists::findOrFail($id);
        $QuoteLists = new QuoteLists();
        $quotecode = $QuoteLists->getForQuote($id);
        $prospectSales = new ProspectSales();
        $prospect_sales = $prospectSales->getForSelect();

        $ProspectSalesHistory = new ProspectSalesHistory();
        $salesHistory = $ProspectSalesHistory->getSalesProspect($quotelist->prospect_sales_id); 

        $QuoteListsDetail = new QuoteListsDetail();
        $list_detail = $QuoteListsDetail->getList($id, $quotelist->prospect_sales_id);
        $selectlist = $QuoteListsDetail->getForSelect($quotelist->prospect_sales_id);
        return view('admin.report.quote-list-detail', compact('quotelist', 'quotecode', 'prospect_sales','list_detail', 'selectlist', 'salesHistory'));
    }

    public function QuoteDetailPdf($id)
    {
        $quotelist = QuoteLists::findOrFail($id);
        $QuoteLists = new QuoteLists();
        $quotecode = $QuoteLists->getForQuote($id);
        $prospectSales = new ProspectSales();
        $prospect_sales = $prospectSales->getForSelect();

        $ProspectSalesHistory = new ProspectSalesHistory();
        $salesHistory = $ProspectSalesHistory->getSalesProspect($quotelist->prospect_sales_id); 

        $QuoteListsDetail = new QuoteListsDetail();
        $list_detail = $QuoteListsDetail->getList($id, $quotelist->prospect_sales_id);
        $selectlist = $QuoteListsDetail->getForSelect($quotelist->prospect_sales_id);
        
        $pdf = \PDF::loadView('admin.report.quote-list-detail-pdf', compact('quotelist', 'quotecode', 'prospect_sales','list_detail', 'selectlist', 'salesHistory'));
        return $pdf->stream();
    }
    
    public function showPurchaseOrder()
    {
        return view('admin.report.purchase-order-list');
    }
    public function anyDataPurchaseOrder()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $quotelist = QuoteLists::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'),'quote_lists.id', 'quote_lists.prospect_sales_id', 'quote_lists.sales_person_id', 'cp.company_name', 'u.name as name_sales', 'quote_lists.quote_list_code', 'quote_lists.requote_list_code', 'ql.quote_list_code as quote_code', 'quote_lists.file', 'quote_lists.date_out', 'quote_lists.fix_data'])
        ->leftJoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'quote_lists.prospect_sales_id')
        ->leftJoin('users as u', 'u.id', '=', 'quote_lists.sales_person_id')
            ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
        ->orderby('quote_lists.quote_list_code', 'ASC')
        ->get();

        return Datatables::of($quotelist)
            ->addColumn('action', function ($quotelist) {
                return '<a class="btn bg-blue-grey btn-xs waves-effect" href="purchase-order-list-detail/'.$quotelist->id.'"><i class="fa fa-eye"></i> View</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function detailPurchaseOrder($id)
    {
        $POLists = PurchaseOrderLists::findOrFail($id);
        
        $PurchaseOrderLists = new PurchaseOrderLists();
        $POCode = $PurchaseOrderLists->getForPO($id);
        $QuoteCode = $PurchaseOrderLists->getForQuote();

        $prospectSales = new ProspectSales();
        $prospect_sales = $prospectSales->getForSelect();
        if($POLists->quote_prospect_sales_id == '0'){
            $Prospect = '-';
        }else{
            $ProspectId = $prospectSales->getForSales($POLists->quote_prospect_sales_id);
            $Prospect = $ProspectId->company_name;
        }

        $sales = new Sales();
        if($POLists->quote_sales_person_id == '0'){
            $salesPerson = '-';
        }else{
            $salesPersonId = $sales->getSales($POLists->quote_sales_person_id);
            $salesPerson = $salesPersonId->name_sales;
        }


        $PurchaseOrderListsDetail = new PurchaseOrderListsDetail();
        $list_detail = $PurchaseOrderListsDetail->getList($id, $POLists->quote_prospect_sales_id);
        $selectlist = $PurchaseOrderListsDetail->getForSelect($POLists->quote_prospect_sales_id);
        return view('admin.report.purchase-order-list-detail', compact('POLists', 'POCode', 'QuoteCode', 'prospect_sales','list_detail', 'selectlist', 'Prospect', 'salesPerson'));
    }

    public function PurchaseOrderDetailPdf($id)
    {
        $POLists = PurchaseOrderLists::findOrFail($id);
        
        $PurchaseOrderLists = new PurchaseOrderLists();
        $POCode = $PurchaseOrderLists->getForPO($id);
        $QuoteCode = $PurchaseOrderLists->getForQuote();

        $prospectSales = new ProspectSales();
        $prospect_sales = $prospectSales->getForSelect();
        if($POLists->quote_prospect_sales_id == '0'){
            $Prospect = '-';
        }else{
            $ProspectId = $prospectSales->getForSales($POLists->quote_prospect_sales_id);
            $Prospect = $ProspectId->company_name;
        }

        $sales = new Sales();
        if($POLists->quote_sales_person_id == '0'){
            $salesPerson = '-';
        }else{
            $salesPersonId = $sales->getSales($POLists->quote_sales_person_id);
            $salesPerson = $salesPersonId->name_sales;
        }


        $PurchaseOrderListsDetail = new PurchaseOrderListsDetail();
        $list_detail = $PurchaseOrderListsDetail->getList($id, $POLists->quote_prospect_sales_id);
        $selectlist = $PurchaseOrderListsDetail->getForSelect($POLists->quote_prospect_sales_id);
        $pdf = \PDF::loadView('admin.report.purchase-order-list-detail-pdf', compact('POLists', 'POCode', 'QuoteCode', 'prospect_sales','list_detail', 'selectlist', 'Prospect', 'salesPerson'));
        return $pdf->stream();
    }
    public function showInvoice()
    {
        return view('admin.report.invoice-list');
    }
    public function anyDataInvoice()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $invoicelist = InvoiceLists::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'),'invoice_lists.id', 'invoice_lists.invoice_list_code', 'po.purchase_order_list_code', 'invoice_lists.file', 'invoice_lists.prospect_sales_id', 'invoice_lists.sales_person_id', 'cp.company_name', 'u.name as name_sales', 'invoice_lists.date_out'])
        ->leftJoin('purchase_order_lists as po', 'po.id', '=', 'invoice_lists.purchase_order_list_code_id')
        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'invoice_lists.prospect_sales_id')
        ->leftJoin('users as u', 'u.id', '=', 'invoice_lists.sales_person_id')
            ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
        ->orderby('invoice_lists.id', 'ASC')
        ->get();
        return Datatables::of($invoicelist)
            ->addColumn('action', function ($invoicelist) {
               
            return '<p><a href="invoice-list-detail/'.$invoicelist->id.'" class="btn bg-blue-grey btn-xs waves-effect"><i class="fa fa-eye"></i> View </a></p>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function detailInvoice($id)
    {
        $InvoiceLists = InvoiceLists::findOrFail($id);
        
        $Invoice = new InvoiceLists();
        $InvoiceCode = $Invoice->getForInvoice($id);
        $POCode = $Invoice->getForPO();

        $invoicePayment = $Invoice->getList($InvoiceLists->purchase_order_list_code_id);
        $invoiceByPO = $Invoice->getInvoicePO($InvoiceLists->purchase_order_list_code_id);

        $prospectSales = new ProspectSales();
        $prospect_sales = $prospectSales->getForSelect();

        if($InvoiceLists->prospect_sales_id == '0'){
            $Prospect = '';
        }else{
            $ProspectId = $prospectSales->getForSales($InvoiceLists->prospect_sales_id);
            $Prospect = $ProspectId->company_name;
        }

        $sales = new Sales();
        if($InvoiceLists->sales_person_id == '0'){
            $salesPerson = '';
        }else{
            $salesPersonId = $sales->getSales($InvoiceLists->sales_person_id);
            $salesPerson = $salesPersonId->name_sales;
        }
        $InvoiceListsDetail = new InvoiceListsDetail();
        $list_detail = $InvoiceListsDetail->getList($id, $InvoiceLists->prospect_sales_id);
        $selectlist = $InvoiceListsDetail->getForSelect($InvoiceLists->prospect_sales_id);


        $InvoiceListsPayment = new InvoiceListsPayment();
        $InvoicePayment = $InvoiceListsPayment->getListPayment($InvoiceLists->id);
        $RestBill = $InvoiceLists->amount_payment - $InvoicePayment->sum('amount');

        $InvoiceListsPayment = new InvoiceListsPayment();
        $data_payment = $InvoiceListsPayment->getListPayment($id);

        $InvoiceListsPayment = new InvoiceListsPayment();
        $data_detail = $InvoiceListsPayment->getListPayment($id);
        return view('admin.report.invoice-list-detail', compact('InvoiceLists', 'InvoiceCode', 'POCode', 'invoicePayment', 'invoiceByPO', 'prospect_sales','list_detail', 'selectlist', 'Prospect', 'salesPerson', 'InvoicePayment','RestBill', 'data_payment', 'data_detail'));
    }
    
    public function invoiceDetailPdf($id)
    {
        $InvoiceLists = InvoiceLists::findOrFail($id);
        
        $Invoice = new InvoiceLists();
        $InvoiceCode = $Invoice->getForInvoice($id);
        $POCode = $Invoice->getForPO();

        $invoicePayment = $Invoice->getList($InvoiceLists->purchase_order_list_code_id);
        $invoiceByPO = $Invoice->getInvoicePO($InvoiceLists->purchase_order_list_code_id);

        $prospectSales = new ProspectSales();
        $prospect_sales = $prospectSales->getForSelect();

        if($InvoiceLists->prospect_sales_id == '0'){
            $Prospect = '';
        }else{
            $ProspectId = $prospectSales->getForSales($InvoiceLists->prospect_sales_id);
            $Prospect = $ProspectId->company_name;
        }

        $sales = new Sales();
        if($InvoiceLists->sales_person_id == '0'){
            $salesPerson = '';
        }else{
            $salesPersonId = $sales->getSales($InvoiceLists->sales_person_id);
            $salesPerson = $salesPersonId->name_sales;
        }
        $InvoiceListsDetail = new InvoiceListsDetail();
        $list_detail = $InvoiceListsDetail->getList($id, $InvoiceLists->prospect_sales_id);
        $selectlist = $InvoiceListsDetail->getForSelect($InvoiceLists->prospect_sales_id);


        $InvoiceListsPayment = new InvoiceListsPayment();
        $InvoicePayment = $InvoiceListsPayment->getListPayment($InvoiceLists->id);
        $RestBill = $InvoiceLists->amount_payment - $InvoicePayment->sum('amount');

        $InvoiceListsPayment = new InvoiceListsPayment();
        $data_payment = $InvoiceListsPayment->getListPayment($id);

        $InvoiceListsPayment = new InvoiceListsPayment();
        $data_detail = $InvoiceListsPayment->getListPayment($id);
        $pdf = \PDF::loadView('admin.report.invoice-list-detail-pdf', compact('InvoiceLists', 'InvoiceCode', 'POCode', 'invoicePayment', 'invoiceByPO', 'prospect_sales','list_detail', 'selectlist', 'Prospect', 'salesPerson', 'InvoicePayment','RestBill', 'data_payment', 'data_detail'));
        return $pdf->stream();
    }

    public function showDeliveryOrder()
    {
        return view('admin.report.delivery-order-list');
    }

    public function anyDataDeliveryOrder()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $deliveryorderlist = DeliveryOrderLists::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'),'delivery_order_lists.id', 'delivery_order_lists.delivery_order_list_code', 'delivery_order_lists.invoice_list_code_id', 'delivery_order_lists.purchase_order_list_code_id', 'po.purchase_order_list_code', 'delivery_order_lists.file', 'cp.company_name', 'u.name as name_sales', 'delivery_order_lists.date_out'])
        ->leftJoin('purchase_order_lists as po', 'po.id', '=', 'delivery_order_lists.purchase_order_list_code_id')
        ->leftJoin('prospect_sales as ps', 'ps.id', '=', 'delivery_order_lists.prospect_sales_id')
        ->leftJoin('users as u', 'u.id', '=', 'delivery_order_lists.sales_person_id')
            ->leftJoin('customer_profile as cp', 'cp.id', '=', 'ps.customer_profile_id')
        ->orderby('delivery_order_list_code', 'ASC')
        ->get();
        return Datatables::of($deliveryorderlist)

            ->addColumn('action', function ($deliveryorderlist) {
                 
                    return '<p><a href="delivery-order-list-detail/'.$deliveryorderlist->id.'" class="btn bg-blue-grey btn-xs waves-effect"><i class="fa fa-eye"></i> View </a></p>';
                    
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function detailDeliveryOrder($id)
    {
        $DOLists = DeliveryOrderLists::findOrFail($id);
        
        $DeliveryOrderLists = new DeliveryOrderLists();
        $POCode = $DeliveryOrderLists->getForPO();


        $DeliveryOrderListsTransaction = new DeliveryOrderListsTransaction();
        $productDO = $DeliveryOrderListsTransaction->getProductDetailDO($DOLists->id);


        $prospectSales = new ProspectSales();
        $prospect_sales = $prospectSales->getForSelect();
        if($DOLists->prospect_sales_id == '0'){
            $Prospect = '';
        }else{
            $ProspectId = $prospectSales->getForSales($DOLists->prospect_sales_id);
            $Prospect = $ProspectId->company_name;
        }

        $sales = new Sales();
        if($DOLists->sales_person_id == '0'){
            $salesPerson = '';
        }else{
            $salesPersonId = $sales->getSales($DOLists->sales_person_id);
            $salesPerson = $salesPersonId->name_sales;
        }

        $InvoiceLists = new InvoiceLists();
        $invoiceListPO = $InvoiceLists->invoiceByPO($DOLists->purchase_order_list_code_id);


        $DeliveryOrderListsDetail = new DeliveryOrderListsDetail();
        $list_detail = $DeliveryOrderListsDetail->getList($id, $DOLists->prospect_sales_id);
        $selectlist = $DeliveryOrderListsDetail->getForSelect($DOLists->prospect_sales_id);


        $DeliveryOrderListsTransaction = new DeliveryOrderListsTransaction();
        $list_transaction = $DeliveryOrderListsTransaction->getListDO($DOLists->id);
        return view('admin.report.delivery-order-list-detail', compact('DOLists', 'productDO', 'POCode', 'prospect_sales','list_detail', 'invoiceListPO', 'selectlist', 'Prospect', 'salesPerson','list_transaction'));
    }

     public function deliveryOrderDetailPdf($id)
    {
        $DOLists = DeliveryOrderLists::findOrFail($id);
        
        $DeliveryOrderLists = new DeliveryOrderLists();
        $POCode = $DeliveryOrderLists->getForPO();


        $DeliveryOrderListsTransaction = new DeliveryOrderListsTransaction();
        $productDO = $DeliveryOrderListsTransaction->getProductDetailDO($DOLists->id);


        $prospectSales = new ProspectSales();
        $prospect_sales = $prospectSales->getForSelect();
        if($DOLists->prospect_sales_id == '0'){
            $Prospect = '';
        }else{
            $ProspectId = $prospectSales->getForSales($DOLists->prospect_sales_id);
            $Prospect = $ProspectId->company_name;
        }

        $sales = new Sales();
        if($DOLists->sales_person_id == '0'){
            $salesPerson = '';
        }else{
            $salesPersonId = $sales->getSales($DOLists->sales_person_id);
            $salesPerson = $salesPersonId->name_sales;
        }

        $InvoiceLists = new InvoiceLists();
        $invoiceListPO = $InvoiceLists->invoiceByPO($DOLists->purchase_order_list_code_id);


        $DeliveryOrderListsDetail = new DeliveryOrderListsDetail();
        $list_detail = $DeliveryOrderListsDetail->getList($id, $DOLists->prospect_sales_id);
        $selectlist = $DeliveryOrderListsDetail->getForSelect($DOLists->prospect_sales_id);


        $DeliveryOrderListsTransaction = new DeliveryOrderListsTransaction();
        $list_transaction = $DeliveryOrderListsTransaction->getListDO($DOLists->id);
        $pdf = \PDF::loadView('admin.report.delivery-order-list-detail-pdf', compact('DOLists', 'productDO', 'POCode', 'prospect_sales','list_detail', 'invoiceListPO', 'selectlist', 'Prospect', 'salesPerson','list_transaction'));
        return $pdf->stream();
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
