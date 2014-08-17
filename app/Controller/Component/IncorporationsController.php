<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
App::import('Controller', 'Forms');
App::import('Controller', 'Documents');
class IncorporationsController extends AppController{
     public $components = array('Session', 'Paginator');
    public $uses = array('ZipFile','IncorporationDocument','Document','Secretary','Auditor','ShareHolder','StakeHolder','User','Company', 'Director','FunctionCorp');
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
    function IncorporationForm(){
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
        }
    

    }
    function editIncorporationForm(){
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
        }
    
        if(!empty($this->params['url']['edit'])){
            $data =  $this->request->data;
           
            $edit = $this->params['url']['edit'];
            $company_info = $this->Company->find("first",array(
                "conditions"=>array(
                    "Company.company_id"=>$data['company']
                )
            ));
            $directors_ids = array();
            $stakeholders_d = $this->StakeHolder->find("all",array(
                "conditions"=>array(
                    "StakeHolder.company_id"=>$data['company'],
                    "StakeHolder.Director"=>1,
                )
            ));
            foreach($stakeholders_d as $stakeholder_d){
                array_push($directors_ids,$stakeholder_d['StakeHolder']['id']);
            }
            $directors = $this->Director->find("all",array(
                "conditions"=>array(
                    "Director.id"=>$directors_ids,
                )
            ));
       
            
            $shareholders_ids = array();
            $stakeholders_s = $this->StakeHolder->find("all",array(
                "conditions"=>array(
                    "StakeHolder.company_id"=>$data['company'],
                    "StakeHolder.Shareholder"=>1,
                )
            ));
            foreach($stakeholders_s as $stakeholder_s){
                array_push($shareholders_ids,$stakeholder_s['StakeHolder']['id']);
            }
            $shareholders = $this->ShareHolder->find("all",array(
                "conditions"=>array(
                    "ShareHolder.id"=>$shareholders_ids,
                )
            ));
            
             $secretaries_ids = array();
            $stakeholders_se = $this->StakeHolder->find("all",array(
                "conditions"=>array(
                    "StakeHolder.company_id"=>$data['company'],
                    "StakeHolder.Secretary"=>1,
                )
            ));
            foreach($stakeholders_se as $stakeholder_se){
                array_push($secretaries_ids,$stakeholder_se['StakeHolder']['id']);
            }
            $secretaries = $this->Secretary->find("all",array(
                "conditions"=>array(
                    "Secretary.id"=>$secretaries_ids,
                )
            ));
            
             $auditors_ids = array();
            $stakeholders_a  = $this->StakeHolder->find("all",array(
                "conditions"=>array(
                    "StakeHolder.company_id"=>$data['company'],
                    "StakeHolder.Auditor"=>1,
                )
            ));
            
            foreach($stakeholders_a as $stakeholder_a){
                array_push($auditors_ids,$stakeholder_a['StakeHolder']['id']);
            }
            $auditors = $this->Auditor->find("all",array(
                "conditions"=>array(
                    "Auditor.id"=>$auditors_ids,
                )
            ));
            $document = $this->Document->find("first",array(
                "conditions"=>array(
                    "Document.company_id"=>$data['company'],
                )
            ));
            $DocumentInc = $this->IncorporationDocument->find("first",array(
                "conditions"=>array(
                    "IncorporationDocument.document_id"=>$document['Document']['id']
                )
            ));
            
            $this->set("company_info",$company_info);
            $this->set("directors", $directors);
            $this->set("shareholders",$shareholders);
            $this->set("secretaries",$secretaries);
            $this->set("auditors",$auditors);
            $this->set("chairman",$DocumentInc['IncorporationDocument']['chairman']);
            //$this->set("edit",$edit);
        }
    }
    
    function generateIncorporationForms(){
        $data = $this->request->data;  
        //ChromePhp::log($data);
        $form = new FormsController();
        if(!empty($data['existCname'])){
            $company_exist = $this->Company->find("first",array(
                "conditions"=>array(
                    "Company.name"=>$data['existCname']
                )
            ));
            $this->Company->delete(array('Company.company_id' => $company_exist['Company']['company_id']));
        }
        $exist_companies = $this->Company->find("all");
        foreach($exist_companies as $exist_com){
            if($exist_com['Company']['name'] === $data['Cname']){
                $this->Session->setFlash(
		    'Sorry! Your company name is already existed. Please try again',
		    'default',
		    array('class' => 'alert alert-danger')
		);
            return $this->redirect(array(
                "controller"=>'Incorporations',
                "action"=>'IncorporationForm'
            ));
            }
        }
        $company_info=array(
            "name"=>$data['Cname'],
            "number"=>"",
            "address1"=>$data['Raddress1'],
            "address2"=>$data['Raddress2'],
            "PrincipalActivity1"=>$data['Pactivity1'],
            "P1Description"=>$data['Pactivity1description'],
            "PrincipalActivity2"=>$data['Pactivity2'],
            "P2Description"=>$data['Pactivity2description'],    
            "FinancialYear"=>$data['financial_year'],
        );
        
        $LO_info = array(
            "LOName"=>$data["LOName"],
            "LOAddressline1"=>$data["LOAddressline1"],
            "LOAddressline2"=>$data["LOAddressline2"],
            "LOAcNo"=>$data["LOAcNo"],
            "LOTelNo"=>$data["LOTelNo"],
            "LOTelFax"=>$data["LOTelFax"]
        );
        $name_shareholders = $data['SNameoftheShareholder'];
         $name_directors = $data['DNameoftheAppointDirector'];
        $sharesInCash = array(
            "Currency"=>$data['Ccurrency'],
            "NumberOfShares"=>$data['Cnoshares'],
            "NominalAmountOfEachShare"=>$data['CnominalAmount'],
            "AmountPaid"=>$data['CamountPaid'],
            "Due&Payable"=>$data['CduePayable'],
            "AmountPremiumPaid"=>$data['CamountPremiumPaid'],
            "AuthorizedShareCapital"=>$data['CauthorizedShareCapital'],
            "IssuedShareCapital"=>$data['CissuedShareCapital'],
            "PaidupShareCapital"=>$data['CpaidupShareCapital'],
            "IssuedOrdinary"=>$data['CIssuedOrdinary'],
            "PaidUpOrdinary"=>$data['CPaidupOrdinary'],  
        );//Payable shares in cash info
        $director_array = array();//Holding list of directors
         for($i = 0;$i < count($name_directors);$i++){
            $director = array(
                "name"=>$data['DNameoftheAppointDirector'][$i],
                "addressline1_Singapore"=>$data['DAddressline1S'][$i],
                "addressline2_Singapore"=>$data['DAddressline2S'][$i],
                "addressline3_Singapore"=>$data['DAddressline3S'][$i],
                "addressline1_OverSea"=>$data['DAddressline1OS'][$i],
                "addressline2_OverSea"=>$data['DAddressline2OS'][$i],
                "addressline3_OverSea"=>$data['DAddressline3OS'][$i],
                "addressline1_Other"=>$data['DAddressline1OT'][$i],
                "addressline2_Other"=>$data['DAddressline2OT'][$i],
                "addressline3_Other"=>$data['DAddressline3OT'][$i],
                "NRIC/Passport"=>$data['DNRIC/Passport'][$i],
                "NationalityatBirth"=>$data['DNationalityatBirth'][$i],
                "NationalityCurrent"=>$data['DNationalityCurrent'][$i],
                "Occupation"=>$data['DOccupation'][$i],
                "NumberofShares"=>$data['DNumberofShares'][$i],
                "NumberofSharesInwords"=>$data['DNumberofSharesInwords'][$i],
                "CertificateNo"=>$data['DCertificateNo'][$i],
                "DateofBirth"=>$data['DDateofBirth'][$i],
                "ClassofShares"=>$data['DClassofShares'][$i],
                "Currency"=>$data['DCurrency'][$i],
                "Placeofbirth"=>$data['DPlaceofbirth'][$i],
                "Nricdateofissue"=>$data['DNricdateofissues'][$i],
                "nricplaceofissue"=>$data['DNricplaceofissue'][$i],
                "passportno"=>$data['DPassportno'][$i],
                "passportdateofissue"=>$data['DPassportDateOfIssue'][$i],
                "passportplaceofissue"=>$data['DPassportPlaceOfIssue'][$i],
                "NatureOfContract"=>$data['DNatureofContract'][$i],
                "Remarks"=>$data['DRemarks'][$i],
                "ConsentToActAsDirector"=>$data['DConsenttoactasdirectorofthecompany'][$i],
                "FormerName"=>$data['DFormerNameifany'][$i]
            );
            array_push($director_array,$director);
         };
        $shareholder_array = array();//Holding list of shareholders
        for($i = 0;$i < count($name_shareholders);$i++){
            $shareholder = array(
                "name"=>$data['SNameoftheShareholder'][$i],
                "addressline1_Singapore"=>$data['SAddressline1S'][$i],
                "addressline2_Singapore"=>$data['SAddressline2S'][$i],
                "addressline3_Singapore"=>$data['SAddressline3S'][$i],
                "addressline1_OverSea"=>$data['SAddressline1OS'][$i],
                "addressline2_OverSea"=>$data['SAddressline2OS'][$i],
                "addressline3_OverSea"=>$data['SAddressline3OS'][$i],
                "addressline1_Other"=>$data['SAddressline1OT'][$i],
                "addressline2_Other"=>$data['SAddressline2OT'][$i],
                "addressline3_Other"=>$data['SAddressline3OT'][$i],
                "NRIC/Passport"=>$data['SNRIC/Passport'][$i],
                "NationalityatBirth"=>$data['SNationalityatBirth'][$i],
                "NationalityCurrent"=>$data['SNationalityCurrent'][$i],
                "Occupation"=>$data['SOccupation'][$i],
                "NumberofShares"=>$data['SNumberofShares'][$i],
                "NumberofSharesInwords"=>$data['SNumberofSharesInwords'][$i],
                "CertificateNo"=>$data['SCertificateNo'][$i],
                "MembersRegisterNo"=>$data['SMembersRegisterNo'][$i],
                "DateofBirth"=>$data['SDateofBirth'][$i],
                "ClassofShares"=>$data['SClassofShares'][$i],
                "Currency"=>$data['SCurrency'][$i],
                "Placeofbirth"=>$data['SPlaceofbirth'][$i],
                "Nricdateofissue"=>$data['SNricdateofissue'][$i],
                "nricplaceofissue"=>$data['Snricplaceofissue'][$i],
                "passportno"=>$data['Spassportno'][$i],
                "passportdateofissue"=>$data['Spassportdateofissue'][$i],
                "passportplaceofissue"=>$data['Spassportplaceofissue'][$i],
                "Remarks"=>$data['SRemarks'][$i],
            );
            array_push($shareholder_array,$shareholder);
        }
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
        $subscriber = array(
            "name"=>$data['Subscribers'],
            "shares"=>$data['Subscriber_Share']
        );
        $auditor = array(
            "name"=>$data['NameoftheAuditor'],
            "addressline1"=>$data['AUAddressline1'],
            "addressline2"=>$data['AUAddressline2'],
            "addressline3"=>$data['AUAddressline3'],
            "nric/passport"=>$data['AUNRIC/Passport'],
            "otherOccupation"=>$data['AUOtherOccupation'],
            "Nationality"=>$data['AUNationality'],
        );
        $chairman = $data['ChairmanoftheMeeting'];
        //
        
        //Create Company
        $this->Company->create();
        $unique_company_key = sha1($company_info['name'].$company_info['number'].time()); 
        $company_data = array(
           "name"=>$company_info['name'],
           //"register_number"=>$company_info['number'],
           "address_1"=>$company_info['address1'],
           "address_2"=>$company_info['address2'],
           "created_at"=>date('Y-m-d H:i:s'),
           "PrincipalActivity1"=>$company_info['PrincipalActivity1'],
           "P1Description"=>$company_info['P1Description'],
           "PrincipalActivity2"=>$company_info['PrincipalActivity2'],
           "P2Description"=>$company_info['P2Description'],
           "LOName"=>$LO_info["LOName"],
           "LOAddressline1"=>$LO_info["LOAddressline1"],
           "LOAddressline2"=>$LO_info["LOAddressline2"],
           "LOAcNo"=>$LO_info["LOAcNo"],
           "LOTelNo"=>$LO_info["LOTelNo"],
           "LOTelFax"=>$LO_info["LOTelFax"],
           "Currency"=>$data['Ccurrency'],
            "NumberOfShares"=>$sharesInCash['NumberOfShares'],
            "NominalAmountOfEachShare"=>$sharesInCash['NominalAmountOfEachShare'],
            "AmountPaid"=>$sharesInCash['AmountPaid'],
            "Due&Payable"=>$sharesInCash['Due&Payable'],
            "AmountPremiumPaid"=>$sharesInCash['AmountPremiumPaid'],
            "AuthorizedShareCapital"=>$sharesInCash['AuthorizedShareCapital'],
            "IssuedShareCapital"=>$sharesInCash['IssuedShareCapital'],
            "PaidupShareCapital"=>$sharesInCash['PaidupShareCapital'],
            "IssuedOrdinary"=>$sharesInCash['IssuedOrdinary'],
            "PaidUpOrdinary"=>$sharesInCash['PaidUpOrdinary'], 
            "unique_key"=>$unique_company_key,
            "suscriberName"=>$subscriber['name'],
            "suscriberShares"=>$subscriber['shares'],
            "Approved"=>0,
            "FinancialYear"=>$company_info['FinancialYear']
          
        );
        $this->Company->save($company_data);
        $company = $this->Company->find("first",array(
            "conditions"=>array(
                "Company.unique_key"=>$unique_company_key
            )
        ));
        $company_info['company_id']=$company ['Company']['company_id'];
       // ChromePhp::log($directors[0]['addressline3_Singapore']);
        $form->generateForm24($shareholder_array,$director_array,$sharesInCash,$LO_info,$company_info);
        $form->generateForm44($director_array,$company_info,$LO_info);
        $form->generateForm45Inc($director_array,$company_info);
         $form->generateFormParticular($director_array,$company_info);
        $form->generateForm45BInc($secretary,$company_info,$LO_info);
        $form->generateForm49Inc($secretary,$director_array,$company_info,$LO_info);
        $form->generateBusinessProfile($director_array,$shareholder_array,$company_info,$sharesInCash);
        $form->generateFormMAA($company_info,$LO_info);
        $form->generateForm9($company_info);
        $form->generateFormFirstMeeting($company_info,$director_array,$subscriber,$chairman);
         $form->generateRegisterOfApplicationsAndAI($shareholder_array,$company_info,$sharesInCash);
         $form->generateRegisterDirectorsAlternateD($director_array,$company_info);
           $form->generateMemberForm($shareholder_array,$company_info,$sharesInCash);
            $form->generateDirectorInterest($director_array,$company_info,$sharesInCash);
        $form->generateCertificationSH($shareholder_array,$company_info);
        $form->generateRegisterMortgagesCharges($company_info);
        $form->generateRegisterTransfer($company_info);
        $form->generateRegisterSealing($company_info);
         $form->generateSecretaryAuditor($company_info,$secretary,$auditor);
        
       
        
        //create directors             
        foreach($director_array as $dData){
            $this->StakeHolder->create();
            $stakeholder_data = array(
                "company_id"=>$company['Company']['company_id'],
                "name"=>$dData['name'],
                "address_1"=>$dData['addressline1_Singapore'],
                "address_2"=>$dData['addressline2_Singapore']." ".$dData['addressline3_Singapore'],
                "nric"=>$dData["NRIC/Passport"],
                "created_at"=>date('Y-m-d H:i:s'),
                "nationality"=>$dData['NationalityCurrent'],
                "Director"=>1       
            );
            $this->StakeHolder->save($stakeholder_data);
//            $director = $this->StakeHolder->find("first",array(
//                "conditions"=>array(
//                    "StakeHolder.nric"=>$dData["NRIC/Passport"]
//                )
//            ));
            $this->Director->create();
            $director_data = array(
                "id"=>$this->StakeHolder->id,
                "Mode"=>"appointed",
                "primary_address"=>1,
                "addressline1_Singapore"=>$dData['addressline1_Singapore'],
                "addressline2_Singapore"=>$dData['addressline2_Singapore'],
                "addressline3_Singapore"=>$dData['addressline3_Singapore'],
                "addressline1_OverSea"=>$dData['addressline1_OverSea'],
                "addressline2_OverSea"=>$dData['addressline2_OverSea'],
                "addressline3_OverSea"=>$dData['addressline3_OverSea'],
                "addressline1_Other"=>$dData['addressline1_Other'],
                "addressline2_Other"=>$dData['addressline2_Other'],
                "addressline3_Other"=>$dData['addressline3_Other'],
                "CertificateNo"=>$dData['CertificateNo'],
                "NationalityatBirth"=>$dData['NationalityatBirth'],
                "Occupation"=>$dData['Occupation'],
                "NumberofShares"=>$dData['NumberofShares'],
                "NumberofSharesInwords"=>$dData['NumberofSharesInwords'],
                "DateofBirth"=>$dData['DateofBirth'],
                "ClassofShares"=>$dData['ClassofShares'],
                "Currency"=>$dData['Currency'],
                "Placeofbirth"=>$dData['Placeofbirth'],
                "Nricdateofissue"=>$dData['Nricdateofissue'],
                "nricplaceofissue"=>$dData['nricplaceofissue'],
                "passportno"=>$dData['passportno'],
                "passportdateofissue"=>$dData['passportdateofissue'],
                "passportplaceofissue"=>$dData['passportplaceofissue'],
                "NatureOfContract"=>$dData['NatureOfContract'],
                "Remarks"=>$dData['Remarks'],
                "ConsentToActAsDirector"=>$dData['ConsentToActAsDirector'],
                "FormerName"=>$dData['FormerName'],
            );
            $this->Director->save($director_data );
            
        }
        //create shareholders            
        foreach($shareholder_array as $dData){
            $exist = $this->StakeHolder->find("first",array(
                "conditions"=>array(
                    "StakeHolder.nric"=>$dData["NRIC/Passport"],
                    "StakeHolder.company_id"=>$company['Company']['company_id'],
                )
            ));
            if($exist){
                $this->StakeHolder->id = $exist['StakeHolder']['id'];
                $this->StakeHolder->saveField("Shareholder",1);
                
            }else{
                $this->StakeHolder->create();
                $stakeholder_data = array(
                    "company_id"=>$company['Company']['company_id'],
                    "name"=>$dData['name'],
                    "address_1"=>$dData['addressline1_Singapore'],
                    "address_2"=>$dData['addressline2_Singapore']." ".$dData['addressline3_Singapore'],
                    "nric"=>$dData["NRIC/Passport"],
                    "created_at"=>date('Y-m-d H:i:s'),
                    "nationality"=>$dData['NationalityCurrent'],
                    "Shareholder"=>1       
                );

                $this->StakeHolder->save($stakeholder_data);
               
            }
//            $shareholder = $this->StakeHolder->find("first",array(
//                   "conditions"=>array(
//                       "StakeHolder.nric"=>$dData["NRIC/Passport"]
//                   )
//            ));
            $this->ShareHolder->create();
            $shareholder_data = array(
                "id"=> $this->StakeHolder->id,
                "primary_address"=>1,
                "addressline1_Singapore"=>$dData['addressline1_Singapore'],
                "addressline2_Singapore"=>$dData['addressline2_Singapore'],
                "addressline3_Singapore"=>$dData['addressline3_Singapore'],
                "addressline1_OverSea"=>$dData['addressline1_OverSea'],
                "addressline2_OverSea"=>$dData['addressline2_OverSea'],
                "addressline3_OverSea"=>$dData['addressline3_OverSea'],
                "addressline1_Other"=>$dData['addressline1_Other'],
                "addressline2_Other"=>$dData['addressline2_Other'],
                "addressline3_Other"=>$dData['addressline3_Other'],
                "CertificateNo"=>$dData['CertificateNo'],
                "NationalityatBirth"=>$dData['NationalityatBirth'],
                "Occupation"=>$dData['Occupation'],
                "NumberofShares"=>$dData['NumberofShares'],
                "NumberofSharesInwords"=>$dData['NumberofSharesInwords'],
                "DateofBirth"=>$dData['DateofBirth'],
                "ClassofShares"=>$dData['ClassofShares'],
                "Currency"=>$dData['Currency'],
                "Placeofbirth"=>$dData['Placeofbirth'],
                "Nricdateofissue"=>$dData['Nricdateofissue'],
                "nricplaceofissue"=>$dData['nricplaceofissue'],
                "passportno"=>$dData['passportno'],
                "passportdateofissue"=>$dData['passportdateofissue'],
                "passportplaceofissue"=>$dData['passportplaceofissue'],
                "Remarks"=>$dData['Remarks'],
                "MembersRegisterNo"=>$dData['MembersRegisterNo'],
                
            );
            $this->ShareHolder->save($shareholder_data);
        }//end
        //create secretary
         $exist = $this->StakeHolder->find("first",array(
                "conditions"=>array(
                    "StakeHolder.nric"=>$secretary["NRIC/Passport"],
                    "StakeHolder.company_id"=>$company['Company']['company_id'],
                )
        ));
        if($exist){
            $this->StakeHolder->id = $exist['StakeHolder']['id'];
            $this->StakeHolder->saveField("Secretary",1);

        }else{
            $this->StakeHolder->create();
            $stakeholder_data = array(
                "company_id"=>$company['Company']['company_id'],
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
//        $sec_obj = $this->StakeHolder->find("first",array(
//            "conditions"=>array(
//                "StakeHolder.nric"=>$secretary["NRIC/Passport"],
//            )
//        ));
        $this->Secretary->create();
        $secretary_data=array(
            "id"=> $this->StakeHolder->id,
            "Mode"=>"appointed",
            "Occupation"=>$secretary['Occupation'],
            "OtherOccupation"=>$secretary['OtherOccupation'],
        );
        $this->Secretary->save($secretary_data);//end
        //Create Auditors
        $exist = $this->StakeHolder->find("first",array(
                "conditions"=>array(
                    "StakeHolder.nric"=>$auditor["nric/passport"],
                    "StakeHolder.company_id"=>$company['Company']['company_id'],
                )
        ));
        if($exist){
            $this->StakeHolder->id = $exist['StakeHolder']['id'];
            $this->StakeHolder->saveField("Auditor",1);

        }else{
            $this->StakeHolder->create();
            $stakeholder_data = array(
                "company_id"=>$company['Company']['company_id'],
                "name"=>$auditor['name'],
                "address_1"=>$auditor['addressline1'],
                "address_2"=>$auditor['addressline2']." ".$auditor['addressline3'],
                "nric"=>$auditor["nric/passport"],
                "created_at"=>date('Y-m-d H:i:s'),
                "nationality"=>$auditor['Nationality'],
                "Auditor"=>1       
            );

            $this->StakeHolder->save($stakeholder_data);
        }
//        $auditor_obj = $this->StakeHolder->find("first",array(
//            "conditions"=>array(
//                "StakeHolder.nric"=>$auditor["nric/passport"],
//            )
//        ));
        $this->Auditor->create();
        $auditor_data=array(
            "id"=> $this->StakeHolder->id,
            "Mode"=>"appointed",
            "OtherOccupation"=>$auditor['otherOccupation'],
            "addressLine1"=>$auditor['addressline1'],
            "addressLine2"=>$auditor['addressline2'],
            "addressLine3"=>$auditor['addressline3'],
        );
        $this->Auditor->save($auditor_data);//end
        //save to Document table
        $this->Document->create();
        $hash_value = sha1($company_info['name'].$company_info['number'].date('Y-m-d H:i:s'));
        $document = array(
            'company_id'=>$company['Company']['company_id'],
            'function_id'=>10,
            'created_at'=>date('Y-m-d H:i:s'),
            'unique_key'=>$hash_value,
            'status'=>"Available",
            'description'=>"Incorporation forms for ".$company['Company']['name']
            
        );
        $this->Document->save($document);
        //Save to DocumentDirector table
        $documentDirectorEntry = $this->Document->find('first',array(
            'conditions'=>array(
                    'Document.unique_key'=>$hash_value
            )
        ));
        $documentControl = new DocumentsController();
        $documentControl->initiateStatusChecking($documentDirectorEntry['Document']['id']);
         $data_Inc = array(
                "document_id"=>$documentDirectorEntry['Document']['id'],
                //"event_id"=>null,
                "chairman"=>$chairman,
                "directors"=>$director_array[0]['name'].",".$director_array[1]['name'].",".$director_array[2]['name'],
                "shareholders"=>$shareholder_array[0]['name'].",".$shareholder_array[1]['name'].",".$shareholder_array[2]['name'],
                "auditor"=>$auditor['name'],
                "secretary"=>$secretary['name']
         );
        $this->IncorporationDocument->create();

        $this->IncorporationDocument->save($data_Inc);
        $files_to_zip = $form->form_downloads;
        $time = date('Y-m-d H-i-s');
        $this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS . 'files' . DS . 'zip' . DS .'IncorporationDocument'.$time.'.zip');
        //$this->create_zip($files_to_zip,APP . WEBROOT_DIR . DS.'\files\zip\IncorporationDocument'.$time.'.zip');
        foreach($files_to_zip as $file){ //Delete files after zipping
            unlink($file);

        };
        //Create zip file
        $this->ZipFile->create();
        $zip_file = array(
            "function_id"=>10,
            "company_id"=>$company['Company']['company_id'],
            "path"=>'IncorporationDocument'.$time.'.zip',
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
            "action"=>'index'
        ));
    

    }
    function editIncorporation(){
        $companies = $this->Company->find("all");
        $this->set("title","Select company for editing incoporation");
        $this->set("companies",$companies);
    }
    function approveIncorporation(){
        $this->Paginator->settings= array(
                       'limit'=>6,
                       'order' => array(
                            'Company.created_at' => 'DESC'
                            ),
         );
        $companies = $this->Paginator->paginate('Company');
        $this->set("companies",$companies);
        $this->set("header","Incorporation approval status");
    }
    function approveInc(){
        $id = $this->params['url']['id'];
        $this->Company->id = $id;
        $this->Company->saveField("Approved",1);
        $this->Session->setFlash(
		    'Company has been successfully approved!',
		    'default',
		    array('class' => 'alert alert-success')
		);
        return $this->redirect(array(
            "controller"=>'Incorporations',
            "action"=>'approveIncorporation'
        ));
    }
    function saveDateAndNo(){
        $data= $this->request->data;
        //$this->Company->id=$data['company_id'];
        $saved_data = array(
            "Company.register_number"=>"'".$data['Company_Number']."'",
            "Company.Date_Of_Inc"=>"'".$data['dateOfIncorporation']."'",
        );
        if ($this->Company->updateAll($saved_data,array("Company.company_id"=>$data['company_id']))){
            $this->Session->setFlash(
		    'Details has been successfully submitted!',
		    'default',
		    array('class' => 'alert alert-success')
		);
            return $this->redirect(array(
                "controller"=>'Incorporations',
                "action"=>'approveIncorporation'
            ));
        }else{
            $this->Session->setFlash(
		    'Details has not been successfully submitted!',
		    'default',
		    array('class' => 'alert alert-danger')
		);
            return $this->redirect(array(
                "controller"=>'Incorporations',
                "action"=>'approveIncorporation'
            ));
        }
    }
}
