<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
*/
include 'ChromePhp.php';
class SecretariesController extends AppController{
    public $uses = array("Secretary","StakeHolder");
    public function checkSecretary(){
         $this->autoRender = false;
         $company_id = $this->request->params['com_id'];
         $sec_name = $this->request->params['sname'];

        $secretaries = $this->StakeHolder->find('first', array('conditions' => array(
            'StakeHolder.company_id = ' => $company_id,
            'StakeHolder.sec_name'=>$sec_name 
            )));
        if($secretaries){
           return true; 
        }else{
            return false;
        }
    }
    public function getSecretaries(){
        $this->autoRender = false;
		$company_id = $this->request->params['id'];

		$secretaries = $this->StakeHolder->find('all', array('conditions' => array(
                    'StakeHolder.company_id = ' => $company_id,
                    'StakeHolder.Secretary'=>1
                    )));
		return json_encode($secretaries);
    }
    public function secretaryForm(){
            $company_id = $this->params['url']['id'];
            ChromePhp::log($company_id );
            $this->set('title', 'CREATE NEW SECRETARY');
	    $this->set('company_id', $company_id);
    }
    
    public function addSecretary() {
            //ChromePhp::log($this->request->data);
            $data = $this->request->data;
            $company_id = $this->request->data['Secretary']['company_id'];
            $created_datas = array();
            $secretary=array(
            "name"=>$data['NameoftheSecretary'],
            "addressLine1"=>$data['SEAddressline1'],
            "addressLine2"=>$data['SEAddressline2'],
            "addressLine3"=>$data['SEAddressline3'],
            "NRIC/Passport"=>$data['SENRIC/Passport'],
            "Nationality"=>$data['SENationality'],
            "Occupation"=>$data['SEOccupation'],
            "OtherOccupation"=>$data['SEOtherOccupation'],
        );
            //create secretary
        $exist = $this->StakeHolder->find("first",array(
            "conditions"=>array(
                "StakeHolder.nric"=>$secretary["NRIC/Passport"],
                "StakeHolder.company_id"=>$company_id,
            )
        ));
        if($exist){
            $this->StakeHolder->id = $exist['StakeHolder']['id'];
            $this->StakeHolder->saveField("Secretary",1);
            

        }else{
            $this->StakeHolder->create();
            $stakeholder_data = array(
                "company_id"=>$company_id,
                "name"=>$secretary['name'],
                "address_1"=>$secretary['addressLine1'],
                "address_2"=>$secretary['addressLine2']." ".$secretary['addressLine3'],
                "nric"=>$secretary["NRIC/Passport"],
                "created_at"=>date('Y-m-d H:i:s'),
                "nationality"=>$secretary['Nationality'],
                "Secretary"=>1       
            );

            $this->StakeHolder->save($stakeholder_data);
        }
        $this->Secretary->create();
        $secretary_data=array(
            "id"=> $this->StakeHolder->id,
            "Mode"=>"appointed",
            "Occupation"=>$secretary['Occupation'],
            "OtherOccupation"=>$secretary['OtherOccupation'],
        );
        $this->Secretary->save($secretary_data);//end

             
                $create_data=array(
                    "company"=>$company_id,
                    "functionCorp"=>0,
                    "secretary_id"=>$this->StakeHolder->id
                );
                array_push($created_datas,$create_data);
     
           $this->Session->write('created_secretary', $created_datas);
           $this->Session->setFlash(
			    'Secretary created',
			    'default',
			    array('class' => 'alert alert-success')
			);
             return $this->redirect(array("controller"=>"FunctionCorps","action"=>"AppointResignS"));
	}
    
}