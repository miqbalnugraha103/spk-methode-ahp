<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Brand extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'brand';

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
    protected $fillable = ['brand', 'image_brand'];

    public function getList() {
        return DB::table($this->table)->get();
    }
}
