<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class StatusProgress extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'status_progress';

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
    protected $fillable = ['name_progress'];

    public function selectForStatus() {
        return DB::table('status_progress as v')
                ->select(DB::raw('v.id, v.name_progress'))
                ->where('v.id', '!=', 1)
                ->get();
    }
    
}
