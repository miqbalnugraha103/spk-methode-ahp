<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class PurchaseOrderLists extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_order_lists';

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
    protected $fillable = ['quote_prospect_sales_id', 'quote_sales_person_id', 'po_prospect_sales_id', 'quote_list_code_id', 'purchase_order_list_code', 'note', 'file', 'gross_price', 'qty', 'total_price', 'total_diskon', 'date_out', 'status', 'fix_data', 'is_active'];

    public function getForQuote()
    {
        if (Auth::user()->role == 1 || Auth::user()->role == 2) {
            return DB::table('quote_lists as v')
                ->select(DB::raw('v.id, v.quote_list_code'))
                ->where('v.is_active', '=', '1')
                ->orderBy('v.quote_list_code', 'ASC')
                ->get();
        }else{
            return DB::table('quote_lists as v')
                ->select(DB::raw('v.id, v.quote_list_code'))
                ->where('v.sales_person_id', Auth::user()->id)
                ->where('v.is_active', '=', '1')
                ->orderBy('v.quote_list_code', 'ASC')
                ->get();
        }
    }

    public function getListPO($po_id) {
        return DB::table('purchase_order_lists as v')
              ->select(DB::raw('v.id, v.purchase_order_list_code, v.quote_sales_person_id'))
              ->where('v.id', $po_id)
              ->first();
    }

    public function getQuote($quote_id) {
        return DB::table('quote_lists as v')
                ->select(DB::raw('v.id, v.prospect_sales_id, v.sales_person_id, v.gross_price, v.qty, v.total_price, v.total_diskon, v.choose_tax, v.tax, v.tax_price, v.after_tax, fix_data'))
                ->where('v.id', $quote_id)
                ->first();
    }
    public function getQuoteDetail($quote_id) {
        return DB::table('quote_list_detail as v')
                ->select(DB::raw('v.id, v.quote_list_id ,v.prospect_sales_id, v.product_id, v.product_name, v.qty, v.price, v.gross_price, v.diskon, v.diskon_nominal, net_price'))
                ->where('v.quote_list_id', $quote_id)
                ->get();
    }

    public function getForPO($id)
    {
        return DB::table('purchase_order_lists as v')
              ->select(DB::raw('v.id, v.purchase_order_list_code'))
              ->orderBy('v.purchase_order_list_code', 'ASC')
              ->where('v.id', '!=', $id)
              ->get();
    }

    public function getProspectSales($prospect_id) {
        return DB::table('prospect_sales_history as v')
                ->select(DB::raw('v.id, v.sales_person_id, sp.name_sales'))
                ->leftJoin('sales_person as sp', 'sp.id', '=', 'v.sales_person_id')
                ->groupBy('sales_person_id')
                ->where('v.prospect_id', $prospect_id)
                ->get();
    }
}
