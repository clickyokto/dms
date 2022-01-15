<?php

namespace Pramix\XBranches\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class BranchesModel extends Model
{
    protected $table = 'branches';
    protected $primaryKey = 'id';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

        });

        static::updating(function ($model) {

        });

    }

    public static function getBranchID()
    {
        $current_url = url('/');

        $locations = BranchesModel::where('url', $current_url)->first();

        return $locations->id;
    }

//    public function branch()
//    {
//        return $this->hasone('Pramix\XBranches\Models\BranchesModel','id','branch_id');
//    }
}
