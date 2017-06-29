<?php

namespace App;

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
    
    public static function create($req) {
        
        try { 
            
            $arr = $req->all();
            $arr['password'] = password_hash($arr['password'],PASSWORD_DEFAULT);
            $arr['type'] = '2';

           \DB::table('user')->insert($arr);
        } catch(\Illuminate\Database\QueryException $ex) {
            
            return ['state' => false, 'msg' => $ex];
        
        }

       return ['state' => true];
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
