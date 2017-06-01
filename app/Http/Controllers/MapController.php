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



}
?>
