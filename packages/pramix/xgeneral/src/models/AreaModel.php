<?php

namespace Pramix\XGeneral\Models;

use Illuminate\Database\Eloquent\Model;
use Pramix\XCustomer\Models\CustomerModel;

class AreaModel extends Model
{
    protected $table = 'areas';
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

        static::deleting(function($model)
        {
            $customer = CustomerModel::where('area_id',$model->id)->count();
            if($customer != 0)
                return false;
        });

    }




    public static function generateAreaCode($area_name)
    {
        while(true)
        {
            $randomletter = strtoupper(substr($area_name, 0, 3));
            $area = AreaModel::where('code', $randomletter)->first();
            if($area == NULL)
            {
                return $randomletter;
                break;
            }
        }




    }

}
