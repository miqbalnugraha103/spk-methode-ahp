<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class ProspectToBrand extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'prospect_to_brand';

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
    protected $fillable = ['prospect_sales_id', 'brand_id'];

    public function getByProspectId($pid) {
        return DB::table($this->table)
            ->where('prospect_sales_id',$pid)
            ->get();
    }
    public function deleteProspectId($pid) {
        return DB::table($this->table)
            ->where('prospect_sales_id', $pid)
            ->delete();   
    }
}
