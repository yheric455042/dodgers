<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller {

    
    public function auth(Request $req) {
        $user = new User($req);
        
        $response = $user->isLogged() ? ['state' =>true] : ['state'=>false,'msg'=> 'user not found'];
        
        return response()->json($response);
    }

    public function createUser(Request $req) {
        
        return response()->json(User::create($req));

    }
    


}

