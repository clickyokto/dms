<?php

namespace Pramix\XGeneral\Models;

use App\Scopes\BranchScopes;
use Config;
use CustomerAddressesModel;
use Illuminate\Database\Eloquent\Model;
use Countries;
use Pramix\XBranches\Models\BranchesModel;


class AddressModel extends Model
{
    protected $table = 'address';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];

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

        static::created(function ($model) {
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

    public static function saveAddresses($address_details, $address_type, $ref_id, $user_type)
    {


        $address_details_temp = $address_details;
        unset($address_details_temp['business_country']);
        unset($address_details_temp['shipping_country']);


        if (!array_filter($address_details_temp)) {
            return false;
        }


        $address = AddressModel::where('address_type', $address_type)->where('ref_id', $ref_id)->where('user_type', $user_type)->first();
        if ($address == NULL)
            $address = new AddressModel();

        if ($address_type == 'B') {

            $address->ref_id = $ref_id;
            $address->address_type = $address_type;
            $address->user_type = $user_type;
            $address->address_line_1 = $address_details['business_street1'];
            $address->address_line_2 = $address_details['business_street2'];
            $address->city_id = $address_details['business_city_id'];
            $address->district_id = $address_details['business_district_id'];
            $address->country = $address_details['business_country'];
            $address->description = $address_details['business_remarks'];

        } elseif ($address_type == 'S') {

            $address->ref_id = $ref_id;
            $address->address_type = $address_type;
            $address->user_type = $user_type;
            $address->address_line_1 = $address_details['shipping_street1'];
            $address->address_line_2 = $address_details['shipping_street2'];
            $address->city_id = $address_details['shipping_city_id'];
            $address->district_id = $address_details['shipping_district_id'];
            $address->country = $address_details['shipping_country'];
            $address->description = $address_details['shipping_remarks'];

        }
        $address->save();

    }

    public static function getAddress($ref_if, $Address_type, $user_tpye)
    {


        $address = AddressModel::where('ref_id', $ref_if)->where('address_type', $Address_type)->where('user_type', $user_tpye)->with('city')->with('district')->first();
if ($address==null)
    return null;

        $address->city_name='';
        $address->district_name = '';
        $address->country_name='';
        if (isset($address->country)){
            $country = Countries::getOne($address->country, 'en');
            $address->country_name= $country;
        }
        if (isset($address->city))
            $address->city_name = $address->city->name_en;
        if (isset($address->district))
            $address->district_name = $address->district->name_en;

        return $address;

    }

    public function city()
    {
        return $this->hasOne('Pramix\XGeneral\Models\CityModel', 'id', 'city_id');
    }

    public function district()
    {
        return $this->hasOne('Pramix\XGeneral\Models\DistrictsModel', 'id', 'district_id');
    }
}
