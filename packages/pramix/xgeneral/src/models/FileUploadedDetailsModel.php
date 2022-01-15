<?php

namespace Pramix\XGeneral\Models;

use Illuminate\Database\Eloquent\Model;

class FileUploadedDetailsModel extends Model
{
    protected $table = 'file_uploaded_details';
    protected $primaryKey = 'id';


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $userid = 0;

            if (isset(auth()->user()->id))
                $userid = auth()->user()->id;
            $model->created_by = $userid;
            $model->updated_by = $userid;
        });

        static::updating(function ($model) {
            $userid = 0;
            if (isset(auth()->user()->id))
                $userid = auth()->user()->id;
            $model->updated_by = $userid;
        });

        static::deleting(function ($model) {

        });
    }
}
