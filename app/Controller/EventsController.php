<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class EventsController extends AppController{
//     public $components = array('Session', 'Paginator');
//	public $uses = array('User','Company', 'Director','FunctionCorp','Document','DocumentDirector');
    public function beforeFilter() {
		parent::beforeFilter();
    }
    public function index(){
        $events = $this->Event->find("all",array(
            "order"=>"Event.created_time DESC"
        ));
        $this->set("events",$events);
    }
}
