<?php

namespace Pramix\XCommunication\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateModel extends Model
{
    protected $table = 'etemplates';
    protected $primaryKey = 'id';
    
    
     use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
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
        
        static::deleting(function($model)
        {              
//          $campings = CampainModel::where('template_id',$model->id)->count();   
//          if($campings)
//              return false;          
        });   
        
       //  static::addGlobalScope(new \App\Scopes\UserScope);
          
    }      

   
}
