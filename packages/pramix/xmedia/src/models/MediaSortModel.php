<?php

namespace Pramix\XMedia\Models;

use Illuminate\Database\Eloquent\Model;

class MediaSortModel extends Model
{
    protected $table = 'media_sort';
    protected $primaryKey = 'id';


    public static function boot() {
        parent::boot();

        static::creating(function($model) {
            $userid = 0;

            if (isset(auth()->user()->id))
                $userid = auth()->user()->id;
            $model->created_by = $userid;
            $model->updated_by = $userid;
        });

        static::updating(function($model) {
            $userid = 0;
            if (isset(auth()->user()->id))
                $userid = auth()->user()->id;
            $model->updated_by = $userid;
        });
        static::deleting(function($model) {

        });
    }
}
