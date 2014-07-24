<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include 'ChromePhp.php';
class AuditorsController extends AppController{
    public $components = array("Session");
    public  $helpers  = array(
              'Html', 
              'Session'
              );
    public $uses = array('Auditor','StakeHolder');
    
    public function addAuditor(){
        $data = $this->request->data;
        //Create Auditors
        $exist = $this->StakeHolder->find("first",array(
                "conditions"=>array(
                    "StakeHolder.nric"=>$data["AUNRIC/Passport"],
                    "StakeHolder.company_id"=>$data['company'],
                )
        ));
        if($exist){
            $this->StakeHolder->id = $exist['StakeHolder']['id'];
            $this->StakeHolder->saveField("Auditor",1);
             $this->Session->setFlash(
		    'The Audtior already exists!',
		    'default',
		    array('class' => 'alert alert-warning')
		);
        }else{
            $this->StakeHolder->create();
            $stakeholder_data = array(
                "company_id"=>$data['company'],
                "name"=>$data['NameoftheAuditor'],
                "address_1"=>$data['AUAddressline1'],
                "address_2"=>$data['AUAddressline2']." ".$data['AUAddressline3'],
                "nric"=>$data["AUNRIC/Passport"],
                "created_at"=>date('Y-m-d H:i:s'),
                "nationality"=>$data['AUNationality'],
                "Auditor"=>1       
            );

            $this->StakeHolder->save($stakeholder_data);
            $this->Auditor->create();
            $auditor_data=array(
                "id"=> $this->StakeHolder->id,
                "Mode"=>"appointed",
                "OtherOccupation"=>$data['AUOtherOccupation'],
                "addressLine1"=>$data['AUAddressline1'],
                "addressLine2"=>$data['AUAddressline2'],
                "addressLine3"=>$data['AUAddressline3'],
            );
            $this->Auditor->save($auditor_data);
            $this->Session->setFlash(
		    'New Auditors are generated!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        }
        return $this->redirect(array(
            "controller"=>'functionCorps',
            "action"=>'AppointResignAuditor',
            "?"=>array("company"=>$data['company'])
        ));
    }
}
