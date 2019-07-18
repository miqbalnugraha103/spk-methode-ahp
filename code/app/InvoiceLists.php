<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class InvoiceLists extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invoice_lists';

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
    protected $fillable = ['prospect_sales_id' , 'sales_person_id', 'purchase_order_list_code_id', 'invoice_sales_person_id', 'invoice_list_code', 'invoice_code', 'note', 'file', 'gross_price', 'qty', 'total_price', 'total_diskon', 'choose_tax', 'tax', 'tax_price', 'after_tax', 'date_out', 'paid_off', 'fix_data', 'is_active'];

    public function save_data($data) {
        return DB::table($this->table)->insertGetId($data);
    }

    public function getInvoiceList($Invoice) {
        return DB::table('invoice_lists as v')
              ->select(DB::raw('v.id, v.prospect_sales_id, v.sales_person_id, v.purchase_order_list_code_id, total_price, v.paid_off'))
              ->where('v.id', $Invoice)
              ->first();
    }

    public function getList($po) {
        return DB::table('invoice_lists as v')
              ->select(DB::raw('v.id, v.prospect_sales_id, v.sales_person_id, v.purchase_order_list_code_id'))
              ->where('v.purchase_order_list_code_id', $po)
              ->get();
    }

    public function getForPOArray($po_id = null)
    {
      if($po_id == null){

          return DB::table('purchase_order_lists as v')
              ->select(DB::raw('v.id, v.purchase_order_list_code'))
              ->where('v.status', 1)
              ->where('v.is_active', 1)
              ->where('v.fix_data', 0)
              ->orderBy('v.id', 'DESC')
              ->get();
      }else{
          return DB::table('purchase_order_lists as v')
              ->select(DB::raw('v.id, v.purchase_order_list_code'))
              ->where('v.status', 1)
              ->where('v.id', '!=', $po_id)
              ->where('v.is_active', 1)
              ->where('v.fix_data', 0)
              ->orderBy('v.id', 'DESC')
              ->get();
      }
        
    }

    public function getForPO()
    {
        return DB::table('purchase_order_lists as v')
              ->select(DB::raw('v.id, v.purchase_order_list_code'))
              ->orderBy('v.purchase_order_list_code', 'ASC')
              ->get();
    }

    public function getPO($po_id) {
        return DB::table('purchase_order_lists as v')
                ->select(DB::raw('v.id, v.quote_prospect_sales_id, v.quote_sales_person_id, v.po_prospect_sales_id, v.gross_price, v.qty, v.total_price, v.total_diskon'))
                ->where('v.id', $po_id)
                ->first();
    }

    public function getPODetail($po_id) {
        return DB::table('purchase_order_list_detail as v')
                ->select(DB::raw('v.id, v.purchase_order_list_id ,v.prospect_sales_id, v.product_id, v.product_name, v.qty, v.price, v.gross_price, v.diskon, v.diskon_nominal, net_price'))
                ->where('v.purchase_order_list_id', $po_id)
                ->get();
    }

    public function getForInvoice($id)
    {
        return DB::table('invoice_lists as v')
              ->select(DB::raw('v.id, v.invoice_list_code'))
              ->orderBy('v.invoice_list_code', 'ASC')
              ->where('v.id', '!=', $id)
              ->get();
    }

    public function getInvoicePO($po_id)
    {
        return DB::table('invoice_lists as v')
              ->select(DB::raw('v.id, v.prospect_sales_id, v.invoice_list_code, v.purchase_order_list_code_id, v.paid_off'))
              ->where('v.purchase_order_list_code_id', '=', $po_id)
              ->get();
    }

    public function allInvoiceAmount($po_id, $invoice)
    {
        return DB::table('invoice_lists as v')
              ->select(DB::raw('v.id, v.prospect_sales_id, v.purchase_order_list_code_id, v.paid_off'))
              ->where('v.purchase_order_list_code_id', '=', $po_id)
              ->where('v.id', '!=', $invoice)
              ->get();
    }

    public function invoiceBy($invoice_id)
    {
        return DB::table('invoice_lists as v')
              ->select(DB::raw('v.id, v.prospect_sales_id, v.sales_person_id, v.purchase_order_list_code_id, v.invoice_list_code, v.paid_off'))
              ->where('v.paid_off', '=', 1)
              ->where('v.id', '=', $invoice_id)
              ->first();
    }

    public function invoiceSales($invoice_sales_person_id)
    {
        return DB::table('invoice_lists as v')
              ->select(DB::raw('v.id, sp.name_sales'))
                ->leftJoin('sales_person as sp', 'sp.id', '=', 'v.invoice_sales_person_id')
              ->where('v.invoice_sales_person_id', '=', $invoice_sales_person_id)
              ->first();
    }
}
