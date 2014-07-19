<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include 'ChromePhp.php';
class UsersController extends AppController{
    public $components = array("Session");
    public  $helpers  = array(
              'Html', 
              'Session'
              );
    function index(){
        $item = $this->request['url']["item"];
        if($item == "login"){
            $this->set("heading","Welcome to login page.");
        }else{
            $this->set("heading","Please log in to access this function");
        } 
    }

    function authenticate(){
           
    }
    function logout(){
        $this->Session->delete('token');
        $this->Session->setFlash(
		    'Logout successfully',
                    'default',
		    array('class' => 'alert alert-success')
		);
            return $this->redirect(array(
                "controller"=>"pages",
                 "action"=>"display"
            ));
    }
    function login(){
        //need to check username existent
        $username=$this->request->data('username');
        $password=sha1($this->request->data('password'));
        $user = $this->User->find('first',array(
                "conditions"=>array(
                        "User.username"=>$username,
                        "User.password"=>$password
                )
        ));
        if($user){
           $token = sha1($username.$password.date('Y-m-d H:i:s'));
           ChromePhp::log($user);
           $data= array(
               "username"=>$username,
               "password"=>$password,
               "firstName"=>$user['User']['firstName'],
               "lastName"=>$user['User']['lastName'],
               "id"=>$user['User']['id'],
               "token"=> $token ,
               "lastLogin"=>date('Y-m-d H:i:s')
           );
           
           $this->User->save($data);
           $this->Session->write('token', $token);
           $this->Session->setFlash(
		    'Login successfully',
                    'default',
		    array('class' => 'alert alert-success')
		);
            return $this->redirect(array(
                "controller"=>"pages",
                 "action"=>"display"
            ));
        }else{
            $this->Session->setFlash(
		    'Oops,Your password or username is incorrect.Please try again',
                    'default',
		    array('class' => 'alert alert-danger')
		);

		return $this->redirect(array('action' => 'index',"?"=>array(
                        "item"=>"incorrect"
                )));
        }
        
       
    }
    
}
