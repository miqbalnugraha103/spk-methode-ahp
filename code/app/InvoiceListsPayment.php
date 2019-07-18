<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class InvoiceListsPayment extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invoice_payment';

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
    protected $fillable = ['invoice_list_id', 'prospect_sales_id', 'file_payment', 'date_payment', 'amount'];

    public function getListPayment($invoice_id) {
        return DB::table('invoice_payment as v')
                ->select(DB::raw('v.id, v.invoice_list_id, v.prospect_sales_id, v.file_payment, v.date_payment, v.amount'))
                ->leftJoin('invoice_lists as il', 'il.id', '=', 'v.invoice_list_id')
                ->where('v.invoice_list_id', $invoice_id)
                ->get();
    }

    public function getProduct($product_id) {
        return DB::table('products as v')
              ->select(DB::raw('v.id, v.name, v.slug, v.price, v.diskon, v.brand_id'))
              ->where('v.id', $product_id)
              ->first();
    }

    public function getByInvoice($invoice) {
        return DB::table('invoice_payment as v')
              ->select(DB::raw('v.id, v.invoice_list_id, v.prospect_sales_id, v.amount'))
              ->where('v.invoice_list_id', $invoice)
              ->get();
    }

    public function getListPaymentCount($invoice_id) {
        return DB::table('invoice_payment as v')
                ->select(DB::raw('v.id, v.invoice_list_id, v.prospect_sales_id, v.file_payment, v.date_payment, v.amount'))
                ->leftJoin('invoice_lists as il', 'il.id', '=', 'v.invoice_list_id')
                ->where('v.invoice_list_id', $invoice_id)
                ->get();
    }
}
