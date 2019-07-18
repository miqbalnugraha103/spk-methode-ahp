<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Orders extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';

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
    protected $fillable = ['code', 'prospect_sales_id', 'total_order', 'total_item', 'diskon', 'order_date'];

    public function getList() {
        return DB::table($this->table)->get();
    }
}
