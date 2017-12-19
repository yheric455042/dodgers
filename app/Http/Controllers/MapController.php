<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;


class MapController extends Controller {
    
     
    public function allInfo() {

        $results = \DB::table('dodgers')->join('user', 'user.account', '=', 'dodgers.account')->select('user.name', 'dodgers.longitude', 'dodgers.latitude', 'dodgers.address', 'dodgers.type')->get();
        
        return response()->json($results);
    
    }
    
    public function getInfo($lat, $lng, $distance) {

        $arr = [];
        $results = \DB::table('dodgers')->join('user', 'user.account', '=', 'dodgers.account')->select('user.name', 'dodgers.longitude', 'dodgers.latitude', 'dodgers.address', 'dodgers.type')->get();
        
        foreach($results as $result) {
            $dis = abs(floatval($result->latitude) - floatval($lat)) > abs(floatval($result->longitude) - floatval($lng)) ? abs(floatval($result->latitude) - floatval($lat)) : abs(floatval($result->longitude) - floatval($lng));
            
            $dis <= 0.00900900901 * intval($distance) && array_push($arr,$result);
        
        }
        
        return response()->json($arr);
    }

    public function addData(Request $req) {
        $arr = $req->all();

        if(!\App\User::verifyUser($arr['token'])) {
            return ['state' => false, 'msg' => 'noLogin'];
        }

        try {
            
            if(!isset($arr['address'])) {
                $arr['address'] = $this->coordinate2address($arr['latitude'], $arr['longitude']);
            
            } else if(!isset($arr['latitude'])) {
                $result = $this->address2coordinate($arr['address']);
                $arr['latitude'] = $result['lat'];
                $arr['longitude'] = $result['lng'];
            }
            
            $arr['timestamp'] = time(); 
            $arr['account'] = \DB::table('user')->select('account')->where('token', '=', $arr['token'])->first()->account;
            unset($arr['token']);
            $destination = $this->returnQueryArr(\DB::table('dodgers')->select('latitude', 'longitude')->get());

            $istoonear = $this->istoonear($arr['latitude'], $arr['longitude'], $destination);
             
            !$istoonear && \DB::table('dodgers')->insert($arr);
            
            $res = ['state' => !$istoonear];
            
            $istoonear && $res['msg'] = 'this data is too near!';
            
            return response()->json($res);

        } catch(\Illuminate\Database\QueryException $ex) {

            return response()->json(['state' => false, 'msg' =>$ex->getMessage()]);
        }
    
    }

    public function findRoad($city,$town) {
        $result = $this->list2array(\DB::table('road')->where('city','=',$city)->where('town', 'LIKE', '%'.$town)->lists('road'));


        return response()->json($result);

    
    }

    public function findTown($city) {
        $result = $this->list2array(\DB::table('road')->groupBy('town')->where('city','=',$city)->lists('town'));

         
        return response()->json($result);

    
    }

    private function list2array($lists) {
        return json_decode(json_encode($lists),true);
    }

    private function returnQueryArr($query) {
        $arr = [];

        foreach($query as $val) {
            array_push($arr,['latitude' => $val->latitude, 'longitude' => $val->longitude]);   
        }

        return $arr;
        
    }

    private function address2coordinate($address) {
        $val = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=AIzaSyC5m5vVvBfhJb6v_3gX1u_TLw56hKYe-Gc"), true);
        
        if($val['status'] === 'ZERO_RESULTS') {
            return false;
        }

        $lat = $val['results'][0]['geometry']['location']['lat']; 
        $lng = $val['results'][0]['geometry']['location']['lng']; 
        
        return ['lat'=>$lat,'lng' => $lng]; 
    }

    private function coordinate2address($lat, $lng) {
        
        $val = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat%2C$lng&key=AIzaSyC5m5vVvBfhJb6v_3gX1u_TLw56hKYe-Gc&language=zh_TW"), true);
        
        return $val['results'][0]['formatted_address'];


    }

    private function istoonear($lat, $lng ,$destinationArr) {
        
        if(count($destinationArr) == 0) {
            
            return false;
        }

        foreach($destinationArr as $destination) {
            $dis = abs(floatval($destination['latitude']) - floatval($lat)) > abs(floatval($destination['longitude']) - floatval($lng)) ? abs(floatval($destination['latitude']) - floatval($lat)) : abs(floatval($destination['longitude']) - floatval($lng));
            
            if($dis <= 0.00900900901 * 0.15) {
                
                return true;
            }
        

        }
        
        
        return false; 
    
    }

}
?>
