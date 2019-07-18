<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\InvoiceLists;
use App\ReInvoice;
use App\Invoice;
use App\QuoteLists;
use App\InvoiceListsDetail;
use App\PurchaseOrderLists;
use App\PurchaseOrderListsDetail;
use App\InvoiceListsPayment;
use App\ProspectSales;
use App\Sales;
use Illuminate\Http\Request;
use App\User;
use Session;
use Auth;
use DB;
use Alert;
use Crypt;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

class InvoiceListController extends Controller
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
        $allInvoiceCount = InvoiceLists::count();
        $newInvoiceCount = InvoiceLists::whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->count();
        return view('admin.invoice.index', compact('allInvoiceCount', 'newInvoiceCount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                $purchaseorderlists = PurchaseOrderLists::pluck('purchase_order_list_code', 'id')
                    ->prepend('Choose Purchase Order', '');
            }else {
                $purchaseorderlists = PurchaseOrderLists::where('quote_sales_person_id', Auth::user()->id)
                    ->pluck('purchase_order_list_code', 'id')
                    ->prepend('Choose Purchase Order', '');
            }
            return view('admin.invoice.create', compact('invoice', 'purchaseorderlists'));
        }else{
            Alert::info('No Access !', 'Attention !');
            return redirect('admin/invoice-list');
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
       $this->validate($request, [
            'purchase_order_list_code_id' => 'required',
            'invoice_list_code' => 'required|unique:invoice_lists,invoice_list_code',
            'files' => 'mimes:jpeg,png,jpg,docx,doc,dotx,xlsx,xls,pdf|max:1000000',
        ]);

        $PurchaseOrderLists = new PurchaseOrderLists();
        $sales_id = $PurchaseOrderLists->getListPO($request->purchase_order_list_code_id);
        $datas = $request['files'];
        if($datas !="" ){
            $path = 'files/invoice';
            $name = date('dmY').'_'.rand(10000,99999).'.'.$datas->getClientOriginalExtension();
            $datas->move($path,$name);
            $request['file'] = $name;
        }

        $requestData = $request->all();
        $requestData['sales_person_id'] = $sales_id->quote_sales_person_id;
        $requestData['created_at'] = date('Y-m-d H:i:s');
        InvoiceLists::create($requestData);

        $requestPO['fix_data'] = 1;
        $po_data = PurchaseOrderLists::findOrFail($request->purchase_order_list_code_id);
        // var_dump($po_data);die();
        $po_data->update($requestPO);


        Alert::success('Your data already created !', 'Success !');

        return redirect('admin/invoice-list');
    }

    public function createNewInvoice(Request $request)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $requestData = $request->all();
            $requestData['is_active'] = 0;
            $id = InvoiceLists::create($requestData);
            return redirect('admin/invoice-list/'.$id->id.'/create');
        }else{
            Alert::info('No Access !', 'Attention !');
            return redirect('admin/invoice-list');
        }
    }

    public function create_payment(Request $request)
    {
        $invoice_id = $request->invoice_list_id;
        $invoice_code = $request->invoice_code;


        $requestInvoice['invoice_code'] = $invoice_code;
        $requestInvoice['invoice_list_code'] = $request->invoice_list_code;
        $requestInvoice['date_out'] = $request->date_out;
        $requestInvoice['note'] = $request->note;
        $requestInvoice['paid_off'] = 0;

        $invoice = InvoiceLists::findOrFail($invoice_id);
        $invoice->update($requestInvoice);

        $old_data = InvoiceListsPayment::where('invoice_list_id', $invoice_id)->get();

        if(count($old_data) > 0)
        {
            InvoiceListsPayment::where('invoice_list_id', $invoice_id)->delete();

        }

        for ($i = 0; $i < $invoice_code; $i++) {

        $requestData['invoice_list_id'] = $invoice_id;
        $requestData['prospect_sales_id'] = $request->prospect_sales_id;
        InvoiceListsPayment::create($requestData);
            
        }
        Alert::success('Payment data already Created !', 'Success !');
        return redirect('admin/invoice-list/'.$request->invoice_list_id.'/create');
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

        if($InvoiceLists->invoice_sales_person_id == ''){
            $invoiceSales = '-';
        }else{
            $InvoiceSalesPerson = $InvoiceLists->invoiceSales($InvoiceLists->invoice_sales_person_id);
            $invoiceSales = $InvoiceSalesPerson->name_sales;

        }

        $InvoiceListsDetail = new InvoiceListsDetail();
        $list_detail = $InvoiceListsDetail->getList($id);
        $selectlist = $InvoiceListsDetail->getForSelect($InvoiceLists->prospect_sales_id);


        $InvoiceListsPayment = new InvoiceListsPayment();
        $InvoicePayment = $InvoiceListsPayment->getListPayment($InvoiceLists->id);
        $RestBill = $InvoiceLists->total_price - $InvoicePayment->sum('amount');
        return view('admin.invoice.show', compact('InvoiceLists', 'InvoiceCode', 'POCode', 'invoicePayment', 'invoiceByPO', 'prospect_sales','list_detail', 'selectlist', 'Prospect', 'salesPerson', 'invoiceSales', 'InvoicePayment','RestBill'));
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
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $invoicelist = InvoiceLists::findOrFail($id);
            if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
                $purchaseorderlists = PurchaseOrderLists::pluck('purchase_order_list_code', 'id')
                    ->prepend('Choose Purchase Order', '');
            }else{
                $purchaseorderlists = PurchaseOrderLists::where('quote_sales_person_id', Auth::user()->id)
                    ->pluck('purchase_order_list_code', 'id')
                    ->prepend('Choose Purchase Order', '');
            }
            return view('admin.invoice.edit', compact('invoicelist', 'purchaseorderlists'));
        }else{
            Alert::info('No Access !', 'Attention !');
            return redirect('admin/invoice-list');
        }
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
            'purchase_order_list_code_id' => 'required',
            'invoice_list_code' => 'required',
            'files' => 'mimes:jpeg,png,jpg,docx,doc,dotx,xlsx,xls,pdf|max:1000000',
        ]);

        $datas = $request['files'];
        if($datas !="" ){
            $path = 'files/invoice';
            $name = date('dmY').'_'.rand(10000,99999).'.'.$datas->getClientOriginalExtension();
            $datas->move($path,$name);

            $request['file'] = $name;
        }

        $requestData = $request->all();

        $InvoiceLists = InvoiceLists::findOrFail($id);
        if ($InvoiceLists->file != '') {
            $image_path = app_path("../../files/invoice/" . $InvoiceLists->file . "");
            unlink($image_path);
        }
        $InvoiceLists->update($requestData);

        Alert::success('Your data already updated !', 'Success !');

        return redirect('admin/invoice-list');
    }

    public function CreateInvoice($id)
    {
        $InvoiceLists = InvoiceLists::findOrFail($id);
        
        $Invoice = new InvoiceLists();
        $InvoiceCode = $Invoice->getForInvoice($id);
        $POCode = $Invoice->getForPO();

        $POCodeArray = $Invoice->getForPOArray($InvoiceLists->purchase_order_list_code_id);

        $invoicePay = $Invoice->getList($InvoiceLists->purchase_order_list_code_id);
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

        if($InvoiceLists->invoice_sales_person_id == ''){
            $invoiceSales = '-';
        }else{
            $InvoiceSalesPerson = $InvoiceLists->invoiceSales($InvoiceLists->invoice_sales_person_id);
            $invoiceSales = $InvoiceSalesPerson->name_sales;

        }

        $InvoiceListsDetail = new InvoiceListsDetail();
        $list_detail = $InvoiceListsDetail->getList($id, $InvoiceLists->prospect_sales_id);
        $selectlist = $InvoiceListsDetail->getForSelect($InvoiceLists->prospect_sales_id);


        $InvoiceListsPayment = new InvoiceListsPayment();
        $invoicePayment = $InvoiceListsPayment->getListPayment($InvoiceLists->id);
        $RestBill = $InvoiceLists->total_price - $invoicePayment->sum('amount');
        return view('admin.invoice.create-invoice', compact('InvoiceLists', 'InvoiceCode', 'POCode', 'POCodeArray', 'invoicePayment', 'invoiceByPO', 'prospect_sales','list_detail', 'selectlist', 'Prospect', 'salesPerson', 'invoiceSales','RestBill'));
    }

    public function transaction($id)
    {
        $InvoiceLists = InvoiceLists::findOrFail($id);
        
        $Invoice = new InvoiceLists();
        $InvoiceCode = $Invoice->getForInvoice($id);
        $POCode = $Invoice->getForPO();

        $POCodeArray = $Invoice->getForPOArray($InvoiceLists->purchase_order_list_code_id);

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

        if($InvoiceLists->invoice_sales_person_id == ''){
            $invoiceSales = '-';
        }else{
            $InvoiceSalesPerson = $InvoiceLists->invoiceSales($InvoiceLists->invoice_sales_person_id);
            $invoiceSales = $InvoiceSalesPerson->name_sales;

        }

        $InvoiceListsDetail = new InvoiceListsDetail();
        $list_detail = $InvoiceListsDetail->getList($id);
        $selectlist = $InvoiceListsDetail->getForSelect($InvoiceLists->prospect_sales_id);


        $InvoiceListsPayment = new InvoiceListsPayment();
        $InvoicePayment = $InvoiceListsPayment->getListPayment($InvoiceLists->id);
        $RestBill = $InvoiceLists->total_price - $InvoicePayment->sum('amount');
        return view('admin.invoice.transaction', compact('InvoiceLists', 'InvoiceCode', 'POCode', 'POCodeArray', 'invoicePayment', 'invoiceByPO', 'prospect_sales','list_detail', 'selectlist', 'Prospect', 'salesPerson', 'invoiceSales', 'InvoicePayment','RestBill'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */

    public function do_create($id, Request $request)
    {
        
        $this->validate($request, [
            'invoice_list_code' => 'required',
            'purchase_order_list_code_id' => 'required',
            'files' => 'mimes:jpeg,png,jpg,pdf|max:1000000',
        ]);

        $datas = $request['files'];
        if($datas !="" ){
            $path = 'files/invoice';
            $name = date('dmY').'_'.rand(10000,99999).'.'.$datas->getClientOriginalExtension();
            $datas->move($path,$name);

            $request['file'] = $name;
        }
        $requestData = $request->all();
        $requestData['is_active'] = 1;
        $requestData['paid_off'] = 1;
        $invoice = InvoiceLists::findOrFail($id);
        $invoice->update($requestData);

        //change the status of the new Purchase Order
            $POLists = PurchaseOrderLists::findOrFail($request->purchase_order_list_code_id);
            $POData['fix_data'] = 1;

            $POLists->update($POData);
        //end
        Alert::success('Your data Invoice already Created !', 'Success !');
    
        return redirect('admin/invoice-list');
    }

    public function do_transaction($id, Request $request)
    {
        
        $this->validate($request, [
            'invoice_list_code' => 'required',
            'purchase_order_list_code_id' => 'required',
            'files' => 'mimes:jpeg,png,jpg,pdf|max:1000000',
        ]);

        $datas = $request['files'];
        if($datas !="" ){
            $path = 'files/invoice';
            $name = rand(10000,99999).'.'.$datas->getClientOriginalExtension();
            $datas->move($path,$name);

            $request['file'] = $name;
        }
        $requestData = $request->all();
        $requestData['is_active'] = 1;
        $invoice = InvoiceLists::findOrFail($id);
        $invoice->update($requestData);
        
        //change the status of the new Purchase Order
            $POLists = PurchaseOrderLists::findOrFail($request->purchase_order_list_code_id);
            $POData['fix_data'] = 1;

            $POLists->update($POData);
        //end
        Alert::success('Your data Invoice already updated !', 'Success !');
    
        return redirect('admin/invoice-list/'.$id.'/transaction');
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
        if (Auth::user()->role == User::ROLE_SUPERADMIN) {
            $invoice = InvoiceLists::findOrFail($id);

            $POLists = PurchaseOrderLists::findOrFail($invoice->purchase_order_list_code_id);
            $POData['fix_data'] = 0;
            $POLists->update($POData);

            InvoiceLists::destroy($id);
            InvoiceListsDetail::where('invoice_list_id', $id)->delete();

            $old_data_payment = InvoiceListsPayment::where('invoice_list_id', $id)->get();
            if (count($old_data_payment) > 0) {
                foreach ($old_data_payment as $payment) {
                    InvoiceListsPayment::where('invoice_list_id', $id)->delete();
                    $image_payment_path = app_path("../../files/invoice-payment/" . $payment->file_payment . "");
                    unlink($image_payment_path);
                }
            }
            if ($invoice->file != '') {
                $image_path = app_path("../../files/invoice/" . $invoice->file . "");
                unlink($image_path);
            }


            Alert::success('Your data already deleted !', 'Success !');
        }
        return redirect('admin/invoice-list');
    }

    public function generatePdf($invoice_list_code)
    {
        $template = InvoiceLists::where('invoice_code_id', $invoice_list_code)->first();
        $invoice = Invoice::where('invoice_code', $invoice_list_code)->first();

        $pdf = \PDF::loadView('admin.invoice.template-pdf', compact('template', 'invoice'));
        return $pdf->stream();
    }

    public function anyData()
    {
        DB::statement(DB::raw('set @rownum=0'));

        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $invoicelist = InvoiceLists::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'invoice_lists.id', 'ql.id as quote_id', 'invoice_lists.invoice_list_code',
                'po.purchase_order_list_code', 'ql.quote_list_code', 'invoice_lists.file as file_invoice', 'po.file as file_po', 'invoice_lists.fix_data', 'invoice_lists.paid_off', 'invoice_lists.created_at'])
                ->leftJoin('purchase_order_lists as po', 'po.id', '=', 'invoice_lists.purchase_order_list_code_id')
                ->leftJoin('quote_lists as ql', 'ql.id', '=', 'po.quote_list_code_id')
                ->groupBy('invoice_lists.invoice_list_code')
                ->orderby('invoice_lists.id', 'DESC')
                ->get();
        }else{
            $invoicelist = InvoiceLists::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'invoice_lists.id', 'ql.id as quote_id', 'invoice_lists.invoice_list_code',
                'po.purchase_order_list_code', 'ql.quote_list_code', 'invoice_lists.file as file_invoice', 'po.file as file_po', 'invoice_lists.fix_data', 'invoice_lists.paid_off', 'invoice_lists.created_at'])
                ->leftJoin('purchase_order_lists as po', 'po.id', '=', 'invoice_lists.purchase_order_list_code_id')
                ->leftJoin('quote_lists as ql', 'ql.id', '=', 'po.quote_list_code_id')
                ->where('invoice_lists.sales_person_id', Auth::user()->id)
                ->groupBy('invoice_lists.invoice_list_code')
                ->orderby('invoice_lists.id', 'DESC')
                ->get();
        }
        return Datatables::of($invoicelist)
            ->addColumn('action', function ($invoicelist) {
                if(Auth::user()->role == User::ROLE_SUPERADMIN) {
                    return '<p><a href="invoice-list/' . $invoicelist->id . '/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-edit"></i> Edit </a></p>
                            <a onclick="deleteData(' . $invoicelist->id . ')" class="btn bg-red btn-xs waves-effect"><i class="fa fa-close"></i> Cancel </a>';
                }elseif(Auth::user()->role == User::ROLE_ADMIN) {
                    return '<p><a href="invoice-list/' . $invoicelist->id . '/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-edit"></i> Edit </a></p>';
                }else{
                    return '-';
                }
            })
            ->addColumn('invoice_list_code', function ($invoicelist) {
                if($invoicelist->file_invoice != ''){
                    if(substr($invoicelist->file_invoice, -3) == 'pdf'){
                        return $invoicelist->invoice_list_code.'<p><a href="'.url('/').'/files/invoice/'.$invoicelist->file_invoice.'" download="Invoice-'.date('Ymd').'" target="_blank"><img src="'.url('/').'/images/ico_pdf.svg" width="40"/></a></p>';
                    }else{
                        return $invoicelist->invoice_list_code.'<p><a href="'.url('/').'/files/invoice/'.$invoicelist->file_invoice.'" download="Invoice-'.date('Ymd').'" target="_blank"><img src="'.url('/').'/images/ico_document.svg" width="50"/></a></p>';
                    }
                }else{
                        return $invoicelist->invoice_list_code;
                }
            })
            ->addColumn('purchase_order_list_code', function ($invoicelist) {
                if($invoicelist->file_po != ''){
                    if(substr($invoicelist->file_po, -3) == 'pdf'){
                        return $invoicelist->purchase_order_list_code.'<p><a href="'.url('/').'/files/purchase-order/'.$invoicelist->file_po.'" download="PO-'.date('Ymd').'" target="_blank"><img src="'.url('/').'/images/ico_pdf.svg" width="40"/></a></p>';
                    }else{
                        return $invoicelist->purchase_order_list_code.'<p><a href="'.url('/').'/files/purchase-order/'.$invoicelist->file_po.'" download="PO-'.date('Ymd').'" target="_blank"><img src="'.url('/').'/images/ico_document.svg" width="50"/></a></p>';
                    }
                }else{
                    return $invoicelist->purchase_order_list_code;
                }
            })
            ->addColumn('quote_list_code', function ($invoicelist) {
                return $invoicelist->quote_list_code.'<p><a href="quote-list/'.Crypt::encrypt($invoicelist->quote_id).'/generate-pdf" target="_blank"><img src="'.url('/').'/images/ico_pdf.svg" width="40"/></a></p>';
            })
            ->addColumn('fix_data', function ($invoicelist) {
                if($invoicelist->fix_data == 0){
                    return '<span class="label label-warning">-</span>';
                }else{
                    return '<span class="label label-success">DO already created</span>';
                }
            })
            ->addColumn('created_at', function ($invoicelist) {
                return Carbon::parse($invoicelist->created_at)->format('Y-m-d');
            })
            ->rawColumns(['action', 'invoice_list_code', 'purchase_order_list_code', 'quote_list_code', 'fix_data', 'created_at'])
            ->make(true);
    }

    public function getPOT(Request $request)
    {
        $invoice_id = $request->invoice_list_id;
        $po_id = $request->purchase_order_list_code_id;
 
        $InvoiceLists = InvoiceLists::findOrFail($invoice_id);
        $InvoiceList = new InvoiceLists();
        $InvoiceId = $InvoiceList->getPO($po_id);


        $po_id = $InvoiceLists->purchase_order_list_code_id;

        //after new Purchase Order is selected, change the old Purchase Order status
            $POList = PurchaseOrderLists::findOrFail($InvoiceLists->purchase_order_list_code_id);
            $PODataA['fix_data'] = 0;
            $POList->update($PODataA);
        //end

        $requestList['purchase_order_list_code_id'] = $InvoiceId->id;
        $requestList['prospect_sales_id'] = $InvoiceId->quote_prospect_sales_id;
        $requestList['invoice_sales_person_id'] = $InvoiceId->po_prospect_sales_id;
        $requestList['invoice_list_code'] = $request->invoice_list_code;
        $requestList['invoice_code'] = 0;
        $requestList['sales_person_id'] = $InvoiceId->quote_sales_person_id;
        $requestList['gross_price'] = $InvoiceId->gross_price;
        $requestList['qty'] = $InvoiceId->qty;
        $requestList['total_price'] = $InvoiceId->total_price;
        $requestList['total_diskon'] = $InvoiceId->total_diskon;
        $requestList['date_out'] = $request->date_out;
        $requestList['note'] = $request->note;
        $requestList['paid_off'] = 0;
        $requestList['is_active'] = 0;

        $data = $InvoiceLists->update($requestList);

        // //change the status of the new Purchase Order
        //     $POLists = PurchaseOrderLists::findOrFail($InvoiceId->id);
        //     $PODataB['fix_data'] = 1;
        //     $POLists->update($PODataB);
        // //end



        $invoiceDetailId = $InvoiceLists->getPODetail($InvoiceId->id);

        $old_data = InvoiceListsDetail::where('invoice_list_id', $invoice_id)->get();

        if(count($old_data) > 0)
        {
            InvoiceListsDetail::where('invoice_list_id', $invoice_id)->delete();

        }

        foreach($invoiceDetailId as $invoiceDetail){
            $requestDetail['invoice_list_id'] = $invoice_id;
            $requestDetail['prospect_sales_id'] = $invoiceDetail->prospect_sales_id;
            $requestDetail['product_id'] = $invoiceDetail->product_id;
            $requestDetail['product_name'] = $invoiceDetail->product_name;
            $requestDetail['qty'] = $invoiceDetail->qty;
            $requestDetail['price'] = $invoiceDetail->price;
            $requestDetail['gross_price'] = $invoiceDetail->gross_price;
            $requestDetail['diskon'] = $invoiceDetail->diskon;
            $requestDetail['diskon_nominal'] = $invoiceDetail->diskon_nominal;
            $requestDetail['net_price'] = $invoiceDetail->net_price;

   
            InvoiceListsDetail::create($requestDetail);


            Alert::success('Purchase Order data already created !', 'Success !');
        }

        $old_data_payment = InvoiceListsPayment::where('invoice_list_id', $invoice_id)->get();

        if(count(array($old_data_payment)) > 0)
        {
            foreach($old_data_payment as $payment){
                if($payment->file_payment != '')
                {
                    InvoiceListsPayment::where('invoice_list_id', $invoice_id)->delete();
                    $image_payment_path = app_path("../../files/invoice-payment/".$payment->file_payment."");
                    unlink($image_payment_path);
                }else
                {
                    InvoiceListsPayment::where('invoice_list_id', $invoice_id)->delete();
                }
            }
        }

    }

    public function getPOTCreate(Request $request)
    {
        $invoice_id = $request->invoice_list_id;
        $po_id = $request->purchase_order_list_code_id;
 
        $InvoiceLists = InvoiceLists::findOrFail($invoice_id);
        $InvoiceList = new InvoiceLists();
        $InvoiceId = $InvoiceList->getPO($po_id);


        $po_id = $InvoiceLists->purchase_order_list_code_id;

        $requestList['purchase_order_list_code_id'] = $InvoiceId->id;
        $requestList['prospect_sales_id'] = $InvoiceId->quote_prospect_sales_id;
        $requestList['invoice_sales_person_id'] = $InvoiceId->po_prospect_sales_id;
        $requestList['invoice_list_code'] = $request->invoice_list_code;
        $requestList['invoice_code'] = 0;
        $requestList['sales_person_id'] = $InvoiceId->quote_sales_person_id;
        $requestList['gross_price'] = $InvoiceId->gross_price;
        $requestList['qty'] = $InvoiceId->qty;
        $requestList['total_price'] = $InvoiceId->total_price;
        $requestList['total_diskon'] = $InvoiceId->total_diskon;
        $requestList['date_out'] = $request->date_out;
        $requestList['note'] = $request->note;
        $requestList['paid_off'] = 0;
        $requestList['is_active'] = 0;

        $data = $InvoiceLists->update($requestList);

        // //change the status of the new Purchase Order
        //     $POLists = PurchaseOrderLists::findOrFail($InvoiceId->id);
        //     $PODataB['fix_data'] = 1;
        //     $POLists->update($PODataB);
        // //end



        $invoiceDetailId = $InvoiceLists->getPODetail($InvoiceId->id);

        $old_data = InvoiceListsDetail::where('invoice_list_id', $invoice_id)->get();

        if(count($old_data) > 0)
        {
            InvoiceListsDetail::where('invoice_list_id', $invoice_id)->delete();

        }

        foreach($invoiceDetailId as $invoiceDetail){
            $requestDetail['invoice_list_id'] = $invoice_id;
            $requestDetail['prospect_sales_id'] = $invoiceDetail->prospect_sales_id;
            $requestDetail['product_id'] = $invoiceDetail->product_id;
            $requestDetail['product_name'] = $invoiceDetail->product_name;
            $requestDetail['qty'] = $invoiceDetail->qty;
            $requestDetail['price'] = $invoiceDetail->price;
            $requestDetail['gross_price'] = $invoiceDetail->gross_price;
            $requestDetail['diskon'] = $invoiceDetail->diskon;
            $requestDetail['diskon_nominal'] = $invoiceDetail->diskon_nominal;
            $requestDetail['net_price'] = $invoiceDetail->net_price;

   
            InvoiceListsDetail::create($requestDetail);


            Alert::success('Purchase Order data already created !', 'Success !');
        }

        $old_data_payment = InvoiceListsPayment::where('invoice_list_id', $invoice_id)->get();

        if(count(array($old_data_payment)) > 0)
        {
            foreach($old_data_payment as $payment){
                if($payment->file_payment != '')
                {
                    InvoiceListsPayment::where('invoice_list_id', $invoice_id)->delete();
                    $image_payment_path = app_path("../../files/invoice-payment/".$payment->file_payment."");
                    unlink($image_payment_path);
                }else
                {
                    InvoiceListsPayment::where('invoice_list_id', $invoice_id)->delete();
                }
            }
        }

    }

    public function choose_tax(Request $request)
    {
        $invoice_id = $request->invoice_list_id;
        $prospect_sales_id = $request->prospect_sales_id;

        $choose_tax = $request->choose_tax;
        $invoice_list_code = $request->invoice_list_code;
        $date_out = $request->date_out;
        $note = $request->note;

        $InvoiceLists = InvoiceLists::findOrFail($invoice_id);
        $tax_price = (10 / 100) * $InvoiceLists->total_price;
        $after_tax = $InvoiceLists->total_price - $tax_price;
        if($choose_tax == 1){
            $choose['choose_tax'] = $choose_tax;
            $choose['tax'] = 10;
            $choose['tax_price'] = $tax_price;
            $choose['after_tax'] = $after_tax;
            $choose['invoice_list_code'] = $invoice_list_code;
            $choose['date_out'] = $date_out;
            $choose['note'] = $note;
        }else{
            $choose['choose_tax'] = $choose_tax;
            $choose['tax'] = 0;
            $choose['tax_price'] = 0;
            $choose['after_tax'] = $InvoiceLists->total_price;
            $choose['invoice_list_code'] = $invoice_list_code;
            $choose['date_out'] = $date_out;
            $choose['note'] = $note;
        }

        $InvoiceLists->update($choose);
        return response()->json(['data' => $choose, 'success' => true, 'message' => 'success'], 200);
    }

    public function tax(Request $request)
    {
        $invoice_id = $request->invoice_list_id;
        $prospect_sales_id = $request->prospect_sales_id;
        $taxx = $request->tax;

        $InvoiceLists = InvoiceLists::findOrFail($invoice_id);
        $tax_price = ($taxx / 100) * $InvoiceLists->total_price;
        $after_tax = $InvoiceLists->total_price - $tax_price;
        $tax['tax'] = $taxx;
        $tax['tax_price'] = $tax_price;
        $tax['after_tax'] = $after_tax;
        $InvoiceLists->update($tax);

        return response()->json(['data' => $tax, 'success' => true, 'message' => 'success'], 200);
    }

    public function do_delete_file_payment($id,Request $request)
    {
        $requestData = array(
                'file_payment' => NULL,
            );

        $InvoiceListsPayment = InvoiceListsPayment::findOrFail($id);
        $image_path = app_path("../../files/invoice-payment/".$InvoiceListsPayment->file_payment."");
        unlink($image_path);

        $InvoiceListsPayment->update($requestData);

        Alert::success('Data is deleted successfully !', 'Success !');

        return redirect('admin/invoice-list/'. $request->invoice_list_id .'/create');
    }

    public function do_btn_update($id, Request $request)
    {
        $this->validate($request, [
            'amount' => 'required',
        ]);
        $invoice_list_id = $request->invoice_list_id;

        $invoice = InvoiceLists::findOrFail($invoice_list_id);
        $InvoiceListsPayment = new InvoiceListsPayment();
        $count_amount_payment = $InvoiceListsPayment->getListPaymentCount($invoice_list_id);
        $amount = str_replace(",","",$request->amount);
        $InvoicePayment = InvoiceListsPayment::findOrFail($id);
        $count_payment = $count_amount_payment->sum('amount') - $InvoicePayment->amount;
        $count_list_payment = $count_payment + $amount;

        if($count_list_payment == $invoice->total_price)
        {
            $requestInvoice['paid_off'] = 1;
            $invoice->update($requestInvoice);
        }else{
            $requestInvoice['paid_off'] = 0;
            $invoice->update($requestInvoice);
        }

        if($amount < $InvoicePayment->amount)
        {
            $date_name = str_replace('/', '-', $request->date_payment);
            $datas = $request->file_payment;
                if($datas !="" ){
                    $path = 'files/invoice-payment';
                    $name = $date_name.'-'.$request->no.'.'.$datas->getClientOriginalExtension();
                    $datas->move($path,$name);

                $requestData = array(
                        'file_payment' => $name,
                        'date_payment' => $request->date_payment,
                        'amount' => $amount,
                        'created_at'=> date('Y-m-d h:i:s')
                    );
                // var_dump($requestData);die();
                }else{
                    $requestData = array(
                        'date_payment' => $request->date_payment,
                        'amount' => $amount,
                        'created_at'=> date('Y-m-d h:i:s')
                    );
                    // var_dump($requestData);die();
                }

                $InvoiceListsPayment = InvoiceListsPayment::findOrFail($id);
                $InvoiceListsPayment->update($requestData);

                Alert::success('Your data already Updated !', 'Success !');
        }
        else
        {

            if($count_list_payment <= $invoice->total_price)
            {
                $date_name = str_replace('/', '-', $request->date_payment);
                $datas = $request->file_payment;
                if($datas !="" ){
                    $path = 'files/invoice-payment';
                    $name = $date_name.'-'.$request->no.'.'.$datas->getClientOriginalExtension();
                    $datas->move($path,$name);

                $requestData = array(
                        'file_payment' => $name,
                        'date_payment' => $request->date_payment,
                        'amount' => $amount,
                        'created_at'=> date('Y-m-d h:i:s')
                    );
                // var_dump($requestData);die();
                }else{
                    $requestData = array(
                        'date_payment' => $request->date_payment,
                        'amount' => $amount,
                        'created_at'=> date('Y-m-d h:i:s')
                    );
                    // var_dump($requestData);die();
                }

                $InvoiceListsPayment = InvoiceListsPayment::findOrFail($id);
                $InvoiceListsPayment->update($requestData);

                Alert::success('Your data already Updated !', 'Success !');

            }else{
                Alert::info("The total amount inserted exceeds the total of remaining amount to be paid", "ATTENTION !")->autoclose(3500);
            }
        }

        return redirect('admin/invoice-list/'. $request->invoice_list_id .'/create');

    }

    public function create_all_invoice(Request $request)
    {
        
        $this->validate($request, [
            'invoice_list_code' => 'required',
        ]);

        
        $requestData = $request->all();
        $requestData['is_active'] = 1;
        $invoice = InvoiceLists::findOrFail($request->id);
        $invoice->update($requestData);
        Alert::success('Your data Invoice already Created !', 'Success !');
    
        return redirect('admin/invoice-list');
    }

    public function create_all_invoice_transaction(Request $request)
    {
        
        $this->validate($request, [
            'invoice_list_code' => 'required',
        ]);

        $requestData = $request->all();
        $invoice = InvoiceLists::findOrFail($request->id);
        $invoice->update($requestData);
        Alert::success('Your data Invoice already Created !', 'Success !');
    
        return redirect('admin/invoice-list');
    }
}
