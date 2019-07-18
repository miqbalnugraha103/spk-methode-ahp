<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class DeliveryOrderLists extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'delivery_order_lists';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['prospect_sales_id', 'sales_person_id', 'invoice_list_code_id', 'purchase_order_list_code_id', 'delivery_order_sales_person_id', 'delivery_order_list_code', 'note', 'file', 'gross_price', 'qty', 'total_price', 'total_diskon', 'invoice_code', 'total_invoice', 'pic_sales', 'pic_client', 'file_pic', 'date_out', 'qty_transaction', 'status', 'is_active'];

    public function getPO($po_id) {
        return DB::table('purchase_order_lists as v')
                ->select(DB::raw('v.id, v.quote_prospect_sales_id, v.quote_sales_person_id, po_prospect_sales_id, v.gross_price, v.qty, v.total_price, v.total_diskon'))
                ->where('v.id', $po_id)
                ->first();
    }

    public function getForInvoiceArray($po_id = null)
    {
      if($po_id == null){

          return DB::table('invoice_lists as v')
              ->leftJoin('purchase_order_lists as pol', 'pol.id', '=', 'v.purchase_order_list_code_id')
              ->select(DB::raw('pol.id, pol.purchase_order_list_code'))
              ->where('v.paid_off', 1)
              ->where('v.is_active', 1)
              ->where('v.fix_data', 0)
              ->orderBy('pol.id', 'DESC')
              ->get();
      }else{
          return DB::table('invoice_lists as v')
              ->leftJoin('purchase_order_lists as pol', 'pol.id', '=', 'v.purchase_order_list_code_id')
              ->select(DB::raw('pol.id, pol.purchase_order_list_code'))
              ->where('pol.id', '!=', $po_id)
              ->where('v.paid_off', 1)
              ->where('v.is_active', 1)
              ->where('v.fix_data', 0)
              ->orderBy('pol.id', 'DESC')
              ->get();
      }
        
    }

    public function getForPO()
    {

        if (Auth::user()->role == User::ROLE_SUPERADMIN || Auth::user()->role == User::ROLE_ADMIN) {
            return DB::table('purchase_order_lists as v')
                ->select(DB::raw('v.id, v.purchase_order_list_code, v.fix_data'))
                ->where('v.fix_data', 1)
                ->orderBy('v.purchase_order_list_code', 'ASC')
                ->get();
        }else{
            return DB::table('purchase_order_lists as v')
                ->select(DB::raw('v.id, v.purchase_order_list_code, v.fix_data'))
                ->where('v.quote_sales_person_id', Auth::user()->id)
                ->where('v.fix_data', 1)
                ->orderBy('v.purchase_order_list_code', 'ASC')
                ->get();
        }
    }

    public function getForInvoice($invoice_id)
    {
        return DB::table('invoice_lists as v')
              ->select(DB::raw('v.id, v.invoice_code'))
              ->where('v.id', $invoice_id)
              ->first();
    }

    public function getForInvoicePayment($invoice_id)
    {
        return DB::table('invoice_payment as v')
              ->select(DB::raw('v.id, v.invoice_list_id, v.amount'))
              ->where('v.invoice_list_id', $invoice_id)
              ->get();
    }

    public function deliveryOrderSales($invoice_sales_person_id)
    {
        return DB::table('delivery_order_lists as v')
              ->select(DB::raw('v.id, sp.name_sales'))
              ->leftJoin('sales_person as sp', 'sp.id', '=', 'v.delivery_order_sales_person_id')
              ->where('v.delivery_order_sales_person_id', '=', $invoice_sales_person_id)
              ->first();
    }

    public function sumProduct($do_id)
    {
        return DB::table('delivery_order_transaction as v')
              ->select(DB::raw('v.id, v.qty'))
              ->where('v.delivery_order_list_id', '=', $do_id)
              ->get();
    }

    public function cekQtyProductDetail($do_id, $product_id)
    {
        return DB::table('delivery_order_detail as v')
              ->select(DB::raw('v.id, v.qty'))
              ->where('v.delivery_order_list_id', '=', $do_id)
              ->where('v.product_id', '=', $product_id)
              ->get();
    }

    public function cekProductTransaction($do_id, $product_id)
    {
        return DB::table('delivery_order_transaction as v')
              ->select(DB::raw('v.id, v.qty'))
              ->where('v.delivery_order_list_id', '=', $do_id)
              ->where('v.product_id', '=', $product_id)
              ->get();
    }
    public function name_product($product_id)
    {
        return DB::table('products as v')
              ->select(DB::raw('v.id, v.name as product_name'))
              ->where('v.id', '=', $product_id)
              ->first();
    }
    
}
