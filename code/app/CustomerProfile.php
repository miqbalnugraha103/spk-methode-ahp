<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    /**
     * The database table Color by the model.
     *
     * @var string
     */
    protected $table = 'customer_profile';

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
    protected $fillable = ['company_name', 'company_address', 'name_pic', 'company_phone'];
}
