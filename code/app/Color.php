<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    /**
     * The database table Color by the model.
     *
     * @var string
     */
    protected $table = 'color';

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
    protected $fillable = ['color_name'];

}
