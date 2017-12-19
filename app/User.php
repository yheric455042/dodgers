<?php

namespace App;

class User {
    
    protected $account;
    protected $rule;
    protected $loginStatus;


    public function __construct($list) {
        $this->account = $list->account;
        //$this->rule = $rule;
        $this->loginStatus = $this->auth($list->input('account'), $list->input('password'));
    }  
    
    public function loginStatus() {
        return $this->loginStatus;
    }
    
    public static function create($req) {
        
        try { 
            
            $data = [];  
            $arr = $req->all();
            $token = get_hash();
            $arr['password'] = password_hash($arr['password'],PASSWORD_DEFAULT);
            $arr['type'] = '2';
            
            
            $arr['isvalid'] = '0';
            $arr['token'] = $token;
            
            send_email($token, $arr['account']);

            \DB::table('user')->insert($arr);
        } catch(\Illuminate\Database\QueryException $ex) {
            
            return ['state' => false, 'msg' => $ex->getMessage()];
        
        }

       return ['state' => true];
    }   
    
    public static function verifyToken($token) {
        return \DB::table('user')->where('token', '=', $token)->update(['isvalid' => 1]);
        
        
    }
     
    private function auth($account, $password) {

        $query = \DB::table('user')->where('account', '=', $account)->first();
        

        if(empty($query) ||!password_verify($password,$query->password)) {

            $state = false;
            $msg = 'user not found';

        } elseif(!intval($query->isvalid)) {
            $token = get_hash();
            \DB::table('user')->where('account', '=', $account)->update(['token'=>$token]);
            send_email($token, $account);
            
            $state = false;
            $msg = 'unverify Email';
            
        } else {

            $token = $query->token;
            $name = $query->name;
            $state = true;
        }
        
        $result = ['state' => $state];
        !$state ? $result['msg'] = $msg : $result['token'] = $token;
        $state && $result['name'] = $name;
        return $result;
    }

    public static function logout($token) {
        
        return \DB::table('user')->where('token', '=', $token)->update(['token' => get_hash()]);

    }
    
    public static function verifyUser($token) {
        return !empty(\DB::table('user')->where('token' , '=', $token)->first());
    }
    
}

function get_hash() {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()+-';
        $random = $chars[mt_rand(0,73)].$chars[mt_rand(0,73)].$chars[mt_rand(0,73)].$chars[mt_rand(0,73)].$chars[mt_rand(0,73)];
        $content = uniqid().$random;

        return sha1($content); 
}


function send_email($token,$account) {
    $data= [];
    $data['content'] = "Verify account: <a href='http://dodgers.ga:8080/dodgers/public/index.php/verifytoken/$token'>click here!!</a>";
    $data['email'] = $account;
    
    \Mail::send([],[], function($message) use ($data) {
        $message->from('dodgerstaiwan@gmail.com', 'Account Verify');

        $message->to($data['email'])->subject('Welcome Dodgers !!');
        
        $message->setBody($data['content'], 'text/html');
    
    });


}

?>
