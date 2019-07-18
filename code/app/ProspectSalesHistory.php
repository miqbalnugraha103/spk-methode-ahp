<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class ProspectSalesHistory extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'prospect_sales_history';

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
    protected $fillable = ['prospect_id', 'user_id', 'status_id', 'notes', 'status', 'assignment_date'];

    public function getSalesProspect($prospect){
        return DB::table('prospect_sales_history as v')
                ->select(DB::raw('v.id, v.prospect_id, v.user_id, u.name as name_sales'))
                ->leftJoin('users as u', 'u.id', '=', 'v.user_id')
                ->groupBy('u.name')
                ->where('v.prospect_id', $prospect)
                ->get();
    }
}