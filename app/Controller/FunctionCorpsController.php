<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
App::import('Controller', 'Forms');
App::import('Controller', 'Documents');

class FunctionCorpsController extends AppController{
    public $uses = array('OptionToPurchase','LoanResolution','ClosureBankAcc','ChangeFinancialYear','ChangeCompanyName','ChangeBankSignatorUob','AppointSecretaryAuditor','AppointResignSecretary','AppointResignDirector','StakeHolder','Secretary',
                        'IncreasePaidUpCapital','IncreaseOfShare','FirstFinalDividend','AllotDirectorFee','NormalStruckOff','ResignAuditor','Auditor','PropertyDisposal','SalesAssetBusiness','ChangeOfRegisteredAddress','FunctionCorps','User','Event','Document','Form', 'Company', 'Director','Pdf','ChangeOfMAA','ZipFile','ChangeOfPassport');
    function generateFunction(){
        $functions = $this->Function->find('all');
    }
    
    function create_zip($files = array(),$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		
		//close the zip -- done!
		$zip->close();
		
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
    }
    public function getFunction(){
            $this->autoRender = false;
		$function_id = $this->request->params['id'];

		$function = $this->FunctionCorps->find('first', array('conditions' => array('FunctionCorps.function_id = ' => $function_id )));
               //ChromePhp::log(json_encode($directors) );
                
		return json_encode($function);
    }
   
    function AppointAs(){
        $data = $this->request->data;
        $company = $data['company'];
        $secretaries = $this->StakeHolder->find('all',array(
              "conditions"=>array(
              'StakeHolder.company_id'=>$company,
              'StakeHolder.Secretary'=>1
           ) 
        ));
        $auditors= $this->StakeHolder->find('all',array(
              "conditions"=>array(
              'StakeHolder.company_id'=>$company,
              'StakeHolder.Auditor'=>1
           ) 
        ));
        
       // ChromePhp::log($auditors);
        //ChromePhp::log($secretaries);
        $this->set('title', 'Appoint both Secretary and Auditor');
         $this->set('secretaries',$secretaries);
         $this->set('auditors',$auditors);
         $this->set('company',$company);      
    }
    
    function AppointResignS(){
        $created_data=$this->Session->read('created_secretary');
        //ChromePhp::log($created_data);
        if($created_data == null){
             $secretaries_created = null;
             $data = $this->request->data;
             //ChromePhp::log($data);
             $company = $data['company'];
             
             $secretaries = $this->StakeHolder->find('all',array(
                   "conditions"=>array(
                   'StakeHolder.company_id'=>$company,
                    'StakeHolder.Secretary'=>1,
                ) 
             ));
         }else{

             $data = $created_data;
             $company = $data[0]['company'];
                
                $secretaries = $this->StakeHolder->find('all',array(
                   "conditions"=>array(
                   'StakeHolder.company_id'=>$company,
                    'StakeHolder.Secretary'=>1,
                ) 
             ));
          
        }
        
   
         $this->set('title', 'Appoint&Resign Secretaries');
         $this->set('secretaries',$secretaries);
         $this->set('company',$company);      
         $this->Session->delete('created_secretary');
    }
     
//Appoint & Resgin Directors
     function AppointResignD(){
   
        $data = $this->request->data;
        $company = isset($data['company'])?$data['company']:$this->params['url']['company'];
        $directors = $this->StakeHolder->find('all',array(
              "conditions"=>array(
              'StakeHolder.company_id'=>$company,
               'StakeHolder.Director'=>1 
           ) 
        ));

         $this->set('title', 'Appoint&Resign Directors');
         $this->set('directors',$directors);
         $this->set('company',$company);
    }
    function editAppointResignS(){
        $data = $this->params['url'];
         $documents = $this->AppointResignSecretary->find("all",array(
            "conditions"=>array(
                "Event.id"=>$data['id']
            )
        ));
         $secretaries = $this->StakeHolder->find('all',array(
              "conditions"=>array(
              'StakeHolder.company_id'=>$data['company'],
               'StakeHolder.Secretary'=>1 
           ) 
        ));
        $lastSubmitSecretaries = array();
        $doc_ids = array();
        foreach($documents as $document){
            $d = $this->StakeHolder->find("first",array(
                "conditions"=>array(
                    "StakeHolder.id"=>$document['AppointResignSecretary']['secretary_id']
                )
            ));
            $d['type']=$document['AppointResignSecretary']['type'];
            $d['attn']=$document['AppointResignSecretary']['attn'];
            $d['NameDS']=$document['AppointResignSecretary']['NameDS'];
            array_push($lastSubmitSecretaries,$d);
            array_push($doc_ids,$document['Document']['id']);
        }
        $types = array("appointment","cessation");
        //ChromePhp::log($lastSubmitSecretaries);
        $this->set('title', 'Edit Appoint&Resign Secretaries');
        $this->set("lastSubmitSecretaries",$lastSubmitSecretaries);
         $this->set('secretaries',$secretaries);
         $this->set('types',$types);
         $this->set('company',$data['company']);
         $this->set('event_id',$data['id']);
         $this->set("doc_ids",$doc_ids);
    }
    function editAppointResignD(){
        $data = $this->params['url'];
        $documents = $this->AppointResignDirector->find("all",array(
            "conditions"=>array(
                "Event.id"=>$data['id']
            )
        ));
        $directors = $this->StakeHolder->find('all',array(
              "conditions"=>array(
              'StakeHolder.company_id'=>$data['company'],
               'StakeHolder.Director'=>1 
           ) 
        ));
        $lastSubmitDirectors = array();
        $doc_ids = array();
        foreach($documents as $document){
            $d = $this->StakeHolder->find("all",array(
                "conditions"=>array(
                    "StakeHolder.id"=>$document['AppointResignDirector']['director_id']
                )
            ));
            $d['type']=$document['AppointResignDirector']['type'];
            $d['attn']=$document['AppointResignDirector']['attn'];
            array_push($lastSubmitDirectors,$d);
            array_push($doc_ids,$document['Document']['id']);
        }
        $types = array("appointment","cessation");
        //ChromePhp::log($lastSubmitDirectors);
        $this->set('title', 'Edit Appoint&Resign Directors');
        $this->set("lastSubmitDirectors",$lastSubmitDirectors);
         $this->set('directors',$directors);
         $this->set('types',$types);
         $this->set('company',$data['company']);
         $this->set('event_id',$data['id']);
         $this->set("doc_ids",$doc_ids);
         
    }
     function preview1(){
            $data = $this->request->data;
            ChromePhp::log($data);
            $director_ids = $data['director'];
               
            $directors = array();
            $attn = $data['attn'];
            $types = $data['type'];
            foreach ($director_ids as $director_id) {
                    if (!empty($director_id)) {

                            $director = $this->Director->find('first', array('conditions' => array('Director.id = ' => $director_id)));
                            array_push($directors,$director);
                    }
            }

            for($i = 0;$i < count($directors);$i++){
                if($types[$i]==="cessation"){
                    $directors[$i]["reportedTo"] = $attn[$i];
                }else{
                    $directors[$i]["reportedTo"]="";
                }
           }
          
           
            $preview_data = array(
                    "title"=>'Preview Form',
                    "data"=>$data, //Remove or not

                    "Director"=>$directors,
                );
 
         $total_directors = $this->StakeHolder->find("all",array(
            "conditions"=>array(
                "StakeHolder.Director"=>1,
                "StakeHolder.company_id"=>$data['company']
            )
        ));
   
        $this->set('preview_data', $preview_data);
        $this->set('total_directors', $total_directors);  
        if(isset($data['edit'])){
            $this->set('edit', $data['edit']);
            $this->set('event_id',$data['event_id']);
            $this->set("doc_ids",$data['doc_ids']);
        }
    }
    
    function generateAppointResignDirector(){
        $form = new FormsController();
        $documentControl = new DocumentsController();
        $data = $this->request->data;
        $directors = $data['director'];
        $types = $data['type'];
        $user = $this->User->find("first",array(
               "conditions"=>array(
                   "User.token"=>$this->Session->read('token')
               )
        ));
        $postfix = "";
        for($i = 0;$i < count($directors);$i++){
            $data_stakeholders = array(
            
                "StakeHolder.nric"=>"'".$data['director_nric'][$i]."'",
                "StakeHolder.address_1"=>"'".$data['director_addr1'][$i]."'",
                "StakeHolder.address_2"=>"'".$data['director_addr2'][$i]."'",
                "StakeHolder.nationality"=>"'".$data['director_nationality'][$i]."'",
                "StakeHolder.updated_at"=>"'".date('Y-m-d H:i:s')."'",
            );
            $this->StakeHolder->updateAll($data_stakeholders ,array(
                   "StakeHolder.id"=>$directors[$i]
            ));
            
           $data_directors=array(
               "Director.Mode"=>($data['type'][$i]=="'cessation'"?"Resigned":"'NULL'")
           );
           $this->Director->updateAll($data_directors ,array(
                   "Director.id"=>$directors[$i]
            )); 
           
           $name = $this->StakeHolder->find("first",array(
                "conditions"=>array(
                    "StakeHolder.id"=>$directors[$i]
                )
           ));
           
           $postfix .= $i != (count($directors) - 1)?$name['StakeHolder']['name'].",":$name['StakeHolder']['name'];
        };
        
        
        //Save to Documents,Events,Appoint&Resign Directors table for document tracking, logging purposes
        //Change previous event to inedible mode
       if(isset($data['edit'])){
           $this->Event->id=$data['event_id'];
           $this->Event->saveField("mode","");
           $document_ids = $data["doc_ids"];
           foreach($document_ids as $id){
               $this->Document->delete($id);
           }
       }
        $this->Event->create();
        $created_time = date('Y-m-d H:i:s');
        $unique_hash = sha1(date('Y-m-d H:i:s').$user['User']['id'].$data['company']."1");
        $event_data = array(
            "function_id"=>1,
            "company_id"=>$data['company'],
            "user_id"=>$user['User']['id'],
            "created_time"=>$created_time ,
            "description"=>isset($data['edit'])? "Edit Appointing/Resigining directors documents for ".$postfix:"Create"."Appointing/Resigining directors documents for ".$postfix,
            "mode"=>"editable",
            "unique_hash"=>$unique_hash 
        );
        $this->Event->save($event_data);
        
        
        $event = $this->Event->find("first",array(
            "conditions"=>array(
                "Event.unique_hash"=>$unique_hash ,
            )
        ));
        
        
      
       // Change Mode of directors to resigned in databases
        $array_documents_id = array();
             for ($i = 0; $i < count($types); $i++) {
                  
                 if(strcmp($types[$i],"cessation")==0){
                     //ChromePhp::log($directors[$i]);
                   
                     $this->Director->updateAll(array(
                            'Director.Mode'=>"'Resigned'"
                        ),array(
                            'Director.id' => $directors[$i]
                        )
                     );
                     $director = $this->StakeHolder->find("first",array(
                        "conditions"=>array(
                            "StakeHolder.id"=>$directors[$i]
                        )
                    ));
                     //save to Document table
                    $this->Document->create();
                    $hash_value = sha1($i."cessation".date('Y-m-d H:i:s'));
                    $document = array(
                        'company_id'=>$data['company'],
                        'function_id'=>1,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'unique_key'=>$hash_value,
                        'status'=>"Available",
                        "description"=>"Cessation of Director ".$director['StakeHolder']['name'],
                    );
                    $this->Document->save($document);
                    //Save to DocumentDirector table
                    $documentDirectorEntry = $this->Document->find('first',array(
                        'conditions'=>array(
                                'Document.unique_key'=>$hash_value
                        )
                    ));
                    array_push($array_documents_id,$documentDirectorEntry['Document']['id']);
                   
                        $data_ARD = array(
                            "document_id"=>$documentDirectorEntry['Document']['id'],
                            "event_id"=>$event['Event']['id'],
                            "director_id"=>$directors[$i],
                            "type"=>"cessation",
                            "attn"=>$data['reportedTo'][$i],
                            "NameSDs"=>$data['prepared_by'],
                            
                        );
                        $this->AppointResignDirector->create();

                        $this->AppointResignDirector->save($data_ARD);
      
             }

                 
             };
             
              //Change Mode of directors to appointed in databases
             for ($i = 0; $i < count($types); $i++) {
                 
                 if(strcmp($types[$i],"appointment")==0){
                    
                     $director = $this->StakeHolder->find("first",array(
                        "conditions"=>array(
                            "StakeHolder.id"=>$directors[$i]
                        )
                    ));
                     $this->Director->updateAll(array(
                            'Director.Mode'=>null
                        ),array(
                            'Director.id' => $directors[$i]
                        )
                     );
                     
                     //save to Document table
                    $this->Document->create();
                    $hash_value = sha1($i."appointment".date('Y-m-d H:i:s'));
                    $document = array(
                        'company_id'=>$data['company'],
                        'function_id'=>1,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'unique_key'=>$hash_value,
                        'status'=>"Available",
                        "description"=>"Appointment of Director ".$director['StakeHolder']['name']
                    );
                    $this->Document->save($document);
                    //Save to DocumentDirector table
                    $documentDirectorEntry = $this->Document->find('first',array(
                        'conditions'=>array(
                                'Document.unique_key'=>$hash_value
                        )
                    ));
                     array_push($array_documents_id,$documentDirectorEntry['Document']['id']);
                     $data_ARD = array(
                            "document_id"=>$documentDirectorEntry['Document']['id'],
                            "event_id"=>$event['Event']['id'],
                            "director_id"=>$directors[$i],
                            "type"=>"appointment",
                            "attn"=>"",
                            "NameSDs"=>$data['prepared_by'],
                  
                        );
                        $this->AppointResignDirector->create();

                        $this->AppointResignDirector->save($data_ARD);
                    
                 }
            };
        $data['function']=1;
        $form_downloads = array();
       
        $form->generateForm45($data);
        $form->generateForm49($data);
        $form->generateResolution($data);
        $form->generateResignationLetter($data);
        $form->generateIndemnityLetter($data);
        $files_to_zip = $form->form_downloads;
        $time = date('Y-m-d H-i-s');
        $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS.'files' . DS . 'zip' . DS . 'AppointResignDirector'.$time.'.zip');
        //$this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS.'\files\zip\AppointResignDirector'.$time.'.zip');
        foreach($files_to_zip as $file){ //Delete files after zipping
            unlink($file);
        };
        //Create zip file
        $this->ZipFile->create();
        $zip_file = array(
            "function_id"=>1,
            "company_id"=>$data['company'],
            "path"=>'AppointResignDirector'.$time.'.zip',
            "created_at"=>date('Y-m-d H:i:s'),
        );
        $this->ZipFile->save($zip_file);
        //Inititaing 5 steps tracking
        foreach($array_documents_id as $id){
            $documentControl->initiateStatusChecking($id);
        }
        
        $this->Session->setFlash(
		    'Forms are generated!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        return $this->redirect(array(
            "controller"=>'forms',
            "action"=>'index'
        ));
    
    }
    
    function generateChangeOfBankingUOB(){
        $form = new FormsController();
        $data = $this->request->data;
       
        $user = $this->User->find("first",array(
               "conditions"=>array(
                   "User.token"=>$this->Session->read('token')
               )
        ));
        $director= $this->Director->find("first",array(
            "conditions"=>array(
                "Director.id"=>$data['director']
            )
        ));
        
        //Save to Events.For logging purpose
        $postfix = "Director: ".$director['StakeHolder']['name'].",Bank: ".$data['bankName'];
        $this->Event->create();
        $created_time = date('Y-m-d H:i:s');
        $unique_hash = sha1($created_time.$director['StakeHolder']['name'].$data['bankName']."3");
        $event_data = array(
            "function_id"=>3,
            "company_id"=>$data['company'],
            "user_id"=>$user['User']['id'],
            "created_time"=>$created_time ,
            "description"=>isset($data['edit'])?"Edit Change of Banking Signators UOB  documents[".$postfix."]":"Create Change of Banking Signators UOB  documents[".$postfix."]",
            "mode"=>"editable",
            "unique_hash"=>$unique_hash
        );
        $this->Event->save($event_data);
        
        
        $event = $this->Event->find("first",array(
            "conditions"=>array(
                "Event.unique_hash"=>$unique_hash,
            )
        ));  

            //save to Document table
           $this->Document->create();
           $hash_value = sha1($data['bankName'].$data['director'].date('Y-m-d H:i:s'));
           $document = array(
               'company_id'=>$data['company'],
               'function_id'=>3,
               'created_at'=>date('Y-m-d H:i:s'),
               'unique_key'=>$hash_value,
               'status'=>"Available",
               'description'=>"Change of Banking Signatories [".$data['bankName']."]"
           );
           $this->Document->save($document);
         
           $documentBankSignatorEntry = $this->Document->find('first',array(
               'conditions'=>array(
                       'Document.unique_key'=>$hash_value
               )
           ));
           $document_id = $documentBankSignatorEntry['Document']['id'];
           
            $data_UOB = array(
                'document_id'=>$document_id ,
                "event_id"=>$event['Event']['id'],
                "director_id"=> $data['director'],
                "bank_name"=>$data['bankName'],
            );
            $this->ChangeBankSignatorUob->create();
           
            $this->ChangeBankSignatorUob->save($data_UOB);   
        
              $data_generate = array(
            "bank"=>$data['bankName'],
            "company"=>$data['company'],
            "function"=>$data['function'],
            "director"=> $director['StakeHolder']['name']
        );
        $form->generateResolutionSignatoriesUOB($data_generate);
        $files_to_zip = $form->form_downloads;
        $time = date('Y-m-d H-i-s');
        $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS .'files' . DS . 'zip' . DS . 'ChangeOfBankingUOB'.$time.'.zip');
        //$this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS.'\files\zip\ChangeOfBankingUOB'.$time.'.zip');
        foreach($files_to_zip as $file){ //Delete files after zipping
            unlink($file);
        };
        //Create zip file
        $this->ZipFile->create();
        $zip_file = array(
            "function_id"=>3,
            "company_id"=>$data['company'],
            "path"=>'ChangeOfBankingUOB'.$time.'.zip',
            "created_at"=>date('Y-m-d H:i:s'),
        );
        $documentControl = new DocumentsController();
        $documentControl->initiateStatusChecking($document_id);
        $this->ZipFile->save($zip_file);
        $this->Session->setFlash(
		    'Forms are generated!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        return $this->redirect(array(
            "controller"=>'forms',
            "action"=>'index'
        ));
    }
    public function editAppointAs() {
        $data = $this->params['url'];
         $documents = $this->AppointSecretaryAuditor->find("all",array(
            "conditions"=>array(
                "Event.id"=>$data['id']
            )
        ));
         $secretaries = $this->StakeHolder->find('all',array(
              "conditions"=>array(
              'StakeHolder.company_id'=>$data['company'],
               'StakeHolder.Secretary'=>1 
           ) 
        ));
        $lastSubmitSecretaries = array();
        $doc_ids = array();
        foreach($documents as $document){
            $d = $this->StakeHolder->find("first",array(
                "conditions"=>array(
                    "StakeHolder.id"=>$document['AppointResignSecretary']['secretary_id']
                )
            ));
            $d['type']=$document['AppointResignSecretary']['type'];
            $d['attn']=$document['AppointResignSecretary']['attn'];
            $d['NameDS']=$document['AppointResignSecretary']['NameDS'];
            array_push($lastSubmitSecretaries,$d);
            array_push($doc_ids,$document['Document']['id']);
        }
        $types = array("appointment","cessation");
        //ChromePhp::log($lastSubmitSecretaries);
        $this->set('title', 'Edit Appoint&Resign Secretaries');
        $this->set("lastSubmitSecretaries",$lastSubmitSecretaries);
         $this->set('secretaries',$secretaries);
         $this->set('types',$types);
         $this->set('company',$data['company']);
         $this->set('event_id',$data['id']);
         $this->set("doc_ids",$doc_ids);
    }
    function generateAppointAs(){
        $form = new FormsController();
        $data = $this->request->data;
        $user = $this->User->find("first",array(
               "conditions"=>array(
                   "User.token"=>$this->Session->read('token')
               )
        ));
        $secretary_id = $data['secretary'][0];
        $auditor_id = $data['auditor'][0];
        //Save Secretary Info
        
//            $data_secretaries = array(
//                "StakeHolder.name"=>"'".$data['sec_name']."'",
//                "StakeHolder.nric"=>"'".$data['sec_nric']."'",
//                "StakeHolder.address_1"=>"'".$data['sec_addr1']."'",
//                "StakeHolder.address_2"=>"'".$data['sec_addr2']."'",
//                "StakeHolder.nationality"=>"'".$data['sec_nationality']."'",
//                "StakeHolder.updated_at"=>"'".date('Y-m-d H:i:s')."'",
//            );
//            
//            $this->StakeHolder->updateAll($data_secretaries,array(
//                   "StakeHolder.id"=>$secretary_id
//            ));
        
                 
  
        $data['function']=2;
           //ChromePhp::log($data); 
         $form->generateForm45B_ASA($data);
          $form->generateForm49ASA($data);
         $form->generateResolutionASA($data);
        $form->generateIndemnityLetter($data);
         $files_to_zip = $form->form_downloads;
         $time = date('Y-m-d H-i-s');
         $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS .'files' . DS . 'zip' . DS . 'AppointSecretaryAuditor'.$time.'.zip');
        //$this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS.'\files\zip\AppointSecretaryAuditor'.$time.'.zip');
        foreach($files_to_zip as $file){ //Delete files after zipping
            unlink($file);
        };
        //Create zip file
        $this->ZipFile->create();
        $zip_file = array(
            "function_id"=>2,
            "company_id"=>$data['company'],
            "path"=>'AppointSecretaryAuditor'.$time.'.zip',
            "created_at"=>date('Y-m-d H:i:s'),
        );
        $this->ZipFile->save($zip_file);
        //for($i = 0;$i < count();$i++){
            $secretary = $this->Secretary->find("first",array(
                    "conditions"=>array(
                        "Secretary.id"=>$secretary_id
                    )
            ));
            $auditor = $this->Auditor->find("first",array(
                    "conditions"=>array(
                        "Auditor.id"=>$auditor_id
                    )
            ));
            $postfix = "Secretary:".$secretary['StakeHolder']['name'].",Auditor:".$auditor['StakeHolder']['name'];
            //Save to Events tables. For logging purpose
            $this->Event->create();
            $created_time = date('Y-m-d H:i:s');
            $unique_hash = sha1($created_time.$postfix."2");
            $event_data = array(
                "function_id"=>2,
                "company_id"=>$data['company'],
                "user_id"=>$user['User']['id'],
                "created_time"=>$created_time ,
                "description"=>isset($data['edit'])?"Edit Appointing-both-secretary-and-auditor documents[".$postfix."]":"Create Appointing-both-secretary-and-auditor documents[".$postfix."]",
                "mode"=>"editable",
                "unique_hash"=>$unique_hash
            );
            $this->Event->save($event_data);


            $event = $this->Event->find("first",array(
                "conditions"=>array(
                    "unique_hash"=>$unique_hash
                )
            ));
           //save to Document table
               $this->Document->create();
               $hash_value = sha1(time()."appointment".date('Y-m-d H:i:s'));
               $document = array(
                   'company_id'=>$data['company'],
                   'function_id'=>2,
                   'created_at'=>date('Y-m-d H:i:s'),
                   'unique_key'=>$hash_value,
                   'status'=>"Available",
                   'description'=>"Appoint Auditor[".$auditor['StakeHolder']['name']."] with Secretary[".$secretary['StakeHolder']['name']."]"
               );
               $this->Document->save($document);
               //Save to DocumentDirector table
//               $documentSecretaryEntry = $this->Document->find('first',array(
//                   'conditions'=>array(
//                           'Document.unique_key'=>$hash_value
//                   )
//               ));
               //$document_id = $documentSecretaryEntry['Document']['id'];
               $documentControl = new DocumentsController();
               $documentControl->initiateStatusChecking($this->Document->id);
                $this->AppointSecretaryAuditor->create();
               $documentSA = array(
                   'document_id'=>$this->Document->id ,
                   "event_id"=>$event['Event']['id'],
                   "secretary_id"=> $secretary['StakeHolder']['id'],
                    "auditor_name"=>$auditor['StakeHolder']['name'],
                    "auditor_address"=>$auditor['StakeHolder']['address_1'],
                    "NameDS"=>$data['prepared_by'],  
               );
            $this->AppointSecretaryAuditor->save($documentSA);
        
        
        $this->Session->setFlash(
		    'Forms are generated!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        return $this->redirect(array(
            "controller"=>'forms',
            "action"=>'index'
        ));
    }
    function generateAppointresignS(){
        $form = new FormsController();
        $data = $this->request->data;
        $user = $this->User->find("first",array(
               "conditions"=>array(
                   "User.token"=>$this->Session->read('token')
               )
        ));
        $secretaries = $data['secretary'];
        $postfix="";
         for($i = 0;$i < count($secretaries);$i++){
            $data_stakeholders = array(
         
                "StakeHolder.nric"=>"'".$data['sec_nric'][$i]."'",
                "StakeHolder.address_1"=>"'".$data['sec_addr1'][$i]."'",
                "StakeHolder.address_2"=>"'".$data['sec_addr2'][$i]."'",
                "StakeHolder.nationality"=>"'".$data['sec_nationality'][$i]."'",
                "StakeHolder.updated_at"=>"'".date('Y-m-d H:i:s')."'"
            );
            $this->StakeHolder->updateAll($data_stakeholders,array(
                   "StakeHolder.id"=>$secretaries[$i]
            ));
            $data_secretaries = array(
                 "Secretary.Mode"=>($data['type'][$i]=="'cessation'"?"Resigned":"'NULL'")
            );
            $this->Secretary->updateAll($data_secretaries,array(
                   "Secretary.id"=>$secretaries[$i]
            ));
            $sec = $this->StakeHolder->find("first",array(
                "conditions"=>array(
                    "StakeHolder.id"=>$secretaries[$i]
                )
            ));
            $postfix .=  ($i != (count($secretaries)-1))?$sec['StakeHolder']['name'].",":$sec['StakeHolder']['name'];
        };
        if(isset($data['edit'])){
           $this->Event->id=$data['event_id'];
           $this->Event->saveField("mode","");
           $document_ids = $data["doc_ids"];
           foreach($document_ids as $id){
               $this->Document->delete($id);
           }
       }
        
        //Save to Events and $postfix EventsDirector table. For logging purpose
        $this->Event->create();
        $created_time = date('Y-m-d H:i:s');
        $unique_hash = sha1($created_time.$data['company'].$postfix."0");
        $event_data = array(
            "function_id"=>0,
            "company_id"=>$data['company'],
            "user_id"=>$user['User']['id'],
            "created_time"=>$created_time ,
            "description"=>isset($data['edit'])?"Edit Appointing/Resigning Secretarys' documents for ".$postfix:"Create Appointing/Resigning Secretarys' documents for ".$postfix,
            "mode"=>"editable",
            "unique_hash"=>$unique_hash
        );
        $this->Event->save($event_data);
        
        
        $event = $this->Event->find("first",array(
            "conditions"=>array(
                "Event.unique_hash"=>$unique_hash,
            )
        ));
     
        $secs = $data['secretary'];
        $types = $data['type'];
        $array_documents_id = array();
        // Change Mode of secretary to resigned in databases
             for ($i = 0; $i < count($types); $i++) {
                 $secretary = $this->StakeHolder->find("first",array(
                     "conditions"=>array(
                         "StakeHolder.id"=>$secs[$i]
                     )
                 ));
                 if(strcmp($types[$i],"cessation")==0){
                     //ChromePhp::log($directors[$i]);
                   
                     $this->Secretary->updateAll(array(
                            'Secretary.Mode'=>"'Resigned'"
                        ),array(
                            'Secretary.id' => $secs[$i],
                        )
                     );
                     
                     //save to Document table
                    $this->Document->create();
                    $hash_value = sha1($i."cessation".date('Y-m-d H:i:s'));
                    $document = array(
                        'company_id'=>$data['company'],
                        'function_id'=>0,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'unique_key'=>$hash_value,
                        'status'=>"Available",
                        "description"=>"Resignation of Secretary ".$secretary['StakeHolder']['name']
                    );
                    $this->Document->save($document);
                    // Save to AppointResignSecretary table
                    $documentSecretaryEntry = $this->Document->find('first',array(
                        'conditions'=>array(
                                'Document.unique_key'=>$hash_value
                        )
                    ));
                    $document_id = $documentSecretaryEntry['Document']['id'];   
                    array_push($array_documents_id,$document_id);
                            $data_ARS = array(
                                "document_id"=>$document_id, 
                                "event_id"=>$event['Event']['id'],
                                "secretary_id"=>$secs[$i],
                                "type"=>"cessation",
                                "attn"=>$data['reportedTo'][$i],
                               "NameDS"=>$data['prepared_by']
                            );
                            $this->AppointResignSecretary->create();

                            $this->AppointResignSecretary->save($data_ARS);    

    
                    }
             }
             
              //Change Mode of secretaries to appointed in databases
             
             for ($i = 0; $i < count($types); $i++) {
                 $secretary = $this->StakeHolder->find("first",array(
                     "conditions"=>array(
                         "StakeHolder.id"=>$secs[$i]
                     )
                 ));
                 if(strcmp($types[$i],"appointment")==0){
                    
                   
                     $this->Secretary->updateAll(array(
                            'Secretary.Mode'=>null
                        ),array(
                            'Secretary.id' => $secs[$i]
                        )
                     );
                     
                     //save to Document table
                    $this->Document->create();
                    $hash_value = sha1($i."appointment".date('Y-m-d H:i:s'));
                    $document = array(
                        'company_id'=>$data['company'],
                        'function_id'=>0,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'unique_key'=>$hash_value,
                        'status'=>"Available",
                        "description"=>"Appointment of Secretary ".$secretary['StakeHolder']['name']
                    );
                    $this->Document->save($document);
                    //Save to DocumentDirector table
                    $documentSecretaryEntry = $this->Document->find('first',array(
                        'conditions'=>array(
                                'Document.unique_key'=>$hash_value
                        )
                    ));
                    $document_id = $documentSecretaryEntry['Document']['id'];
                    array_push($array_documents_id,$document_id);
                    $secs = $data['secretary'];
                            $data_ARS = array(
                                "document_id"=>$document_id,
                                "event_id"=>$event['Event']['id'],
                                "secretary_id"=>$secs[$i],
                                "type"=>"appointment",
                                "attn"=>"",
                                "NameDS"=>$data['prepared_by']
                            );
                            $this->AppointResignSecretary->create();

                            $this->AppointResignSecretary->save($data_ARS);     
                    }
        }
        $data['function']=0;
          $form->generateForm45B($data);
         $form->generateForm49($data);
         $form->generateResolutionASRS($data);
         $form->generateResignationLetter($data);
         $form->generateIndemnityLetter($data);

          $files_to_zip = $form->form_downloads;
          $time = date('Y-m-d H-i-s');
           $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS .'files' . DS . 'zip' . DS . 'AppointResignSecretary'.$time.'.zip');
        //$this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS.'\files\zip\AppointResignSecretary'.$time.'.zip');
        foreach($files_to_zip as $file){ //Delete files after zipping
            unlink($file);
        };
        $documentControl = new DocumentsController();
        foreach($array_documents_id as $id){
            $documentControl->initiateStatusChecking($id);
        }
        //Create zip file
        $this->ZipFile->create();
        $zip_file = array(
            "function_id"=>0,
            "company_id"=>$data['company'],
            "path"=>'AppointResignSecretary'.$time.'.zip',
            "created_at"=>date('Y-m-d H:i:s'),
        );
        $this->ZipFile->save($zip_file);
        $this->Session->setFlash(
		    'Forms are generated!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        return $this->redirect(array(
            "controller"=>'forms',
            "action"=>'index',
        ));
        
    }
    
//    function preview2(){
//        if(isset($this->params['url']['edit'])){
//            $event_id = $this->request['url']['id'];
//             $company_id = $this->request['url']['company'];
//             $submission = $this->AppointSecretaryAuditor->find("first",array(
//                 "conditions"=>array(
//                     "AppointSecretaryAuditor.event_id"=>$event_id
//                 )
//             ));
//           
//             $preview_data = array();
//             
//             $preview_data['title']="Edit Last Submission";
//             $preview_data['function']=2;
//             $preview_data['company']=$company_id;
//             $preview_data['prepared_by']=$submission['AppointSecretaryAuditor']['NameDS'];
//             $secretary = $this->Secretary->find('first', array('conditions' => array('Secretary.id = ' => $submission['StakeHolder']['id'])));
//             $preview_data["auditor"]=array(
//                    "name"=>$submission['AppointSecretaryAuditor']['auditor_name'],
//                    "address"=>$submission['AppointSecretaryAuditor']['auditor_address']
//             );
//                
//               $preview_data['Secretary'] = $secretary ;
//               $preview_data['edit'] = "y";
//        }else{
//            $data = $this->request->data;
//
//            $id = $data['secretary'];
//
//            $secretary = $this->Secretary->find('first', array('conditions' => array('Secretary.id' =>  $id )));    
//            $auditor = $this->Auditor->find('first', array('conditions' => array('Auditor.id' =>  $id ))); 
//            $preview_data = array(
//                        "title"=>'Preview Form',
//                        "Secretary"=>$secretary,
//                        "prepared_by"=>$data['prepared_by'],
//                        "Auditor"=>$auditor,
//                        "company"=>$data['company'],
//                        "function"=>$data['function']
//                    );
//        }
//        
//           $total_secretaries = $this->Secretary->find("all");  
//           $total_auditors = $this->Secretary->find("all"); 
//        $this->set('preview_data', $preview_data);
//         $this->set('total_secretaries', $total_secretaries);
//    }
    function preview0(){
       
        $data = $this->request->data;
        $ids = $data['director'];
        $secretaries = array();
        foreach ($ids as $sec_id) {
                $secretary = $this->Secretary->find('first', array('conditions' => array('Secretary.id = ' => $sec_id)));
                array_push($secretaries, $secretary);

        }

        for($i = 0;$i < count($secretaries);$i++){
            if($data['type'][$i]==="cessation"){
                $secretaries[$i]["reportedTo"] = $data['attn'][$i];
                $secretaries[$i]["type"] = "cessation";
            }else{
                $secretaries[$i]["reportedTo"] = "";
                $secretaries[$i]["type"] = "appointment";
            }
       }  

         $preview_data = array(
                "title"=>'Preview Form',
                "Secretary"=>$secretaries,
                "prepared_by"=>$data['prepared_by'],
                "company"=>$data['company'],
              
         );
        
        
        $total_secretaries = $this->Secretary->find("all");
      
        $this->set('preview_data', $preview_data);
        $this->set('total_secretaries', $total_secretaries);
        if(isset($data['edit'])){
            $this->set('edit', $data['edit']);
            $this->set('event_id',$data['event_id']);
            $this->set("doc_ids",$data['doc_ids']);
        }
        
    }
    function displayLastSubmissions(){
        $function_id = $this->request->data['functionCorp'];
        $company_id = $this->request->data['company'];
        $token = $this->Session->read('token');
        $user = $this->User->find("first",array(
                "conditions"=>array(
                    "User.token"=>$token
                )
        ));
        $events = $this->Event->find("all",array(
            "conditions"=>array(
                "Event.function_id"=>$function_id,
                "Event.company_id"=>$company_id,
                "Event.user_id"=>$user['User']['id'],
                "Event.mode"=>"editable"
            ),
            "order"=>"Event.created_time DESC"
        ));
        
        $this->set("events",$events);
        
    }
    function ChangeOfBankingSignatoriesUOB(){
        $data = $this->request->data;
        $directors = $this->StakeHolder->find("all",array(
            "conditions"=>Array(
                "StakeHolder.company_id"=>$data['company'],
                "StakeHolder.Director"=>1,
            )
        ));
        $this->set("company",$data['company']);
        $this->set("function",$data['functionCorp']);
        $this->set("directors",$directors);
        //ChromePhp::log($data);
    }
    function preview3(){
        $event_id = $this->request['url']['id'];
             $company_id = $this->request['url']['company'];
             $directors = $this->Director->find("all");
             $submission = $this->ChangeBankSignatorUob->find("first",array(
                 "conditions"=>array(
                     "ChangeBankSignatorUob.event_id"=>$event_id
                 )
             ));
          
        $this->set("company",$company_id);
        $this->set("function", $this->request['url']['function_id']);
        $this->set("directors",$directors);
        $this->set("selected_director",$submission['StakeHolder']['id']);
        $this->set("bankName",$submission['ChangeBankSignatorUob']['bank_name']);
        $this->set("edit","y");
        
        ChromePhp::log($directors);
    }
    function changeOfCompanyName(){   
        if(isset($this->params['url']['company'])){
            $company = $this->params['url']['company'];
        }else{
            $data=$this->request->data;
            $company = $data['company'];
        }
        $directors= $this->StakeHolder->find('all',array(
              "conditions"=>array(
              'StakeHolder.company_id'=>$company,
              'StakeHolder.Director'=>1
           ) 
        ));
         $shareholders= $this->StakeHolder->find('all',array(
              "conditions"=>array(
              'StakeHolder.company_id'=>$company,
              'StakeHolder.ShareHolder'=>1
           ) 
        ));
        $this->set('title', 'Change Company Name');
         $this->set('directors',$directors);
         $this->set('shareholders',$shareholders);
         $this->set('company',$company);   
         
    }
    function preview4(){
         if(isset($this->params['url']['edit'])){
            $event_id = $this->request['url']['id'];
             $company_id = $this->request['url']['company'];
             $submission = $this->ChangeCompanyName->find("first",array(
                 "conditions"=>array(
                     "ChangeCompanyName.event_id"=>$event_id
                 )
             ));
			 $directors=explode(",",$submission['ChangeCompanyName']['directors']);
			 $shareholders=explode(",",$submission['ChangeCompanyName']['shareholders']);
			   $preview_data=array(
                "title"=>"Edit Last Submission",
                "Director"=>$directors,
                "Shareholder"=>$shareholders,
                "newCompanyName"=>$submission['ChangeCompanyName']['new_company'],
                "nameDS"=>$submission['ChangeCompanyName']['nameDS'],
                "meeting1"=>$submission['ChangeCompanyName']['meeting_address1'],
                "meeting2"=>$submission['ChangeCompanyName']['meeting_address2'],
                "chairman"=>$submission['ChangeCompanyName']['chairman'],
                "company"=>$submission['ChangeCompanyName']['old_company'],
				'edit'=>"y"
            );
			   
        }else{
            $data=$this->request->data;
            $directors_obj = $this->StakeHolder->find("all",array(
                "conditions"=>array(
                    "StakeHolder.id"=>$data['director']
                    )
                )
            );
            $shareholders_obj = $this->StakeHolder->find("all",array(
                "conditions"=>array(
                    "StakeHolder.id"=>$data['shareholder']
                    )
                )
            );
			$directors=array();
			$shareholders=array();
			for($i =0;$i < count($directors_obj);$i++){
				array_push($directors,$directors_obj[$i]['StakeHolder']['name']);
			};
			
			for($i =0;$i < count($shareholders_obj);$i++){
				array_push($shareholders,$shareholders_obj[$i]['StakeHolder']['name']);
			};
			
            $preview_data=array(
                "title"=>"Change Company name",
                "Director"=>$directors,
                "Shareholder"=>$shareholders,
                "newCompanyName"=>$data['new_company_name'],
                "nameDS"=>$data['prepared_by'],
                "meeting1"=>$data['m_address1'],
                "meeting2"=>$data['m_address2'],
                "chairman"=>$data['chairman'],
                "company"=>$directors_obj[0]['Company']['company_id']
            );
        }
        $this->set("data",$preview_data);
    }
    function generateChangeOfCompanyName(){
        $form = new FormsController();
        $data = $this->request->data;
        $directors = "";
        for($i = 0 ; $i < count($data['director']);$i++){
            if($i ==count($data['director'])- 1 ){
                $directors.= $data['director'][$i];
            }else{
                $directors.= $data['director'][$i].",";
            }
            
        };
         $shareholders = "";
        for($i = 0 ; $i < count($data['shareholder']);$i++){
            if($i ==count($data['shareholder'])- 1 ){
                $shareholders .= $data['shareholder'][$i];
            }else{
                $shareholders .= $data['shareholder'][$i].",";
            }
            
        };
        $user = $this->User->find("first",array(
               "conditions"=>array(
                   "User.token"=>$this->Session->read('token')
               )
        ));
        $company = $this->Company->find("first",array(
            "conditions"=>array(
                "Company.company_id"=>$data['company']
            )
        ));
        $postfix="\n[Last Company nane: ".$company['Company']['name'].",New company name: ".$data['new_company_name'];
        $this->Event->create();
        $created_time = date('Y-m-d H:i:s');
        $unique_hash = sha1(date('Y-m-d H:i:s').$user['User']['id'].$data['company']."4");
        $event_data = array(
            "function_id"=>4,
            "company_id"=>$data['company'],
            "user_id"=>$user['User']['id'],
            "created_time"=>$created_time ,
            "description"=>isset($data['edit'])? "Edit Changing company name documents".$postfix:"Create Change Company name documents for ".$postfix,
            "mode"=>"editable",
            "unique_hash"=>$unique_hash 
        );
        $this->Event->save($event_data);
        
        
        $event = $this->Event->find("first",array(
            "conditions"=>array(
                "Event.unique_hash"=>$unique_hash ,
            )
        ));
               $this->Document->create();
               $hash_value = sha1($directors."changeCompanyName".date('Y-m-d H:i:s'));
               $document = array(
                   'company_id'=>$data['company'],
                   'function_id'=>4,
                   'created_at'=>date('Y-m-d H:i:s'),
                   'unique_key'=>$hash_value,
                   'status'=>"Available",
                   "description"=>"Change of Company Name from ".$company['Company']['name']." to ".$data['new_company_name']
               );
               $this->Document->save($document);
             
               $documentEntry = $this->Document->find('first',array(
                   'conditions'=>array(
                           'Document.unique_key'=>$hash_value
                   )
               ));
                   $data_CCN = array(
                       "document_id"=>$documentEntry['Document']['id'],
                       "event_id"=>$event['Event']['id'],
                       "directors"=>$directors,
                       "nameDS"=>$data['prepared_by'],
                       "shareholders"=>$shareholders,
                       "chairman"=>$data['chairman'],
                       "meeting_address1"=>$data['m_address1'],
                       "meeting_address2"=>$data['m_address2'],
                       "new_company"=>$data['new_company_name'],
					   "old_company"=>$company['Company']['name'],
                       
                   );
                   $this->ChangeCompanyName->create();
                   $this->ChangeCompanyName->save($data_CCN);
            
            $input = array(
                "company"=>$data['company'],
                "directors"=>$directors,
                "nameDS"=>$data['prepared_by'],
                "shareholders"=>$shareholders,
                "chairman"=>$data['chairman'],
                "meeting_address1"=>$data['m_address1'],
                "meeting_address2"=>$data['m_address2'],
                "new_company"=>$data['new_company_name']
                
                
            );
           $form->generateIndemnityLetter($input);
           $form->generateform11($input);
           $form->generateEOGM($input);
		   
		   //Save new company
//		   $this->Company->id = $data['company'];
//		   $this->Company->saveField("name",$data['new_company_name']);
                    $files_to_zip = $form->form_downloads;
                    $time = date('Y-m-d H-i-s');
        $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS .'files' . DS . 'zip' . DS . 'ChangeCompanyName'.$time.'.zip');
       // $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS.'\files\zip\ChangeCompanyName'.$time.'.zip');
        foreach($files_to_zip as $file){ //Delete files after zipping
            unlink($file);
        };
        //Create zip file
        $this->ZipFile->create();
        $zip_file = array(
            "function_id"=>4,
            "company_id"=>$data['company'],
            "path"=>'ChangeCompanyName'.$time.'.zip',
            "created_at"=>date('Y-m-d H:i:s'),
        );
        $this->ZipFile->save($zip_file);
		   $this->Session->setFlash(
		    'Forms are generated!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        $documentControl = new DocumentsController();
        $documentControl->initiateStatusChecking($documentEntry['Document']['id']);
        return $this->redirect(array(
            "controller"=>'forms',
            "action"=>'index',
        ));
        }
    public function changeOfFY() {
        $data = $this->request->data;
        $company = $this->Company->find("first",array(
            "conditions"=>array(
                "Company.company_id"=>$data['company']
            )
        ));
        $fy=$company['Company']['FinancialYear'];
        $view_data = array(
            "function_id"=>$data['functionCorp'],
            "company_id"=>$data['company'],
            "fy"=>$fy
        );
        $this->set("view_data",$view_data);
    }
   public function generateChangeFY(){
       //Require company_id
       $form = new FormsController();
       $data = $this->request->data;
       $new_FY = $data['newFY'];
       $function_id = $data['function_id'];
       $com= $this->Company->find("first",array(
           "conditions"=>array(
               "Company.company_id"=>$data['company_id']
           )
       ));
       $pre_FY = $com['Company']['FinancialYear'];
       $send_data = array(
           "company"=>$data['company_id'],
           "pre_FY"=>$pre_FY,
           "new_FY"=>$new_FY
                      
       );       
       $form->generateIndemnityLetter($send_data);
       $form->generateResolutionFY($send_data);
       //Update company financial year
       $this->Company->id = $com['Company']['company_id'];
       $this->Company->saveField("FinancialYear",$new_FY);
       //Create Document
        $this->Document->create();
        $hash_value = sha1($data['company_id'].$new_FY.date('Y-m-d H:i:s'));
        $document = array(
            'company_id'=>$data['company_id'],
            'function_id'=>5,
            'created_at'=>date('Y-m-d H:i:s'),
            'unique_key'=>$hash_value,
            'status'=>"Available",
            'description'=>"Change financial year from ".$pre_FY." to ".$new_FY
        );
        $this->Document->save($document);//end
  
        $documentEntry = $this->Document->find('first',array(
            'conditions'=>array(
                    'Document.unique_key'=>$hash_value
            )
        ));
        $data_FY = array(
                "document_id"=>$documentEntry['Document']['id'],
                //"event_id"=>null,
//                "pre_FY"=>$pre_FY,
//                "new_FY"=>$new_FY
         );
        $this->ChangeFinancialYear->create();

        $this->ChangeFinancialYear->save($data_FY );//end
         $files_to_zip = $form->form_downloads;
          $time = date('Y-m-d H-i-s');
          $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS .'files' . DS . 'zip' . DS . 'ChangeFinancialYear'.$time.'.zip');
       // $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS.'\files\zip\ChangeFinancialYear'.$time.'.zip');
        foreach($files_to_zip as $file){ //Delete files after zipping
            unlink($file);
        };
        //Create zip file
        $this->ZipFile->create();
        $zip_file = array(
            "function_id"=>5,
            "company_id"=>$data['company_id'],
            "path"=>'ChangeFinancialYear'.$time.'.zip',
            "created_at"=>date('Y-m-d H:i:s'),
        );
        $this->ZipFile->save($zip_file);
        $documentControl = new DocumentsController();
        $documentControl->initiateStatusChecking($documentEntry['Document']['id']);

        $this->Session->setFlash(
		    'Forms are generated!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        return $this->redirect(array(
            "controller"=>'forms',
            "action"=>'index',
        ));
       
       
       //Create Event and edit last submission
       //CreateDocument
   }
      public function changeOfMAA() {
        if(isset($this->params['url']['company'])){
            $company = $this->params['url']['company'];
        }else{
            $data=$this->request->data;
            $company = $data['company'];
        }
        $directors= $this->StakeHolder->find('all',array(
              "conditions"=>array(
              'StakeHolder.company_id'=>$company,
              'StakeHolder.Director'=>1
           ) 
        ));
         $shareholders= $this->StakeHolder->find('all',array(
              "conditions"=>array(
              'StakeHolder.company_id'=>$company,
              'StakeHolder.ShareHolder'=>1
           ) 
        ));
        $this->set('title', 'Change of M&AA-Directorship');
         $this->set('directors',$directors);
         $this->set('shareholders',$shareholders);
         $this->set('company',$company);   
    }
    public function preview5(){
        if(isset($this->params['url']['edit'])){
            $event_id = $this->request['url']['id'];
             $company_id = $this->request['url']['company'];
             $submission = $this->ChangeCompanyName->find("first",array(
                 "conditions"=>array(
                     "ChangeCompanyName.event_id"=>$event_id
                 )
             ));
			 $directors=explode(",",$submission['ChangeCompanyName']['directors']);
			 $shareholders=explode(",",$submission['ChangeCompanyName']['shareholders']);
			   $preview_data=array(
                "title"=>"Edit Last Submission",
                "Director"=>$directors,
                "Shareholder"=>$shareholders,
                "newCompanyName"=>$submission['ChangeCompanyName']['new_company'],
                "nameDS"=>$submission['ChangeCompanyName']['nameDS'],
                "meeting1"=>$submission['ChangeCompanyName']['meeting_address1'],
                "meeting2"=>$submission['ChangeCompanyName']['meeting_address2'],
                "chairman"=>$submission['ChangeCompanyName']['chairman'],
                "company"=>$submission['ChangeCompanyName']['old_company'],
				'edit'=>"y"
            );
			   
        }else{
            $data=$this->request->data;
            $directors_obj = $this->StakeHolder->find("all",array(
                "conditions"=>array(
                    "StakeHolder.id"=>$data['director']
                    )
                )
            );
            $shareholders_obj = $this->StakeHolder->find("all",array(
                "conditions"=>array(
                    "StakeHolder.id"=>$data['shareholder']
                    )
                )
            );
			$directors=array();
			$shareholders=array();
			for($i =0;$i < count($directors_obj);$i++){
				array_push($directors,$directors_obj[$i]['StakeHolder']['name']);
			};
			
			for($i =0;$i < count($shareholders_obj);$i++){
				array_push($shareholders,$shareholders_obj[$i]['StakeHolder']['name']);
			};
			
            $preview_data=array(
                "title"=>"Change Company name",
                "Director"=>$directors,
                "Shareholder"=>$shareholders,
                "nameDS"=>$data['prepared_by'],
                "meeting1"=>$data['m_address1'],
                "meeting2"=>$data['m_address2'],
                "chairman"=>$data['chairman'],
                "company"=>$directors_obj[0]['Company']['company_id']
            );
        }
        $this->set("data",$preview_data);
    }
    
    public function generateChangeOfMAA(){
        $form = new FormsController();
        $data = $this->request->data;
        $directors = "";
        for($i = 0 ; $i < count($data['director']);$i++){
            if($i ==count($data['director'])- 1 ){
                $directors.= $data['director'][$i];
            }else{
                $directors.= $data['director'][$i].",";
            }
            
        };
         $shareholders = "";
        for($i = 0 ; $i < count($data['shareholder']);$i++){
            if($i ==count($data['shareholder'])- 1 ){
                $shareholders .= $data['shareholder'][$i];
            }else{
                $shareholders .= $data['shareholder'][$i].",";
            }
            
        };
            $input = array(
                "company"=>$data['company'],
                "directors"=>$directors,
                "nameDS"=>$data['prepared_by'],
                "shareholders"=>$shareholders,
                "chairman"=>$data['chairman'],
                "meeting_address1"=>$data['m_address1'],
                "meeting_address2"=>$data['m_address2'],
                
                
            );
           $form->generateIndemnityLetter($input);
           $form->generateform11_MAA($input);
           $form->generateEOGM_MAA($input);
           //Create Document
        $this->Document->create();
        $hash_value = sha1($data['company']."generateChangeOfMAA".date('Y-m-d H:i:s'));
        $document = array(
            'company_id'=>$data['company'],
            'function_id'=>6,
            'created_at'=>date('Y-m-d H:i:s'),
            'unique_key'=>$hash_value,
            'status'=>"Available",
            'description'=>"Change Of MAA"
        );
        $this->Document->save($document);//end
  
        $documentEntry = $this->Document->find('first',array(
            'conditions'=>array(
                    'Document.unique_key'=>$hash_value
            )
        ));
        $data_MAA= array(
                "document_id"=>$documentEntry['Document']['id'],
                //"event_id"=>null,
                //Add later
         );
        $this->ChangeOfMAA->create();

        $this->ChangeOfMAA->save($data_MAA);//end
         $files_to_zip = $form->form_downloads;
          $time = date('Y-m-d H-i-s');
          $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS .'files' . DS . 'zip' . DS . 'ChangeOfMAA'.$time.'.zip');
        //$this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS.'\files\zip\ChangeOfMAA'.$time.'.zip');
        foreach($files_to_zip as $file){ //Delete files after zipping
            unlink($file);
        };
        //Create zip file
        $this->ZipFile->create();
        $zip_file = array(
            "function_id"=>6,
            "company_id"=>$data['company'],
            "path"=>'ChangeOfMAA'.$time.'.zip',
            "created_at"=>date('Y-m-d H:i:s'),
        );
        $documentControl = new DocumentsController();
        $documentControl->initiateStatusChecking($documentEntry['Document']['id']);
        $this->ZipFile->save($zip_file);

		   $this->Session->setFlash(
		    'Forms are generated!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        return $this->redirect(array(
            "controller"=>'forms',
            "action"=>'index',
        ));
    }
    public function ClosureOfBankAccResolution(){
        $data = $this->request->data;
        //ChromePhp::log($data);
        $this->set("view_data",$data);
    }
    public function generateClosureOfBankAccResolution(){
        $form = new FormsController();
        $data=$this->request->data;
//        ChromePhp:: log($data);
        $form->generateClosureOfBankAccResolution($data);
       //Create Document
        $this->Document->create();
        $hash_value = sha1($data['company_id']."generateClosureOfBankAccResolution".date('Y-m-d H:i:s'));
        $document = array(
            'company_id'=>$data['company_id'],
            'function_id'=>7,
            'created_at'=>date('Y-m-d H:i:s'),
            'unique_key'=>$hash_value,
            'status'=>"Available",
            'description'=>"Closure of Bank Account Resolution"
        );
        $this->Document->save($document);
  
        $documentEntry = $this->Document->find('first',array(
            'conditions'=>array(
                    'Document.unique_key'=>$hash_value
            )
        ));
        $data_ClosureBankAcc= array(
                "document_id"=>$documentEntry['Document']['id'],
                //"event_id"=>null,
                //Add later
         );
        $this->ClosureBankAcc->create();

        $this->ClosureBankAcc->save($data_ClosureBankAcc);//end
         $files_to_zip = $form->form_downloads;
          $time = date('Y-m-d H-i-s');
          $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS .'files' . DS . 'zip' . DS . 'ClosureBankAcc'.$time.'.zip');
        //$this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS.'\files\zip\ClosureBankAcc'.$time.'.zip');
        foreach($files_to_zip as $file){ //Delete files after zipping
            unlink($file);
        };
        //Create zip file
        $this->ZipFile->create();
        $zip_file = array(
            "function_id"=>7,
            "company_id"=>$data['company_id'],
            "path"=>'ClosureBankAcc'.$time.'.zip',
            "created_at"=>date('Y-m-d H:i:s'),
        );
        $this->ZipFile->save($zip_file);
        $documentControl = new DocumentsController();
        $documentControl->initiateStatusChecking($documentEntry['Document']['id']);
        $this->Session->setFlash(
		    'Forms are generated!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        return $this->redirect(array(
            "controller"=>'forms',
            "action"=>'index',
        ));
    }
    public function LoanResolution(){
        $data = $this->request->data;
         $this->set("view_data",$data);
    }
    public function generateLoanResolution(){
        $form = new FormsController();
        $data=$this->request->data;
        $form->generateLoanResolution($data);
         //Create Document
        $this->Document->create();
        $hash_value = sha1($data['company_id']."generateLoanResolution".date('Y-m-d H:i:s'));
        $document = array(
            'company_id'=>$data['company_id'],
            'function_id'=>8,
            'created_at'=>date('Y-m-d H:i:s'),
            'unique_key'=>$hash_value,
            'status'=>"Available",
            'description'=>"Loan Resolution"
        );
        $this->Document->save($document);
  
        $documentEntry = $this->Document->find('first',array(
            'conditions'=>array(
                    'Document.unique_key'=>$hash_value
            )
        ));
        $data_LoanResolution= array(
                "document_id"=>$documentEntry['Document']['id'],
                //"event_id"=>null,
                //Add later
         );
        $this->LoanResolution->create();
        
        $this->LoanResolution->save($data_LoanResolution);//end
         $files_to_zip = $form->form_downloads;
          $time = date('Y-m-d H-i-s');
          $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS .'files' . DS . 'zip' . DS . 'LoanResolution'.$time.'.zip');
        //$this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS.'\files\zip\LoanResolution'.$time.'.zip');
        foreach($files_to_zip as $file){ //Delete files after zipping
            unlink($file);
        };
        //Create zip file
        $this->ZipFile->create();
        $zip_file = array(
            "function_id"=>8,
            "company_id"=>$data['company_id'],
            "path"=>'LoanResolution'.$time.'.zip',
            "created_at"=>date('Y-m-d H:i:s'),
        );
        $this->ZipFile->save($zip_file);
        $documentControl = new DocumentsController();
        $documentControl->initiateStatusChecking($documentEntry['Document']['id']);
        $this->Session->setFlash(
		    'Forms are generated!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        return $this->redirect(array(
            "controller"=>'forms',
            "action"=>'index',
        ));
        
    }
    public function OptionToPurchase(){
        $data = $this->request->data;      
        $this->set("view_data",$data);
    }
    public function generateOptionToPurchase(){
        $form = new FormsController();
        $data=$this->request->data;
        $form->generateOptionToPurchase($data);
        //Create Document
        $this->Document->create();
        $hash_value = sha1($data['company_id']."generateOptionToPurchase".date('Y-m-d H:i:s'));
        $document = array(
            'company_id'=>$data['company_id'],
            'function_id'=>9,
            'created_at'=>date('Y-m-d H:i:s'),
            'unique_key'=>$hash_value,
            'status'=>"Available",
            'description'=>'Option To Purchase'
        );
        $this->Document->save($document);
  
        $documentEntry = $this->Document->find('first',array(
            'conditions'=>array(
                    'Document.unique_key'=>$hash_value
            )
        ));
        $data_OptionToPurchase= array(
                "document_id"=>$documentEntry['Document']['id'],
                //"event_id"=>null,
                //Add later
         );
        $this->OptionToPurchase->create();

        $this->OptionToPurchase->save($data_OptionToPurchase);//end
         $files_to_zip = $form->form_downloads;
         $time = date('Y-m-d H-i-s');
         $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS .'files' . DS . 'zip' . DS . 'OptionToPurchase'.$time.'.zip');
        //$this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS.'\files\zip\OptionToPurchase'.$time.'.zip');
        foreach($files_to_zip as $file){ //Delete files after zipping
            unlink($file);
        };
        //Create zip file
        $this->ZipFile->create();
        $zip_file = array(
            "function_id"=>9,
            "company_id"=>$data['company_id'],
            "path"=>'OptionToPurchase'.$time.'.zip',
            "created_at"=>date('Y-m-d H:i:s'),
        );
        $this->ZipFile->save($zip_file);
        $documentControl = new DocumentsController();
        $documentControl->initiateStatusChecking($documentEntry['Document']['id']);
        $this->Session->setFlash(
		    'Forms are generated!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        return $this->redirect(array(
            "controller"=>'forms',
            "action"=>'index',
        ));
    }

    public function ChangeOfPassport(){
        $data = $this->request->data;
        $stakeholders = $this->StakeHolder->find("all",array(
            "conditions"=>array(
                "StakeHolder.company_id"=>$data['company']    
            )
        ));
        $this->set("title","Change of Passport");
        $this->set("stakeholders",$stakeholders);
        $this->set("company",$data['company']);
        $this->set("function",$data['functionCorp']);
    }
    public function generateChangeOfPassport(){
        $data = $this->request->data;
        //ChromePhp::log($data);
        $form = new FormsController();
        $form->generateForm49COP($data);
        $form->generateIndemnityLetter($data);
        //Create Document
        $this->Document->create();
        $hash_value = sha1($data['company']."generateChangeOfPassport".date('Y-m-d H:i:s'));
        $document = array(
            'company_id'=>$data['company'],
            'function_id'=>11,
            'created_at'=>date('Y-m-d H:i:s'),
            'unique_key'=>$hash_value,
            'status'=>"Available",
            'description'=>'Change of Passport'
        );
        $this->Document->save($document);
  
        $documentEntry = $this->Document->find('first',array(
            'conditions'=>array(
                    'Document.unique_key'=>$hash_value
            )
        ));
        $data_ChangeOfPassport= array(
                "document_id"=>$documentEntry['Document']['id'],
                //"event_id"=>null,
                //Add later
         );
        $this->ChangeOfPassport->create();
        
        $this->ChangeOfPassport->save($data_ChangeOfPassport);//end
         $files_to_zip = $form->form_downloads;
         $time = date('Y-m-d H-i-s');
         
        $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS .'files' . DS . 'zip' . DS . 'ChangeOfPassport'.$time.'.zip');
        foreach($files_to_zip as $file){ //Delete files after zipping
            unlink($file);
        };
        $documentControl = new DocumentsController();
        $documentControl->initiateStatusChecking($documentEntry['Document']['id']);
        //Create zip file
        $this->ZipFile->create();
        $zip_file = array(
            "function_id"=>11,
            "company_id"=>$data['company'],
            "path"=>'ChangeOfPassport'.$time.'.zip',
            "created_at"=>date('Y-m-d H:i:s'),
        );
        $this->ZipFile->save($zip_file);
        
        $this->Session->setFlash(
		    'Forms are generated!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        return $this->redirect(array(
            "controller"=>'forms',
            "action"=>'index',
        ));
    }
    public function ChangeRegisteredAddress() {
        $data = $this->request->data;
        $company = $this->Company->find("first",array(
            "conditions"=>array(
                "Company.company_id"=>$data['company']
            )
        ));
        $com_address=$company['Company']['address_1']." ".$company['Company']['address_2'];
        $view_data = array(
            "function_id"=>$data['functionCorp'],
            "company_id"=>$data['company'],
            "last_address"=>$com_address
        );
        $this->set("view_data",$view_data);
    }
    function generateChangeRegisteredAddress(){
        $data = $this->request->data;
        //ChromePhp::log($data);
        $form = new FormsController();
        $form->generateForm44A($data);
        $form->generateIndemnityLetter($data);
        $form->generateResolutionRegisteredAddress($data);
       
        //Create Document
        $this->Document->create();
        $hash_value = sha1($data['company']." generateChangeRegisteredAddress".date('Y-m-d H:i:s'));
        $document = array(
            'company_id'=>$data['company'],
            'function_id'=>12,
            'created_at'=>date('Y-m-d H:i:s'),
            'unique_key'=>$hash_value,
            'status'=>"Available",
            'description'=>'Change Register Address'
        );
        $this->Document->save($document);
  
        $documentEntry = $this->Document->find('first',array(
            'conditions'=>array(
                    'Document.unique_key'=>$hash_value
            )
        ));
        $documentControl = new DocumentsController();
        $documentControl->initiateStatusChecking($documentEntry['Document']['id']);
        $company=$this->Company->find("first",array(
            "conditions"=>array(
                "Company.company_id"=>$data['company']
            )
        ));
        $data_ChangeOfRegisteredAddress= array(
                "document_id"=> $this->Document->id,
                "old_address"=>$company['Company']['address_1']." ".$company['Company']['address_2'],
                "new_address"=>$data['address_1']." ".$data['address_2'],
                "nameDS"=>$data['nameDS']
                //"event_id"=>null,
                //Add later
         );
        
        $this->ChangeOfRegisteredAddress->create();

        $this->ChangeOfRegisteredAddress->save($data_ChangeOfRegisteredAddress);//end
         //Save company
      
        $new_company_adress = array(
            "Company.address_1"=>"'".$data['address_1']."'",
            "Company.address_2"=>"'".$data['address_2']."'",
        );
        $this->Company->updateAll($new_company_adress,array(
            "Company.company_id"=>$data['company']
        )); 
        
        $files_to_zip = $form->form_downloads;
         $time = date('Y-m-d H-i-s');
        $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS .'files' . DS . 'zip' . DS . 'ChangeOfRegisteredAddress'.$time.'.zip');
        foreach($files_to_zip as $file){ //Delete files after zipping
            unlink($file);
        };
        //Create zip file
        $this->ZipFile->create();
        $zip_file = array(
            "function_id"=>12,
            "company_id"=>$data['company'],
            "path"=>'ChangeOfRegisteredAddress'.$time.'.zip',
            "created_at"=>date('Y-m-d H:i:s'),
        );
        $this->ZipFile->save($zip_file);
        $this->Session->setFlash(
		    'Forms are generated!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        return $this->redirect(array(
            "controller"=>'forms',
            "action"=>'index',
        ));
    }
    public function SalesAssetBusiness() {
        $data = $this->request->data;
        $this->set("view_data",$data);
        
    }
    public function generateSalesAssetBusiness(){
        $data = $this->request->data;
        //ChromePhp::log($data);
        $form = new FormsController();
        $form->generateEOGM_SALES_ASSET($data);
        $form->generateIndemnityLetter($data);
        //Create Document
        $this->Document->create();
        $hash_value = sha1($data['company']." generateSalesAssetBusiness".date('Y-m-d H:i:s'));
        $document = array(
            'company_id'=>$data['company'],
            'function_id'=>13,
            'created_at'=>date('Y-m-d H:i:s'),
            'unique_key'=>$hash_value,
            'status'=>"Available",
            'description'=>'Sales Asset Business'
        );
        $this->Document->save($document);
        $documentControl = new DocumentsController();
        $documentControl->initiateStatusChecking($this->Document->id);
      
        $data_SalesAssetBusiness= array(
                "document_id"=> $this->Document->id,
                "price"=>$data['price'],
                "seller"=>$data['seller'],
                "buyer"=>$data['buyer'],
                //"event_id"=>null,
                //Add later
         );
        
        $this->SalesAssetBusiness->create();

        $this->SalesAssetBusiness->save($data_SalesAssetBusiness);
        
        $files_to_zip = $form->form_downloads;
         $time = date('Y-m-d H-i-s');
        $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS .'files' . DS . 'zip' . DS . 'SalesOfAssetBusiness'.$time.'.zip');
        foreach($files_to_zip as $file){ //Delete files after zipping
            unlink($file);
        };
        //Create zip file
        $this->ZipFile->create();
        $zip_file = array(
            "function_id"=>13,
            "company_id"=>$data['company'],
            "path"=>'SalesOfAssetBusiness'.$time.'.zip',
            "created_at"=>date('Y-m-d H:i:s'),
        );
        $this->ZipFile->save($zip_file);
        $this->Session->setFlash(
		    'Forms are generated!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        return $this->redirect(array(
            "controller"=>'forms',
            "action"=>'index',
        ));
    }
    public function PropertyDisposal(){
        $data = $this->request->data;
        $this->set("view_data",$data);
    }
    public function generatePropertyDisposal(){
        $data = $this->request->data;
        $form = new FormsController();
        $form->generateResolutionPropertyDisposal($data);
        //Create Document
        $this->Document->create();
        $hash_value = sha1($data['company']."generatePropertyDisposal".date('Y-m-d H:i:s'));
        $document = array(
            'company_id'=>$data['company'],
            'function_id'=>14,
            'created_at'=>date('Y-m-d H:i:s'),
            'unique_key'=>$hash_value,
            'status'=>"Available",
            'description'=>"Property Disposal"
        );
        $this->Document->save($document);
        $documentControl = new DocumentsController();
        $documentControl->initiateStatusChecking($this->Document->id);
      
        $data_PropertyDisposal= array(
                "document_id"=> $this->Document->id,
                "propertyName"=>$data['property'],
                "price"=>$data['price']
                //"event_id"=>null,
                //Add later
         );
        
        $this->PropertyDisposal->create();

        $this->PropertyDisposal->save($data_PropertyDisposal);
        
        $files_to_zip = $form->form_downloads;
         $time = date('Y-m-d H-i-s');
        $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS .'files' . DS . 'zip' . DS . 'PropertyDisposal'.$time.'.zip');
        foreach($files_to_zip as $file){ //Delete files after zipping
            unlink($file);
        };
        //Create zip file
        $this->ZipFile->create();
        $zip_file = array(
            "function_id"=>14,
            "company_id"=>$data['company'],
            "path"=>'PropertyDisposal'.$time.'.zip',
            "created_at"=>date('Y-m-d H:i:s'),
        );
        $this->ZipFile->save($zip_file);
        $this->Session->setFlash(
		    'Forms are generated!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        return $this->redirect(array(
            "controller"=>'forms',
            "action"=>'index',
        ));
    }
    public function ResignAuditor() {
        if(isset($this->params['url']['company'])){
            $data= array();
            $data['company'] = $this->params['url']['company'];
        }else{
            $data = $this->request->data;
        }
        $stakeholders = $this->StakeHolder->find("all",array(
            "conditions"=>array(
                "StakeHolder.company_id"=>$data['company'],
                "StakeHolder.Auditor"=>1,
            )
        ));
       $auditor_ids = array();
       foreach ($stakeholders as $stakeholder){
           array_push($auditor_ids,$stakeholder['StakeHolder']['id']);
       }
      $auditors = $this->Auditor->find("all",array(
          "conditions"=>array(
              "Auditor.id"=>$auditor_ids,
              "Auditor.Mode"=>"appointed"
          )
      ));
     $this->set("auditors",$auditors);   
     $this->set("view_data",$data);   
    }
    public function generateResignAuditor(){
        $data = $this->request->data;
        //ChromePhp::log($data);
        $form = new FormsController();
      $form->generateForm49RA($data);
        $form->generateResolutionRA($data);
        $form->generateIndemnityLetter($data);
        //Create Document
        $this->Document->create();
        $hash_value = sha1($data['company']."generateResignAuditor".date('Y-m-d H:i:s'));
        $document = array(
            'company_id'=>$data['company'],
            'function_id'=>15,
            'created_at'=>date('Y-m-d H:i:s'),
            'unique_key'=>$hash_value,
            'status'=>"Available",
            'description'=>'Resign Auditor'
        );
        $this->Document->save($document);
  
        $auditor = $this->Auditor->find("first",array(
            "conditions"=>array(
                "Auditor.id"=>$data['auditor']
            )
        ));
        $data_ResignAuditor= array(
                "document_id"=> $this->Document->id,
                "auditorName"=>$auditor['StakeHolder']['name'],
                //"price"=>$data['price']
                //"event_id"=>null,
                //Add later
         );
        
        $this->ResignAuditor->create();

        $this->ResignAuditor->save($data_ResignAuditor);
        
        $files_to_zip = $form->form_downloads;
         $time = date('Y-m-d H-i-s');
        $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS .'files' . DS . 'zip' . DS . 'ResignAuditor'.$time.'.zip');
        foreach($files_to_zip as $file){ //Delete files after zipping
            unlink($file);
        };
        $documentControl = new DocumentsController();
        $documentControl->initiateStatusChecking($this->Document->id);
        //Create zip file
        $this->ZipFile->create();
        $zip_file = array(
            "function_id"=>15,
            "company_id"=>$data['company'],
            "path"=>'ResignAuditor'.$time.'.zip',
            "created_at"=>date('Y-m-d H:i:s'),
        );
        $this->ZipFile->save($zip_file);
        $this->Session->setFlash(
		    'Forms are generated!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        return $this->redirect(array(
            "controller"=>'forms',
            "action"=>'index',
        ));
    }
    public function NormalStruckOff() {
        $data = $this->request->data;
        $stakeholders = $this->StakeHolder->find("all",array(
            "conditions"=>array(
                "StakeHolder.company_id"=>$data['company'],
                "OR"=>array(
                    "StakeHolder.Director"=>1,
                    "StakeHolder.Shareholder"=>1
                )
            )
        ));
        $this->set("stakeholders",$stakeholders);
        $this->set("company",$data['company']);
        //ChromePhp::log($shareholders);
    }
    public function generateNormalStruckOff() {
        $data = $this->request->data;
        //ChromePhp::log($data);
        $form = new FormsController();
       $form->generateEGM($data);
       $form->generateLetterAcra($data);
       $form->generateStatutoryDeclaration($data);
       $form->generateForm94($data);
       $form->generateIndemnityLetter($data);
        $this->Document->create();
        $hash_value = sha1($data['company']."generateResignAuditor".date('Y-m-d H:i:s'));
        $document = array(
            'company_id'=>$data['company'],
            'function_id'=>16,
            'created_at'=>date('Y-m-d H:i:s'),
            'unique_key'=>$hash_value,
            'status'=>"Available",
            'description'=>'Normal Struck Off'
        );
        $this->Document->save($document);
        $documentControl = new DocumentsController();
        $documentControl->initiateStatusChecking($this->Document->id);
        $data_NormalStruckOff= array(
                "document_id"=> $this->Document->id,
                //"price"=>$data['price']
                //"event_id"=>null,
                //Add later
         );
        
        $this->NormalStruckOff->create();

        $this->NormalStruckOff->save($data_NormalStruckOff);
        
        $files_to_zip = $form->form_downloads;
         $time = date('Y-m-d H-i-s');
        $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS .'files' . DS . 'zip' . DS . 'NormalStruckOff'.$time.'.zip');
        foreach($files_to_zip as $file){ //Delete files after zipping
            unlink($file);
        };
        //Create zip file
        $this->ZipFile->create();
        $zip_file = array(
            "function_id"=>16,
            "company_id"=>$data['company'],
            "path"=>'NormalStruckOff'.$time.'.zip',
            "created_at"=>date('Y-m-d H:i:s'),
        );
        $this->ZipFile->save($zip_file);
        $this->Session->setFlash(
		    'Forms are generated!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        return $this->redirect(array(
            "controller"=>'forms',
            "action"=>'index',
        ));
    }
    public function AllotDirectorFee() {
        $data = $this->request->data;
        $stakeholders = $this->StakeHolder->find("all",array(
            "conditions"=>array(
                "StakeHolder.company_id"=>$data['company'],
                "StakeHolder.Director"=>1,
            )
        ));
        $this->set("stakeholders",$stakeholders);
        $this->set("company",$data['company']);
        
    }
    public function generateAllotDirectorFee(){
        $data = $this->request->data;
        //ChromePhp::log($data);
        $form = new FormsController();
       $form->generateEOGMAllotDirector($data);
        $this->Document->create();
        $hash_value = sha1($data['company']."generateAllotDirectorFee".date('Y-m-d H:i:s'));
        $document = array(
            'company_id'=>$data['company'],
            'function_id'=>17,
            'created_at'=>date('Y-m-d H:i:s'),
            'unique_key'=>$hash_value,
            'status'=>"Available",
            'description'=>'Allot Director Fee'
        );
        $this->Document->save($document);
        $data_AllotDirectorFee= array(
                "document_id"=> $this->Document->id,
                //"price"=>$data['price']
                //"event_id"=>null,
                //Add later
         );
        $documentControl = new DocumentsController();
        $documentControl->initiateStatusChecking($this->Document->id);
        $this->AllotDirectorFee->create();

        $this->AllotDirectorFee->save($data_AllotDirectorFee);
        
        $files_to_zip = $form->form_downloads;
         $time = date('Y-m-d H-i-s');
        $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS .'files' . DS . 'zip' . DS . 'AllotDirectorFee'.$time.'.zip');
        foreach($files_to_zip as $file){ //Delete files after zipping
            unlink($file);
        };
        //Create zip file
        $this->ZipFile->create();
        $zip_file = array(
            "function_id"=>17,
            "company_id"=>$data['company'],
            "path"=>'AllotDirectorFee'.$time.'.zip',
            "created_at"=>date('Y-m-d H:i:s'),
        );
        $this->ZipFile->save($zip_file);
        $this->Session->setFlash(
		    'Forms are generated!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        return $this->redirect(array(
            "controller"=>'forms',
            "action"=>'index',
        ));
    }
    public function firstFinalDividend() {
         $data = $this->request->data;
        $stakeholders = $this->StakeHolder->find("all",array(
            "conditions"=>array(
                "StakeHolder.company_id"=>$data['company'],
                    "StakeHolder.Shareholder"=>1
                )
            )
        );
        $this->set("stakeholders",$stakeholders);
        $this->set("company",$data['company']);
    }
    public function generateFirstFinalDividend(){
        $data = $this->request->data;
        //ChromePhp::log($data);
        $form = new FormsController();
       $form->generateEOGMFirstFinalDividend($data);
        $this->Document->create();
        $hash_value = sha1($data['company']."generateFirstFinalDividend".date('Y-m-d H:i:s'));
        $document = array(
            'company_id'=>$data['company'],
            'function_id'=>18,
            'created_at'=>date('Y-m-d H:i:s'),
            'unique_key'=>$hash_value,
            'status'=>"Available",
            'description'=>"First Final Dividend"
        );
        $this->Document->save($document);
        $data_FirstFinalDividend= array(
                "document_id"=> $this->Document->id,
                "type"=>$data['devidendType']
                //"price"=>$data['price']
                //"event_id"=>null,
                //Add later
         );
        $documentControl = new DocumentsController();
        $documentControl->initiateStatusChecking($this->Document->id);
        $this->FirstFinalDividend->create();

        $this->FirstFinalDividend->save($data_FirstFinalDividend);
        
        $files_to_zip = $form->form_downloads;
         $time = date('Y-m-d H-i-s');
        $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS .'files' . DS . 'zip' . DS . 'FirstFinalDividend'.$time.'.zip');
        foreach($files_to_zip as $file){ //Delete files after zipping
            unlink($file);
        };
        //Create zip file
        $this->ZipFile->create();
        $zip_file = array(
            "function_id"=>18,
            "company_id"=>$data['company'],
            "path"=>'FirstFinalDividend'.$time.'.zip',
            "created_at"=>date('Y-m-d H:i:s'),
        );
        $this->ZipFile->save($zip_file);
        $this->Session->setFlash(
		    'Forms are generated!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        return $this->redirect(array(
            "controller"=>'forms',
            "action"=>'index',
        ));
    }
    public function increaseOfShare() {
        $data = $this->request->data;
        $stakeholders = $this->StakeHolder->find("all",array(
            "conditions"=>array(
                "StakeHolder.company_id"=>$data['company'],
                    "StakeHolder.Shareholder"=>1
                )
            )
        );
        $this->set("stakeholders",$stakeholders);
        $this->set("company",$data['company']);
    }
    public function generateIncreaseOfShare(){
        $data = $this->request->data;
        //ChromePhp::log($data);
        $form = new FormsController();
       $form->generateEOGMIncreaseOfShares($data);
       $form->generateLetterAllotmentIncreaseOfShares($data);
      $form->generateForm24_IncreasingOfShares($data);
       $form->generateForm11_IncreasingOfShares($data);
       $form->generateIndemnityLetter($data);
        $this->Document->create();
        $hash_value = sha1($data['company']."generateIncreaseOfShare".date('Y-m-d H:i:s'));
        $document = array(
            'company_id'=>$data['company'],
            'function_id'=>19,
            'created_at'=>date('Y-m-d H:i:s'),
            'unique_key'=>$hash_value,
            'status'=>"Available",
            'description'=>'Increase of Share'
        );
        $this->Document->save($document);
        $data_IncreaseOfShare= array(
                "document_id"=> $this->Document->id,
                //"price"=>$data['price']
                //"event_id"=>null,
                //Add later
         );
        $documentControl = new DocumentsController();
        $documentControl->initiateStatusChecking($this->Document->id);
        $this->IncreaseOfShare->create();

        $this->IncreaseOfShare->save($data_IncreaseOfShare);
        
        $files_to_zip = $form->form_downloads;
         $time = date('Y-m-d H-i-s');
        $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS .'files' . DS . 'zip' . DS . 'IncreaseOfShare'.$time.'.zip');
        foreach($files_to_zip as $file){ //Delete files after zipping
            unlink($file);
        };
        //Create zip file
        $this->ZipFile->create();
        $zip_file = array(
            "function_id"=>19,
            "company_id"=>$data['company'],
            "path"=>'IncreaseOfShare'.$time.'.zip',
            "created_at"=>date('Y-m-d H:i:s'),
        );
        $this->ZipFile->save($zip_file);
        $this->Session->setFlash(
		    'Forms are generated!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        return $this->redirect(array(
            "controller"=>'forms',
            "action"=>'index',
        ));
    }
     public function IncreasePaidUpCapital() {
        $data = $this->request->data;
        $stakeholders = $this->StakeHolder->find("all",array(
            "conditions"=>array(
                "StakeHolder.company_id"=>$data['company'],
                    "StakeHolder.Shareholder"=>1
                )
            )
        );
        $this->set("stakeholders",$stakeholders);
        $this->set("company",$data['company']); 
     }
    public function generateIncreaseNonCashCapital() {
         $data = $this->request->data;
        //ChromePhp::log($data);
        $form = new FormsController();
       $form->generateEOGMIncreaseNonCashCapital($data);
       $form->generateForm11IncreaseNonCashCapital($data);
       $form->generateForm24IncreaseNonCashCapital($data);
       $form->generateForm25IncreaseNonCashCapital($data);
       $form->generateLetterAllotmentIncreaseNonCashCapital($data);
       $form->generateIndemnityLetter($data);
       
        $this->Document->create();
        $hash_value = sha1($data['company']."generateIncreaseNonCashCapital".date('Y-m-d H:i:s'));
        $document = array(
            'company_id'=>$data['company'],
            'function_id'=>20,
            'created_at'=>date('Y-m-d H:i:s'),
            'unique_key'=>$hash_value,
            'status'=>"Available",
            'description'=>"Increase Non Cash Capital"
        );
        $this->Document->save($document);
        $data_IncreasePaidUpCapital= array(
                "document_id"=> $this->Document->id,
                //"price"=>$data['price']
                //"event_id"=>null,
                //Add later
         );
        $documentControl = new DocumentsController();
        $documentControl->initiateStatusChecking($this->Document->id);
        $this->IncreasePaidUpCapital->create();

        $this->IncreasePaidUpCapital->save($data_IncreasePaidUpCapital);
        
        $files_to_zip = $form->form_downloads;
         $time = date('Y-m-d H-i-s');
        $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS .'files' . DS . 'zip' . DS . 'IncreasePaidUpCapital'.$time.'.zip');
        foreach($files_to_zip as $file){ //Delete files after zipping
            unlink($file);
        };
        //Create zip file
        $this->ZipFile->create();
        $zip_file = array(
            "function_id"=>20,
            "company_id"=>$data['company'],
            "path"=>'IncreasePaidUpCapital'.$time.'.zip',
            "created_at"=>date('Y-m-d H:i:s'),
        );
        $this->ZipFile->save($zip_file);
        $this->Session->setFlash(
		    'Forms are generated!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        return $this->redirect(array(
            "controller"=>'forms',
            "action"=>'index',
        ));
    }
  
}