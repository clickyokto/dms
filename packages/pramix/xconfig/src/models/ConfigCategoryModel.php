<?php

namespace Pramix\XConfig\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Artisan;


class ConfigCategoryModel extends Model
{
    protected $table = 'configuration_category';
    protected $primaryKey = 'id';

    use SoftDeletes;

    protected $dates = ['deleted_at'];

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
    }
}
