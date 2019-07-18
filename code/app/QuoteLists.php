<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class QuoteLists extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'quote_lists';

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
    protected $fillable = ['prospect_sales_id', 'sales_person_id', 'quote_list_code', 'requote_list_code', 'company_name', 'name_pic', 'company_address', 'company_phone', 'note', 'file', 'quote_template_id', 'term_condition_id', 'gross_price', 'qty', 'total_price', 'total_diskon', 'choose_tax', 'tax', 'tax_price', 'after_tax', 'date_out', 'fix_data', 'is_active'];

    public function getForQuote($id)
    {
        return DB::table('quote_lists as v')
              ->select(DB::raw('v.id, v.quote_list_code'))
              ->orderBy('v.quote_list_code', 'ASC')
              ->where('v.id', '!=', $id)
              ->get();
    }

    public function getQuote($id)
    {
        return DB::table('quote_lists as v')
              ->select(DB::raw('v.id, v.quote_list_code, v.sales_person_id'))
              ->where('v.id', '=', $id)
              ->first();
    }
    public function getSales($sales_id){
        return DB::table('users as v')
              ->select(DB::raw('v.id, v.name as name_sales'))
              ->where('v.id', '=', $sales_id)
              ->first();
    }

    public function prospectData($prospect_id)
    {
        return DB::table('quote_lists as v')
              ->select(DB::raw('v.id, v.sales_person_id, cp.company_name, cp.company_address, cp.name_pic, cp.company_phone'))
              ->leftjoin('prospect_sales as ps','ps.id', '=', 'v.prospect_sales_id')
              ->leftjoin('customer_profile as cp','cp.id', '=', 'ps.customer_profile_id')
              ->where('v.prospect_sales_id', '=', $prospect_id)
              ->first();
    }
}
