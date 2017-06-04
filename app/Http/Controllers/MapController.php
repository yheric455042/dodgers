<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;


class MapController extends Controller {
    
    public function allInfo() {

        $result = \DB::select('select * from dodgers where ?',[1]);
        
        return response()->json($result);
    
    }

    public function addData(Request $req) {
        try {
            
            \DB::table('dodgers')->insert($req->all());
            return response()->json(['state' => true]);
        } catch(\Illuminate\Database\QueryException $ex) {

            return response()->json(['state' => false, 'massage' =>$ex->getMessage()]);
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

}
?>
