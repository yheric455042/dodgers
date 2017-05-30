<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;


class MapController extends Controller {
    
    public function showInfo($id) {
        $arr = array('id' => $id);
        
        return response()->json($arr);
    
    }

    public function store(Request $req) {
        $arr = array('id' => $req->input('id'));
        
        return response()->json($arr);
    
    }



}










?>
