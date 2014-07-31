<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include 'ChromePhp.php';
class DocumentsController extends AppController{
    public $components = array('Session', 'Paginator');
	public $uses = array('IncreasePaidUpCapital','IncreaseOfShare','FirstFinalDividend','AllotDirectorFee','NormalStruckOff','ResignAuditor','PropertyDisposal','SalesAssetBusiness','ChangeOfRegisteredAddress','ChangeOfPassport','ChangeOfMAA','OptionToPurchase','LoanResolution','ClosureBankAcc','ChangeFinancialYear','IncorporationDocument','ChangeCompanyName','ChangeBankSignatorUob','AppointSecretaryAuditor','AppointResignSecretary','Secretary','Event','User','Company', 'Director','FunctionCorp','Document','AppointResignDirector','StakeHolder');
    public function beforeFilter() {
		parent::beforeFilter();

	} 
    public function index(){
        
        $session_token = $this->Session->read('token');
        $token = $this->User->find("first",array(
                "conditions"=>array(
                        "User.token"=>$session_token
                )
        ));
        if(!$token){
           return $this->redirect(array(
                "controller"=>"users",
                "action"=>"index",
               "?"=>array(
                   "item"=>"documents"
               )
            ));
        }else{
            $functions = $this->FunctionCorp->find('all');
            $companies = $this->Company->find('all');

            $this->set('title', 'Document Management');

            $this->set('functions', $functions);
            $this->set('companies', $companies);
        }
                
    }    
    public function downloadFile(){
       $type = $this->params['url']['type'];
       $document_id = $this->params['url']['id'];
       $company_id = $this->params['url']['company'];
       $function_id = $this->params['url']['function'];
       
     
        if($function_id == 0){
            $document = $this->AppointResignSecretary->find("first",array(
                "conditions"=>array(
                    "AppointResignSecretary.document_id"=>$document_id
                )
            ));
            $function = "[Appoint and Resign Secretaries,Secretary: ".$document['StakeHolder']['name']."]";;
        }else if ($function_id == 1){
             $document = $this->AppointResignDirector->find("first",array(
                "conditions"=>array(
                    "AppointResignDirector.document_id"=>$document_id
                )
            ));
             $function = "[Function: Appoint and Resign Directors, Director: ".$document['StakeHolder']['name']."]";
        }else if ($function_id == 2){
             $document = $this->AppointSecretaryAuditor->find("first",array(
                "conditions"=>array(
                    "AppointSecretaryAuditor.document_id"=>$document_id
                )
            ));
             $function = "[Appoint both Auditors and Secretaries,Secretary:".$document['StakeHolder']['name']." Auditor:".$document['AppointSecretaryAuditor']['auditor_name']."]";
        }else if ($function_id == 3){
             $document = $this->ChangeBankSignatorUob->find("first",array(
                "conditions"=>array(
                    "ChangeBankSignatorUob.document_id"=>$document_id
                )
            ));
             $function = "[Change of bank signatories UOB, Director:".$document['StakeHolder']['name']."]";
        }else if ($function_id ==10){
            $document = $this->IncorporationDocument->find("first",array(
                "conditions"=>array(
                    "IncorporationDocument.document_id"=>$document_id
                )
            ));
             $function = "Incorporation Document";
        }else if ($function_id == 4){
             $document = $this->ChangeCompanyName->find("first",array(
                "conditions"=>array(
                    "ChangeCompanyName.document_id"=>$document_id
                )
            ));
             $function = "[Change of Company Name, From:".$document['ChangeCompanyName']['old_company']." To:".$document['ChangeCompanyName']['new_company']."]";
        }else if ($function_id == 5){
             $document = $this->ChangeFinancialYear->find("first",array(
                "conditions"=>array(
                    "ChangeFinancialYear.document_id"=>$document_id
                )
            ));
             $function = "ChangeFinancialYear";
        }else if ($function_id == 6){
             $document = $this->ChangeOfMAA->find("first",array(
                "conditions"=>array(
                    "ChangeOfMAA.document_id"=>$document_id
                )
            ));
             $function = "ChangeOfMAA";
        }else if ($function_id == 7){
             $document = $this->ClosureBankAcc->find("first",array(
                "conditions"=>array(
                    "ClosureBankAcc.document_id"=>$document_id
                )
            ));
             $function = "ClosureBankAcc";
        }else if ($function_id == 8){
             $document = $this->LoanResolution->find("first",array(
                "conditions"=>array(
                    "LoanResolution.document_id"=>$document_id
                )
            ));
             $function = "LoanResolution";
        }else if ($function_id == 9){
             $document = $this->OptionToPurchase->find("first",array(
                "conditions"=>array(
                    "OptionToPurchase.document_id"=>$document_id
                )
            ));
             $function = "OptionToPurchase";
        }else if ($function_id == 11){
             $document = $this->ChangeOfPassport->find("first",array(
                "conditions"=>array(
                    "ChangeOfPassport.document_id"=>$document_id
                )
            ));
             $function = "ChangeOfPassport";
        }
        else if ($function_id == 12){
             $document = $this->ChangeOfRegisteredAddress->find("first",array(
                "conditions"=>array(
                    "ChangeOfRegisteredAddress.document_id"=>$document_id
                )
            ));
             $function = "ChangeRegisteredAddress";
        }else if ($function_id == 13){
             $document = $this->SalesAssetBusiness->find("first",array(
                "conditions"=>array(
                    "SalesAssetBusiness.document_id"=>$document_id
                )
            ));
             $function = "SalesAssetBusiness";
        }else if ($function_id == 14){
             $document = $this->PropertyDisposal->find("first",array(
                "conditions"=>array(
                    "PropertyDisposal.document_id"=>$document_id
                )
            ));
             $function = "PropertyDisposal";
        }else if ($function_id == 15){
             $document = $this->ResignAuditor->find("first",array(
                "conditions"=>array(
                    "ResignAuditor.document_id"=>$document_id
                )
            ));
             $function = "ResignAuditor";
        }else if ($function_id == 16){
             $document = $this->NormalStruckOff->find("first",array(
                "conditions"=>array(
                    "NormalStruckOff.document_id"=>$document_id
                )
            ));
             $function = "NormalStruckOff";
        }else if ($function_id == 17){
             $document = $this->AllotDirectorFee->find("first",array(
                "conditions"=>array(
                    "AllotDirectorFee.document_id"=>$document_id
                )
            ));
             $function = "AllotDirectorFee";
        }else if ($function_id == 18){
             $document = $this->FirstFinalDividend->find("first",array(
                "conditions"=>array(
                    "FirstFinalDividend.document_id"=>$document_id
                )
            ));
             $function = "FirstFinalDividend";
        }else if ($function_id == 19){
             $document = $this->IncreaseOfShare->find("first",array(
                "conditions"=>array(
                    "IncreaseOfShare.document_id"=>$document_id
                )
            ));
             $function = "IncreaseOfShare";
        }else if ($function_id == 20){
             $document = $this->IncreasePaidUpCapital->find("first",array(
                "conditions"=>array(
                    "IncreasePaidUpCapital.document_id"=>$document_id
                )
            ));
             $function = "IncreasePaidUpCapital";
        }
      
      
     
       if($type === 'before'){
           $pdf_path = 'files/before/';
           $filename_arr = explode('/', $document['Document']['before']);
           $description = "Download before submission documents ".$function;
       }else{
           $pdf_path = "files/after/";
           $filename_arr = explode('/', $document['Document']['after']);
           $description = " Download after submission documents".$function;
       }
       
       $user = $this->User->find("first",array(
                "conditions"=>array(
                        "User.token"=>$this->Session->read('token')
       )));
       
        //Create Event
       $this->Event->create();
        $created_time = date('Y-m-d H:i:s');
        $event_data = array(
            "function_id"=>$function_id,
            "company_id"=>$company_id ,
            "user_id"=>$user['User']['id'],
            "created_time"=>$created_time ,
            "description"=>$description
        );
        $this->Event->save($event_data);

		$file = $filename_arr[count($filename_arr)-1];
		$extension_arr = explode('.', $file);
		$filename = $extension_arr[0];
		$extension = $extension_arr[count($extension_arr)-1];
//
        $this->viewClass = 'Media';
        $params = array(
            'id'        => $file,
            'name'      => $filename,
            'download'  => true,
            'extension' => $extension,
            'path'      => APP . WEBROOT_DIR . DS . $pdf_path
        );
        $this->set($params);
    }
    public function deleteFile(){
        $document_id = $this->params['url']['id'];
        $type = $this->params['url']['type'];
        $company_id = $this->params['url']['company'];
        $function_id = $this->params['url']['function'];
       
        
        if($function_id == 0){
             $document = $this->AppointResignSecretary->find("first",array(
                "conditions"=>array(
                    "AppointResignSecretary.document_id"=>$document_id
                )
            ));
            $this->Document->id = $document_id;
            $this->Document->saveField($type ,null);
            $function = "[Appoint and Resign Secretaries,Secretary:".$document['StakeHolder']['name']."]";
            $action = "appointResignS";
        }else if ($function_id == 1){
            $document = $this->AppointResignDirector->find("first",array(
                "conditions"=>array(
                    "AppointResignDirector.document_id"=>$document_id
                )
            ));
             $this->Document->id = $document_id;
            $this->Document->saveField($type ,null);
             $function = "[Function: Appoint and Resign Directors, Director:".$document['StakeHolder']['name']."]";
             $action = "appointResignD";
        }else if ($function_id == 2){
            $document = $this->AppointSecretaryAuditor->find("first",array(
                "conditions"=>array(
                    "AppointSecretaryAuditor.document_id"=>$document_id
                )
            ));
            $this->Document->id = $document_id;
            $this->Document->saveField($type ,null);
             $function = "[Appoint both Seccretaries and Auditors,Secretary:".$document['StakeHolder']['name']." Auditor:".$document['AppointSecretaryAuditor']['auditor_name']."]";
             $action = "appointAs";
        }
        else if ($function_id == 3){
            $document = $this->ChangeBankSignatorUob->find("first",array(
                "conditions"=>array(
                    "ChangeBankSignatorUob.document_id"=>$document_id
                )
            ));
            $this->Document->id = $document_id;
            $this->Document->saveField($type ,null);
             $function = "[Change of bank signatories UOB,Director:".$document['StakeHolder']['name']."]";
             $action = "ChangeOfBankingSignatoriesUOB";
        } else if ($function_id == 4){
            $document = $this->ChangeCompanyName->find("first",array(
                "conditions"=>array(
                    "ChangeCompanyName.document_id"=>$document_id
                )
            ));
            $this->Document->id = $document_id;
            $this->Document->saveField($type ,null);
             $function = "[Change of Company Name,From:".$document['ChangeCompanyName']['old_company']." To:".$document['ChangeCompanyName']['new_company']."]";
             $action = "ChangeOfCompanyName";
        }else if ($function_id ==10){
            $document = $this->IncorporationDocument->find("first",array(
                "conditions"=>array(
                    "IncorporationDocument.document_id"=>$document_id
                )
            ));
            $this->Document->id = $document_id;
            $this->Document->saveField($type ,null);
             $function = "Incorporation Document";
             $action = "Incorporation";
        }else if ($function_id == 5){
            $document = $this->ChangeFinancialYear->find("first",array(
                "conditions"=>array(
                    "ChangeFinancialYear.document_id"=>$document_id
                )
            ));
            $this->Document->id = $document_id;
            $this->Document->saveField($type ,null);
             $function = "ChangeFinancialYears";
             $action = "changeOfFY";
        }else if ($function_id == 6){
            $document = $this->ChangeOfMAA->find("first",array(
                "conditions"=>array(
                    "ChangeOfMAA.document_id"=>$document_id
                )
            ));
            $this->Document->id = $document_id;
            $this->Document->saveField($type ,null);
             $function = "ChangeOfMAA";
             $action = "ChangeOfMAA";
        }else if ($function_id == 7){
            $document = $this->ClosureBankAcc->find("first",array(
                "conditions"=>array(
                    "ClosureBankAcc.document_id"=>$document_id
                )
            ));
            $this->Document->id = $document_id;
            $this->Document->saveField($type ,null);
             $function = "ClosureOfBankAccResolution";
             $action = "ClosureOfBankAccResolution";
        }else if ($function_id == 8){
            $document = $this->LoanResolution->find("first",array(
                "conditions"=>array(
                    "LoanResolution.document_id"=>$document_id
                )
            ));
            $this->Document->id = $document_id;
            $this->Document->saveField($type ,null);
             $function = "LoanResolution";
             $action = "LoanResolution";
        }else if ($function_id == 9){
            $document = $this->OptionToPurchase->find("first",array(
                "conditions"=>array(
                    "OptionToPurchase.document_id"=>$document_id
                )
            ));
            $this->Document->id = $document_id;
            $this->Document->saveField($type ,null);
             $function = "OptionToPurchase";
             $action = "OptionToPurchase";
        }else if ($function_id == 11){
            $document = $this->ChangeOfPassport->find("first",array(
                "conditions"=>array(
                    "ChangeOfPassport.document_id"=>$document_id
                )
            ));
            $this->Document->id = $document_id;
            $this->Document->saveField($type ,null);
             $function = "ChangeOfPassport";
             $action = "ChangeOfPassport";
        }else if ($function_id == 12){
            $document = $this->ChangeOfRegisteredAddress->find("first",array(
                "conditions"=>array(
                    "ChangeOfRegisteredAddress.document_id"=>$document_id
                )
            ));
            $this->Document->id = $document_id;
            $this->Document->saveField($type ,null);
             $function = "ChangeRegisteredAddress";
             $action = "ChangeRegisteredAddress";
        }
        else if ($function_id == 13){
            $document = $this->SalesAssetBusiness->find("first",array(
                "conditions"=>array(
                    "SalesAssetBusiness.document_id"=>$document_id
                )
        ));
            $this->Document->id = $document_id;
            $this->Document->saveField($type ,null);
             $function = "SalesAssetBusiness";
             $action = "SalesAssetBusiness";
        }else if ($function_id == 14){
            $document = $this->PropertyDisposal->find("first",array(
                "conditions"=>array(
                    "PropertyDisposal.document_id"=>$document_id
                )
        ));
            $this->Document->id = $document_id;
            $this->Document->saveField($type ,null);
             $function = "PropertyDisposal";
             $action = "PropertyDisposal";
        }else if ($function_id == 15){
            $document = $this->ResignAuditor->find("first",array(
                "conditions"=>array(
                    "ResignAuditor.document_id"=>$document_id
                )
        ));
            $this->Document->id = $document_id;
            $this->Document->saveField($type ,null);
             $function = "ResignAuditor";
             $action = "ResignAuditor";
        }else if ($function_id == 16){
            $document = $this->NormalStruckOff->find("first",array(
                "conditions"=>array(
                    "NormalStruckOff.document_id"=>$document_id
                )
        ));
            $this->Document->id = $document_id;
            $this->Document->saveField($type ,null);
             $function = "NormalStruckOff";
             $action = "NormalStruckOff";
        }else if ($function_id == 17){
            $document = $this->AllotDirectorFee->find("first",array(
                "conditions"=>array(
                    "AllotDirectorFee.document_id"=>$document_id
                )
        ));
            $this->Document->id = $document_id;
            $this->Document->saveField($type ,null);
             $function = "AllotDirectorFee";
             $action = "AllotDirectorFee";
        }else if ($function_id == 18){
            $document = $this->FirstFinalDividend->find("first",array(
                "conditions"=>array(
                    "FirstFinalDividend.document_id"=>$document_id
                )
        ));
            $this->Document->id = $document_id;
            $this->Document->saveField($type ,null);
             $function = "FirstFinalDividend";
             $action = "FirstFinalDividend";
        }else if ($function_id == 19){
            $document = $this->IncreaseOfShare->find("first",array(
                "conditions"=>array(
                    "IncreaseOfShare.document_id"=>$document_id
                )
        ));
            $this->Document->id = $document_id;
            $this->Document->saveField($type ,null);
             $function = "IncreaseOfShare";
             $action = "IncreaseOfShare";
        }else if ($function_id == 20){
            $document = $this->IncreasePaidUpCapital->find("first",array(
                "conditions"=>array(
                    "IncreasePaidUpCapital.document_id"=>$document_id
                )
        ));
            $this->Document->id = $document_id;
            $this->Document->saveField($type ,null);
             $function = "IncreasePaidUpCapital";
             $action = "IncreasePaidUpCapital";
        }

         $user = $this->User->find("first",array(
                "conditions"=>array(
                        "User.token"=>$this->Session->read('token')
       )));
        //Create Event
       $this->Event->create();
        $created_time = date('Y-m-d H:i:s');
        $event_data = array(
            "function_id"=>$function_id,
            "company_id"=>$company_id,
            "user_id"=>$user['User']['id'],
            "created_time"=>$created_time ,
            "description"=>($type == "before")?"Delete before-submission documents ".$function :"Delete after-submission documents ".$function 
        );
        $this->Event->save($event_data);
        
        return $this->redirect(array(
           "controller"=>"Documents",
            "action"=>$action 
        ));
    }
     public function deleteDocument(){
        $document_id = $this->params['url']['id'];
        $function_id = $this->params['url']['function'];
      
        
        if($function_id == 0){
            $document = $this->AppointResignSecretary->find("first",array(
              "conditions"=>array(
                  "AppointResignSecretary.document_id"=>$document_id
              )
          ));
            $this->Document->id=$document_id;
           $this->Document->saveField("status","deleted");
            $function = "[Appoint and Resign Secretaries,Secretary:" .$document['StakeHolder']['name']."]";
            $action = "appointResignS";
        }else if ($function_id == 1){
            $document = $this->AppointResignDirector->find("first",array(
              "conditions"=>array(
                  "AppointResignDirector.document_id"=>$document_id
              )
          ));
            $this->Document->id=$document_id;
             $this->Document->saveField("status","deleted");
             $function = "[Function: Appoint and Resign Directors, Director:" .$document['StakeHolder']['name']."]";
             $action = "appointResignD";
        }else if ($function_id == 2){
            $document = $this->AppointSecretaryAuditor->find("first",array(
              "conditions"=>array(
                  "AppointSecretaryAuditor.document_id"=>$document_id
              )
          ));
            $this->Document->id=$document_id;
           $this->Document->saveField("status","deleted");
             $function = "[Appoint both Auditors and Secretaries, Secretary:" .$document['StakeHolder']['name']." Auditor: ".$document['AppointSecretaryAuditor']['auditor_name']."]";
             $action = "appointAs";
        }
        else if ($function_id == 3){
            $document = $this->ChangeBankSignatorUob->find("first",array(
              "conditions"=>array(
                  "ChangeBankSignatorUob.document_id"=>$document_id
              )
          ));
            $this->Document->id=$document_id;
             $this->Document->saveField("status","deleted");
             $function = "[Change of bank signatories UOB,Director: ".$document['StakeHolder']['name']."]";
             $action = "ChangeOfBankingSignatoriesUOB";
        } else if ($function_id == 4){
            $document = $this->ChangeCompanyName->find("first",array(
              "conditions"=>array(
                  "ChangeCompanyName.document_id"=>$document_id
              )
          ));
            $this->Document->id=$document_id;
             $this->Document->saveField("status","deleted");
             $function = "[Change of Company Name,From: ".$document['ChangeCompanyName']['old_company']." To:".$document['ChangeCompanyName']['new_company']."]";
             $action = "ChangeOfCompanyName";
        }else if ($function_id ==10){
            $document = $this->IncorporationDocument->find("first",array(
                "conditions"=>array(
                    "IncorporationDocument.document_id"=>$document_id
                )
            ));
            $this->Document->id = $document_id;
            $this->Document->saveField("status","deleted");
             $function = "Incorporation Document";
             $action = "Incorporation";
        } else if ($function_id == 5){
            $document = $this->ChangeFinancialYear->find("first",array(
              "conditions"=>array(
                  "ChangeFinancialYear.document_id"=>$document_id
              )
          ));
            $this->Document->id=$document_id;
             $this->Document->saveField("status","deleted");
             $function = "ChangeFinancialYear";
             $action = "changeOfFY";
        } else if ($function_id == 6){
            $document = $this->ChangeOfMAA->find("first",array(
              "conditions"=>array(
                  "ChangeOfMAA.document_id"=>$document_id
              )
          ));
            $this->Document->id=$document_id;
             $this->Document->saveField("status","deleted");
             $function = "changeOfMAA";
             $action = "changeOfMAA";
        } else if ($function_id == 7){
            $document = $this->ClosureBankAcc->find("first",array(
              "conditions"=>array(
                  "ClosureBankAcc.document_id"=>$document_id
              )
          ));
            $this->Document->id=$document_id;
             $this->Document->saveField("status","deleted");
             $function = "ClosureOfBankAccResolution";
             $action = "ClosureOfBankAccResolution";
        } else if ($function_id == 8){
            $document = $this->LoanResolution->find("first",array(
              "conditions"=>array(
                  "LoanResolution.document_id"=>$document_id
              )
          ));
            $this->Document->id=$document_id;
             $this->Document->saveField("status","deleted");
             $function = "LoanResolution";
             $action = "LoanResolution";
        } else if ($function_id == 9){
            $document = $this->OptionToPurchase->find("first",array(
              "conditions"=>array(
                  "OptionToPurchase.document_id"=>$document_id
              )
          ));
            $this->Document->id=$document_id;
             $this->Document->saveField("status","deleted");
             $function = "OptionToPurchase";
             $action = "OptionToPurchase";
        } else if ($function_id == 11){
            $document = $this->ChangeOfPassport->find("first",array(
              "conditions"=>array(
                  "ChangeOfPassport.document_id"=>$document_id
              )
          ));
            $this->Document->id=$document_id;
             $this->Document->saveField("status","deleted");
             $function = "ChangeOfPassport";
             $action = "ChangeOfPassport";
        }else if ($function_id == 12){
            $document = $this->ChangeOfRegisteredAddress->find("first",array(
              "conditions"=>array(
                  "ChangeOfRegisteredAddress.document_id"=>$document_id
              )
          ));
            $this->Document->id=$document_id;
             $this->Document->saveField("status","deleted");
             $function = "ChangeRegisteredAddress";
             $action = "ChangeRegisteredAddress";
        }
        else if ($function_id == 13){
            $document = $this->SalesAssetBusiness->find("first",array(
              "conditions"=>array(
                  "SalesAssetBusiness.document_id"=>$document_id
              )
          ));
            $this->Document->id=$document_id;
             $this->Document->saveField("status","deleted");
             $function = "SalesAssetBusiness";
             $action = "SalesAssetBusiness";
        }else if ($function_id == 14){
            $document = $this->PropertyDisposal->find("first",array(
              "conditions"=>array(
                  "PropertyDisposal.document_id"=>$document_id
              )
          ));
            $this->Document->id=$document_id;
             $this->Document->saveField("status","deleted");
             $function = "PropertyDisposal";
             $action = "PropertyDisposal";
        }else if ($function_id == 15){
            $document = $this->ResignAuditor->find("first",array(
              "conditions"=>array(
                  "ResignAuditor.document_id"=>$document_id
              )
          ));
            $this->Document->id=$document_id;
             $this->Document->saveField("status","deleted");
             $function = "ResignAuditor";
             $action = "ResignAuditor";
        }else if ($function_id == 16){
            $document = $this->NormalStruckOff->find("first",array(
              "conditions"=>array(
                  "NormalStruckOff.document_id"=>$document_id
              )
          ));
            $this->Document->id=$document_id;
             $this->Document->saveField("status","deleted");
             $function = "NormalStruckOff";
             $action = "NormalStruckOff";
        }else if ($function_id == 17){
            $document = $this->AllotDirectorFee->find("first",array(
              "conditions"=>array(
                  "AllotDirectorFee.document_id"=>$document_id
              )
          ));
            $this->Document->id=$document_id;
             $this->Document->saveField("status","deleted");
             $function = "AllotDirectorFee";
             $action = "AllotDirectorFee";
        }else if ($function_id == 18){
            $document = $this->FirstFinalDividend->find("first",array(
              "conditions"=>array(
                  "FirstFinalDividend.document_id"=>$document_id
              )
          ));
            $this->Document->id=$document_id;
             $this->Document->saveField("status","deleted");
             $function = "FirstFinalDividend";
             $action = "FirstFinalDividend";
        }else if ($function_id == 19){
            $document = $this->IncreaseOfShare->find("first",array(
              "conditions"=>array(
                  "IncreaseOfShare.document_id"=>$document_id
              )
          ));
            $this->Document->id=$document_id;
             $this->Document->saveField("status","deleted");
             $function = "IncreaseOfShare";
             $action = "IncreaseOfShare";
        }else if ($function_id == 20){
            $document = $this->IncreasePaidUpCapital->find("first",array(
              "conditions"=>array(
                  "IncreasePaidUpCapital.document_id"=>$document_id
              )
          ));
            $this->Document->id=$document_id;
             $this->Document->saveField("status","deleted");
             $function = "IncreasePaidUpCapital";
             $action = "IncreasePaidUpCapital";
        }
       
        $user = $this->User->find("first",array(
            "conditions"=>array(
                "User.token"=>$this->Session->read("token")
            )
        ));
        //Create Event
       $this->Event->create();
        $created_time = date('Y-m-d H:i:s');
        $event_data = array(
            "function_id"=>$this->params['url']['function'],
            "company_id"=>$this->params['url']['company'],
            "user_id"=>$user['User']['id'],
            "created_time"=>$created_time ,
            "description"=>"Delete  set of documents from".$function
        );
        $this->Event->save($event_data);
        
        return $this->redirect(array(
           "controller"=>"Documents",
            "action"=>$action
        ));
    }
    public function uploadBeforeSubmission(){
         
         $data = $this->request->data;
         $function_id = $data ['function_id'];
          $acra = $data['acra'];
         ChromePhp::log($data);
       $filePath = "files/before/".$data['Documents']['Browse']['name'].time().".pdf"; 
       //ChromePhp::log($filePath);
       move_uploaded_file($data['Documents']['Browse']['tmp_name'], $filePath);//save files to directory
//      
//       ChromePhp::log($data);
        if($function_id == 0){
            $this->Document->id = $data['document_secretary_id'];
            $this->Document->saveField('acra_before',$acra);
            $this->Document->saveField('before',$filePath);
            $this->Document->saveField('before_time',date('Y-m-d H:i:s'));
            $secretary = $this->AppointResignSecretary->find("first",array(
                    "conditions"=>array(
                        "AppointResignSecretary.document_id" => $data['document_secretary_id']
                    )
            ));
            $description = "Upload before-submission documents[Function:Appoint and Resign Secretaries,Secretary:". $secretary['StakeHolder']['name'] ."]";
            $action = "appointResignS";
        }else if ($function_id == 1){
            $this->Document->id = $data['document_director_id'];
            $this->Document->saveField('acra_before',$acra);
            $this->Document->saveField('before',$filePath);
            $this->Document->saveField('before_time',date('Y-m-d H:i:s'));
            $director = $this->AppointResignDirector->find("first",array(
                    "conditions"=>array(
                        "AppointResignDirector.document_id" => $data['document_director_id']
                    )
            ));
            $description = "Upload before-submission documents[Function:Appoint and Resign Directors, Director:".$director['StakeHolder']['name']."] ";
            $action = "appointResignD";
        }else if ($function_id == 2){
            $this->Document->id = $data['document_secretary_auditor_id'];
            $this->Document->saveField('acra_before',$acra);
            $this->Document->saveField('before',$filePath);
            $this->Document->saveField('before_time',date('Y-m-d H:i:s'));
            $secretary = $this->AppointSecretaryAuditor->find("first",array(
                    "conditions"=>array(
                        "AppointSecretaryAuditor.document_id" => $data['document_secretary_auditor_id']
                    )
            ));
            $description = "Upload before-submission documents[Function:Appoint both Secretaries and Auditors, Director:".$secretary['StakeHolder']['name']." Auditor:".$secretary['AppointSecretaryAuditor']['auditor_name']."]";
            $action = "appointAs";
        }else if ($function_id == 3){
            $this->Document->id = $data['document_director_id'];
            $this->Document->saveField('acra_before',$acra);
            $this->Document->saveField('before',$filePath);
            $this->Document->saveField('before_time',date('Y-m-d H:i:s'));
            $director = $this->ChangeBankSignatorUob->find("first",array(
                    "conditions"=>array(
                        "ChangeBankSignatorUob.document_id" => $data['document_director_id']
                    )
            ));
            $description = "Upload before-submission documents[Function: Change of bank signatories UOB, Director:".$director ['StakeHolder']['name']."]";
            $action = "ChangeOfBankingSignatoriesUOB";
        }else if ($function_id == 4){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_before',$acra);
            $this->Document->saveField('before',$filePath);
            $this->Document->saveField('before_time',date('Y-m-d H:i:s'));
            $ccn = $this->ChangeCompanyName->find("first",array(
                    "conditions"=>array(
                        "ChangeCompanyName.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload before-submission documents[Function: Change Company Name, From:".$ccn['ChangeCompanyName']['old_company']." To:".$ccn['ChangeCompanyName']['new_company']."]";
            $action = "changeOfCompanyName";
        }else if ($function_id ==10){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_before',$acra);
            $this->Document->saveField('before',$filePath);
            $this->Document->saveField('before_time',date('Y-m-d H:i:s'));
            $document = $this->IncorporationDocument->find("first",array(
                "conditions"=>array(
                    "IncorporationDocument.document_id"=>$data['document_id']
                )
            ));
            $description = "Upload before-submission Incorporation Document";
            $action = "Incorporation";
        }else if ($function_id == 5){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_before',$acra);
            $this->Document->saveField('before',$filePath);
            $this->Document->saveField('before_time',date('Y-m-d H:i:s'));
            $ccn = $this->ChangeFinancialYear->find("first",array(
                    "conditions"=>array(
                        "ChangeFinancialYear.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload before-submission documents[Function: ChangeFinancialYear]";
            $action = "changeOfFY";
        }else if ($function_id == 6){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_before',$acra);
            $this->Document->saveField('before',$filePath);
            $this->Document->saveField('before_time',date('Y-m-d H:i:s'));
            $ccn = $this->ChangeOfMAA->find("first",array(
                    "conditions"=>array(
                        "ChangeOfMAA.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload before-submission documents[Function: Change of M&AA- 1 Directorship]";
            $action = "changeOfMAA";
        }else if ($function_id == 7){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_before',$acra);
            $this->Document->saveField('before',$filePath);
            $this->Document->saveField('before_time',date('Y-m-d H:i:s'));
            $ccn = $this->ClosureBankAcc->find("first",array(
                    "conditions"=>array(
                        "ClosureBankAcc.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload before-submission documents[Function: Closure Of Bank Account Resolution]";
            $action = "ClosureOfBankAccResolution";
        }else if ($function_id == 8){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_before',$acra);
            $this->Document->saveField('before',$filePath);
            $this->Document->saveField('before_time',date('Y-m-d H:i:s'));
            $ccn = $this->LoanResolution->find("first",array(
                    "conditions"=>array(
                        "LoanResolution.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload before-submission documents[Function: Loan Resolution ]";
            $action = "LoanResolution";
        }else if ($function_id == 9){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_before',$acra);
            $this->Document->saveField('before',$filePath);
            $this->Document->saveField('before_time',date('Y-m-d H:i:s'));
            $ccn = $this->OptionToPurchase->find("first",array(
                    "conditions"=>array(
                        "OptionToPurchase.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload before-submission documents[Function: OptionToPurchase]";
            $action = "OptionToPurchase";
        }else if ($function_id == 11){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_before',$acra);
            $this->Document->saveField('before',$filePath);
            $this->Document->saveField('before_time',date('Y-m-d H:i:s'));
            $ccn = $this->ChangeOfPassport->find("first",array(
                    "conditions"=>array(
                        "ChangeOfPassport.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload before-submission documents[Function: ChangeOfPassport]";
            $action = "ChangeOfPassport";
        }else if ($function_id == 12){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_before',$acra);
            $this->Document->saveField('before',$filePath);
            $this->Document->saveField('before_time',date('Y-m-d H:i:s'));
            $ccn = $this->ChangeOfRegisteredAddress->find("first",array(
                    "conditions"=>array(
                        "ChangeOfRegisteredAddress.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload before-submission documents[Function: ChangeOfRegisteredAddress]";
            $action = "ChangeRegisteredAddress";
        }
        else if ($function_id == 13){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_before',$acra);
            $this->Document->saveField('before',$filePath);
            $this->Document->saveField('before_time',date('Y-m-d H:i:s'));
            $ccn = $this->SalesAssetBusiness->find("first",array(
                    "conditions"=>array(
                        "SalesAssetBusiness.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload before-submission documents[Function: SalesAssetBusiness]";
            $action = "SalesAssetBusiness";
        }else if ($function_id == 14){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_before',$acra);
            $this->Document->saveField('before',$filePath);
            $this->Document->saveField('before_time',date('Y-m-d H:i:s'));
            $ccn = $this->PropertyDisposal->find("first",array(
                    "conditions"=>array(
                        "PropertyDisposal.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload before-submission documents[Function: PropertyDisposal]";
            $action = "PropertyDisposal";
        }else if ($function_id == 15){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_before',$acra);
            $this->Document->saveField('before',$filePath);
            $this->Document->saveField('before_time',date('Y-m-d H:i:s'));
            $ccn = $this->ResignAuditor->find("first",array(
                    "conditions"=>array(
                        "ResignAuditor.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload before-submission documents[Function: ResignAuditor]";
            $action = "ResignAuditor";
        }else if ($function_id == 16){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_before',$acra);
            $this->Document->saveField('before',$filePath);
            $this->Document->saveField('before_time',date('Y-m-d H:i:s'));
            $ccn = $this->NormalStruckOff->find("first",array(
                    "conditions"=>array(
                        "NormalStruckOff.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload before-submission documents[Function: NormalStruckOff]";
            $action = "NormalStruckOff";
        }else if ($function_id == 17){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_before',$acra);
            $this->Document->saveField('before',$filePath);
            $this->Document->saveField('before_time',date('Y-m-d H:i:s'));
            $ccn = $this->AllotDirectorFee->find("first",array(
                    "conditions"=>array(
                        "AllotDirectorFee.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload before-submission documents[Function: AllotDirectorFee]";
            $action = "AllotDirectorFee";
        }else if ($function_id == 18){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_before',$acra);
            $this->Document->saveField('before',$filePath);
            $this->Document->saveField('before_time',date('Y-m-d H:i:s'));
            $ccn = $this->FirstFinalDividend->find("first",array(
                    "conditions"=>array(
                        "FirstFinalDividend.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload before-submission documents[Function: FirstFinalDividend]";
            $action = "FirstFinalDividend";
        }else if ($function_id == 19){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_before',$acra);
            $this->Document->saveField('before',$filePath);
            $this->Document->saveField('before_time',date('Y-m-d H:i:s'));
            $ccn = $this->IncreaseOfShare->find("first",array(
                    "conditions"=>array(
                        "IncreaseOfShare.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload before-submission documents[Function: IncreaseOfShare]";
            $action = "IncreaseOfShare";
        }else if ($function_id == 20){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_before',$acra);
            $this->Document->saveField('before',$filePath);
            $this->Document->saveField('before_time',date('Y-m-d H:i:s'));
            $ccn = $this->IncreasePaidUpCapital->find("first",array(
                    "conditions"=>array(
                        "IncreasePaidUpCapital.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload before-submission documents[Function: IncreasePaidUpCapital]";
            $action = "IncreasePaidUpCapital";
        }
         
         $user = $this->User->find("first",array(
                "conditions"=>array(
                        "User.token"=>$this->Session->read('token')
       )));
         //Create Event
       $this->Event->create();
        $created_time = date('Y-m-d H:i:s');
        $event_data = array(
            "function_id"=>$data['function_id'],
            "company_id"=>$data['company_id'],
            "user_id"=>$user['User']['id'],
            "created_time"=>$created_time ,
            "description"=>$description
        );
        $this->Event->save($event_data);
         
       return $this->redirect(array(
           "controller"=>"Documents",
           "action"=>$action
       ));

       
    }
    public function uploadAfterSubmission(){
        
         $data = $this->request->data;
       $filePath = "files/after/".$this->request->data['Documents']['Browse']['name']; 
        move_uploaded_file($data['Documents']['Browse']['tmp_name'], $filePath);//save files to directory
       $acra = $data['acra'];
        $function_id = $data ['function_id'];
        if($function_id == 0){
            $this->Document->id = $data['document_secretary_id'];
            $this->Document->saveField('acra_after',$acra);
            $this->Document->saveField('after',$filePath);
            $this->Document->saveField('after_time',date('Y-m-d H:i:s'));
            $secretary = $this->AppointResignSecretary->find("first",array(
                    "conditions"=>array(
                        "AppointResignSecretary.document_id" => $data['document_secretary_id']
                    )
            ));
            $description = "Upload after-submission documents[Function:Appoint and Resign Secretaries,Secretary:". $secretary ['StakeHolder']['name'] ."]";
            $action = "appointResignS";
        }else if ($function_id == 1){
            $this->Document->id = $data['document_director_id'];
            $this->Document->saveField('acra_after',$acra);
            $this->Document->saveField('after',$filePath);
            $this->Document->saveField('after_time',date('Y-m-d H:i:s'));
            $director = $this->AppointResignDirector->find("first",array(
                    "conditions"=>array(
                        "AppointResignDirector.document_id" => $data['document_director_id']
                    )
            ));
            $description = "Upload after-submission documents[Function:Appoint and Resign Directors, Director:".$director['StakeHolder']['name']."]";
            $action = "appointResignD";
        }else if ($function_id == 2){
            $this->Document->id = $data['document_secretary_auditor_id'];
            $this->Document->saveField('acra_after',$acra);
            $this->Document->saveField('after',$filePath);
            $this->Document->saveField('after_time',date('Y-m-d H:i:s'));
            $secretary = $this->AppointSecretaryAuditor->find("first",array(
                    "conditions"=>array(
                        "AppointSecretaryAuditor.document_id" => $data['document_secretary_auditor_id']
                    )
            ));
            $description = "Upload after-submission documents[Function:Appoint both Secretaries and Auditors, Director:".$secretary['StakeHolder']['name']." Auditor:".$secretary['AppointSecretaryAuditor']['auditor_name']."]";
            $action = "appointAs";
        }else if ($function_id == 3){
            $this->Document->id = $data['document_director_id'];
            $this->Document->saveField('acra_after',$acra);
            $this->Document->saveField('after',$filePath);
            $this->Document->saveField('after_time',date('Y-m-d H:i:s'));
            $directord = $this->ChangeBankSignatorUob->find("first",array(
                    "conditions"=>array(
                        "ChangeBankSignatorUob.document_id" => $data['document_secretary_auditor_id']
                    )
            ));
            $description = "Upload after-submission documents[Function: Change of bank signatories UOB, Director:".$director['StakeHolder']['name']."]";
            $action = "ChangeOfBankingSignatoriesUOB";
        }else if ($function_id == 4){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_after',$acra);
            $this->Document->saveField('after',$filePath);
            $this->Document->saveField('after_time',date('Y-m-d H:i:s'));
            $ccn = $this->ChangeCompanyName->find("first",array(
                    "conditions"=>array(
                        "ChangeCompanyName.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload after-submission documents[Function: Change Company Name,From:".$ccn['ChangeCompanyName']['old_company']." To:".$ccn['ChangeCompanyName']['new_company']."]";
            $action = "changeOfCompanyName";
        }else if ($function_id ==10){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_after',$acra);
            $this->Document->saveField('after',$filePath);
            $this->Document->saveField('after_time',date('Y-m-d H:i:s'));
            $document = $this->IncorporationDocument->find("first",array(
                "conditions"=>array(
                    "IncorporationDocument.document_id"=>$data['document_id']
                )
            ));
            $description = "Upload after-submission Incorporation Document";
            $action = "Incorporation";
        }else if ($function_id == 5){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_after',$acra);
            $this->Document->saveField('after',$filePath);
            $this->Document->saveField('after_time',date('Y-m-d H:i:s'));
            $ccn = $this->ChangeFinancialYear->find("first",array(
                    "conditions"=>array(
                        "ChangeFinancialYear.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload after-submission documents[Function: ChangeFinancialYear]";
            $action = "changeOfFY";
        }else if ($function_id == 6){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_after',$acra);
            $this->Document->saveField('after',$filePath);
            $this->Document->saveField('after_time',date('Y-m-d H:i:s'));
            $ccn = $this->ChangeOfMAA->find("first",array(
                    "conditions"=>array(
                        "ChangeOfMAA.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload after-submission documents[Function: Change of M&AA- 1 Directorship]";
            $action = "changeOfMAA";
        }else if ($function_id == 7){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_after',$acra);
            $this->Document->saveField('after',$filePath);
            $this->Document->saveField('after_time',date('Y-m-d H:i:s'));
            $ccn = $this->ClosureBankAcc->find("first",array(
                    "conditions"=>array(
                        "ClosureBankAcc.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload after-submission documents[Function: ClosureOfBankAccResolution]";
            $action = "ClosureOfBankAccResolution";
        }else if ($function_id == 8){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_after',$acra);
            $this->Document->saveField('after',$filePath);
            $this->Document->saveField('after_time',date('Y-m-d H:i:s'));
            $ccn = $this->LoanResolution->find("first",array(
                    "conditions"=>array(
                        "LoanResolution.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload after-submission documents[Function: LoanResolution]";
            $action = "LoanResolution";
        }else if ($function_id == 9){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_after',$acra);
            $this->Document->saveField('after',$filePath);
            $this->Document->saveField('after_time',date('Y-m-d H:i:s'));
            $ccn = $this->OptionToPurchase->find("first",array(
                    "conditions"=>array(
                        "OptionToPurchase.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload after-submission documents[Function: OptionToPurchase]";
            $action = "OptionToPurchase";
        }
        else if ($function_id == 11){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_after',$acra);
            $this->Document->saveField('after',$filePath);
            $this->Document->saveField('after_time',date('Y-m-d H:i:s'));
            $ccn = $this->ChangeOfPassport->find("first",array(
                    "conditions"=>array(
                        "ChangeOfPassport.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload after-submission documents[Function: ChangeOfPassport]";
            $action = "ChangeOfPassport";
        } else if ($function_id == 12){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_after',$acra);
            $this->Document->saveField('after',$filePath);
            $this->Document->saveField('after_time',date('Y-m-d H:i:s'));
            $ccn = $this->ChangeOfRegisteredAddress->find("first",array(
                    "conditions"=>array(
                        "ChangeOfRegisteredAddress.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload after-submission documents[Function: ChangeOfRegisteredAddress]";
            $action = "ChangeRegisteredAddress";
        }else if ($function_id == 13){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_after',$acra);
            $this->Document->saveField('after',$filePath);
            $this->Document->saveField('after_time',date('Y-m-d H:i:s'));
            $ccn = $this->SalesAssetBusiness->find("first",array(
                    "conditions"=>array(
                        "SalesAssetBusiness.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload after-submission documents[Function: SalesAssetBusiness]";
            $action = "SalesAssetBusiness";
        }else if ($function_id == 14){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_after',$acra);
            $this->Document->saveField('after',$filePath);
            $this->Document->saveField('after_time',date('Y-m-d H:i:s'));
            $ccn = $this->PropertyDisposal->find("first",array(
                    "conditions"=>array(
                        "PropertyDisposal.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload after-submission documents[Function: PropertyDisposal]";
            $action = "PropertyDisposal";
        }else if ($function_id == 15){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_after',$acra);
            $this->Document->saveField('after',$filePath);
            $this->Document->saveField('after_time',date('Y-m-d H:i:s'));
            $ccn = $this->ResignAuditor->find("first",array(
                    "conditions"=>array(
                        "ResignAuditor.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload after-submission documents[Function: ResignAuditor]";
            $action = "ResignAuditor";
        }else if ($function_id == 16){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_after',$acra);
            $this->Document->saveField('after',$filePath);
            $this->Document->saveField('after_time',date('Y-m-d H:i:s'));
            $ccn = $this->NormalStruckOff->find("first",array(
                    "conditions"=>array(
                        "NormalStruckOff.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload after-submission documents[Function: NormalStruckOff]";
            $action = "NormalStruckOff";
        }else if ($function_id == 17){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_after',$acra);
            $this->Document->saveField('after',$filePath);
            $this->Document->saveField('after_time',date('Y-m-d H:i:s'));
            $ccn = $this->AllotDirectorFee->find("first",array(
                    "conditions"=>array(
                        "AllotDirectorFee.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload after-submission documents[Function: AllotDirectorFee]";
            $action = "AllotDirectorFee";
        }else if ($function_id == 18){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_after',$acra);
            $this->Document->saveField('after',$filePath);
            $this->Document->saveField('after_time',date('Y-m-d H:i:s'));
            $ccn = $this->FirstFinalDividend->find("first",array(
                    "conditions"=>array(
                        "FirstFinalDividend.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload after-submission documents[Function: FirstFinalDividend]";
            $action = "FirstFinalDividend";
        }else if ($function_id == 19){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_after',$acra);
            $this->Document->saveField('after',$filePath);
            $this->Document->saveField('after_time',date('Y-m-d H:i:s'));
            $ccn = $this->IncreaseOfShare->find("first",array(
                    "conditions"=>array(
                        "IncreaseOfShare.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload after-submission documents[Function: IncreaseOfShare]";
            $action = "IncreaseOfShare";
        }else if ($function_id ==20){
            $this->Document->id = $data['document_id'];
            $this->Document->saveField('acra_after',$acra);
            $this->Document->saveField('after',$filePath);
            $this->Document->saveField('after_time',date('Y-m-d H:i:s'));
            $ccn = $this->IncreasePaidUpCapital->find("first",array(
                    "conditions"=>array(
                        "IncreasePaidUpCapital.document_id" => $data['document_id']
                    )
            ));
            $description = "Upload after-submission documents[Function: IncreasePaidUpCapital]";
            $action = "IncreasePaidUpCapital";
        }
          $user = $this->User->find("first",array(
                "conditions"=>array(
                        "User.token"=>$this->Session->read('token')
       )));
         //Create Event
       $this->Event->create();
        $created_time = date('Y-m-d H:i:s');
        $event_data = array(
            "function_id"=>$data['function_id'],
            "company_id"=>$data['company_id'],
            "user_id"=>$user['User']['id'],
            "created_time"=>$created_time ,
            "description"=>$description
        );
        $this->Event->save($event_data);
       return $this->redirect(array(
           "controller"=>"Documents",
           "action"=>$action
       ));
       
    }
    
    public function appointResignD(){
        if(isset($this->request->data['director'])){
            $company = $this->request->data['company_id'];
            if(isset($this->request->data['director'][0])){
                $director = $this->request->data['director'][0];
            }else{
                $director = "";
            }
            
            $function = $this->request->data['function_id'];
            
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$company,
                                'Document.function_id'=>$function,
                                "Document.status"=>"Available"
                         )
                ));
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }
                 $directors = $this->StakeHolder->find("all",array(
                     'conditions'=>array(
                            'StakeHolder.company_id'=>$company,
                            'StakeHolder.Director'=>1
                     )
                ));
            if($director ==""){
                 $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                                 "Document.status"=>"Available",
                            
                               
                        ),
                         'limit'=>6,
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
                    );
            }else{
                $ardics = $this->AppointResignDirector->find("all",array(
                    "conditions"=>array(
                        "AppointResignDirector.director_id"=>$director
                    )
                ));
                 $ids_directors = array();
                 foreach($ardics as $ar_dic){
                     array_push($ids_directors,$ar_dic['Document']['id']); 
                 }
                 
                 $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                                "Document.status"=>"Available"
                        ),
                       'limit'=>6,
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
                    );
                 $available_docs = $this->Document->find("all",array(
                     "conditions"=>array(
                         "Document.id"=>$ids_directors,
                         "Document.status"=>"Available"
                     )
                 ));
                 $ids = array();
                 foreach($available_docs as $available_doc){
                     array_push($ids,$available_doc['Document']['id']); 
                 }
                 
            }
            
            $this->set("company",$company);
            $this->set("functionCorp",$function);
           
            
        }else{
            $data = $this->request->data;
            if(isset($data['company'])){  
                 $this->Session->write("sessionData",$data);
            }else{
                $session_data = $this->Session->read("sessionData");
                $data = $session_data; 
            }
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$data['company'],
                                'Document.function_id'=>$data['functionCorp'],
                                "Document.status"=>"Available"
                         )
                ));
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }
                 $directors = $this->StakeHolder->find("all",array(
                     'conditions'=>array(
                            'StakeHolder.company_id'=>$data['company'],
                            'StakeHolder.Director'=>1
                     )
                ));
            $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                                
                        ),
                        'limit'=>6, 
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
                    );
            
             $this->set("company",$data['company'] );
             $this->set("functionCorp",$data['functionCorp']);
        }
        
        
        $documents = $this->Paginator->paginate('Document');
        $ARDirectors = $this->AppointResignDirector->find("all",array(
            "conditions"=>array(
                "AppointResignDirector.document_id"=>$ids
            )
        ));
        ChromePhp::log($ARDirectors);
        
        $this->set("documents",$documents );
        $this->set("header","Directors's resignation and appointment");
        $this->set("directors",$directors);
        $this->set("ARdirectors",$ARDirectors);

    }
    
    public function appointResignS(){
        ChromePhp::log($this->request->data);
        if(isset($this->request->data['secretary'])){
            $company = $this->request->data['company_id'];
            if(isset($this->request->data['secretary'][0])){
                $secretary = $this->request->data['secretary'][0];
            }else{
               
                $secretary= "";
            }
            
            $function = $this->request->data['function_id'];
        
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$company,
                                'Document.function_id'=>$function
                         )
                ));
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }
                 $secretaries = $this->StakeHolder->find("all",array(
                     'conditions'=>array(
                            'StakeHolder.company_id'=>$company,
                            'StakeHolder.Secretary'=>1
                     )
                ));
            if($secretary ==""){
                 $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                                 "Document.status"=>"Available"
                               
                        ),
                         'limit'=>6,
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
                    );
            }else{
                 $arsecs = $this->AppointResignSecretary->find("all",array(
                     "conditions"=>array(
                        "AppointResignSecretary.secretary_id"=>$secretary,
                     )
                 ));
                 $ids = array();
                 foreach($arsecs as $ar_sec){
                     array_push($ids,$ar_sec['Document']['id']); 
                 }
                 $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                                 "Document.status"=>"Available"
                        ),
                       'limit'=>6,
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
                    );
            }
            
            $this->set("company",$company);
            $this->set("functionCorp",$function);
           
//            
        }else{
            $data = $this->request->data;


            if(isset($data['company'])){  
                 $this->Session->write("sessionData",$data);
            }else{
                $session_data = $this->Session->read("sessionData");
                $data = $session_data; 
            }
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$data['company'],
                                'Document.function_id'=>$data['functionCorp']
                         )
                ));
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }
                 $secretaries = $this->StakeHolder->find("all",array(
                     'conditions'=>array(
                            'StakeHolder.company_id'=>$data['company'],
                            'StakeHolder.Secretary'=>1
                     )
                ));
            $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                                "Document.status"=>"Available"
                        ),
                        'limit'=>6, 
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
                    );
            
             $this->set("company",$data['company'] );
             $this->set("functionCorp",$data['functionCorp']);
        }
        
        
        $documents = $this->Paginator->paginate('Document');
        $ARSecretaries = $this->AppointResignSecretary->find("all",array(
            "conditions"=>array(
                "AppointResignSecretary.document_id"=>$ids
            )
        ));
        $this->set("documents",$documents );
        $this->set("header","Secretaries' resignation and appointment");
        $this->set("secretaries",$secretaries);
        $this->set("ARSecretaries",$ARSecretaries); 
        }   
        
        function AppointAs(){
            if(isset($this->request->data['secretary'])){
            $company = $this->request->data['company_id'];
            if(isset($this->request->data['secretary'][0])){
                $secretary = $this->request->data['secretary'][0];
            }else{
               
                $secretary= "";
            }
            
            $function = $this->request->data['function_id'];
        
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$company,
                                'Document.function_id'=>$function
                         )
                ));
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }
                 $secretaries = $this->StakeHolder->find("all",array(
                     'conditions'=>array(
                            'StakeHolder.company_id'=>$company,
                            'StakeHolder.Secretary'=>1
                     )
                ));
            if($secretary ==""){
                 $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                                 "Document.status"=>"Available"
                               
                        ),
                         'limit'=>6,
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
                    );
            }else{
                $asecs = $this->AppointSecretaryAuditor->find("all",array(
                    "conditions"=>array(
                        "AppointSecretaryAuditor.secretary_id"=>$secretary
                    )
                ));
                 $ids = array();
                 foreach($asecs as $asec){
                     array_push($ids,$asec['Document']['id']); 
                 }
                 $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                            
                                 "Document.status"=>"Available"
                        ),
                       'limit'=>6,
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
                    );
            }
            
            $this->set("company",$company);
            $this->set("functionCorp",$function);
           
//            
        }else{
            $data = $this->request->data;


            if(isset($data['company'])){  
                 $this->Session->write("sessionData",$data);
            }else{
                $session_data = $this->Session->read("sessionData");
                $data = $session_data; 
            }
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$data['company'],
                                'Document.function_id'=>$data['functionCorp']
                         )
                ));
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }
               // ChromePhp::log($ids);
                 $secretaries = $this->StakeHolder->find("all",array(
                     'conditions'=>array(
                            'StakeHolder.company_id'=>$data['company'],
                            'StakeHolder.Secretary'=>1
                     )
                ));
            $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                                "Document.status"=>"Available"
                        ),
                        'limit'=>6, 
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
                    );
            
             $this->set("company",$data['company'] );
             $this->set("functionCorp",$data['functionCorp']);
        }
        
        
        $documents = $this->Paginator->paginate('Document');
        $AASecretaries = $this->AppointSecretaryAuditor->find("all",array(
            "conditions"=>array(
                "AppointSecretaryAuditor.document_id"=>$ids
            )
        ));
        $this->set("documents",$documents );
        $this->set("header","Appointment both Auditors and Secretaries");
        $this->set("secretaries",$secretaries );
        $this->set("AASecretaries",$AASecretaries);
        }
        
    public function ChangeOfBankingSignatoriesUOB(){
        if(isset($this->request->data['director'])){
            $company = $this->request->data['company_id'];
            if(isset($this->request->data['director'][0])){
                $director = $this->request->data['director'][0];
            }else{
                $director = "";
            }
            
            $function = $this->request->data['function_id'];
            
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$company,
                                'Document.function_id'=>$function
                         )
                ));
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }
                 $secretaries = $this->StakeHolder->find("all",array(
                     'conditions'=>array(
                            'StakeHolder.company_id'=>$company,
                            'StakeHolder.Director'=>1
                     )
                ));
            if($director ==""){
                 $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                                 "Document.status"=>"Available"
                               
                        ),
                         'limit'=>6,
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
                    );
            }else{
                 $document_directors = $this->ChangeBankSignatorUob->find("all",array(
                    "conditions"=>array(
                        "ChangeBankSignatorUob.director_id"=>$director
                    )
                ));
                 $ids = array();
                 foreach($document_directors as $d){
                     array_push($ids,$d['Document']['id']); 
                 }
                 $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                                 "Document.status"=>"Available"
                        ),
                       'limit'=>6,
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
                    );
            }
            
            $this->set("company",$company);
            $this->set("functionCorp",$function);
           
            
        }else{
            $data = $this->request->data;
            if(isset($data['company'])){  
                 $this->Session->write("sessionData",$data);
            }else{
                $session_data = $this->Session->read("sessionData");
                $data = $session_data; 
            }
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$data['company'],
                                'Document.function_id'=>$data['functionCorp']
                         )
                ));
               
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }
                 $secretaries = $this->StakeHolder->find("all",array(
                     'conditions'=>array(
                            'StakeHolder.company_id'=>$data['company'],
                            'StakeHolder.Director'=>1
                     )
                ));
                  ChromePhp:: log($ids);
            $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                                "Document.status"=>"Available"
                        ),
                        'limit'=>6, 
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
                    );
            
             $this->set("company",$data['company'] );
             $this->set("functionCorp",$data['functionCorp']);
        }
        
        
        $documents = $this->Paginator->paginate('Document');
        $CBSdirectors = $this->ChangeBankSignatorUob->find("all",array(
            "conditions"=>array(
                "ChangeBankSignatorUob.document_id"=>$ids
            )
        ));
        ChromePhp::log($documents);
        ChromePhp::log($CBSdirectors);
        $this->set("documents",$documents);
        $this->set("header","Change Of Banking Signatories UOB");
        $this->set("directors",$secretaries );
        $this->set("CBSdirectors",$CBSdirectors); 
    }
	function changeOfCompanyName(){
            $data = $this->request->data;
            if(isset($data['company'])){  
                 $this->Session->write("sessionData",$data);
            }else{
                $session_data = $this->Session->read("sessionData");
                $data = $session_data; 
            }
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$data['company'],
                                'Document.function_id'=>$data['functionCorp']
                         )
                ));
               
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }

            $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                                "Document.status"=>"Available"
                        ),
                        'limit'=>6, 
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
            );           
		 $this->set("company",$data['company'] );
		 $this->set("functionCorp",$data['functionCorp']);

        $documents = $this->Paginator->paginate('Document');
        $changeCompanyData = $this->ChangeCompanyName->find("all",array(
            "conditions"=>array(
                "ChangeCompanyName.document_id"=>$ids
            )
        ));
        $this->set("documents",$documents);
        $this->set("changeCompanyData",$changeCompanyData); 
		$this->set("company",$data['company']); 
		$this->set("functionCorp",$data['functionCorp']); 
	}
        function Incorporation(){
            $data = $this->request->data;
            if(isset($data['company'])){  
                 $this->Session->write("sessionData",$data);
            }else{
                $session_data = $this->Session->read("sessionData");
                $data = $session_data; 
            }
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$data['company'],
                                'Document.function_id'=>$data['functionCorp']
                         )
                ));
               
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }

            $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                                "Document.status"=>"Available"
                        ),
                        'limit'=>6, 
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
            );           
		 $this->set("company",$data['company'] );
		 $this->set("functionCorp",$data['functionCorp']);

        $documents = $this->Paginator->paginate('Document');
        $IncorporationData = $this->IncorporationDocument->find("all",array(
            "conditions"=>array(
                "IncorporationDocument.document_id"=>$ids
            )
        ));
        $this->set("documents",$documents);
        $this->set("IncorporationData",$IncorporationData); 
		$this->set("company",$data['company']); 
		$this->set("functionCorp",$data['functionCorp']); 
	}
        function changeOfFY(){
            $data = $this->request->data;
            if(isset($data['company'])){  
                 $this->Session->write("sessionData",$data);
            }else{
                $session_data = $this->Session->read("sessionData");
                $data = $session_data; 
            }
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$data['company'],
                                'Document.function_id'=>$data['functionCorp']
                         )
                ));
               
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }

            $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                                "Document.status"=>"Available"
                        ),
                        'limit'=>6, 
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
            );           
		 $this->set("company",$data['company'] );
		 $this->set("functionCorp",$data['functionCorp']);

        $documents = $this->Paginator->paginate('Document');
        $FYData = $this->ChangeFinancialYear->find("all",array(
            "conditions"=>array(
                "ChangeFinancialYear.document_id"=>$ids
            )
        ));
        $this->set("documents",$documents);
        $this->set("FYData",$FYData); 
		$this->set("company",$data['company']); 
		$this->set("functionCorp",$data['functionCorp']); 
        }
        function changeOfMAA(){
            $data = $this->request->data;
            if(isset($data['company'])){  
                 $this->Session->write("sessionData",$data);
            }else{
                $session_data = $this->Session->read("sessionData");
                $data = $session_data; 
            }
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$data['company'],
                                'Document.function_id'=>$data['functionCorp']
                         )
                ));
               
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }

            $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                                "Document.status"=>"Available"
                        ),
                        'limit'=>6, 
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
            );           
		 $this->set("company",$data['company'] );
		 $this->set("functionCorp",$data['functionCorp']);

        $documents = $this->Paginator->paginate('Document');
        $view_data = $this->ChangeOfMAA->find("all",array(
            "conditions"=>array(
                "ChangeOfMAA.document_id"=>$ids
            )
        ));
        $this->set("documents",$documents);
        $this->set("view_data",$view_data); 
		$this->set("company",$data['company']); 
		$this->set("functionCorp",$data['functionCorp']); 
        }
        function ClosureOfBankAccResolution(){
            $data = $this->request->data;
            if(isset($data['company'])){  
                 $this->Session->write("sessionData",$data);
            }else{
                $session_data = $this->Session->read("sessionData");
                $data = $session_data; 
            }
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$data['company'],
                                'Document.function_id'=>$data['functionCorp']
                         )
                ));
               
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }

            $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                                "Document.status"=>"Available"
                        ),
                        'limit'=>6, 
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
            );           
		 $this->set("company",$data['company'] );
		 $this->set("functionCorp",$data['functionCorp']);

        $documents = $this->Paginator->paginate('Document');
        $view_data = $this->ClosureBankAcc->find("all",array(
            "conditions"=>array(
                "ClosureBankAcc.document_id"=>$ids
            )
        ));
        $this->set("documents",$documents);
        $this->set("view_data",$view_data); 
		$this->set("company",$data['company']); 
		$this->set("functionCorp",$data['functionCorp']); 
        }
        function LoanResolution() {
            $data = $this->request->data;
            if(isset($data['company'])){  
                 $this->Session->write("sessionData",$data);
            }else{
                $session_data = $this->Session->read("sessionData");
                $data = $session_data; 
            }
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$data['company'],
                                'Document.function_id'=>$data['functionCorp']
                         )
                ));
               
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }

            $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                                "Document.status"=>"Available"
                        ),
                        'limit'=>6, 
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
            );           
		 $this->set("company",$data['company'] );
		 $this->set("functionCorp",$data['functionCorp']);

        $documents = $this->Paginator->paginate('Document');
        $view_data = $this->LoanResolution->find("all",array(
            "conditions"=>array(
                "LoanResolution.document_id"=>$ids
            )
        ));
        $this->set("documents",$documents);
        $this->set("view_data",$view_data); 
		$this->set("company",$data['company']); 
		$this->set("functionCorp",$data['functionCorp']); 
        }
        function OptionToPurchase() {
            $data = $this->request->data;
            if(isset($data['company'])){  
                 $this->Session->write("sessionData",$data);
            }else{
                $session_data = $this->Session->read("sessionData");
                $data = $session_data; 
            }
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$data['company'],
                                'Document.function_id'=>$data['functionCorp']
                         )
                ));
               
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }

            $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                                "Document.status"=>"Available"
                        ),
                        'limit'=>6, 
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
            );           
		 $this->set("company",$data['company'] );
		 $this->set("functionCorp",$data['functionCorp']);

        $documents = $this->Paginator->paginate('Document');
        $view_data = $this->OptionToPurchase->find("all",array(
            "conditions"=>array(
                "OptionToPurchase.document_id"=>$ids
            )
        ));
        $this->set("documents",$documents);
        $this->set("view_data",$view_data); 
		$this->set("company",$data['company']); 
		$this->set("functionCorp",$data['functionCorp']); 
        }
        function ChangeOfPassport(){
            $data = $this->request->data;
            if(isset($data['company'])){  
                 $this->Session->write("sessionData",$data);
            }else{
                $session_data = $this->Session->read("sessionData");
                $data = $session_data; 
            }
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$data['company'],
                                'Document.function_id'=>$data['functionCorp']
                         )
                ));
               
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }

            $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                                "Document.status"=>"Available"
                        ),
                        'limit'=>6, 
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
            );           
		 $this->set("company",$data['company'] );
		 $this->set("functionCorp",$data['functionCorp']);

        $documents = $this->Paginator->paginate('Document');
        $view_data = $this->ChangeOfPassport->find("all",array(
            "conditions"=>array(
                "ChangeOfPassport.document_id"=>$ids
            )
        ));
        $this->set("documents",$documents);
        $this->set("view_data",$view_data); 
		$this->set("company",$data['company']); 
		$this->set("functionCorp",$data['functionCorp']); 
        }
     function ChangeRegisteredAddress(){
         $data = $this->request->data;
            if(isset($data['company'])){  
                 $this->Session->write("sessionData",$data);
            }else{
                $session_data = $this->Session->read("sessionData");
                $data = $session_data; 
            }
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$data['company'],
                                'Document.function_id'=>$data['functionCorp'],
                                "Document.status"=>"Available",
                         )
                ));
               
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }

            $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                          
                        ),
                        'limit'=>6, 
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
            );           
		 $this->set("company",$data['company'] );
		 $this->set("functionCorp",$data['functionCorp']);

        $documents = $this->Paginator->paginate('Document');
        $view_data = $this->ChangeOfRegisteredAddress->find("all",array(
            "conditions"=>array(
                "ChangeOfRegisteredAddress.document_id"=>$ids
            )
        ));
        $this->set("documents",$documents);
        $this->set("view_data",$view_data); 
		$this->set("company",$data['company']); 
		$this->set("functionCorp",$data['functionCorp']); 
        
     }
     public function SalesAssetBusiness() {
         $data = $this->request->data;
            if(isset($data['company'])){  
                 $this->Session->write("sessionData",$data);
            }else{
                $session_data = $this->Session->read("sessionData");
                $data = $session_data; 
            }
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$data['company'],
                                'Document.function_id'=>$data['functionCorp'],
                                "Document.status"=>"Available",
                         )
                ));
               
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }

            $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                          
                        ),
                        'limit'=>6, 
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
            );           
		 $this->set("company",$data['company'] );
		 $this->set("functionCorp",$data['functionCorp']);

        $documents = $this->Paginator->paginate('Document');
        $view_data = $this->SalesAssetBusiness->find("all",array(
            "conditions"=>array(
                "SalesAssetBusiness.document_id"=>$ids
            )
        ));
        $this->set("documents",$documents);
        $this->set("view_data",$view_data); 
	$this->set("company",$data['company']); 
	$this->set("functionCorp",$data['functionCorp']); 
         
     }
     public function PropertyDisposal() {
         $data = $this->request->data;
            if(isset($data['company'])){  
                 $this->Session->write("sessionData",$data);
            }else{
                $session_data = $this->Session->read("sessionData");
                $data = $session_data; 
            }
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$data['company'],
                                'Document.function_id'=>$data['functionCorp'],
                                "Document.status"=>"Available",
                         )
                ));
               
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }

            $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                          
                        ),
                        'limit'=>6, 
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
            );           
		 $this->set("company",$data['company'] );
		 $this->set("functionCorp",$data['functionCorp']);

        $documents = $this->Paginator->paginate('Document');
        $view_data = $this->PropertyDisposal->find("all",array(
            "conditions"=>array(
                "PropertyDisposal.document_id"=>$ids
            )
        ));
        $this->set("documents",$documents);
        $this->set("view_data",$view_data); 
	$this->set("company",$data['company']); 
	$this->set("functionCorp",$data['functionCorp']); 
    }
    public function ResignAuditor() {
        $data = $this->request->data;
            if(isset($data['company'])){  
                 $this->Session->write("sessionData",$data);
            }else{
                $session_data = $this->Session->read("sessionData");
                $data = $session_data; 
            }
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$data['company'],
                                'Document.function_id'=>$data['functionCorp'],
                                "Document.status"=>"Available",
                         )
                ));
               
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }

            $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                          
                        ),
                        'limit'=>6, 
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
            );           
		 $this->set("company",$data['company'] );
		 $this->set("functionCorp",$data['functionCorp']);

        $documents = $this->Paginator->paginate('Document');
        $view_data = $this->ResignAuditor->find("all",array(
            "conditions"=>array(
                "ResignAuditor.document_id"=>$ids
            )
        ));
        $this->set("documents",$documents);
        $this->set("view_data",$view_data); 
	$this->set("company",$data['company']); 
	$this->set("functionCorp",$data['functionCorp']); 
    }
    public function NormalStruckOff() {
         $data = $this->request->data;
            if(isset($data['company'])){  
                 $this->Session->write("sessionData",$data);
            }else{
                $session_data = $this->Session->read("sessionData");
                $data = $session_data; 
            }
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$data['company'],
                                'Document.function_id'=>$data['functionCorp'],
                                "Document.status"=>"Available",
                         )
                ));
               
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }

            $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                          
                        ),
                        'limit'=>6, 
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
            );           
		 $this->set("company",$data['company'] );
		 $this->set("functionCorp",$data['functionCorp']);

        $documents = $this->Paginator->paginate('Document');
        $view_data = $this->NormalStruckOff->find("all",array(
            "conditions"=>array(
                "NormalStruckOff.document_id"=>$ids
            )
        ));
        $this->set("documents",$documents);
        $this->set("view_data",$view_data); 
	$this->set("company",$data['company']); 
	$this->set("functionCorp",$data['functionCorp']); 
        
    }
    public function AllotDirectorFee() {
        $data = $this->request->data;
            if(isset($data['company'])){  
                 $this->Session->write("sessionData",$data);
            }else{
                $session_data = $this->Session->read("sessionData");
                $data = $session_data; 
            }
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$data['company'],
                                'Document.function_id'=>$data['functionCorp'],
                                "Document.status"=>"Available",
                         )
                ));
               
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }

            $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                          
                        ),
                        'limit'=>6, 
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
            );           
		 $this->set("company",$data['company'] );
		 $this->set("functionCorp",$data['functionCorp']);

        $documents = $this->Paginator->paginate('Document');
        $view_data = $this->AllotDirectorFee->find("all",array(
            "conditions"=>array(
                "AllotDirectorFee.document_id"=>$ids
            )
        ));
        $this->set("documents",$documents);
        $this->set("view_data",$view_data); 
	$this->set("company",$data['company']); 
	$this->set("functionCorp",$data['functionCorp']); 
    }
     public function firstFinalDividend() {
         $data = $this->request->data;
            if(isset($data['company'])){  
                 $this->Session->write("sessionData",$data);
            }else{
                $session_data = $this->Session->read("sessionData");
                $data = $session_data; 
            }
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$data['company'],
                                'Document.function_id'=>$data['functionCorp'],
                                "Document.status"=>"Available",
                         )
                ));
               
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }

            $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                          
                        ),
                        'limit'=>6, 
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
            );           
		 $this->set("company",$data['company'] );
		 $this->set("functionCorp",$data['functionCorp']);

        $documents = $this->Paginator->paginate('Document');
        $view_data = $this->FirstFinalDividend->find("all",array(
            "conditions"=>array(
                "FirstFinalDividend.document_id"=>$ids
            )
        ));
        $this->set("documents",$documents);
        $this->set("view_data",$view_data); 
	$this->set("company",$data['company']); 
	$this->set("functionCorp",$data['functionCorp']); 
    }   
    public function increaseOfShare() {
        $data = $this->request->data;
            if(isset($data['company'])){  
                 $this->Session->write("sessionData",$data);
            }else{
                $session_data = $this->Session->read("sessionData");
                $data = $session_data; 
            }
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$data['company'],
                                'Document.function_id'=>$data['functionCorp'],
                                "Document.status"=>"Available",
                         )
                ));
               
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }

            $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                          
                        ),
                        'limit'=>6, 
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
            );           
		 $this->set("company",$data['company'] );
		 $this->set("functionCorp",$data['functionCorp']);

        $documents = $this->Paginator->paginate('Document');
        $view_data = $this->IncreaseOfShare->find("all",array(
            "conditions"=>array(
                "IncreaseOfShare.document_id"=>$ids
            )
        ));
        $this->set("documents",$documents);
        $this->set("view_data",$view_data); 
	$this->set("company",$data['company']); 
	$this->set("functionCorp",$data['functionCorp']); 
    }
    public function IncreasePaidUpCapital() {
        $data = $this->request->data;
            if(isset($data['company'])){  
                 $this->Session->write("sessionData",$data);
            }else{
                $session_data = $this->Session->read("sessionData");
                $data = $session_data; 
            }
            $docs = $this->Document->find("all",array(
                         'conditions'=>array(
                                'Document.company_id'=>$data['company'],
                                'Document.function_id'=>$data['functionCorp'],
                                "Document.status"=>"Available",
                         )
                ));
               
                $ids = array();
                foreach($docs as $doc){
                    array_push($ids,$doc['Document']['id']);  
                }

            $this->Paginator->settings= array(
                        'conditions'=>array(
                                "Document.id"=>$ids,
                          
                        ),
                        'limit'=>6, 
                       'order' => array(
                            'Document.created_at' => 'DESC'
                            ),
            );           
		 $this->set("company",$data['company'] );
		 $this->set("functionCorp",$data['functionCorp']);

        $documents = $this->Paginator->paginate('Document');
        $view_data = $this->IncreasePaidUpCapital->find("all",array(
            "conditions"=>array(
                "IncreasePaidUpCapital.document_id"=>$ids
            )
        ));
        $this->set("documents",$documents);
        $this->set("view_data",$view_data); 
	$this->set("company",$data['company']); 
	$this->set("functionCorp",$data['functionCorp']); 
    }
}