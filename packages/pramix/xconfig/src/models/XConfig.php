<?php

namespace Pramix\XConfig\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;


class XConfig extends Model
{

    protected $table = 'configurations';
    protected $primaryKey = 'id';

    protected $casts = [
        'options_array' => 'array'
    ];

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $userid = 0;
            if (Auth::check())
                $userid = auth()->user()->id;
            $model->created_by = $userid;
            $model->updated_by = $userid;


        });

        static::updating(function ($model) {
            $userid = 0;
            if (Auth::check())
                $userid = auth()->user()->id;
            $model->updated_by = $userid;


        });
    }

    public function category()
    {
        return $this->hasOne('Pramix\XConfig\Models\ConfigCategoryModel', 'id', 'category_id');

    }


    public static function get($config_name)
    {

        $config = static::where('name', $config_name)->first();

        if ($config != NULL && $config->config_type == 'TX')
            return $config->value;
        else if ($config != NULL && $config->config_type == 'DD')
            return json_decode($config->options_array);
    }

}
