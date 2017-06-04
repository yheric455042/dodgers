<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller {

    protected $user;
    


    public function auth(Request $req) {
        $this->user = new User($req);
        
        $response = $this->user->isLogged() ? ['state' =>true] : ['state'=>false,'mes'=> 'user not found'];
        
        return response()->json($response);
    }


}

