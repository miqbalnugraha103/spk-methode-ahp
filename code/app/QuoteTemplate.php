<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class QuoteTemplate extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'quote_template';

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
    protected $fillable = ['code', 'header', 'footer'];

    public function getList() {
        return DB::table($this->table)->get();
    }
}
