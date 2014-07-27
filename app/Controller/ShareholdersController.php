<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
*/
include 'ChromePhp.php';
class ShareholdersController extends AppController{
    public $uses = array("StakeHolder");
    public function getShareholders(){
        $this->autoRender = false;
		$company_id = $this->params['url']['id'];

		$shareholders = $this->StakeHolder->find('all', array('conditions' => array(
                    'StakeHolder.company_id = ' => $company_id,
                    'StakeHolder.ShareHolder'=>1
                    )));
		return json_encode($shareholders);
    }
    public function shareholderForm(){
            $company_id = $this->params['url']['id'];
            
            $directors = $this->StakeHolder->find('all', array('conditions' => array(
                'StakeHolder.company_id = ' => $company_id,
                'StakeHolder.Director'=>1
                )));
               
            $this->set('title', 'CREATE NEW SHAREHOLDER');
	    $this->set('company_id', $company_id);
             $this->set('directors', $directors);
    }
    public function updateShareHolder(){
        $id = $this->request->data['director'];
        ChromePhp::log($id);
        $this->StakeHolder->id = $id;
        $this->StakeHolder->saveField("Shareholder",1);
        $this->Session->setFlash(
			    'ShareHolder created',
			    'default',
			    array('class' => 'alert alert-success')
			);
             return $this->redirect(array("controller"=>"FunctionCorps","action"=>"changeOfCompanyName","?"=>array(
                        "company"=>$this->request->data['company_id'])));
    }
    public function addShareHolder() {
            $data = $this->request->data;
            $company_id = $this->request->data['company_id'];
      
            $created_datas = array();
         
           
          
                
		$name = $data['name'];
		$nric = $data['nric'];
		$address1 = $data['address_1'];
		$address2 = $data['address_2'];
		$nationality = $data['nationality'];
		

                $this->StakeHolder->create();
                $new_stakeholder_data = array(
				'company_id' => $company_id,
				'name' => $name,
				'nric' => $nric,
				'address_1' => $address1,
				'address_2' => $address2,
				'nationality' => $nationality,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
                                'Shareholder'=>1
			);
                $this->StakeHolder->save($new_stakeholder_data);
                
                $secretary = $this->StakeHolder->find("first",array(
                    "conditions"=>array(
                        "nric"=>$nric
                    )
                ));
           $this->Session->setFlash(
			    'Shareholder created',
			    'default',
			    array('class' => 'alert alert-success')
			);
              return $this->redirect(array("controller"=>"FunctionCorps","action"=>"changeOfCompanyName","?"=>array(
                        "company"=>$this->request->data['company_id'])));
	}  
        public function getShareholdersDirectors(){
            $this->autoRender = false;
            $id = $this->params['url']['id'];
            $stakeholders = $this->StakeHolder->find("all",array(
            "conditions"=>array(
                "StakeHolder.company_id"=>$id,
                "OR"=>array(
                    "StakeHolder.Director"=>1,
                    "StakeHolder.Shareholder"=>1
                )
            )
            ));
            return json_encode($stakeholders);
        }
}