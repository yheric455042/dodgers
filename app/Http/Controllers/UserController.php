<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller {

    
    public function auth(Request $req) {
        $user = new User($req);
                
        $response = $user->loginStatus();        
        return response()->json($response);
    }

    public function createUser(Request $req) {
        
        return response()->json(User::create($req));

    }

    public function verifyToken($token) {
       return User::verifyToken($token) ? '驗證成功' : '你已經驗證過或驗證過期了!!'; 
    }  
    
    public function logout(Request $req) {
       $results = \App\User::logout($req->input('token')) ? ['state' => true] : ['state' => false, 'msg' => 'token error'];
        return response()->json($results);
    
    }   

}

