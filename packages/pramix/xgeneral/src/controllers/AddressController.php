<?php


namespace Pramix\XGeneral\Controllers;

use Illuminate\Http\Request;
use Pramix\XGeneral\Models\CityModel;
use App\Http\Controllers\Controller;
use Pramix\XGeneral\Models\DistrictsModel;

class AddressController extends Controller
{

    public  function getCitiesByDistrict(Request $request)
    {

        $district_id = ($request['district_id']);

        if ($district_id!=null)
        $cities = CityModel::where('district_id', $district_id)->get();
        else
            $cities = CityModel::all();

        return response()->json(['status' => 'success', 'cities'=>$cities]);


    }
    public  function getDistrictByCity(Request $request)
    {

        $city_id = ($request['city_id']);

        $city=CityModel::find($city_id);
        return response()->json(['status' => 'success', 'district_id'=>$city->district_id]);


    }
}
