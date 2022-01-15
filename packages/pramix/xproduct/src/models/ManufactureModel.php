<?php

namespace Pramix\XProduct\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class ManufactureModel extends Model
{
    protected $table = 'manufacture';
    protected $primaryKey = 'id';

    use SoftDeletes;

    protected $dates = ['deleted_at'];
}
