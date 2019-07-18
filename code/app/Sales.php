<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Sales extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sales_person';

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
    protected $fillable = ['name_sales', 'user_id'];

    public function getSales() {
        return DB::table($this->table)->get();
    }

}
