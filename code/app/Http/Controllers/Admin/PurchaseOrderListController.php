<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\PurchaseOrderLists;
use App\PurchaseOrderListsDetail;
use App\QuoteLists;
use App\ProspectSales;
use App\Sales;
use App\InvoiceLists;
use Illuminate\Http\Request;
use App\User;
use Session;
use Auth;
use DB;
use Alert;
use Crypt;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

class PurchaseOrderListController extends Controller
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
        $allPOCount = PurchaseOrderLists::where('is_active', 1)->count();
        $newPOCount = PurchaseOrderLists::where('is_active', 1)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->count();
        return view('admin.purchase-order.index', compact('allPOCount', 'newPOCount'));
    }

      /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $PurchaseOrderLists = new PurchaseOrderLists();
            $QuoteCode = $PurchaseOrderLists->getForQuote();
            return view('admin.purchase-order.create', compact('QuoteCode'));
        }else{
            Alert::info('No Access !', 'Attention !');
            return redirect('admin/purchase-order-list');
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'quote_list_code_id'       => 'required',
            'purchase_order_list_code' => 'required|unique:purchase_order_lists,purchase_order_list_code',
            'files'                    => 'mimes:jpeg,png,jpg,docx,doc,dotx,xlsx,xls,pdf|max:1000000',
        ]);
        $quote_id = $request->quote_list_code_id;
        $QuoteLists = new QuoteLists();
        $sales_id = $QuoteLists->getQuote($quote_id);

        $datas = $request['files'];
        if($datas !="" ){
            $path = 'files/purchase-order';
            $name = date('dmY').'_'.rand(10000,99999).'.'.$datas->getClientOriginalExtension();
            $datas->move($path,$name);

            $request['file'] = $name;
        }

        $requestData = $request->except(['files','_token']);
        $requestData['quote_sales_person_id'] = $sales_id->sales_person_id;
        $requestData['created_at'] = date('Y-m-d H:i:s');
        $requestData['is_active'] = 1;
        $id = PurchaseOrderLists::insertGetId($requestData);

        $requestQuote['fix_data'] = 1;
        $quote_data = QuoteLists::findOrFail($quote_id);
        // var_dump($quote_data);die();
        $quote_data->update($requestQuote);

        Alert::success('Your data already created !', 'Success !');

        return redirect('admin/purchase-order-list');        
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
        $POLists = PurchaseOrderLists::findOrFail($id);
        
        $PurchaseOrderLists = new PurchaseOrderLists();
        $POCode = $PurchaseOrderLists->getForPO($id);
        $QuoteCode = $PurchaseOrderLists->getForQuote();
        $getProspectSales = $PurchaseOrderLists->getProspectSales($POLists->quote_prospect_sales_id);

        $prospectSales = new ProspectSales();
        $prospect_sales = $prospectSales->getForSelect();
        if($POLists->quote_prospect_sales_id == '0'){
            $Prospect = '';
        }else{
            $ProspectId = $prospectSales->getForSales($POLists->quote_prospect_sales_id);
            $Prospect = $ProspectId->company_name;
        }

        $sales = new Sales();
        if($POLists->quote_sales_person_id == '0'){
            $salesPerson = '';
        }else{
            $salesPersonId = $sales->getSales($POLists->quote_sales_person_id);
            $salesPerson = $salesPersonId->name_sales;
        }


        $PurchaseOrderListsDetail = new PurchaseOrderListsDetail();
        $list_detail = $PurchaseOrderListsDetail->getList($id, $POLists->quote_prospect_sales_id);
        $selectlist = $PurchaseOrderListsDetail->getForSelect($POLists->quote_prospect_sales_id);
        return view('admin.purchase-order.show', compact('POLists', 'POCode', 'QuoteCode', 'getProspectSales', 'prospect_sales','list_detail', 'selectlist', 'Prospect', 'salesPerson'));
    }

    public function CreatePurchaseOrder($id)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $POLists = PurchaseOrderLists::findOrFail($id);

            $PurchaseOrderLists = new PurchaseOrderLists();
            $POCode = $PurchaseOrderLists->getForPO($id);
            $QuoteCode = $PurchaseOrderLists->getForQuote();
            $getProspectSales = $PurchaseOrderLists->getProspectSales($POLists->quote_prospect_sales_id);

            $prospectSales = new ProspectSales();
            $prospect_sales = $prospectSales->getForSelect();
            if ($POLists->quote_prospect_sales_id == '0') {
                $Prospect = '';
            } else {
                $ProspectId = $prospectSales->getForSales($POLists->quote_prospect_sales_id);
                $Prospect = $ProspectId->company_name;
            }

            $sales = new Sales();
            if ($POLists->quote_sales_person_id == '0') {
                $salesPerson = '';
            } else {
                $salesPersonId = $sales->getSales($POLists->quote_sales_person_id);
                $salesPerson = $salesPersonId->name_sales;
            }


            $PurchaseOrderListsDetail = new PurchaseOrderListsDetail();
            $list_detail = $PurchaseOrderListsDetail->getList($id, $POLists->quote_prospect_sales_id);
            $selectlist = $PurchaseOrderListsDetail->getForSelect($POLists->quote_prospect_sales_id);
            return view('admin.purchase-order.create-purchase-order', compact('POLists', 'POCode', 'QuoteCode', 'getProspectSales', 'prospect_sales', 'list_detail', 'selectlist', 'Prospect', 'salesPerson'));

        }else{
            Alert::info('No Access !', 'Attention !');
            return redirect('admin/purchase-order-list');
        }
    }

    public function edit($id)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $POLists = PurchaseOrderLists::findOrFail($id);

            $PurchaseOrderLists = new PurchaseOrderLists();
            $QuoteCode = $PurchaseOrderLists->getForQuote();

            return view('admin.purchase-order.edit', compact('POLists', 'QuoteCode'));
        }else{
            Alert::info('No Access !', 'Attention !');
            return redirect('admin/purchase-order-list');
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
            'quote_list_code_id' => 'required',
            'purchase_order_list_code' => 'required',
            'files' => 'mimes:jpeg,png,jpg,docx,doc,dotx,xlsx,xls,pdf|max:1000000',
        ]);

        $datas = $request['files'];
        if($datas !="" ){
            $path = 'files/purchase-order';
            $name = date('dmY').'_'.rand(10000,99999).'.'.$datas->getClientOriginalExtension();
            $datas->move($path,$name);

            $request['file'] = $name;
        }

        $requestData = $request->all();

        $PurchaseOrderLists = PurchaseOrderLists::findOrFail($id);
        $PurchaseOrderLists->update($requestData);

        $requestQuote['fix_data'] = 1;
        $quote_data = QuoteLists::findOrFail($PurchaseOrderLists->quote_list_code_id);
        $quote_data->update($requestQuote);

        Alert::success('Your data already updated !', 'Success !');

        return redirect('admin/purchase-order-list');
    }


    public function do_create($id, Request $request)
    {
        
        $this->validate($request, [
            'purchase_order_list_code' => 'required',
            'po_prospect_sales_id' => 'required',
            'quote_list_code_id' => 'required',
            'date_out' => 'required'
        ]);

        $datas = $request['files'];
        if($datas !="" ){
            $path = 'files/purchase-order';
            $name = date('dmY').'_'.rand(10000,99999).'.'.$datas->getClientOriginalExtension();
            $datas->move($path,$name);

            $request['file'] = $name;
        }

        $requestData = $request->all();
        $requestData['is_active'] = 1;
        $requestData['qty'] = $request->qty_sum;
        $uploadfile = PurchaseOrderLists::findOrFail($id);
        $uploadfile->update($requestData);
        Alert::success('Purchase Order data already Created !', 'Success !');
        return redirect('admin/purchase-order-list');
    }

    public function do_edit($id, Request $request)
    {
        
        $this->validate($request, [
            'purchase_order_list_code' => 'required',
            'quote_list_code_id' => 'required',
            'files' => 'mimes:pdf,jpg,png,jpeg'
        ]);

        $datas = $request['files'];
        if($datas !="" ){
            $path = 'files/purchase-order';
            $name = date('dmY').'_'.rand(10000,99999).'.'.$datas->getClientOriginalExtension();
            $datas->move($path,$name);

            $request['file'] = $name;
        }

        $requestData = $request->all();
        $requestData['is_active'] = 1;
        $requestData['qty'] = $request->qty_sum;
        $uploadfile = PurchaseOrderLists::findOrFail($id);
        $uploadfile->update($requestData);
        Alert::success('Your data already updated !', 'Success !');
        return redirect('admin/purchase-order-list');
    }

    public function do_update_sum($id, Request $request)
    {
        
        $requestData = $request->all();
        $PurchaseOrderLists = PurchaseOrderLists::findOrFail($id);
        $data = $PurchaseOrderLists->update($requestData);
        // return Alert::success('Your data already updated !', 'Success !');
        return response()->json(['data' => $data, 'success' => true, 'message' => 'success'], 200);
    }

    public function approve($id, Request $request)
    {
        
        $PurchaseOrderLists = PurchaseOrderLists::findOrFail($id);
        // $requestData['status'] = 1;
        $PurchaseOrderLists->update(array('status' => 1));

        Alert::success('Purchase Order has been approved ', 'Success');
        return redirect()->back();
        // return redirect('','refresh')->back();
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
            $PurchaseOrderLists = PurchaseOrderLists::findOrFail($id);

            $QuoteLists = QuoteLists::findOrFail($PurchaseOrderLists->quote_list_code_id);
            $QuoteData['fix_data'] = 0;
            $QuoteLists->update($QuoteData);
            if ($PurchaseOrderLists->file != '') {
                $image_path = app_path("../../files/purchase-order/" . $PurchaseOrderLists->file . "");
                unlink($image_path);
            }
            PurchaseOrderLists::destroy($id);

            Alert::success('Your data already deleted !', 'Success !');
        }
        return redirect('admin/purchase-order-list');
    }

    public function generatePdf($purchase_order_list_code)
    {
        $template = PurchaseOrderLists::where('purchase_order_code_id', $purchase_order_list_code)->first();
        $purchaseorder = PurchaseOrder::where('purchase_order_code', $purchase_order_list_code)->first();

        $pdf = \PDF::loadView('admin.purchase-order.template-pdf', compact('template', 'purchaseorder'));
        return $pdf->stream();
    }

    public function anyData()
    {
        DB::statement(DB::raw('set @rownum=0'));
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $purchaseorderlist = PurchaseOrderLists::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'purchase_order_lists.id', 'ql.id as quote_id',
                'purchase_order_lists.quote_list_code_id', 'ql.quote_list_code', 'purchase_order_lists.purchase_order_list_code', 'purchase_order_lists.file',
                'purchase_order_lists.status', 'purchase_order_lists.fix_data as fix_invoice', 'il.fix_data as fix_do', 'purchase_order_lists.is_active', 'purchase_order_lists.created_at'])
                ->leftJoin('quote_lists as ql', 'ql.id', '=', 'purchase_order_lists.quote_list_code_id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'purchase_order_lists.id')
                ->groupBy('purchase_order_lists.purchase_order_list_code')
                ->where('purchase_order_lists.is_active', 1)
                ->orderby('purchase_order_lists.id', 'DESC')
                ->get();
        }else{
            $purchaseorderlist = PurchaseOrderLists::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'purchase_order_lists.id', 'ql.id as quote_id',
                'purchase_order_lists.quote_list_code_id', 'ql.quote_list_code', 'purchase_order_lists.purchase_order_list_code', 'purchase_order_lists.file',
                'purchase_order_lists.status', 'purchase_order_lists.fix_data as fix_invoice', 'il.fix_data as fix_do', 'purchase_order_lists.is_active', 'purchase_order_lists.created_at'])
                ->leftJoin('quote_lists as ql', 'ql.id', '=', 'purchase_order_lists.quote_list_code_id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'purchase_order_lists.id')
                ->groupBy('purchase_order_lists.purchase_order_list_code')
                ->where('purchase_order_lists.quote_sales_person_id', Auth::user()->id)
                ->where('purchase_order_lists.is_active', 1)
                ->orderby('purchase_order_lists.id', 'DESC')
                ->get();
        }
        return Datatables::of($purchaseorderlist)
            ->addColumn('action', function ($purchaseorderlist) {
                if(Auth::user()->role == User::ROLE_SUPERADMIN){
                    return '<p><a href="purchase-order-list/' . $purchaseorderlist->id . '/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-edit"></i> Edit </a></p>
                            <p><a onclick="deleteData(' . $purchaseorderlist->id . ')" class="btn bg-red btn-xs waves-effect"><i class="fa fa-trash-o"></i> Delete </a></p>';
                }elseif(Auth::user()->role == User::ROLE_ADMIN) {
                    return '<p><a href="purchase-order-list/' . $purchaseorderlist->id . '/edit" class="btn bg-cyan btn-xs waves-effect"><i class="fa fa-edit"></i> Edit </a></p>';
                }else{
                    return '-';

                }
                
            })
            ->addColumn('purchase_order_list_code', function ($purchaseorderlist) {
                if($purchaseorderlist->file != '')
                {
                    if(substr($purchaseorderlist->file, -3) == 'pdf'){
                        return $purchaseorderlist->purchase_order_list_code.'<p><a href="'.url('/').'/files/purchase-order/'.$purchaseorderlist->file.'" download="PO-'.date('Ymd').'" target="_blank"><img src="'.url('/').'/images/ico_pdf.svg" width="40"/></a></p>';
                    }else{
                        return $purchaseorderlist->purchase_order_list_code.'<p><a href="'.url('/').'/files/purchase-order/'.$purchaseorderlist->file.'" download="PO-'.date('Ymd').'" target="_blank"><img src="'.url('/').'/images/ico_document.svg" width="50"/></a></p>';
                    }
                }else{
                    return $purchaseorderlist->purchase_order_list_code;
                }
            })
            ->addColumn('quote_list_code', function ($purchaseorderlist) {
                return $purchaseorderlist->quote_list_code.'<p><a href="quote-list/'.Crypt::encrypt($purchaseorderlist->quote_id).'/generate-pdf" target="_blank"><img src="'.url('/').'/images/ico_pdf.svg" width="40"/></a></p>';

            })
            ->addColumn('fix_invoice', function ($purchaseorderlist) {
                if($purchaseorderlist->fix_invoice == 0 && $purchaseorderlist->fix_do == 0)
                {
                    return '<span class="label label-warning">-</span>';
                }
                elseif($purchaseorderlist->fix_invoice == 1 && $purchaseorderlist->fix_do == 0)
                {
                    return '<p><span class="label label-primary">Invoice already created</span></p>';
                }
                elseif($purchaseorderlist->fix_invoice == 1 && $purchaseorderlist->fix_do == 1)
                {
                    return '<p><span class="label label-primary">Invoice already created</span></p>
                            <p><span class="label label-success">DO already created</span></p>';
                }
            })
            ->addColumn('created_at', function ($purchaseorderlist) {
                return Carbon::parse($purchaseorderlist->created_at)->format('Y-m-d');
            })
            ->rawColumns(['action', 'purchase_order_list_code', 'quote_list_code', 'fix_invoice', 'created_at'])
            ->make(true);
    }

    public function getQuote(Request $request)
    {
        $po_id = $request->po_list_id;
        $quote_id = $request->quote_list_code_id;
        $purchase_order_list_code = $request->purchase_order_list_code;
        $date_out = $request->date_out;
        $note = $request->note;


        $requestQuote['fix_data'] = 1;
        $quote_data = QuoteLists::findOrFail($quote_id);
        $quote_data->update($requestQuote);
        
        
        $POLists = PurchaseOrderLists::findOrFail($po_id);
        $quoteId = $POLists->getQuote($quote_id);

        $requestList['quote_list_code_id']       = $quoteId->id;
        $requestList['quote_prospect_sales_id']  = $quoteId->prospect_sales_id;
        $requestList['quote_sales_person_id']    = $quoteId->sales_person_id;
        $requestList['purchase_order_list_code'] = $purchase_order_list_code;
        $requestList['po_prospect_sales_id']     = $quoteId->sales_person_id;
        $requestList['gross_price']              = $quoteId->gross_price;
        $requestList['qty']                      = $quoteId->qty;
        $requestList['total_price']              = $quoteId->total_price;
        $requestList['total_diskon']             = $quoteId->total_diskon;
        $requestList['choose_tax']               = $quoteId->choose_tax;
        $requestList['tax']                      = $quoteId->tax;
        $requestList['tax_price']                = $quoteId->tax_price;
        $requestList['after_tax']                 = $quoteId->after_tax;
        // $requestList['date_out'] = $date_out;
        // $requestList['note'] = $note;

        $POLists->update($requestList);



        $quoteDetailId = $POLists->getQuoteDetail($quote_id);

        $old_data = PurchaseOrderListsDetail::where('purchase_order_list_id', $po_id)->get();

        if(count($old_data) > 0)
        {
            PurchaseOrderListsDetail::where('purchase_order_list_id', $po_id)->delete();

        }

        foreach($quoteDetailId as $quoteDetail){
            $requestDetail['purchase_order_list_id'] = $po_id;
            $requestDetail['prospect_sales_id'] = $quoteDetail->prospect_sales_id;
            $requestDetail['product_id'] = $quoteDetail->product_id;
            $requestDetail['product_name'] = $quoteDetail->product_name;
            $requestDetail['qty'] = $quoteDetail->qty;
            $requestDetail['price'] = $quoteDetail->price;
            $requestDetail['gross_price'] = $quoteDetail->gross_price;
            $requestDetail['diskon'] = $quoteDetail->diskon;
            $requestDetail['diskon_nominal'] = $quoteDetail->diskon_nominal;
            $requestDetail['net_price'] = $quoteDetail->net_price;

            
            PurchaseOrderListsDetail::create($requestDetail);

            Alert::success('Your data already created !', 'Success !');
        }
        
        $PurchaseOrderListsDetail = new PurchaseOrderListsDetail();
        $data_detail = $PurchaseOrderListsDetail->getList($po_id, $quoteId->prospect_sales_id);

        $requestData['qty'] = $data_detail->sum('qty');
        $requestData['gross_price'] = $data_detail->sum('gross_price');
        $requestData['total_diskon'] = $data_detail->sum('diskon_nominal');
        $requestData['total_price'] = $data_detail->sum('net_price');

        $PurchaseOrderLists = PurchaseOrderLists::findOrFail($po_id);
        $PurchaseOrderLists->update($requestData);
        
    }

    public function getDataDetail(Request $request)
    {
        $quote_prospect_sales_id = $request->quote_prospect_sales_id;
        $product_id = $request->product_id;
        $po_id = $request->po_list_id;

        $PurchaseOrderListsDetail = new PurchaseOrderListsDetail();
        $data_detail = $PurchaseOrderListsDetail->getList($po_id, $quote_prospect_sales_id);
        foreach ($data_detail as $key) {
            $product_id_array[] = $key->product_id;
        }
        
        if(count($data_detail) > 0){
        $data = '';
        $no = 1;
            foreach ($data_detail as $detail => $index)
            {
                if(count($data_detail) > 0){
                    $selectlist = $PurchaseOrderListsDetail->getForSelectDetail($quote_prospect_sales_id, $product_id_array, $index->product_id);
                }else{
                    $selectlist = $PurchaseOrderListsDetail->getForSelectDetail($quote_prospect_sales_id);
                }
                $data .= '
                        <tr class="table-secondary">
                            <td>'. $no .'</td>
                            <td><select name="product_id" id="product_id_'.$no.'" data-id1="'. $index->id .'" class="form-control">';
                            foreach($selectlist as $list){
                                if($list->id == $index->product_id){
                                    $data .='<option value="'. $list->id.'" selected="selected">'. $list->name.'</option>';
                                }else{
                                    $data .='<option value="'. $list->id.'">'. $list->name.'</option>';
                                }
                            }
                            $data .='</select>
                                <input type="hidden" id="id_detail_'.$no.'" name="id" value="'.$index->id.'">
                                </td>
                            <td><input type="text" class="form-control qty_'.$no.'" name="qty" data-id2="'. $index->id .'" value="'.$index->qty.'" onkeyup="this.value = qty(this.value, 1, 9999)" style="width:60px;"></td>
                            <td class="text-right price_'.$no.'" data-id3="'. $index->id .'">'.number_format($index->price, 2).'</td>
                            <td data-id4="'. $index->id .'" class="text-right gross_price_'.$no.'">'.number_format($index->gross_price, 2).'</td>
                            <td><input type="text" class="form-control diskon_'.$no.'" name="diskon" data-id5="'. $index->id .'" value="'.$index->diskon.'" onkeyup="this.value = diskon(this.value, 0, 100)" maxlength="3" style="width:60px;"></td>
                            <td><input type="text" class="form-control text-right diskon_nominal_'.$no.'" name="diskon_nominal" data-id6="'. $index->id .'" value="'. $index->diskon_nominal .'" onkeyup="this.value = diskon_nominal(this.value, 0, 1000000000)"></td>
                            <td class="text-right net_price_'.$no.'" data-id7="'. $index->id .'" contenteditable="false">'.number_format($index->net_price, 2).'</td>

                            <td align="center">
                                <p><button type="button" name="btn_update" id="btn_update_'.$no.'" class="btn btn-success btn-xs" data-id9="'. $index->id .'"><i class="fa fa-edit"></i> Update </button></p>
                                <p><button type="button" name="btn_delete" id="btn_delete_'.$no.'" class="btn btn-danger btn-xs" data-id8="'. $index->id .'"><i class="fa fa-trash-o"></i> Delete </button></p>
                            </td>
                        </tr>
                    ';
                    $no++;
            }
        }
        
        return $data;
    }

    public function getProduct(Request $request)
    {

        $quote_prospect_sales_id = $request->quote_prospect_sales_id;
        $product_id = $request->product_id;
        $po_id = $request->po_list_id;

        $PurchaseOrderListsDetail = new PurchaseOrderListsDetail();
        $data_detail = $PurchaseOrderListsDetail->getList($po_id, $quote_prospect_sales_id);
        foreach ($data_detail as $key) {
            $product_id_array[] = $key->product_id;
        }
        if(count($data_detail) > 0){
            $selectlist = $PurchaseOrderListsDetail->getForSelect($quote_prospect_sales_id, $product_id_array);
        }else{
            $selectlist = $PurchaseOrderListsDetail->getForSelect($quote_prospect_sales_id);
        }
        $getProduct = $PurchaseOrderListsDetail->getProduct($product_id);
        $diskon_nominal = ($getProduct->diskon / 100) * $getProduct->price;
        $net_price = $getProduct->price - $diskon_nominal;
        $data = "";
        $data .='<tr class="table-secondary">
                <td></td>
                <td>
                    <select name="product_id" id="product_idd" class="form-control">
                    <option value="">-- Choose Product --</option>';
                    foreach($selectlist as $list){
                        if($list->id == $product_id){
                            $data .='<option value="'. $list->id.'" selected="selected">'. $list->name.'</option>';
                        }else{
                            $data .='<option value="'. $list->id.'">'. $list->name.'</option>';
                        }
                    }
        $data .='</select><input type="hidden" id="product_name" name="product_name" value="'.$getProduct->name.'"></td>
                <td><input type="text" class="form-control" name="qty" id="qty" value="1" maxlength="5" onkeyup="this.value = qty(this.value, 1, 9999)" style="width:60px;"></td>
                <td class="text-right"><input type="hidden" id="price" name="price" value="'.$getProduct->price.'">'. number_format($getProduct->price, 2) .'</td>
                <td id="gross_price" class="text-right" contenteditable="false">'. number_format($getProduct->price, 2) .'</td>
                <td><input type="text" class="form-control" name="diskon" id="diskon" value="'. $getProduct->diskon .'" maxlength="3" onkeyup="this.value = diskon(this.value, 0, 100)" style="width:60px;"></td>
                <td><input type="text" class="form-control text-right" name="diskon_nominal" id="diskon_nominal" value="'. $diskon_nominal .'" maxlength="10" onkeyup="this.value = diskon_nominal(this.value, 0, 1000000000)"></td>
                <td id="net_price" class="text-right" contenteditable="false">'.number_format($net_price, 2).'</td>
                <td align="center"><button type="button" name="btn_add" id="btn_add" class="btn btn-info btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add</button></td>
            </tr>
            <tr>
                <th></th>
                <th class="text-right"></th>
                <th class="text-center" >'. $data_detail->sum('qty') .'</th>
                <th></th>
                <th class="text-right" >'. number_format($data_detail->sum('gross_price'), 2) .'</th>
                <th></th>
                <th class="text-right" >'. number_format($data_detail->sum('diskon_nominal'), 2) .'</th>
                <th class="text-right" >'. number_format($data_detail->sum('net_price'), 2) .'</th>
                <th></th>
            </tr>
        ';
        return $data;
    }

    public function NewRows(Request $request)
    {

        $quote_prospect_sales_id = $request->quote_prospect_sales_id;
        $po_id = $request->po_list_id;

        $PurchaseOrderListsDetail = new PurchaseOrderListsDetail();
        $data_detail = $PurchaseOrderListsDetail->getList($po_id, $quote_prospect_sales_id);
        foreach ($data_detail as $key) {
            $product_id_array[] = $key->product_id;
        }
        if(count($data_detail) > 0){
            $selectlist = $PurchaseOrderListsDetail->getForSelect($quote_prospect_sales_id, $product_id_array);
        }else{
            $selectlist = $PurchaseOrderListsDetail->getForSelect($quote_prospect_sales_id);
        }
        $data = "";
        $data .='<tr class="table-secondary">
                <td></td>
                <td>
                    <select name="product_id" id="product_idd" class="form-control">
                    <option value="">-- Choose Product --</option>';
                    foreach($selectlist as $list){
                        $data .='<option value="'. $list->id.'">'. $list->name.'</option>';
                    }
        $data .='</select></td>
                <td><input type="text" class="form-control" name="qty" id="qty" value="1" maxlength="5" onkeyup="this.value = qty(this.value, 1, 9999)" style="width:60px;"></td>
                <td class="text-right" ><input type="hidden" id="price" name="price" value=""></td>
                <td id="gross_price" class="text-right" contenteditable="false"></td>
                <td><input type="text" class="form-control" name="diskon" id="diskon" value="" maxlength="3" onkeyup="this.value = diskon(this.value, 0, 100)" style="width:60px;"></td>
                <td><input type="text" class="form-control text-right" name="diskon_nominal" id="diskon_nominal" value="" maxlength="10" onkeyup="this.value = diskon_nominal(this.value, 0, 1000000000)"></td>
                <td id="net_price" contenteditable="false"></td>
                <td align="center"><button type="button" name="btn_add" id="btn_add" class="btn btn-info btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add</button></td></td>
            </tr>
            <tr>
                <th></th>
                <th class="text-right"></th>
                <th class="text-center">'. $data_detail->sum('qty') .'</th>
                <th></th>
                <th class="text-right">'. number_format($data_detail->sum('gross_price'), 2) .'</th>
                <th></th>
                <th class="text-right">'. number_format($data_detail->sum('diskon_nominal'), 2) .'</th>
                <th class="text-right">'. number_format($data_detail->sum('net_price'), 2) .'</th>
                <th></th>
            </tr>
        ';
        return $data;
    }

    public function RefreshData(Request $request)
    {
        $quote_prospect_sales_id = $request->quote_prospect_sales_id;
        $po_id = $request->po_list_id;

        $PurchaseOrderListsDetail = new PurchaseOrderListsDetail();
        $data_detail = $PurchaseOrderListsDetail->getList($po_id, $quote_prospect_sales_id);

        $data = "";

        $data .='<div class="col-sm-3 col-xs-5">
                    <hr>
                    <label class="form-label" style="font-weight: 100; color: #aaa;">Total QTY</label>&nbsp;:&nbsp;
                    <h4><span class="label label-default">'. $data_detail->sum('qty') .'</span></h4>
                    <input type="hidden" name="qty_sum" id="qty_sum" value="'. $data_detail->sum('qty') .'">
                </div>
                <div class="col-sm-3 col-xs-7">
                    <hr>
                    <label class="form-label" style="font-weight: 100; color: #aaa;">Total Gross Price</label>&nbsp;:&nbsp;
                    <h4><span class="label label-default">Rp. '. number_format($data_detail->sum('gross_price'), 2) .'</span></h4>
                    <input type="hidden" name="gross_price" id="gross_price_sum" value="'. $data_detail->sum('gross_price') .'">
                </div>
                <div class="col-sm-3 col-xs-5">
                    <hr>
                    <label class="form-label" style="font-weight: 100; color: #aaa;">Total Discount</label>&nbsp;:&nbsp;
                    <h4><span class="label label-default">Rp. '. number_format($data_detail->sum('diskon_nominal'), 2) .'</span></h4>
                    <input type="hidden" name="total_diskon" id="diskon_total_sum" value="'. $data_detail->sum('diskon_nominal') .'">
                </div>
                <div class="col-sm-3 col-xs-7">
                    <hr>
                    <label class="form-label" style="font-weight: 100; color: #aaa;">Total Price</label>&nbsp;:&nbsp;
                    <h4><span class="label label-default">Rp. '. number_format($data_detail->sum('net_price'), 2) .'</span></h4>
                    <input type="hidden" name="total_price" id="total_price_sum" value="'. $data_detail->sum('net_price') .'">
                </div>';
        return $data;
    }

    public function do_save_detail(Request $request)
    {
       $this->validate($request, [
            'product_id' => 'required',
            'qty' => 'required',
        ]);
        $PurchaseOrderListsDetail = new PurchaseOrderListsDetail();

        $gross_price = $request->qty * $request->price;
        $net_price1 = str_replace(".00","",$request->net_price);
        $net_price = str_replace(",","",$request->net_price);

        $po_id = $request->po_list_id;
        $quote_prospect_sales_id = $request->quote_prospect_sales_id;

        $requestData = array(
            'purchase_order_list_id' => $po_id,
            'prospect_sales_id' => $quote_prospect_sales_id,
            'product_id' => $request->product_id,
            'product_name' => $request->product_name,
            'qty' => $request->qty,
            'price' => $request->price,
            'gross_price' => $gross_price,
            'diskon' => $request->diskon,
            'diskon_nominal' => $request->diskon_nominal,
            'net_price' => $net_price,
            'created_at'=> date('Y-m-d h:i:s')
        );

        $data = PurchaseOrderListsDetail::create($requestData);

        $data_detail = $PurchaseOrderListsDetail->getList($po_id, $quote_prospect_sales_id);

        $requestData['purchase_order_list_code'] = $request->purchase_order_list_code;
        $requestData['po_prospect_sales_id'] = $request->po_prospect_sales_id;
        $requestData['date_out'] = $request->date_out;
        $requestData['note'] = $request->note;
        $requestData['qty'] = $data_detail->sum('qty');
        $requestData['gross_price'] = $data_detail->sum('gross_price');
        $requestData['total_diskon'] = $data_detail->sum('diskon_nominal');
        $requestData['total_price'] = $data_detail->sum('net_price');

        $PurchaseOrderLists = PurchaseOrderLists::findOrFail($po_id);
        $PurchaseOrderLists->update($requestData);
        Alert::success('Your data already Created !', 'Success !');

        return response()->json(['data' => $data, 'success' => true, 'message' => 'success'], 200);
    }
    public function do_update_product($id, Request $request)
    {
        
        $this->validate($request, [
            'product_id' => 'required',
        ]);
        $product_id = $request->product_id;
        $po_list_id = $request->po_list_id;

        $PurchaseOrderListsDetail = new PurchaseOrderListsDetail();
        $getProduct = $PurchaseOrderListsDetail->getProduct($product_id);
        $qty = PurchaseOrderListsDetail::findOrFail($id);
        $gross_price = $qty->qty * $getProduct->price;
        $diskon_nominal = ($getProduct->diskon/100) * $gross_price;
        $subtotal = $gross_price - $diskon_nominal;


        $requestData = array(
                'product_id' => $product_id,
                'price' => $getProduct->price,
                'gross_price' => $gross_price,
                'diskon' => $getProduct->diskon,
                'diskon_nominal' => $diskon_nominal,
                'net_price' => $subtotal
        );
        $PurchaseOrderListsDetail = PurchaseOrderListsDetail::findOrFail($id);
        $PurchaseOrderListsDetail->update($requestData);

        return $this->getDataDetail($request);
    }

    public function do_btn_update($id, Request $request)
    {
       $this->validate($request, [
            'product_id' => 'required',
            'qty' => 'required',
        ]);

        $po_id = $request->po_list_id;
        $quote_prospect_sales_id = $request->quote_prospect_sales_id;
       
        $PurchaseOrderListsDetail = new PurchaseOrderListsDetail();
        $getProduct = $PurchaseOrderListsDetail->getProduct($request->product_id);

        $requestData = array(
            'purchase_order_list_id' => $po_id,
            'prospect_sales_id' => $quote_prospect_sales_id,
            'product_id' => $request->product_id,
            'product_name' => $getProduct->name,
            'qty' => $request->qty,
            'price' => $request->price,
            'gross_price' => $request->gross_price,
            'diskon' => $request->diskon,
            'diskon_nominal' => $request->diskon_nominal,
            'net_price' => $request->net_price,
            'created_at'=> date('Y-m-d h:i:s')
        );

        $PurchaseOrderListsDetail = PurchaseOrderListsDetail::findOrFail($id);
        $PurchaseOrderListsDetail->update($requestData);

        $data_detail = $PurchaseOrderListsDetail->getList($po_id, $quote_prospect_sales_id);

        $requestData['purchase_order_list_code'] = $request->purchase_order_list_code;
        $requestData['po_prospect_sales_id'] = $request->po_prospect_sales_id;
        $requestData['date_out'] = $request->date_out;
        $requestData['note'] = $request->note;
        $requestData['qty'] = $data_detail->sum('qty');
        $requestData['gross_price'] = $data_detail->sum('gross_price');
        $requestData['total_diskon'] = $data_detail->sum('diskon_nominal');
        $requestData['total_price'] = $data_detail->sum('net_price');

        $PurchaseOrderLists = PurchaseOrderLists::findOrFail($po_id);
        $PurchaseOrderLists->update($requestData);

        return $this->getDataDetail($request);
    }

    public function delete_data_detail($id, Request $request)
    {
        PurchaseOrderListsDetail::destroy($id);

        $po_id = $request->po_list_id;
        $quote_prospect_sales_id = $request->quote_prospect_sales_id;

        $PurchaseOrderListsDetail = new PurchaseOrderListsDetail();
        $data_detail = $PurchaseOrderListsDetail->getList($po_id, $quote_prospect_sales_id);

        $requestData['qty'] = $data_detail->sum('qty');
        $requestData['gross_price'] = $data_detail->sum('gross_price');
        $requestData['total_diskon'] = $data_detail->sum('diskon_nominal');
        $requestData['total_price'] = $data_detail->sum('net_price');

        $PurchaseOrderLists = PurchaseOrderLists::findOrFail($po_id);
        $PurchaseOrderLists->update($requestData);

        Alert::success('Your data already deleted !', 'Success !');

        // return $this->getDataDetail($request);
    }


}
