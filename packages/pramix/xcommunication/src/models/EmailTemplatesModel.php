<?php

namespace Pramix\XCommunication\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplatesModel extends Model {

    protected $table = 'email_templates';
    protected $primaryKey = 'id';

    public static function boot() {
        parent::boot();

        static::creating(function($model) {
            $userid = auth()->user()->id;
            $model->created_by = $userid;
            $model->updated_by = $userid;
        });

        static::updating(function($model) {
            $userid = auth()->user()->id;
            $model->updated_by = $userid;
        });

        static::deleting(function($model) {

        });
    }

}
