<?php

namespace App;
use App\Exceptions\NotFoundmonException;

class User {
    
    protected $account;
    protected $rule;
    protected $islogin;


    public function __construct($list) {
        $this->account = $list->account;
        //$this->rule = $rule;
        $this->islogin = $this->auth($list->input('account'), $list->input('password'));
    }  
    
    public function isLogged() {
        return $this->islogin;
    }

     
    private function auth($account, $password) {

        $query = \DB::table('user')->where('account', '=', $account)->first();
        

        if(empty($query) ||!password_verify($password,$query->password)) {

            return false;

        } else {
            $this->account = $query->account;
            //$this->rule = $query->rule;
            
            return true;    
        }
        
    }

}


?>
