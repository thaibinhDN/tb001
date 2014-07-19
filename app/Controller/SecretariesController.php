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
            $count = 0;
            $created_datas = array();
         
           
            for($i = 0;$i<count($data['name']);$i++){
                
		$name = $data['name'][$i];
		$nric = $data['nric'][$i];
		$address1 = $data['address_1'][$i];
		$address2 = $data['address_2'][$i];
		$nationality = $data['nationality'][$i];
	

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
                                'Secretary'=>1
			);
                $this->StakeHolder->save($new_stakeholder_data);
                
//                $secretary = $this->StakeHolder->find("first",array(
//                    "conditions"=>array(
//                        "nric"=>$nric
//                    )
//                ));
                
                $this->Secretary->create(); 
                $sec_data = array(
                    "id" =>  $this->StakeHolder->id,
                    "Mode"=>null
                );
                $this->Secretary->save( $sec_data); 
                
             
                $create_data=array(
                    "company"=>$company_id,
                    "functionCorp"=>0,
                    "secretary_id"=>$this->StakeHolder->id
                );
                array_push($created_datas,$create_data);
            }
            $this->Session->write('created_secretary', $created_datas);
           $this->Session->setFlash(
			    'Secretary created',
			    'default',
			    array('class' => 'alert alert-success')
			);
             return $this->redirect(array("controller"=>"FunctionCorps","action"=>"AppointResignS"));
	}
    
}