<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Brand;
use App\ProductItem;
use App\QuoteLists;
use App\ReQuote;
use App\Sales;
use App\ProspectSales;
use App\QuoteTemplate;
use App\TermCondition;
use App\QuoteListsDetail;
use App\ProspectSalesHistory;
use App\User;
use Illuminate\Http\Request;
use Session;
use Auth;
use DB;
use Alert;
use Crypt;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

class QuoteListController extends Controller
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
        $allQuoteCount = QuoteLists::where('is_active', 1)->count();
        $newQuoteCount = QuoteLists::where('is_active', 1)->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->count();
        return view('admin.quote.index', compact('allQuoteCount', 'newQuoteCount'));
    }

    public function createNewQuote(Request $request)
    {
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $requestData = $request->all();
            $requestData['is_active'] = 0;
            $id = QuoteLists::create($requestData);

            return redirect('admin/quote-list/' . Crypt::encrypt($id->id) . '/create');
        }else{
            Alert::info('No Access !', 'Attention !');
            return redirect('admin/quote-list');
        }
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id_encrypt)
    {
        $id = Crypt::decrypt($id_encrypt);
        $quotelist            = QuoteLists::findOrFail($id);

        $QuoteLists           = new QuoteLists();
        $prospectData         = $QuoteLists->prospectData($quotelist->prospect_sales_id);
        $quotecode            = $QuoteLists->getForQuote($id);

        $prospectSales        = new ProspectSales();
        $prospect_sales       = $prospectSales->getForSelect();

        $ProspectSalesHistory = new ProspectSalesHistory();
        $salesHistory         = $ProspectSalesHistory->getSalesProspect($quotelist->prospect_sales_id); 

        $QuoteTemplate        = new QuoteTemplate();
        $getTemplate          = $QuoteTemplate->getList();

        $TermCondition        = new TermCondition();
        $getTerm              = $TermCondition->getList();

        $QuoteListsDetail     = new QuoteListsDetail();
        $list_detail          = $QuoteListsDetail->getList($id, $quotelist->prospect_sales_id);
        $selectlist           = $QuoteListsDetail->getForSelect($quotelist->prospect_sales_id);

        $total_diskon = ($quotelist->total_diskon / $quotelist->gross_price) * 100;

        return view('admin.quote.show', compact('quotelist', 'quotecode', 'prospectData', 'getTemplate', 'getTerm', 'prospect_sales','list_detail', 'selectlist', 'salesHistory', 'total_diskon'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */

    public function createQuote($id_encrypt)
    {
        $id = Crypt::decrypt($id_encrypt);
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $quotelist = QuoteLists::findOrFail($id);
            if ($quotelist->is_active == 0) {
                $QuoteLists = new QuoteLists();
                $quotecode = $QuoteLists->getForQuote($id);

                $prospectSales = new ProspectSales();
                $prospect_sales = $prospectSales->getForSelect();

                $ProspectSalesHistory = new ProspectSalesHistory();
                $salesHistory = $ProspectSalesHistory->getSalesProspect($quotelist->prospect_sales_id);

                $QuoteTemplate = new QuoteTemplate();
                $getTemplate = $QuoteTemplate->getList();

                $TermCondition = new TermCondition();
                $getTerm = $TermCondition->getList();

                $QuoteListsDetail = new QuoteListsDetail();
                $list_detail = $QuoteListsDetail->getList($id, $quotelist->prospect_sales_id);
                $selectlist = $QuoteListsDetail->getForSelect($quotelist->prospect_sales_id);

                return view('admin.quote.create_quote', compact('quotelist', 'quotecode', 'getTemplate', 'getTerm', 'prospect_sales', 'list_detail', 'selectlist', 'salesHistory'));
            } else {
                Alert::info('No Access !', 'Attention !');
                return redirect('admin/quote-list');
            }
        }else{
            Alert::info('No Access !', 'Attention !');
            return redirect('admin/quote-list');
        }
    }

    public function requote($id_encrypt)
    {
        $id = Crypt::decrypt($id_encrypt);
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $quotelist = QuoteLists::findOrFail($id);
            if($quotelist->requote_list_code != ''){
                $QuoteLists           = new QuoteLists();
                $quotecode            = $QuoteLists->getForQuote($id);
                $getquote             = $QuoteLists->getQuote($quotelist->requote_list_code);

                $prospectSales        = new ProspectSales();
                $prospect_sales       = $prospectSales->getForSelect();

                $ProspectSalesHistory = new ProspectSalesHistory();
                $salesHistory         = $ProspectSalesHistory->getSalesProspect($quotelist->prospect_sales_id);

                $QuoteTemplate        = new QuoteTemplate;
                $getTemplate          = $QuoteTemplate->getList();

                $TermCondition        = new TermCondition();
                $getTerm              = $TermCondition->getList();

                $QuoteListsDetail     = new QuoteListsDetail();
                $list_detail          = $QuoteListsDetail->getList($id, $quotelist->prospect_sales_id);
                $selectlist           = $QuoteListsDetail->getForSelect($quotelist->prospect_sales_id);

                return view('admin.quote.requote', compact('quotelist', 'quotecode', 'getTemplate', 'getTerm', 'getquote', 'prospect_sales','list_detail', 'selectlist', 'salesHistory'));
            }else{
                Alert::info('Data Requote Not Found !', 'Attention !');
                return redirect('admin/quote-list');
            }
        }else{
            Alert::info('No Access !', 'Attention !');
            return redirect('admin/quote-list');
        }
    }

    public function do_create($id, Request $request)
    {
        $this->validate($request, [
            'prospect_sales_id' => 'required',
            'quote_list_code'   => 'required|unique:quote_lists,quote_list_code,' . $id,
            'date_out'          => 'required',
            'quote_template_id' => 'required',
        ]);
        $requestData              = $request->all();
        $requestData['is_active'] = '1';
        $requestData['qty']       = $request->qty_sum;

        $uploadfile = QuoteLists::findOrFail($id);
        $uploadfile->update($requestData);
        Alert::success('Your data already updated !', 'Success !');
        return redirect('admin/quote-list/');
    }

    public function createRequote($id_encrypt, Request $request)
    {
        $id = Crypt::decrypt($id_encrypt);
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $quotelist = QuoteLists::findOrFail($id);

            $requote['file']              = $quotelist->file;
            $requote['prospect_sales_id'] = $quotelist->prospect_sales_id;
            $requote['sales_person_id']   = $quotelist->sales_person_id;
            $requote['quote_list_code']   = '';
            $requote['requote_list_code'] = $quotelist->id;
            $requote['quote_template_id'] = $quotelist->quote_template_id;
            $requote['term_condition_id'] = $quotelist->term_condition_id;
            $requote['qty']               = $quotelist->qty;
            $requote['gross_price']       = $quotelist->gross_price;
            $requote['total_diskon']      = $quotelist->total_diskon;
            $requote['total_price']       = $quotelist->total_price;
            $requote['choose_tax']        = $quotelist->choose_tax;
            $requote['tax']               = $quotelist->tax;
            $requote['tax_price']         = $quotelist->tax_price;
            $requote['after_tax']         = $quotelist->after_tax;
            $requote['is_active']         = '0';
            $requote['created_at']        = date('Y-m-d H:i:s');
            $requote['updated_at']        = date('Y-m-d H:i:s');

            $code_id          = QuoteLists::create($requote);
            $QuoteListsDetail = new QuoteListsDetail();
            $requoteDetailId  = $QuoteListsDetail->getQuoteDetail($id);

            foreach($requoteDetailId as $requoteDetail){
                $getProduct = $QuoteListsDetail->getProduct($requoteDetail->product_id);
                $requestDetail['quote_list_id']     = $code_id->id;
                $requestDetail['prospect_sales_id'] = $requoteDetail->prospect_sales_id;
                $requestDetail['product_id']        = $requoteDetail->product_id;
                $requestDetail['product_name']      = $requoteDetail->product_name;
                $requestDetail['product_image']     = $getProduct->image_name;
                $requestDetail['qty']               = $requoteDetail->qty;
                $requestDetail['price']             = $requoteDetail->price;
                $requestDetail['gross_price']       = $requoteDetail->gross_price;
                $requestDetail['diskon']            = $requoteDetail->diskon;
                $requestDetail['diskon_nominal']    = $requoteDetail->diskon_nominal;
                $requestDetail['net_price']         = $requoteDetail->net_price;

                QuoteListsDetail::create($requestDetail);
            }

            return redirect('admin/quote-list/'.Crypt::encrypt($code_id->id).'/requote');
        }else{
            Alert::info('No Access !', 'Attention !');
            return redirect('admin/quote-list');
        }
    }

    public function do_requote($id, Request $request)
    {
        $this->validate($request, [
            'prospect_sales_id' => 'required',
            'quote_list_code'   => 'required',
            'date_out'          => 'required',
            'quote_template_id' => 'required',
        ]);

        $quotelist = QuoteLists::findOrFail($request->id);

        $requote['prospect_sales_id'] = $request->prospect_sales_id;
        $requote['quote_list_code']   = $request->quote_list_code;
        $requote['requote_list_code'] = $request->requote_list_code;
        $requote['qty']               = $request->qty_sum;
        $requote['gross_price']       = $request->gross_price;
        $requote['total_diskon']      = $request->total_diskon;
        $requote['total_price']       = $request->total_price;
        $requote['date_out']          = $request->date_out;
        $requote['note']              = $request->note;
        $requote['is_active']         = '1';
        $requote['created_at']        = date('Y-m-d H:i:s');
        $requote['updated_at']        = date('Y-m-d H:i:s');

        $QuoteLists                   = QuoteLists::findOrFail($id);
        $QuoteLists->update($requote);
        
        Alert::success('Requote data already Created !', 'Success !');

        return redirect('admin/quote-list');
    }

    public function do_update_sum($id, Request $request)
    {
        
        $requestData = $request->all();
        $QuoteLists  = QuoteLists::findOrFail($id);
        $data        = $QuoteLists->update($requestData);
        return response()->json(['data' => $data, 'success' => true, 'message' => 'success'], 200);
    }

    public function do_transaction($id, Request $request)
    {
        
        $this->validate($request, [
            'prospect_sales_id' => 'required',
            'quote_list_code'   => 'required'
        ]);

        $datas = $request['files'];
        if($datas !="" ){
            $path = 'files/quote-list';
            $name = rand(10000,99999).'.'.$datas->getClientOriginalExtension();
            $datas->move($path,$name);

            $request['file'] = $name;
        }

        $requestData = $request->all();
        $uploadfile  = QuoteLists::findOrFail($id);
        $uploadfile->update($requestData);
        Alert::success('Your data already updated !', 'Success !');
        return redirect('admin/quote-list/'.$id.'/transaction');
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
        $quote = QuoteLists::findOrFail($id);
        // if($quote->file != '')
        // {
        //     $image_path = app_path("../../files/quote-list/".$quote->file."");
        //     unlink($image_path);
        // }
        
        QuoteLists::destroy($id);
        QuoteListsDetail::where('quote_list_id', $id)->delete();
        
//        Alert::success('Your data already deleted !', 'Success !');

        return redirect('admin/quote-list');
    }
    // report generate to pdf 
    public function generatePdf($id_encrypt)
    {
        $id = Crypt::decrypt($id_encrypt);
        $template             = QuoteLists::findOrFail($id);
        $QuoteLists           = new QuoteLists();
        $prospectData         = $QuoteLists->prospectData($template->prospect_sales_id);
        $salesQuote           = $QuoteLists->getSales($template->sales_person_id);

        $quotecode            = $QuoteLists->getForQuote($id);
        $prospectSales        = new ProspectSales();
        $prospect_sales       = $prospectSales->getForSelect();
        $getquote             = $QuoteLists->getQuote($template->requote_list_code);
        $users                = new User();
        $getsales             = $users->getSales($prospectData->sales_person_id);

        $ProspectSalesHistory = new ProspectSalesHistory();
        $salesHistory         = $ProspectSalesHistory->getSalesProspect($template->prospect_sales_id);

        $QuoteTemplate        = new QuoteTemplate;
        $getTemplate          = $QuoteTemplate->getList();
        $TermCondition        = new TermCondition();
        $getTerm              = $TermCondition->getList();


        $QuoteListsDetail     = new QuoteListsDetail();
        $list_detail          = $QuoteListsDetail->getList($id, $template->prospect_sales_id);
        $selectlist           = $QuoteListsDetail->getForSelect($template->prospect_sales_id);

        $total_diskon = ($template->total_diskon / $template->gross_price) * 100;

        $pdf = \PDF::loadView('admin.quote.template-pdf', compact('template', 'prospectData', 'getTemplate', 'quotecode', 'salesQuote', 'getsales', 'prospect_sales', 'getquote', 'getTerm', 'list_detail', 'selectlist', 'salesHistory', 'total_diskon'));
        return $pdf->stream();
    }
    // end report

    public function anyData()
    {
        DB::statement(DB::raw('set @rownum=0'));
        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            $quotelist = QuoteLists::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'quote_lists.id', 'quote_lists.quote_list_code', 'quote_lists.requote_list_code',
                'ql.quote_list_code as quote_code', 'quote_lists.file', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.is_active', 'quote_lists.created_at'])
                ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                ->groupBy('quote_lists.quote_list_code')
                ->where('quote_lists.is_active', '=', '1')
                ->orderby('quote_lists.id', 'DESC')
                ->get();
        }else{
            $quotelist = QuoteLists::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'quote_lists.id', 'quote_lists.quote_list_code', 'quote_lists.requote_list_code',
                'ql.quote_list_code as quote_code', 'quote_lists.file', 'quote_lists.fix_data as fix_po', 'po.fix_data as fix_invoice', 'il.fix_data as fix_do', 'quote_lists.is_active', 'quote_lists.created_at'])
                ->leftjoin('quote_lists as ql', 'ql.id', '=', 'quote_lists.requote_list_code')
                ->leftJoin('purchase_order_lists as po', 'po.quote_list_code_id', '=', 'quote_lists.id')
                ->leftJoin('invoice_lists as il', 'il.purchase_order_list_code_id', '=', 'po.id')
                ->groupBy('quote_lists.quote_list_code')
                ->where('quote_lists.sales_person_id', Auth::user()->id)
                ->where('quote_lists.is_active', '=', '1')
                ->orderby('quote_lists.id', 'DESC')
                ->get();
        }

        return Datatables::of($quotelist)
            ->addColumn('action', function ($quotelist) {

                    if(Auth::user()->role == User::ROLE_SUPERADMIN){
                        return '<p><a class="btn bg-grey btn-xs waves-effect" href="quote-list/view/'.Crypt::encrypt($quotelist->id).'"><i class="fa fa-eye"></i> View</a></p>
                                <p><a href="quote-list/'.Crypt::encrypt($quotelist->id).'/create_requote" class="btn bg-orange btn-xs waves-effect"><i class="fa fa-handshake-o"></i> Requote </a></p>
                                <p><a onclick="deleteData('.$quotelist->id.')" class="btn bg-red btn-xs waves-effect"><i class="fa fa-trash-o"></i> Delete </a></p>';
                    }elseif(Auth::user()->role == User::ROLE_ADMIN) {
                        return '<p><a class="btn bg-grey btn-xs waves-effect" href="quote-list/view/'.Crypt::encrypt($quotelist->id).'"><i class="fa fa-eye"></i> View</a></p>
                            <p><a href="quote-list/'.Crypt::encrypt($quotelist->id).'/create_requote" class="btn bg-orange btn-xs waves-effect"><i class="fa fa-handshake-o"></i> Requote </a></p>';
                    }else{
                        return '<p><a class="btn bg-grey btn-xs waves-effect" href="quote-list/view/'.Crypt::encrypt($quotelist->id).'"><i class="fa fa-eye"></i> View</a></p>';
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
            ->addColumn('quote_list_code', function ($quotelist) {
                return $quotelist->quote_list_code.'<p><a href="quote-list/'.Crypt::encrypt($quotelist->id).'/generate-pdf" target="_blank"><img src="'.url('/').'/images/ico_pdf.svg" width="40"/></a></p>';
                
            })
            ->addColumn('fix_po', function ($quotelist) {
                if($quotelist->fix_po == 0 && $quotelist->fix_invoice == 0 && $quotelist->fix_do == 0)
                {
                    return '<span class="label label-warning">-</span>';
                }
                elseif($quotelist->fix_po == 1 && $quotelist->fix_invoice == 0 && $quotelist->fix_do == 0)
                {
                    return '<span class="label label-info">PO already created</span>';
                }
                elseif($quotelist->fix_po == 1 && $quotelist->fix_invoice == 1 && $quotelist->fix_do == 0)
                {
                    return '<p><span class="label label-info">PO already created</span></p>
                            <p><span class="label label-primary">Invoice already created</span></p>';
                }
                elseif($quotelist->fix_po == 1 && $quotelist->fix_invoice == 1 && $quotelist->fix_do == 1)
                {
                    return '<p><span class="label label-info">PO already created</span></p>
                            <p><span class="label bg-primary">Invoice already created</span></p>
                            <p><span class="label label-success">DO already created</span></p>';
                }
            })
            ->addColumn('created_at', function ($quotelist) {
                return Carbon::parse($quotelist->created_at)->format('Y-m-d');
            })
            ->rawColumns(['action', 'quote_code','fix_po', 'quote_list_code', 'created_at'])
            ->make(true);
    }

    public function updateTemplate(Request $request)
    {
        $quote_list_id     = $request->quote_list_id;
        $quote_template_id = $request->quote_template_id;

        $template['quote_template_id'] = $quote_template_id;
        $templateQuote = QuoteLists::findOrFail($quote_list_id);
        $templateQuote->update($template);

        return response($template);

    }

    public function updateTermCondition(Request $request)
    {
        $quote_list_id     = $request->quote_list_id;
        $term_condition_id = $request->term_condition_id;

        $term['term_condition_id'] = $term_condition_id;
        $termQuote = QuoteLists::findOrFail($quote_list_id);
        $termQuote->update($term);

        return response($term);

    }

    public function getSales(Request $request)
    {

        $quote_list_id     = $request->quote_list_id;
        $prospect_sales_id = $request->prospect_sales_id;
        $quote_list_code   = $request->quote_list_code;
        $date_out          = $request->date_out;
        $note              = $request->note;

        $ProspectSales     = new ProspectSales();
        $prospectsalesid   = $ProspectSales->getSalesPersonByRequest($prospect_sales_id);

        $history['company_name']      = $prospectsalesid->company_name;
        $history['name_pic']          = $prospectsalesid->name_pic;
        $history['company_address']   = $prospectsalesid->company_address;
        $history['company_phone']     = $prospectsalesid->company_phone;

        $history['gross_price']       = 0;
        $history['qty']               = 0;
        $history['total_price']       = 0;
        $history['total_diskon']      = 0;
        $history['choose_tax']        = 0;
        $history['tax']               = 0;
        $history['tax_price']         = 0;
        $history['after_tax']         = 0;
        $history['prospect_sales_id'] = $prospect_sales_id;
        $history['sales_person_id']   = $prospectsalesid->user_id;
        $history['quote_list_code']   = $quote_list_code;
        $history['date_out']          = $date_out;


        $old_data = QuoteListsDetail::where('quote_list_id', $quote_list_id)->get();

        $Quotelist = QuoteLists::findOrFail($quote_list_id);

        if(count($old_data) > 0)
        {
            QuoteListsDetail::where('quote_list_id', $quote_list_id)->delete();

        }
        $Quotelist->update($history);

        Alert::success('Your data already updated !', 'Success !');
    }

    public function getDataDetail(Request $request)
    {
        $prospect_sales_id = $request->prospect_sales_id;
        $product_id        = $request->product_id;
        $quote_list_id     = $request->quote_list_id;

        $QuoteListsDetail  = new QuoteListsDetail();
        $data_detail       = $QuoteListsDetail->getList($quote_list_id , $prospect_sales_id);

        foreach ($data_detail as $key) {
            $product_id_array[] = $key->product_id;
        }
        
        if(count($data_detail) > 0){
        $data = '';
        $no = 1;
            foreach ($data_detail as $detail => $index)
            {
                if(count($data_detail) > 0){
                    $selectlist = $QuoteListsDetail->getForSelectDetail($prospect_sales_id, $product_id_array, $index->product_id);
                }else{
                    $selectlist = $QuoteListsDetail->getForSelectDetail($prospect_sales_id);
                }
                $data .= '
                        <tr>
                            <td>'. $no .'</td>
                            <td><select name="product_id" id="product_id_'.$no.'" data-id1="'. $index->id .'" class="form-control show-tick" data-live-search ="true">';
                            foreach($selectlist as $list){
                                if($list->id == $index->product_id){
                                    $data .='<option value="'. $list->id.'" selected="selected">'. $list->name.'</option>';
                                }else{
                                    $data .='<option value="'. $list->id.'">'. $list->name.'</option>';
                                }
                            }
                            $data .='</select>
                                <input type="hidden" id="id_detail_'.$no.'" name="id" value="'.$index->id.'"><input type="hidden" name="brand" class="brand_'.$no.'" value="'.$index->brand.'">
                            </td>';
                            if($index->product_image == ''){
                                        $data .='<td>No Picture</td>';
                            }else {
                  $data .= '<td><img src="'.url('/').'/files/product/'.$index->product_image.'" alt="'.$index->product_name .'" width="120"></td>';
                            }
                   $data .='<td><input type="text" class="form-control qty_'.$no.'" name="qty" data-id2="'. $index->id .'" value="'.$index->qty.'" maxlength="5" style="width:60px;">
                            <input type="hidden" class="form-control quality_'.$no.'" name="quality" data-id2="'. $index->id .'" value="'.$index->quality.'" style="width:60px;"></td>
                            <td class="text-right price_'.$no.'" data-id3="'. $index->id .'">'.number_format($index->price, 2).'</td>
                            <td data-id4="'. $index->id .'" class="text-right gross_price_'.$no.'">'.number_format($index->gross_price, 2).'</td>
                            <td><input type="text" class="form-control diskon_'.$no.'" name="diskon" data-id5="'. $index->id .'" value="'.$index->diskon.'" maxlength="5" style="width:60px;"></td>
                            <td><input type="text" class="form-control text-right diskon_nominal_'.$no.'" name="diskon_nominal" data-id6="'. $index->id .'" value="'. $index->diskon_nominal .'"></td>
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

        $prospect_sales_id = $request->prospect_sales_id;
        $product_id        = $request->product_id;
        $quote_list_id     = $request->quote_list_id;

        $QuoteListsDetail  = new QuoteListsDetail();
        $data_detail       = $QuoteListsDetail->getList($quote_list_id, $prospect_sales_id);

        foreach ($data_detail as $key) {
            $product_id_array[] = $key->product_id;
        }

        if(count($data_detail) > 0){
            $selectlist = $QuoteListsDetail->getForSelect($prospect_sales_id, $product_id_array);
        }else{
            $selectlist = $QuoteListsDetail->getForSelect($prospect_sales_id);
        }

        $getProduct = $QuoteListsDetail->getProduct($product_id);
        if($getProduct->diskon == 0 || $getProduct->diskon == ''){
            $diskon = 0;
        }else{
            $diskon = $getProduct->diskon;
        }

        $diskon_nominal = ($getProduct->diskon / 100) * $getProduct->price;
        $net_price = $getProduct->price - $diskon_nominal;
        $data = "";
         if(count($selectlist) == 0){

        }else{
            $data .='<tr class="table-secondary">
                    <td></td>
                    <td>
                        <select name="product_id" id="product_idd" class="form-control show-tick" data-live-search ="true">
                        <option value="">-- Choose Product --</option>';
                        foreach($selectlist as $list){
                            if($list->id == $product_id){
                                $data .='<option value="'. $list->id.'" selected="selected">'. $list->name.'</option>';
                            }else{
                                $data .='<option value="'. $list->id.'">'. $list->name.'</option>';
                            }
                        }
            $data .='</select><input type="hidden" id="product_name" name="product_name" value="'.$getProduct->name.'"><input type="hidden" name="brand" id="brand" value="'.$getProduct->brand.'"></td>';
                    if($getProduct->image_name == ''){
                                $data .='<td>No Picture</td>';
                    }else {
          $data .= '<td><img src="'.url('/').'/files/product/'.$getProduct->image_name.'" alt="'.$getProduct->image_name .'" width="120"></td>';
                    }
             $data .='<td><input type="text" class="form-control" name="qty" id="qty" value="1" maxlength="5" style="width:60px;">
                    <input type="hidden" class="form-control" name="quality" id="quality" value="'.$getProduct->quality.'" style="width:60px;"></td>
                    <td class="text-right"><input type="hidden" id="price" name="price" value="'.$getProduct->price.'">'. number_format($getProduct->price, 2) .'</td>
                    <td id="gross_price" class="text-right" contenteditable="false">'. number_format($getProduct->price, 2) .'</td>
                    <td><input type="text" class="form-control" name="diskon" id="diskon" value="'. $diskon .'" maxlength="5" style="width:60px;"></td>
                    <td><input type="text" class="form-control text-right" name="diskon_nominal" id="diskon_nominal" value="'. $diskon_nominal .'" maxlength="10"></td>
                    <td id="net_price" class="text-right" contenteditable="false">'.number_format($net_price, 2).'</td>
                    <td align="center"><button type="button" name="btn_add" id="btn_add" class="btn btn-info btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add</button></td></td>
                </tr>';
            }
            $data .='<tr>
                    <th></th>
                    <th class="text-right"></th>
                    <th class="text-right"></th>
                    <th class="text-center" >'. $data_detail->sum('qty') .'</th>
                    <th></th>
                    <th class="text-right" >'. number_format($data_detail->sum('gross_price'), 2) .'</th>
                    <th></th>
                    <th class="text-right" >'. number_format($data_detail->sum('diskon_nominal'), 2) .'</th>
                    <th class="text-right" >'. number_format($data_detail->sum('net_price'), 2) .'</th>
                    <th></th>
                </tr>';
        return $data;
    }
    //add rows quote detail
    public function NewRows(Request $request)
    {

        $prospect_sales_id = $request->prospect_sales_id;
        $quote_list_id     = $request->quote_list_id;

        $QuoteListsDetail  = new QuoteListsDetail();
        $data_detail       = $QuoteListsDetail->getList($quote_list_id, $prospect_sales_id);

        foreach ($data_detail as $key) {
            $product_id_array[] = $key->product_id;
        }

        if(count($data_detail) > 0){
            $selectlist = $QuoteListsDetail->getForSelect($prospect_sales_id, $product_id_array);
        }else{
            $selectlist = $QuoteListsDetail->getForSelect($prospect_sales_id);
        }

        $data = "";
        if(count($selectlist) == 0){

        }else{
            $data .='<tr class="table-secondary">
                    <td></td>
                    <td>
                        <select name="product_id" id="product_idd" class="form-control show-tick" data-live-search ="true">
                        <option value="">-- Choose Product --</option>';
                        foreach($selectlist as $list){
                            $data .='<option value="'. $list->id.'">'. $list->name.'</option>';
                        }
            $data .='</select></td>
                    <td>Choose Product</td>
                    <td><input type="text" class="form-control" name="qty" id="qty" value="1" maxlength="5" style="width:60px;"></td>
                    <td class="text-right" ><input type="hidden" id="price" name="price" value=""></td>
                    <td id="gross_price" class="text-right" contenteditable="false"></td>
                    <td><input type="text" class="form-control" name="diskon" id="diskon" value="" maxlength="5" style="width:60px;"></td>
                    <td><input type="text" class="form-control text-right" name="diskon_nominal" id="diskon_nominal" value="" maxlength="10"></td>
                    <td id="net_price" contenteditable="false"></td>
                    <td align="center"><button type="button" name="btn_add" id="btn_add" class="btn btn-info btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add</button></td></td>
                </tr>';
        }

            $data .='<tr>
                    <th></th>
                    <th class="text-right"></th>
                    <th class="text-right"></th>
                    <th class="text-center">'. $data_detail->sum('qty') .'</th>
                    <th></th>
                    <th class="text-right">'. number_format($data_detail->sum('gross_price'), 2) .'</th>
                    <th></th>
                    <th class="text-right">'. number_format($data_detail->sum('diskon_nominal'), 2) .'</th>
                    <th class="text-right">'. number_format($data_detail->sum('net_price'), 2) .'</th>
                    <th></th>
                </tr>';
        return $data;
    }
    //show data quote detail
    public function RefreshData(Request $request)
    {
        $prospect_sales_id = $request->prospect_sales_id;
        $quote_list_id     = $request->quote_list_id;

        $QuoteListsDetail  = new QuoteListsDetail();
        $data_detail       = $QuoteListsDetail->getList($quote_list_id, $prospect_sales_id);

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
        $gross_price       = $request->qty * $request->price;
        $net_price1        = str_replace(".00","",$request->net_price);
        $net_price         = str_replace(",","",$request->net_price);

        $quote_id          = $request->quote_list_id;
        $prospect_sales_id = $request->prospect_sales_id;

        $QuoteListsDetail  = new QuoteListsDetail();
        $getProduct = $QuoteListsDetail->getProduct($request->product_id);

        $requestData = array(
            'quote_list_id'     => $quote_id,
            'prospect_sales_id' => $prospect_sales_id,
            'quote_list_code'   => $request->quote_list_code,
            'product_id'        => $request->product_id,
            'product_name'      => $request->product_name,
            'product_image'     => $getProduct->image_name,
            'brand'             => $request->brand,
            'qty'               => $request->qty,
            'quality'           => $request->quality,
            'price'             => $request->price,
            'gross_price'       => $gross_price,
            'diskon'            => $request->diskon,
            'diskon_nominal'    => $request->diskon_nominal,
            'net_price'         => $net_price,
            'created_at'        => date('Y-m-d h:i:s')
        );

        QuoteListsDetail::create($requestData);

        $data_detail = $QuoteListsDetail->getList($quote_id, $prospect_sales_id);
        $quotelist   = QuoteLists::findOrFail($quote_id);
        $tax_price   = ($quotelist->tax / 100) * $data_detail->sum('net_price');
        $after_tax   = $data_detail->sum('net_price') + $tax_price;

        if($quotelist->choose_tax == "" || $quotelist->choose_tax == 0){
            $requestData['purchase_order_list_code'] = $request->purchase_order_list_code;
            $requestData['date_out']                 = $request->date_out;
            $requestData['note']                     = $request->note;
            $requestData['quote_template_id']        = $request->quote_template_id;
            $requestData['term_condition_id']        = $request->term_condition_id;
            $requestData['brand']                    = $request->brand;
            $requestData['qty']                      = $data_detail->sum('qty');
            $requestData['gross_price']              = $data_detail->sum('gross_price');
            $requestData['total_diskon']             = $data_detail->sum('diskon_nominal');
            $requestData['total_price']              = $data_detail->sum('net_price');
            $requestData['choose_tax']               = 0;
            $requestData['tax']                      = 0;
            $requestData['tax_price']                = 0;
            $requestData['after_tax']                = $data_detail->sum('net_price');

            $QuoteLists = QuoteLists::findOrFail($quote_id);
            $QuoteLists->update($requestData);
        }else{
            $requestData['purchase_order_list_code'] = $request->purchase_order_list_code;
            $requestData['date_out']                 = $request->date_out;
            $requestData['note']                     = $request->note;
            $requestData['quote_template_id']        = $request->quote_template_id;
            $requestData['term_condition_id']        = $request->term_condition_id;
            $requestData['brand']                    = $request->brand;
            $requestData['qty']                      = $data_detail->sum('qty');
            $requestData['gross_price']              = $data_detail->sum('gross_price');
            $requestData['total_diskon']             = $data_detail->sum('diskon_nominal');
            $requestData['total_price']              = $data_detail->sum('net_price');
            $requestData['choose_tax']               = 1;
            $requestData['tax']                      = $quotelist->tax;
            $requestData['tax_price']                = $tax_price;
            $requestData['after_tax']                = $after_tax;

            $QuoteLists = QuoteLists::findOrFail($quote_id);
            $QuoteLists->update($requestData);
        }

        return $this->getDataDetail($request);
    }

    public function do_btn_update($id, Request $request)
    {
       $this->validate($request, [
            'product_id' => 'required',
            'qty' => 'required',
        ]);
       
        $quote_id          = $request->quote_list_id;
        $prospect_sales_id = $request->prospect_sales_id;
        $choose_tax        = $request->choose_tax;
        $tax               = $request->tax;

        $QuoteListsDetail  = new QuoteListsDetail();
        $getProduct        = $QuoteListsDetail->getProduct($request->product_id);

        $requestData = array(
            'quote_list_id'     => $quote_id,
            'prospect_sales_id' => $prospect_sales_id,
            'product_id'        => $request->product_id,
            'product_name'      => $getProduct->name,
            'brand'             => $request->brand,
            'qty'               => $request->qty,
            'price'             => $request->price,
            'gross_price'       => $request->gross_price,
            'diskon'            => $request->diskon,
            'diskon_nominal'    => $request->diskon_nominal,
            'net_price'         => $request->net_price,
            'created_at'        => date('Y-m-d h:i:s')
        );

        $QuoteListsDetail = QuoteListsDetail::findOrFail($id);
        $QuoteListsDetail->update($requestData);

        $data_detail      = $QuoteListsDetail->getList($quote_id, $prospect_sales_id);

        if($choose_tax == 0 || $choose_tax == ''){
            $requestData['quote_list_code']     = $request->quote_list_code;
            $requestData['date_out']            = $request->date_out;
            $requestData['note']                = $request->note;
            $requestData['quote_template_id']   = $request->quote_template_id;
            $requestData['term_condition_id']   = $request->term_condition_id;
            $requestData['brand']               = $request->brand;
            $requestData['qty']                 = $data_detail->sum('qty');
            $requestData['gross_price']         = $data_detail->sum('gross_price');
            $requestData['total_diskon']        = $data_detail->sum('diskon_nominal');
            $requestData['total_price']         = $data_detail->sum('net_price');

            $tax_parse['tax_price']             = 0;
            $tax_parse['after_tax']             = $data_detail->sum('net_price');
        }else{
            $tax_price                          = ($tax / 100) * $data_detail->sum('net_price');
            $after_tax                          = $data_detail->sum('net_price') + $tax_price;

            $requestData['quote_list_code']     = $request->quote_list_code;
            $requestData['date_out']            = $request->date_out;
            $requestData['note']                = $request->note;
            $requestData['quote_template_id']   = $request->quote_template_id;
            $requestData['term_condition_id']   = $request->term_condition_id;
            $requestData['brand']               = $request->brand;
            $requestData['qty']                 = $data_detail->sum('qty');
            $requestData['gross_price']         = $data_detail->sum('gross_price');
            $requestData['total_diskon']        = $data_detail->sum('diskon_nominal');
            $requestData['total_price']         = $data_detail->sum('net_price');
            $requestData['tax_price']           = $tax_price;
            $requestData['after_tax']           = $after_tax;

            $tax_parse['tax_price']             = $tax_price;
            $tax_parse['after_tax']             = $after_tax;
        }
        $QuoteLists = QuoteLists::findOrFail($quote_id);
        $QuoteLists->update($requestData);

        return response()->json(['data' => $tax_parse, 'success' => true, 'message' => 'success'], 200);
    }

    public function do_update_product($id, Request $request)
    {
        
        $this->validate($request, [
            'product_id' => 'required',
        ]);
        $product_id       = $request->product_id;
        $quote_list_id    = $request->quote_list_id;

        $QuoteListsDetail = new QuoteListsDetail();
        $getProduct       = $QuoteListsDetail->getProduct($product_id);
        $qty              = QuoteListsDetail::findOrFail($id);
        $gross_price      = $qty->qty * $getProduct->price;
        $diskon_nominal   = ($getProduct->diskon/100) * $gross_price;
        $subtotal         = $gross_price - $diskon_nominal;


        $requestData = array(
                'product_id'     => $product_id,
                'price'          => $getProduct->price,
                'product_image'  => $getProduct->image_name,
                'gross_price'    => $gross_price,
                'quality'        => $getProduct->quality,
                'diskon'         => $getProduct->diskon,
                'diskon_nominal' => $diskon_nominal,
                'net_price'      => $subtotal
        );
        $QuoteListsDetail = QuoteListsDetail::findOrFail($id);
        $QuoteListsDetail->update($requestData);

        return $this->getDataDetail($request);
    }

    public function delete_data_detail($id, Request $request)
    {
        QuoteListsDetail::destroy($id);

        $quote_id          = $request->quote_list_id;
        $prospect_sales_id = $request->prospect_sales_id;

        $QuoteListsDetail  = new QuoteListsDetail();
        $data_detail       = $QuoteListsDetail->getList($quote_id, $prospect_sales_id);
        $quotelist         = QuoteLists::findOrFail($quote_id);

        $tax_price         = ($quotelist->tax / 100) * $data_detail->sum('net_price');
        $after_tax         = $data_detail->sum('net_price') + $tax_price;

        if($quotelist->choose_tax == "" || $quotelist->choose_tax == 0){
            $requestData['qty']          = $data_detail->sum('qty');
            $requestData['gross_price']  = $data_detail->sum('gross_price');
            $requestData['total_diskon'] = $data_detail->sum('diskon_nominal');
            $requestData['total_price']  = $data_detail->sum('net_price');
            $requestData['choose_tax']   = 0;
            $requestData['tax']          = 0;
            $requestData['after_tax']    = $data_detail->sum('net_price');
        }else{
            $requestData['qty']          = $data_detail->sum('qty');
            $requestData['gross_price']  = $data_detail->sum('gross_price');
            $requestData['total_diskon'] = $data_detail->sum('diskon_nominal');
            $requestData['total_price']  = $data_detail->sum('net_price');
            $requestData['choose_tax']   = 1;
            $requestData['tax']          = $quotelist->tax;
            $requestData['tax_price']    = $tax_price;
            $requestData['after_tax']    = $after_tax;
        }
        $QuoteLists = QuoteLists::findOrFail($quote_id);
        $QuoteLists->update($requestData);
    }

    public function choose_tax(Request $request)
    {
        $quote_id          = $request->quote_list_id;
        $prospect_sales_id = $request->prospect_sales_id;
        $choose_tax        = $request->choose_tax;
        $quote_list_code   = $request->quote_list_code;
        $date_out          = $request->date_out;
        $note              = $request->note;

        $QuoteLists        = QuoteLists::findOrFail($quote_id);
        $tax_price         = (10 / 100) * $QuoteLists->total_price;
        $after_tax         = $QuoteLists->total_price + $tax_price;

        if($choose_tax == 1){
            $choose['choose_tax']      = $choose_tax;
            $choose['tax']             = 10;
            $choose['tax_price']       = $tax_price;
            $choose['after_tax']       = $after_tax;
            $choose['quote_list_code'] = $quote_list_code;
            $choose['date_out']        = $date_out;
            $choose['note']            = $note;
        }else{
            $choose['choose_tax']      = $choose_tax;
            $choose['tax']             = 0;
            $choose['tax_price']       = 0;
            $choose['after_tax']       = $QuoteLists->total_price;
            $choose['quote_list_code'] = $quote_list_code;
            $choose['date_out']        = $date_out;
            $choose['note']            = $note;
        }

        $QuoteLists->update($choose);
        return response()->json(['data' => $choose, 'success' => true, 'message' => 'success'], 200);
    }

    public function tax(Request $request)
    {
        $quote_id          = $request->quote_list_id;
        $prospect_sales_id = $request->prospect_sales_id;
        $taxx              = $request->tax;

        $QuoteLists        = QuoteLists::findOrFail($quote_id);
        $tax_price         = ($taxx / 100) * $QuoteLists->total_price;
        $after_tax         = $QuoteLists->total_price + $tax_price;
        if($taxx == 0 || $taxx == ''){
            $tax['choose_tax'] = 0;
            $tax['tax']        = $taxx;
            $tax['tax_price']  = $tax_price;
            $tax['after_tax']  = $after_tax;
        }else{
            $tax['choose_tax'] = 1;
            $tax['tax']        = $taxx;
            $tax['tax_price']  = $tax_price;
            $tax['after_tax']  = $after_tax;
        }
        
        $QuoteLists->update($tax);

        return response()->json(['data' => $tax, 'success' => true, 'message' => 'success'], 200);
    }

    public function getTemplate(Request $request)
    {
        $quote_id                               = $request->quote_list_id;
        $QuoteLists                             = QuoteLists::findOrFail($quote_id);
        $requestTemplate['quote_template_id']   = $request->quote_template_id;
        $QuoteLists->update($requestTemplate);
        return $requestTemplate;

    }

    public function getTermCondition(Request $request)
    {
        $quote_id                               = $request->quote_list_id;
        $QuoteLists                             = QuoteLists::findOrFail($quote_id);
        $requestTerm['term_condition_id']       = $request->term_condition_id;
        $QuoteLists->update($requestTerm);
        return $requestTerm;

    }

}
