<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class DeliveryOrderListsTransaction extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'delivery_order_transaction';

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
    protected $fillable = ['delivery_order_list_id', 'product_id', 'product_name', 'qty', 'date_transaction'];

    public function getListDO($do_id) {
        return DB::table('delivery_order_transaction as v')
                ->select(DB::raw('v.id, v.delivery_order_list_id, v.product_id, v.product_name, v.qty, v.date_transaction'))
                ->leftJoin('delivery_order_lists as do', 'do.id', '=', 'v.delivery_order_list_id')
                ->where('v.delivery_order_list_id', $do_id)
                ->get();
    }

    public function getProductDetailDO($do_id) {
        return DB::table('delivery_order_detail as v')
                ->select(DB::raw('v.id, v.delivery_order_list_id, v.product_id, v.product_name, v.qty'))
              ->leftJoin('delivery_order_lists as do', 'do.id', '=', 'v.delivery_order_list_id')
              ->where('v.delivery_order_list_id', $do_id)
              ->get();
    }
}
