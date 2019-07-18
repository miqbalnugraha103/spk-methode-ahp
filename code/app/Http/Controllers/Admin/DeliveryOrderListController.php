<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\DeliveryOrderLists;
use App\ReDeliveryOrder;
use App\DeliveryOrder;
use App\DeliveryOrderListsDetail;
use App\DeliveryOrderListsTransaction;
use App\InvoiceLists;
use App\InvoiceListsDetail;
use App\PurchaseOrderLists;
use App\PurchaseOrderListsDetail;
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

class DeliveryOrderListController extends Controller
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
        $allDOCount = DeliveryOrderLists::count();
        $newDOCount = DeliveryOrderLists::whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->count();

        return view('admin.delivery-order.index', compact('allDOCount', 'newDOCount'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $DeliveryOrderLists = new DeliveryOrderLists();
                $POCode = $DeliveryOrderLists->getForPO();
            return view('admin.delivery-order.create', compact('POCode'));
        }else{
            Alert::info('No Access !', 'Attention !');
            return redirect('admin/delivery-order-list');
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
            'delivery_order_list_code' => 'required',
            'files' => 'mimes:jpeg,png,jpg,docx,doc,dotx,xlsx,xls,pdf|max:1000000',
        ]);

        $datas = $request['files'];
        if($datas !="" ){
            $path = 'files/delivery-order';
            $name = date('dmY').'_'.rand(10000,99999).'.'.$datas->getClientOriginalExtension();
            $datas->move($path,$name);

            $request['file'] = $name;
        }
        $PurchaseOrderLists = new PurchaseOrderLists();
        $sales_id = $PurchaseOrderLists->getListPO($request->purchase_order_list_code_id);

        $requestData = $request->except(['files','_token']);
        $requestData['sales_person_id'] = $sales_id->quote_sales_person_id;
        $requestData['is_active'] = 1;
        $requestData['created_at'] = date('Y-m-d H:i:s');
        $id = DeliveryOrderLists::insertGetId($requestData);

        $DeliveryOrderLists = DeliveryOrderLists::findOrFail($id);
        $InvoiceLists = InvoiceLists::where('purchase_order_list_code_id', $DeliveryOrderLists->purchase_order_list_code_id)->first();
        $requestDO['invoice_list_code_id'] = $InvoiceLists->id;
        $DeliveryOrderLists->update($requestDO);

        $getInvoice = InvoiceLists::findOrFail($InvoiceLists->id);
        $requestInvoice['fix_data'] = 1;
        $getInvoice->update($requestInvoice);

        Alert::success('Your data already created !', 'Success !');

        return redirect('admin/delivery-order-list');
    }

    public function createNewDO(Request $request)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $requestData = $request->all();
            $requestData['is_active'] = 0;
            $id = deliveryOrderLists::create($requestData);
            return redirect('admin/delivery-order-list/'.$id->id.'/create');
        }else{
            Alert::info('No Access !', 'Attention !');
            return redirect('admin/delivery-order-list');
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

        if($DOLists->delivery_order_sales_person_id == ''){
            $DOSales = '-';
        }else{
            $DOSalesPerson = $DeliveryOrderLists->deliveryOrderSales($DOLists->delivery_order_sales_person_id);
            $DOSales = $DOSalesPerson->name_sales;

        }

        $InvoiceLists = new InvoiceLists();

        if($DOLists->invoice_list_code_id != ''){
            $poInvoice = $InvoiceLists->invoiceBy($DOLists->invoice_list_code_id);
            $invoiceListPO = $poInvoice->invoice_list_code;
        }else{
            $invoiceListPO = '-';
        }

        $DeliveryOrderListsDetail = new DeliveryOrderListsDetail();
        $list_detail = $DeliveryOrderListsDetail->getList($id, $DOLists->prospect_sales_id);
        $selectlist = $DeliveryOrderListsDetail->getForSelect($DOLists->prospect_sales_id);


        $DeliveryOrderListsTransaction = new DeliveryOrderListsTransaction();
        $list_transaction = $DeliveryOrderListsTransaction->getListDO($DOLists->id);
        $count_item = $list_detail->sum('qty') - $list_transaction->sum('qty');
        return view('admin.delivery-order.show', compact('DOLists', 'productDO', 'POCode', 'prospect_sales','list_detail', 'invoiceListPO', 'selectlist', 'Prospect', 'salesPerson', 'DOSales', 'list_transaction', 'count_item'));
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
            $deliveryorderlist = DeliveryOrderLists::findOrFail($id);

            $DeliveryOrderLists = new DeliveryOrderLists();
            $POCode = $DeliveryOrderLists->getForPO();
            return view('admin.delivery-order.edit', compact('deliveryorderlist', 'POCode'));
        }else{
            Alert::info('No Access !', 'Attention !');
            return redirect('admin/delivery-order-list');
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
            'delivery_order_list_code' => 'required',
            'files' => 'mimes:jpeg,png,jpg,docx,doc,dotx,xlsx,xls,pdf|max:1000000',
        ]);

        $datas = $request['files'];
        if($datas !="" ){
            $path = 'files/delivery-order';
            $name = date('dmY').'_'.rand(10000,99999).'.'.$datas->getClientOriginalExtension();
            $datas->move($path,$name);

            $request['file'] = $name;
        }

        $requestData = $request->all();

        $DeliveryOrderLists = DeliveryOrderLists::findOrFail($id);
        if ($DeliveryOrderLists->file != '') {
            $image_path = app_path("../../files/delivery-order/" . $DeliveryOrderLists->file . "");
            unlink($image_path);
        }
        $DeliveryOrderLists->update($requestData);

        Alert::success('Your data already updated !', 'Success !');

        return redirect('admin/delivery-order-list');
    }

    public function do_create($id, Request $request)
    {
        
        $this->validate($request, [
            'purchase_order_list_code_id' => 'required',
            'delivery_order_list_code' => 'required',
            'date_out' => 'required',
            'pic_sales' => 'required',
            'pic_client' => 'required',
            'files' => 'mimes:jpeg,png,jpg,docx,doc,dotx,pdf|max:1000000',
            'files_pic' => 'mimes:jpeg,png,jpg,docx,doc,pdf|max:1000000',
        ]);

        $datas = $request['files'];
        if($datas !="" ){
            $path = 'files/delivery-order';
            $name = date('dmY').'_'.rand(10000,99999).'.'.$datas->getClientOriginalExtension();
            $datas->move($path,$name);

            $request['file'] = $name;
        }

        $data_pic = $request['files_pic'];
        if($data_pic !="" ){
            $path_pic = 'files/delivery-order-pic';
            $name_pic = date('dmY').'_'.rand(10000,99999).'.'.$data_pic->getClientOriginalExtension();
            $data_pic->move($path_pic,$name_pic);

            $request['file_pic'] = $name_pic;
        }
        $DeliveryOrderLists = new DeliveryOrderLists();
        $requestData = $request->all();
        $DOLists = DeliveryOrderLists::findOrFail($id);
        $requestData['is_active'] = 1;
        $DOLists->update($requestData);

        //change the status of the new Delivery Order
            $POLists = InvoiceLists::where('purchase_order_list_code_id', $request->purchase_order_list_code_id)->where('paid_off', 1)->first();
            $POData['fix_data'] = 1;

            $POLists->update($POData);
        //end

        Alert::success('Your data already updated !', 'Success !');
        return redirect('admin/delivery-order-list/'.$id.'/create');
    }

    public function do_transaction($id, Request $request)
    {
        
        $this->validate($request, [
            'purchase_order_list_code_id' => 'required',
            'date_out' => 'required',
            'pic_sales' => 'required',
            'pic_client' => 'required',
            'files' => 'mimes:jpeg,png,jpg,docx,doc,dotx,pdf|max:1000000',
            'files_pic' => 'mimes:jpeg,png,jpg,docx,doc,pdf|max:1000000',
        ]);

        $datas = $request['files'];
        if($datas !="" ){
            $path = 'files/delivery-order';
            $name = date('dmY').'_'.rand(10000,99999).'.'.$datas->getClientOriginalExtension();
            $datas->move($path,$name);

            $request['file'] = $name;
        }

        $data_pic = $request['files_pic'];
        if($data_pic !="" ){
            $path_pic = 'files/delivery-order-pic';
            $name_pic = date('dmY').'_'.rand(10000,99999).'.'.$data_pic->getClientOriginalExtension();
            $data_pic->move($path_pic,$name_pic);

            $request['file_pic'] = $name_pic;
        }
        $DeliveryOrderLists = new DeliveryOrderLists();
        $requestData = $request->all();
        $DOLists = DeliveryOrderLists::findOrFail($id);
        $requestData['is_active'] = 1;
        $DOLists->update($requestData);

        //change the status of the new Delivery Order
            $POLists = InvoiceLists::where('purchase_order_list_code_id', $request->purchase_order_list_code_id)->where('paid_off', 1)->first();
            $POData['fix_data'] = 1;

            $POLists->update($POData);
        //end

        Alert::success('Your data already updated !', 'Success !');
        return redirect('admin/delivery-order-list/'.$id.'/edit');
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
            $DeliveryOrderLists = DeliveryOrderLists::findOrFail($id);

            $InvoiceLists = InvoiceLists::findOrFail($DeliveryOrderLists->invoice_list_code_id);
            $InvoiceData['fix_data'] = 0;
            $InvoiceLists->update($InvoiceData);

            DeliveryOrderLists::destroy($id);
            DeliveryOrderListsDetail::where('delivery_order_list_id', $id)->delete();

            $old_data_transaction = DeliveryOrderListsTransaction::where('delivery_order_list_id', $id)->get();
            if (count($old_data_transaction) > 0) {
                foreach ($old_data_transaction as $transaction) {
                    InvoiceListsPayment::where('delivery_order_list_id', $id)->delete();
                }
            }
            if ($DeliveryOrderLists->file != '') {
                $image_path = app_path("../../files/delivery-order/" . $DeliveryOrderLists->file . "");
                unlink($image_path);
            }
            if ($DeliveryOrderLists->file_pic != '') {
                $image_path_pic = app_path("../../files/delivery-order-pic/" . $DeliveryOrderLists->file_pic . "");
                unlink($image_path_pic);
            }

            Alert::success('Your data already deleted !', 'Success !');
        }
        return redirect('admin/delivery-order-list');
    }

    public function anyData()
    {
        DB::statement(DB::raw('set @rownum=0'));
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $deliveryorderlist = DeliveryOrderLists::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'delivery_order_lists.id', 'ql.id as quote_id', 'delivery_order_lists.delivery_order_list_code',
                'il.invoice_list_code', 'po.purchase_order_list_code', 'ql.quote_list_code', 'delivery_order_lists.invoice_list_code_id', 'delivery_order_lists.purchase_order_list_code_id',
                'po.purchase_order_list_code', 'delivery_order_lists.file', 'il.file as file_invoice', 'po.file as file_po', 'ql.file as file_quote', 'delivery_order_lists.qty_transaction',
                'delivery_order_lists.status', 'delivery_order_lists.is_active', 'delivery_order_lists.created_at'])
                ->leftJoin('invoice_lists as il', 'il.id', '=', 'delivery_order_lists.invoice_list_code_id')
                ->leftJoin('purchase_order_lists as po', 'po.id', '=', 'delivery_order_lists.purchase_order_list_code_id')
                ->leftJoin('quote_lists as ql', 'ql.id', '=', 'po.quote_list_code_id')
                ->where('delivery_order_lists.is_active', 1)
                ->orderby('id', 'DESC')
                ->get();
        }else{
            $deliveryorderlist = DeliveryOrderLists::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'delivery_order_lists.id', 'ql.id as quote_id', 'delivery_order_lists.delivery_order_list_code',
                'il.invoice_list_code', 'po.purchase_order_list_code', 'ql.quote_list_code', 'delivery_order_lists.invoice_list_code_id', 'delivery_order_lists.purchase_order_list_code_id',
                'po.purchase_order_list_code', 'delivery_order_lists.file', 'il.file as file_invoice', 'po.file as file_po', 'ql.file as file_quote', 'delivery_order_lists.qty_transaction',
                'delivery_order_lists.status', 'delivery_order_lists.is_active', 'delivery_order_lists.created_at'])
                ->leftJoin('invoice_lists as il', 'il.id', '=', 'delivery_order_lists.invoice_list_code_id')
                ->leftJoin('purchase_order_lists as po', 'po.id', '=', 'delivery_order_lists.purchase_order_list_code_id')
                ->leftJoin('quote_lists as ql', 'ql.id', '=', 'po.quote_list_code_id')
                ->where('delivery_order_lists.sales_person_id', Auth::user()->id)
                ->where('delivery_order_lists.is_active', 1)
                ->orderby('id', 'DESC')
                ->get();
        }
        return Datatables::of($deliveryorderlist)

            ->addColumn('action', function ($deliveryorderlist) {
                if(Auth::user()->role == User::ROLE_SUPERADMIN) {
                    return '<p><a href="delivery-order-list/' . $deliveryorderlist->id . '/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-edit"></i> Edit </a></p>
                        <a onclick="deleteData(' . $deliveryorderlist->id . ')" class="btn bg-red btn-xs waves-effect"><i class="fa fa-close"></i> Cancel </a>';
                }elseif(Auth::user()->role == User::ROLE_ADMIN){
                    return '<p><a href="delivery-order-list/' . $deliveryorderlist->id . '/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-edit"></i> Edit </a></p>';
                }else{
                    return '-';
                }
                        // <p><a class="btn bg-grey btn-xs waves-effect" href="delivery-order-list/view/'.$deliveryorderlist->id.'"><i class="fa fa-eye"></i> View</a></p>
            })
            ->addColumn('delivery_order_list_code', function ($deliveryorderlist) {
                if($deliveryorderlist->file != '')
                {
                    if(substr($deliveryorderlist->file, -3) == 'pdf'){
                        return $deliveryorderlist->delivery_order_list_code.'<p><a href="'.url('/').'/files/delivery-order/'.$deliveryorderlist->file.'" download="DO-'.date('Ymd').'" target="_blank"><img src="'.url('/').'/images/ico_pdf.svg" width="40"/></a></p>';
                    }else{
                        return $deliveryorderlist->delivery_order_list_code.'<p><a href="'.url('/').'/files/delivery-order/'.$deliveryorderlist->file.'" download="DO-'.date('Ymd').'" target="_blank"><img src="'.url('/').'/images/ico_document.svg" width="50"/></a></p>';
                    }
                }else{
                    return $deliveryorderlist->invoice_list_code;
                }
            })
            ->addColumn('invoice_list_code', function ($deliveryorderlist) {
                if($deliveryorderlist->file_invoice != '')
                {
                    if(substr($deliveryorderlist->file_invoice, -3) == 'pdf'){
                        return $deliveryorderlist->invoice_list_code.'<p><a href="'.url('/').'/files/invoice/'.$deliveryorderlist->file_invoice.'" download="Invoice-'.date('Ymd').'" target="_blank"><img src="'.url('/').'/images/ico_pdf.svg" width="40"/></a></p>';
                    }else{
                        return $deliveryorderlist->invoice_list_code.'<p><a href="'.url('/').'/files/invoice/'.$deliveryorderlist->file_invoice.'" download="Invoice-'.date('Ymd').'" target="_blank"><img src="'.url('/').'/images/ico_document.svg" width="50"/></a></p>';
                    }
                }else{
                    return $deliveryorderlist->invoice_list_code;
                }
            })
            ->addColumn('purchase_order_list_code', function ($deliveryorderlist) {
                if($deliveryorderlist->file_po != '')
                {
                    if(substr($deliveryorderlist->file_po, -3) == 'pdf'){
                        return $deliveryorderlist->purchase_order_list_code.'<p><a href="'.url('/').'/files/purchase-order/'.$deliveryorderlist->file_po.'" download="PO-'.date('Ymd').'" target="_blank"><img src="'.url('/').'/images/ico_pdf.svg" width="40"/></a></p>';
                    }else{
                        return $deliveryorderlist->purchase_order_list_code.'<p><a href="'.url('/').'/files/purchase-order/'.$deliveryorderlist->file_po.'" download="PO-'.date('Ymd').'" target="_blank"><img src="'.url('/').'/images/ico_document.svg" width="50"/></a></p>';
                    }
                }else{
                    return $deliveryorderlist->purchase_order_list_code;
                }
            })
            ->addColumn('quote_list_code', function ($deliveryorderlist) {
                return $deliveryorderlist->quote_list_code.'<p><a href="quote-list/'.Crypt::encrypt($deliveryorderlist->quote_id).'/generate-pdf" target="_blank"><img src="'.url('/').'/images/ico_pdf.svg" width="40"/></a></p>';
                // <p><a href="'.url('/').'/files/quote-list/'.$deliveryorderlist->file_quote.'" download="Quote-'.date('Ymd').'" target="_blank"><img src="'.url('/').'/images/ico_pdf.svg" width="40"/></a></p>
            })
            ->addColumn('created_at', function ($deliveryorderlist) {
                return Carbon::parse($deliveryorderlist->created_at)->format('Y-m-d');
            })
            ->rawColumns(['action', 'delivery_order_list_code', 'invoice_list_code', 'purchase_order_list_code', 'quote_list_code', 'created_at'])
            ->make(true);
    }

    public function getPO(Request $request)
    {
        $do_list_id = $request->do_list_id;
        $po_id = $request->purchase_order_list_code_id;

        $DeliveryOrderLists = DeliveryOrderLists::findOrFail($do_list_id);
        $DOListsId = $DeliveryOrderLists->getPO($po_id);

        //after new Purchase Order is selected, change the old Purchase Order status
            $POList = InvoiceLists::findOrFail($DeliveryOrderLists->invoice_list_code_id);
            $InvoiceData['fix_data'] = 0;
            $POList->update($InvoiceData);
        //end

        $InvoiceLists = InvoiceLists::where('purchase_order_list_code_id', $po_id)->where('paid_off', 1)->first();
        $sales = new Sales();
        $salesPersonId = $sales->getSales($DOListsId->quote_prospect_sales_id);
        $sales_person = $salesPersonId->name_sales;

        $DeliveryOrderListsDetail = new DeliveryOrderListsDetail();

        $requestList['purchase_order_list_code_id'] = $DOListsId->id;
        $requestList['delivery_order_sales_person_id'] = $DOListsId->po_prospect_sales_id;
        $requestList['delivery_order_list_code'] = $request->delivery_order_list_code;
        $requestList['prospect_sales_id'] = $DOListsId->quote_prospect_sales_id;
        $requestList['sales_person_id'] = $DOListsId->quote_sales_person_id;
        $requestList['gross_price'] = $DOListsId->gross_price;
        $requestList['qty'] = $DOListsId->qty;
        $requestList['total_price'] = $DOListsId->total_price;
        $requestList['total_diskon'] = $DOListsId->total_diskon;
        $requestList['invoice_list_code_id'] = $InvoiceLists->id;
        $getInvoice = InvoiceLists::findOrFail($InvoiceLists->id);
        $requestList['invoice_code'] = $getInvoice->invoice_code;
        $InvoicePayment = $DeliveryOrderLists->getForInvoicePayment($InvoiceLists->id);
        $requestList['total_invoice'] = $InvoicePayment->sum('amount');
        $requestList['date_out'] = $request->date_out;
        $requestList['pic_sales'] = $sales_person;
        $requestList['pic_client'] = $request->pic_client;
        $requestList['note'] = $request->note;
        $requestList['status'] = 0;
        $requestData['is_active'] = 0;
        $requestData['qty_transaction'] = $DOListsId->qty;

        $DeliveryOrderLists->update($requestList);


        $DODetailId = $DeliveryOrderListsDetail->getPODetail($po_id);

        $old_data = DeliveryOrderListsDetail::where('delivery_order_list_id', $do_list_id)->get();

        if(count($old_data) > 0)
        {
            DeliveryOrderListsDetail::where('delivery_order_list_id', $do_list_id)->delete();
            DeliveryOrderListsTransaction::where('delivery_order_list_id', $do_list_id)->delete();

        }

        foreach($DODetailId as $DODetail){
            $requestDetail['delivery_order_list_id'] = $do_list_id;
            $requestDetail['prospect_sales_id'] = $DODetail->prospect_sales_id;
            $requestDetail['product_id'] = $DODetail->product_id;
            $requestDetail['product_name'] = $DODetail->product_name;
            $requestDetail['qty'] = $DODetail->qty;
            $requestDetail['price'] = $DODetail->price;
            $requestDetail['gross_price'] = $DODetail->gross_price;
            $requestDetail['diskon'] = $DODetail->diskon;
            $requestDetail['diskon_nominal'] = $DODetail->diskon_nominal;
            $requestDetail['net_price'] = $DODetail->net_price;
   
            DeliveryOrderListsDetail::create($requestDetail);

            Alert::success('Your data already created !', 'Success !');
        }
    }

    public function getPOCreate(Request $request)
    {
        $do_list_id = $request->do_list_id;
        $po_id = $request->purchase_order_list_code_id;

        $DeliveryOrderLists = DeliveryOrderLists::findOrFail($do_list_id);
        $DOListsId = $DeliveryOrderLists->getPO($po_id);

        $InvoiceLists = InvoiceLists::where('purchase_order_list_code_id', $po_id)->where('paid_off', 1)->first();
        $sales = new Sales();
        $salesPersonId = $sales->getSales($DOListsId->quote_prospect_sales_id);
        $sales_person = $salesPersonId->name_sales;

        $DeliveryOrderListsDetail = new DeliveryOrderListsDetail();

        $requestList['purchase_order_list_code_id'] = $DOListsId->id;
        $requestList['delivery_order_sales_person_id'] = $DOListsId->po_prospect_sales_id;
        $requestList['delivery_order_list_code'] = $request->delivery_order_list_code;
        $requestList['prospect_sales_id'] = $DOListsId->quote_prospect_sales_id;
        $requestList['sales_person_id'] = $DOListsId->quote_sales_person_id;
        $requestList['gross_price'] = $DOListsId->gross_price;
        $requestList['qty'] = $DOListsId->qty;
        $requestList['total_price'] = $DOListsId->total_price;
        $requestList['total_diskon'] = $DOListsId->total_diskon;
        $requestList['invoice_list_code_id'] = $InvoiceLists->id;
        $getInvoice = InvoiceLists::findOrFail($InvoiceLists->id);
        $requestList['invoice_code'] = $getInvoice->invoice_code;
        $InvoicePayment = $DeliveryOrderLists->getForInvoicePayment($InvoiceLists->id);
        $requestList['total_invoice'] = $InvoicePayment->sum('amount');
        $requestList['date_out'] = $request->date_out;
        $requestList['pic_sales'] = $sales_person;
        $requestList['pic_client'] = $request->pic_client;
        $requestList['note'] = $request->note;
        $requestList['status'] = 0;
        $requestData['is_active'] = 0;
        $requestData['qty_transaction'] = $DOListsId->qty;


        $DeliveryOrderLists->update($requestList);


        $DODetailId = $DeliveryOrderListsDetail->getPODetail($po_id);

        $old_data = DeliveryOrderListsDetail::where('delivery_order_list_id', $do_list_id)->get();

        if(count($old_data) > 0)
        {
            DeliveryOrderListsDetail::where('delivery_order_list_id', $do_list_id)->delete();
            DeliveryOrderListsTransaction::where('delivery_order_list_id', $do_list_id)->delete();

        }

        foreach($DODetailId as $DODetail){
            $requestDetail['delivery_order_list_id'] = $do_list_id;
            $requestDetail['prospect_sales_id'] = $DODetail->prospect_sales_id;
            $requestDetail['product_id'] = $DODetail->product_id;
            $requestDetail['product_name'] = $DODetail->product_name;
            $requestDetail['qty'] = $DODetail->qty;
            $requestDetail['price'] = $DODetail->price;
            $requestDetail['gross_price'] = $DODetail->gross_price;
            $requestDetail['diskon'] = $DODetail->diskon;
            $requestDetail['diskon_nominal'] = $DODetail->diskon_nominal;
            $requestDetail['net_price'] = $DODetail->net_price;
   
            DeliveryOrderListsDetail::create($requestDetail);

            Alert::success('Your data already created !', 'Success !');
        }
    }

    public function do_save_qty_create(Request $request)
    {
        $this->validate($request, [
            'product_name' => 'required',
        ]);
        $do_id = $request->delivery_order_list_id;
        $product_id = $request->product_name;
        $qty = $request->qty_transaction;
        $po_id = $request->purchase_order_id;


        $DOLists = DeliveryOrderLists::findOrFail($do_id);

        $DeliveryOrderListsTransaction = new DeliveryOrderListsTransaction();
        $list_transaction = $DeliveryOrderListsTransaction->getListDO($do_id);
        $list_qty = $list_transaction->sum('qty') + $qty;
        $list_sum_qty = $DOLists->qty - $list_qty;

        $DeliveryOrderLists = new DeliveryOrderLists();
        $sumProduct = $DeliveryOrderLists->sumProduct($do_id);
        $sumQtyProduct = $sumProduct->sum('qty') + $qty;

        $cek_qty_product_detail = $DeliveryOrderLists->cekQtyProductDetail($do_id, $product_id);

        $cek_product_transaction = $DeliveryOrderLists->cekProductTransaction($do_id, $product_id);
        $sum_qty = $cek_product_transaction->sum('qty') + $qty;

        if($qty == '0' || $qty == '')
        {
            Alert::info("Quantity cannot be 0 / empty  !", "ATTENTION !")->autoclose(3500);
        }
        elseif($cek_qty_product_detail->sum('qty') >= $sum_qty)
        {
            $requestData = array(
                'delivery_order_list_id' => $do_id,
                'product_id' => $product_id,
                'qty' => $qty
            );
            
            DeliveryOrderListsTransaction::create($requestData);

            if($DOLists->qty == $sumQtyProduct)
            {

                $dataDO['qty_transaction'] = $list_sum_qty;
                $dataDO['status'] = 1;
                $DOLists->update($dataDO);
            }
            else
            {

                $dataDO['qty_transaction'] = $list_sum_qty;
                $dataDO['status'] = 0;
                $DOLists->update($dataDO);
            }

            Alert::success('Quantity Payment already created !', 'Success !')->autoclose(1500);
        }
        else
        {

        $product_name = $DeliveryOrderLists->name_product($product_id);
            Alert::info("Total quantity cannot exceed ". $product_name->product_name ." !", "ATTENTION !")->autoclose(3500);
        }

        return redirect('admin/delivery-order-list/'.$do_id.'/create');
    }

    public function do_save_qty_transaction(Request $request)
    {
         $this->validate($request, [
            'product_name' => 'required',
        ]);
        $do_id = $request->delivery_order_list_id;
        $product_id = $request->product_name;
        $qty = $request->qty;
        $po_id = $request->purchase_order_id;

        $DOLists = DeliveryOrderLists::findOrFail($do_id);

        $DeliveryOrderListsTransaction = new DeliveryOrderListsTransaction();
        $list_transaction = $DeliveryOrderListsTransaction->getListDO($do_id);
        $list_qty = $list_transaction->sum('qty') + $qty ;
        $list_sum_qty = $DOLists->qty - $list_qty;

        $DeliveryOrderLists = new DeliveryOrderLists();
        $sumProduct = $DeliveryOrderLists->sumProduct($do_id);
        $sumQtyProduct = $sumProduct->sum('qty') + $qty;

        $cek_qty_product_detail = $DeliveryOrderLists->cekQtyProductDetail($do_id, $product_id);

        $cek_product_transaction = $DeliveryOrderLists->cekProductTransaction($do_id, $product_id);
        $sum_qty = $cek_product_transaction->sum('qty') + $qty;

        if($qty == '0' || $qty == '')
        {
            Alert::info("Quantity cannot be 0 / empty  !", "ATTENTION !")->autoclose(3500);
        }
        elseif($cek_qty_product_detail->sum('qty') >= $sum_qty)
        {
            $requestData = array(
                'delivery_order_list_id' => $do_id,
                'product_id' => $product_id,
                'qty' => $qty
            );
            
            DeliveryOrderListsTransaction::create($requestData);

            if($DOLists->qty == $sumQtyProduct)
            {

                $dataDO['qty_transaction'] = $list_sum_qty;
                $dataDO['status'] = 1;
                $DOLists->update($dataDO);
            }
            else
            {

                $dataDO['qty_transaction'] = $list_sum_qty;
                $dataDO['status'] = 0;
                $DOLists->update($dataDO);
            }

            Alert::success('Quantity Payment already created !', 'Success !')->autoclose(1500);
        }
        else
        {
            $product_name = $DeliveryOrderLists->name_product($product_id);
            Alert::info("Total quantity cannot exceed ". $product_name->product_name ." !", "ATTENTION !")->autoclose(3500);
        }

        return redirect('admin/delivery-order-list/'.$do_id.'/edit');
    }

    public function do_update_qty_create($id, Request $request)
    {
        $do_id = $request->delivery_order_list_id;
        $product_id = $request->product_id;
        $qty = $request->qty;
        $po_id = $request->purchase_order_id;

        $DOLists = DeliveryOrderLists::findOrFail($do_id);
        $DeliveryTransaction = DeliveryOrderListsTransaction::findOrFail($id);

        $DeliveryOrderListsTransaction = new DeliveryOrderListsTransaction();
        $list_transaction = $DeliveryOrderListsTransaction->getListDO($do_id);
        $list_qty = $list_transaction->sum('qty') - $DeliveryTransaction->qty;
        $qty_sum = $list_qty + $qty;
        // return $qty_sum;
        $list_sum = $DOLists->qty - $qty_sum;

        $DeliveryOrderLists = new DeliveryOrderLists();
        $sumProduct = $DeliveryOrderLists->sumProduct($do_id);
        $sumQtyProduct = $sumProduct->sum('qty') - $DeliveryTransaction->qty;
        $sumQty = $sumQtyProduct + $qty;

        $cek_qty_product_detail = $DeliveryOrderLists->cekQtyProductDetail($do_id, $product_id);
        $cek_qty_product = $cek_qty_product_detail->sum('qty');

        $cek_product_transaction = $DeliveryOrderLists->cekProductTransaction($do_id, $product_id);
        $cek_product = $cek_product_transaction->sum('qty') - $DeliveryTransaction->qty;
        $cek_qty = $cek_product + $qty;

        if($cek_qty_product >= $cek_qty)
        {
            $requestData['qty'] = $qty;
            $DeliveryTransaction->update($requestData);

            if($sumQty == $DOLists->qty)
            {
                $dataDO['qty_transaction'] = $list_sum;
                $dataDO['status'] = 1;
                $DOLists->update($dataDO);
            }
            else
            {
                $dataDO['qty_transaction'] = $list_sum;
                $dataDO['status'] = 0;
                $DOLists->update($dataDO);
            }

            Alert::success('Your data Delivery Order Transaction already Updated !', 'Success !')->autoclose(2000);
        }
        else{
            $product_name = $DeliveryOrderLists->name_product($product_id);
            Alert::info("Total quantity cannot exceed ". $product_name->product_name ." !", "ATTENTION !")->autoclose(3500);
        }

        return redirect('admin/delivery-order-list/'.$do_id.'/create');
    }

    public function do_update_qty_transaction($id, Request $request)
    {
        $do_id = $request->delivery_order_list_id;
        $product_id = $request->product_id;
        $qty = $request->qty;
        $po_id = $request->purchase_order_id;

        $DOLists = DeliveryOrderLists::findOrFail($do_id);
        $DeliveryTransaction = DeliveryOrderListsTransaction::findOrFail($id);

        $DeliveryOrderListsTransaction = new DeliveryOrderListsTransaction();
        $list_transaction = $DeliveryOrderListsTransaction->getListDO($do_id);
        $list_qty = $list_transaction->sum('qty') - $DeliveryTransaction->qty;
        $qty_sum = $list_qty + $qty;
        $list_sum = $DOLists->qty - $qty_sum;

        $DeliveryOrderLists = new DeliveryOrderLists();
        $sumProduct = $DeliveryOrderLists->sumProduct($do_id);
        $sumQtyProduct = $sumProduct->sum('qty') - $DeliveryTransaction->qty;
        $sumQty = $sumQtyProduct + $qty;

        if($sumQty == $DOLists->qty)
        {
            $dataDO['qty_transaction'] = $list_sum;
            $dataDO['status'] = 1;
            $DOLists->update($dataDO);
        }
        else
        {
            $dataDO['qty_transaction'] = $list_sum;
            $dataDO['status'] = 0;
            $DOLists->update($dataDO);
        }

        $cek_qty_product_detail = $DeliveryOrderLists->cekQtyProductDetail($do_id, $product_id);
        $cek_qty_product = $cek_qty_product_detail->sum('qty');

        $cek_product_transaction = $DeliveryOrderLists->cekProductTransaction($do_id, $product_id);
        $cek_product = $cek_product_transaction->sum('qty') - $DeliveryTransaction->qty;
        $cek_qty = $cek_product + $qty;

        if($cek_qty_product >= $cek_qty)
        {
            $requestData['qty'] = $qty;
            $DeliveryTransaction->update($requestData);

            if($sumQty == $DOLists->qty)
            {
                $dataDO['qty_transaction'] = $list_sum;
                $dataDO['status'] = 1;
                $DOLists->update($dataDO);
            }
            else
            {
                $dataDO['qty_transaction'] = $list_sum;
                $dataDO['status'] = 0;
                $DOLists->update($dataDO);
            }
            Alert::success('Your data Delivery Order Transaction already Updated !', 'Success !')->autoclose(2000);
        }
        else{
            $product_name = $DeliveryOrderLists->name_product($product_id);
            Alert::info("Total quantity cannot exceed ". $product_name->product_name ." !", "ATTENTION !")->autoclose(3500);
        }

        return redirect('admin/delivery-order-list/'.$do_id.'/edit');
    }

    public function do_delete_qty($id, Request $request)
    {
        $do_id = $request->do_id;
        $doTransaction = DeliveryOrderListsTransaction::findOrFail($id);
        $doTransaction_sum = DeliveryOrderListsTransaction::where('delivery_order_list_id', $do_id);
        $DOLists = DeliveryOrderLists::findOrFail($do_id);
        $qty_sum = $doTransaction_sum->sum('qty') - $doTransaction->qty;
        $sum_qty = $DOLists->qty - $qty_sum;
        $dataDO['qty_transaction'] = $sum_qty;
        $dataDO['status'] = 0;
        $DOLists->update($dataDO);
        DeliveryOrderListsTransaction::destroy($id);

        Alert::success('Your data already deleted !', 'Success !');

    }

    public function create_all_do(Request $request)
    {
        
        $this->validate($request, [
            'delivery_order_list_code' => 'required',
        ]);

        $requestData = $request->all();
        $DeliveryOrderLists = DeliveryOrderLists::findOrFail($request->id);
        $DeliveryOrderLists->update($requestData);
        Alert::success('Delivery Order data already Created !', 'Success !');
    
        return redirect('admin/delivery-order-list');
    }

    public function create_all_do_transaction(Request $request)
    {
        
        $this->validate($request, [
            'delivery_order_list_code' => 'required',
        ]);

        $requestData = $request->all();
        $DeliveryOrderLists = DeliveryOrderLists::findOrFail($request->id);
        $DeliveryOrderLists->update($requestData);
        Alert::success('Delivery Order data already Updated !', 'Success !');
    
        return redirect('admin/delivery-order-list');
    }


}
