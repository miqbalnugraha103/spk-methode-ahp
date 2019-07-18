<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class termCondition extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'term_condition';

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
    protected $fillable = ['code', 'name', 'slug', 'content'];

    public function getList() {
        return DB::table($this->table)->get();
    }
}
