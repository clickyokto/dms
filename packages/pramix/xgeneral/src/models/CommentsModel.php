<?php

namespace Pramix\XGeneral\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Pramix\XGeneral\Models\AddressModel;

class CommentsModel extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'id';


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $userid = auth()->user()->id;
            $model->created_by = $userid;
            $model->updated_by = $userid;
        });

        static::updating(function ($model) {
            $userid = auth()->user()->id;
            $model->updated_by = $userid;
        });
    }

    public function user()
    {
        return $this->hasOne('Pramix\XUser\Models\User', 'id', 'created_by');
    }



}
