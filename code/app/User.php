<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable
{
    use Notifiable;

    const ROLE_SUPERADMIN = 1;
    const ROLE_ADMIN = 2;
    const ROLE_SALES = 3;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'username' , 'role', 'created_by', 'updated_by'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getList() {
        return DB::table($this->table)->get();
    }
    public function UserSelect($select_diff = null) {
        return DB::table('users as v')
            ->select(DB::raw('v.*'))
            ->orderBy('v.email', 'ASC')
            ->whereIn('v.id', $select_diff)
            ->where('v.role', '!=', 1)
            ->get();
    }

    public function getSales($sales_id) {
        return DB::table('users as v')
            ->select('v.name as name_sales')
            ->where('v.id', $sales_id)
            ->first();
    }
}
