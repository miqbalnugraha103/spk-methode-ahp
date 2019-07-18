<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class DeliveryOrderListsDetail extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'delivery_order_detail';

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
    protected $fillable = ['delivery_order_list_id', 'prospect_sales_id', 'product_id', 'product_name', 'qty', 'price', 'gross_price', 'diskon', 'diskon_nominal', 'net_price'];

    public function getList($do_id, $prospect_sales_id) {
        return DB::table('delivery_order_detail as v')
                ->select(DB::raw('v.id, v.delivery_order_list_id, v.prospect_sales_id, v.product_id, v.product_name, v.qty, v.price, v.gross_price, v.diskon, v.diskon_nominal, v.net_price'))
                ->leftJoin('delivery_order_lists as do', 'do.id', '=', 'v.delivery_order_list_id')
                ->where('v.delivery_order_list_id', $do_id)
                ->Where('v.prospect_sales_id', $prospect_sales_id)
                ->get();
    }

    public function getPODetail($po_id) {
        return DB::table('purchase_order_list_detail as v')
                ->select(DB::raw('v.id, v.purchase_order_list_id ,v.prospect_sales_id, v.product_id, v.product_name, v.qty, v.price, v.gross_price, v.diskon, v.diskon_nominal, net_price'))
                ->where('v.purchase_order_list_id', $po_id)
                ->get();
    }

    public function getForSelect($prospect_sales_id) {
        return DB::table('products as v')
              ->select(DB::raw('v.id, v.name, v.slug, v.price, v.diskon, v.brand_id, pb.prospect_sales_id'))
              ->leftJoin('brand as b', 'b.id', '=', 'v.brand_id')
              ->leftJoin('prospect_to_brand as pb', 'pb.brand_id', '=', 'v.brand_id')
              ->orderBy('v.name', 'ASC')
              ->where('pb.prospect_sales_id', $prospect_sales_id)
              ->get();
    }

    public function getProduct($product_id) {
        return DB::table('products as v')
              ->select(DB::raw('v.id, v.name, v.slug, v.price, v.diskon, v.brand_id'))
              ->where('v.id', $product_id)
              ->first();
    }
}
