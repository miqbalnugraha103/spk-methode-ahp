<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class PurchaseOrderListsDetail extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_order_list_detail';

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
    protected $fillable = ['purchase_order_list_id', 'prospect_sales_id', 'product_id', 'product_name', 'qty', 'price', 'gross_price', 'diskon', 'diskon_nominal', 'net_price'];

    public function getList($po_id, $prospect_sales_id) {
        return DB::table('purchase_order_list_detail as v')
                ->select(DB::raw('v.id, v.purchase_order_list_id, v.prospect_sales_id, v.product_id, v.product_name, v.qty, v.price, v.gross_price, v.diskon, v.diskon_nominal, v.net_price'))
                ->leftJoin('purchase_order_lists as po', 'po.id', '=', 'v.purchase_order_list_id')
                ->where('v.purchase_order_list_id', $po_id)
                ->Where('v.prospect_sales_id', $prospect_sales_id)
                ->get();
    }

    public function getForSelectDetail($prospect_sales_id, $product_id_array = null, $product_id = null) {
      if($product_id_array == null){
            return DB::table('products as v')
                  ->select(DB::raw('v.id, v.name, v.slug, v.price, v.diskon, v.brand_id, pb.prospect_sales_id'))
                  // ->leftJoin('prospect_sales as p', 'p.id', '=', 'v.prospect_sales_id')
                  ->leftJoin('brand as b', 'b.id', '=', 'v.brand_id')
                  ->leftJoin('prospect_to_brand as pb', 'pb.brand_id', '=', 'v.brand_id')
                  ->orderBy('v.name', 'ASC')
                  ->where('pb.prospect_sales_id', $prospect_sales_id)
                  ->get();
      }else{
          if (($key = array_search($product_id, $product_id_array)) !== false) {
              unset($product_id_array[$key]);
          }
            return DB::table('products as v')
                  ->select(DB::raw('v.id, v.name, v.slug, v.price, v.diskon, v.brand_id, pb.prospect_sales_id'))
                  // ->leftJoin('prospect_sales as p', 'p.id', '=', 'v.prospect_sales_id')
                  ->leftJoin('brand as b', 'b.id', '=', 'v.brand_id')
                  ->leftJoin('prospect_to_brand as pb', 'pb.brand_id', '=', 'v.brand_id')
                  ->orderBy('v.name', 'ASC')
                  ->where('pb.prospect_sales_id', $prospect_sales_id)
                  ->whereNotIn('v.id', $product_id_array)
                  ->get();
      }
    }

    public function getForSelect($prospect_sales_id, $product_id_array = null) {
      if($product_id_array == null){
        return DB::table('products as v')
              ->select(DB::raw('v.id, v.name, v.slug, v.price, v.diskon, v.brand_id, pb.prospect_sales_id'))
              // ->leftJoin('prospect_sales as p', 'p.id', '=', 'v.prospect_sales_id')
              ->leftJoin('brand as b', 'b.id', '=', 'v.brand_id')
              ->leftJoin('prospect_to_brand as pb', 'pb.brand_id', '=', 'v.brand_id')
              ->orderBy('v.name', 'ASC')
              ->where('pb.prospect_sales_id', $prospect_sales_id)
              ->get();

      }else{
        return DB::table('products as v')
              ->select(DB::raw('v.id, v.name, v.slug, v.price, v.diskon, v.brand_id, pb.prospect_sales_id'))
              // ->leftJoin('prospect_sales as p', 'p.id', '=', 'v.prospect_sales_id')
              ->leftJoin('brand as b', 'b.id', '=', 'v.brand_id')
              ->leftJoin('prospect_to_brand as pb', 'pb.brand_id', '=', 'v.brand_id')
              ->orderBy('v.name', 'ASC')
              ->where('pb.prospect_sales_id', $prospect_sales_id)
              ->whereNotIn('v.id', $product_id_array)
              ->get();
      }
    }

    public function getProduct($product_id) {
        return DB::table('products as v')
              ->select(DB::raw('v.id, v.name, v.slug, v.price, v.diskon, v.brand_id'))
              ->where('v.id', $product_id)
              ->first();
    }
}
