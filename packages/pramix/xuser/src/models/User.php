<?php

namespace Pramix\XUser\Models;

use App\Scopes\BranchScopes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Illuminate\Support\Facades\Hash;
use Pramix\XBranches\Models\BranchesModel;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Validator;


class User extends Authenticatable
{

    use Notifiable;


    use HasRoles;


//    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScopes());

        static::creating(function ($model) {
            $userid = 0;

            if (isset(auth()->user()->id))
                $userid = auth()->user()->id;
            $model->created_by = $userid;
            $model->updated_by = $userid;
            $model->branch_id = BranchesModel::getBranchID();
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


    public static function addUser($user_details)
    {


        // $role = Role::find($user_details['role']);


        $user = new User();
        $user->username = $user_details['user_name'];
        $user->fname = $user_details['fname'];
        $user->lname = $user_details['lname'];
        $user->email = $user_details['email'];
        $user->password = Hash::make($user_details['password']);
        $user->status = 1;

        try {
            $user->save();
            $user->assignRole($user_details['role']);
            return $user;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function updateUser($user_details, $id)
    {

        $role = Role::find($user_details['role']);

        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user = User::find($id);
        $user->username = $user_details['user_name'];
        $user->fname = $user_details['fname'];
        $user->lname = $user_details['lname'];
        $user->email = $user_details['email'];

        if (!empty($user_details['password'])) {

            $user->password = bcrypt($user_details['password']);
        }
        $user->status = 1;

        try {

            $user->save();


            $user->assignRole($user_details['role']);
            return $user;
        } catch (\Exception $e) {

            return FALSE;
        }
    }

}
