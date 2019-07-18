<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class QuoteListsDetail extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'quote_list_detail';

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
    protected $fillable = ['quote_list_id', 'prospect_sales_id', 'product_id', 'brand', 'product_name', 'product_image', 'qty', 'quality', 'gross_price', 'diskon', 'diskon_nominal', 'net_price', 'price'];

    public function getList($quote_list_id, $prospect_sales_id) {
        return DB::table('quote_list_detail as v')
                ->select(DB::raw('v.id, v.quote_list_id, v.prospect_sales_id, v.product_id, b.brand, v.product_name, p.slug, v.product_image, p.description, v.qty, v.quality, v.price, v.gross_price, v.diskon, v.diskon_nominal, v.net_price'))
                ->leftJoin('quote_lists as ql', 'ql.id', '=', 'v.quote_list_id')
                ->leftJoin('products as p', 'p.id', '=', 'v.product_id')
                ->leftJoin('brand as b', 'b.id', '=', 'p.brand_id')
                ->where('v.quote_list_id', $quote_list_id)
                ->Where('v.prospect_sales_id', $prospect_sales_id)
                ->get();
    }

    public function getForSelectDetail($prospect_sales_id, $product_id_array = null, $product_id = null) {
      if ($product_id_array == null) {
          return DB::table('products as v')
                  ->select(DB::raw('v.id, b.brand, v.name, v.slug, v.price, v.diskon, v.brand_id, pb.prospect_sales_id'))
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
                  ->select(DB::raw('v.id, b.brand, v.name, v.slug, v.price, v.diskon, v.brand_id, pb.prospect_sales_id'))
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
      if ($product_id_array == null) {
        return DB::table('products as v')
              ->select(DB::raw('v.id, b.brand, v.name, v.slug, v.image_name, v.price, v.diskon, v.brand_id, pb.prospect_sales_id'))
              // ->leftJoin('prospect_sales as p', 'p.id', '=', 'v.prospect_sales_id')
              ->leftJoin('brand as b', 'b.id', '=', 'v.brand_id')
              ->leftJoin('prospect_to_brand as pb', 'pb.brand_id', '=', 'v.brand_id')
              ->orderBy('v.name', 'ASC')
              ->where('pb.prospect_sales_id', $prospect_sales_id)
              ->get();
      }else{
        return DB::table('products as v')
              ->select(DB::raw('v.id, b.brand, v.name, v.slug, v.image_name, v.price, v.diskon, v.brand_id, pb.prospect_sales_id'))
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
              ->select(DB::raw('v.id, v.brand_id, b.brand, v.name, v.slug, v.price, v.quality, v.diskon, image_name'))
              ->leftJoin('brand as b', 'b.id', '=', 'v.brand_id')
              ->where('v.id', $product_id)
              ->first();
    }

        public function getQuoteDetail($quote_id) {
        return DB::table('quote_list_detail as v')
                ->select(DB::raw('v.id, v.quote_list_id, v.prospect_sales_id, v.product_id, v.product_name, v.product_image, v.qty, v.quality, v.price, v.gross_price, v.diskon, v.diskon_nominal, v.net_price'))
                ->leftJoin('quote_lists as ql', 'ql.id', '=', 'v.quote_list_id')
                ->where('v.quote_list_id', $quote_id)
                ->get();
    }
}
