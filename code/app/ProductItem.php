<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class ProductItem extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

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
    protected $fillable = ['brand_id', 'color_id', 'product_code', 'name', 'slug', 'price', 'quantity', 'diskon', 'image_name', 'size', 'quality', 'description'];

    public function getList() {
        return DB::table($this->table)->get();
    }
}
