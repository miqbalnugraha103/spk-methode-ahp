<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class ProspectSales extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'prospect_sales';

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
    protected $fillable = ['customer_profile_id', 'user_id', 'assignment_date', 'status_id', 'progress_notes'];

    Public function getProspectSales($prospect_id)
    {
        return DB::table('prospect_sales as v')
            ->select(DB::raw('v.id, v.customer_profile_id, v.user_id , cp.company_name, cp.name_pic, cp.company_address, cp.company_phone, u.name as name_sales'))
            ->leftJoin('customer_profile as cp', 'cp.id', '=', 'v.customer_profile_id')
            ->leftJoin('users as u', 'u.id', '=', 'v.user_id')
            ->where('v.id', $prospect_id)
            ->first();
    }

    Public function getForSelect()
    {
        if (Auth::user()->role == 1 || Auth::user()->role == 2) {
            return DB::table('prospect_sales as v')
                ->select(DB::raw('v.id, cp.company_name, u.name as name_sales'))
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'v.customer_profile_id')
                ->leftJoin('users as u', 'u.id', '=', 'v.user_id')
                ->orderBy('cp.company_name', 'ASC')
                ->get();
        }else{
            return DB::table('prospect_sales as v')
                ->select(DB::raw('v.id, cp.company_name, u.name as name_sales'))
                ->leftJoin('customer_profile as cp', 'cp.id', '=', 'v.customer_profile_id')
                ->leftJoin('users as u', 'u.id', '=', 'v.user_id')
                ->orderBy('cp.company_name', 'ASC')
                ->where('u.id', Auth::user()->id)
                ->get();
        }
    }


    Public function getForSales($sales)
    {
        return DB::table('prospect_sales as v')
            ->select(DB::raw('v.id, u.id as user_id, cp.company_name, u.name as name_sales'))
            ->leftJoin('customer_profile as cp', 'cp.id', '=', 'v.customer_profile_id')
            ->leftJoin('users as u', 'u.id', '=', 'v.user_id')
            ->where('v.user_id', $sales)
            ->first();
    }
    public function getSalesPersonByRequest($prospect_id) {
        return DB::table('prospect_sales as v')
            ->leftJoin('customer_profile as cp', 'cp.id', '=', 'v.customer_profile_id')
            ->select(DB::raw('v.*, cp.company_name, cp.name_pic, cp.company_phone, cp.company_address'))
            ->where('v.id', $prospect_id)
            ->first();
    }

}
