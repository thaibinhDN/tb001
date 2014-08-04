<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'FunctionCorps');

/**
 * Companies Controller
 *
 * @property Companies $Companies
 * @property PaginatorComponent $Paginator
 */
class CompaniesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Session', 'Paginator');
        public $helpers = array('Html','Session');
	public $uses = array('StakeHolder','User','Company', 'Director','FunctionCorp');

	public $paginate =  array(
        'limit' => 10
    );

	public function beforeFilter() {
		parent::beforeFilter();

	} 

/**
 * index method
 *
 * @return void
 */
	public function index() {
                
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
                           "item"=>"company"
                       )
                    ));
                }else{
                    $companies = $this->Company->find('all');

                    $this->set('title', 'LIST ALL COMPANIES');
                    $this->set('companies', $companies);
                }
	}

	public function companyForm() {

		$this->set('title', 'CREATE NEW COMPANY');
	}

	public function addCompany() {
		$name = $this->request->data['Company']['name'];
		$register_number = $this->request->data['Company']['register_number'];
		$address1 = $this->request->data['Company']['address_1'];
		$address2 = $this->request->data['Company']['address_2'];
		$telephone = $this->request->data['Company']['telephone'];
		$fax = $this->request->data['Company']['fax'];
                $fy = $this->request->data['Company']['FY'];

		if ($this->request->is('post')) {
			$this->Company->create();

			$data = array(
				'name' => $name,
				'register_number' => $register_number,
				'address_1' => $address1,
				'address_2' => $address2,
				'telephone' => $telephone,
				'fax' => $fax,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
                                'FinancialYear'=>$fy
			);
			$this->Company->save($data);

			$this->Session->setFlash(
			    'Company created',
			    'default',
			    array('class' => 'alert alert-success')
			);

			return $this->redirect(array('action' => 'index'));
		}

		$this->Session->setFlash(
		    'Oops, something went wrong. Please try again!',
		    'default',
		    array('class' => 'alert alert-danger')
		);

		return $this->redirect(array('action' => 'companyForm'));
	}

	public function editCompanyForm() {
		$company_id = $this->request->params['id'];

		$company = $this->Company->find('first', array('conditions' => array('company_id' => $company_id)));

		$this->set('title', 'EDIT COMPANY PROFILE');
		$this->set('company', $company);
	}

	public function editCompany() {
		if ($this->request->is('post')) {
			$this->Company->updateAll(
				array(
					'Company.name' => "'".$this->request->data['Company']['name']."'",
					'Company.register_number' => "'".$this->request->data['Company']['register_number']."'",
					'Company.address_1' => "'".$this->request->data['Company']['address_1']."'",
					'Company.address_2' => "'".$this->request->data['Company']['address_2']."'",
					'Company.telephone' => "'".$this->request->data['Company']['telephone']."'",
					'Company.fax' => "'".$this->request->data['Company']['fax']."'",
					'Company.updated_at' => "'".date('Y-m-d H:i:s')."'"
				),
				array(
					'Company.company_id' => $this->request->data['Company']['company_id']
				)
			);

			$this->Session->setFlash(
			    'Update is successful',
			    'default',
			    array('class' => 'alert alert-success')
			);

			return $this->redirect(array('action' => 'index'));
		}

		$this->Session->setFlash(
		    'Oops, something went wrong. Please try again!',
		    'default',
		    array('class' => 'alert alert-danger')
		);

		return $this->redirect(array('action' => 'editCompanyForm', 'id' => $this->request->data['Company']['company_id']));
	}

	public function deleteCompany() {
		$id = $this->request->params['id'];

		$company = $this->Company->find('first', array('conditions' => array('company_id = ' => $id)));
		$this->Company->delete(array('Company.company_id' => $id));

		$this->Session->setFlash(
		    'Delete is successful',
		    'default',
		    array('class' => 'alert alert-success')
		);

		return $this->redirect(array('action' => 'index'));
	}

	public function viewCompany() {
		$company_id = $this->request->params['id'];

		$company = $this->Company->find('first', array('conditions' => array('company_id' => $company_id)));

		$directors = $this->Director->find('all', array('conditions' => array('Director.company_id' => $company_id), 'order' => array('Director.Mode ASC')));
                ChromePhp::log(json_encode($directors) );
		$this->set('title', strtoupper($company['Company']['name']).'\'S DIRECTORS');
		$this->set('company', $company);
		$this->set('directors', $directors);
	}

	public function directorForm() {
		$company_id = $this->request->params['id'];

		$this->set('title', 'CREATE NEW DIRECTOR');
		$this->set('company_id', $company_id);
	}

	public function addDirector() {
            $data = $this->request->data;
            $company_id = $this->request->data['Director']['company_id'];

         
           


		$dData= array(
                    "name"=>$data['DNameoftheAppointDirector'],
                    "addressline1_Singapore"=>$data['DAddressline1S'],
                    "addressline2_Singapore"=>$data['DAddressline2S'],
                    "addressline3_Singapore"=>$data['DAddressline3S'],
//                    "addressline1_OverSea"=>$data['DAddressline1OS'],
//                    "addressline2_OverSea"=>$data['DAddressline2OS'],
//                    "addressline3_OverSea"=>$data['DAddressline3OS'],
//                    "addressline1_Other"=>$data['DAddressline1OT'],
//                    "addressline2_Other"=>$data['DAddressline2OT'],
//                    "addressline3_Other"=>$data['DAddressline3OT'],
                    "NRIC/Passport"=>$data['DNRIC/Passport'],
                    "NationalityatBirth"=>$data['DNationalityatBirth'],
                    "NationalityCurrent"=>$data['DNationalityCurrent'],
                    "Occupation"=>$data['DOccupation'],
                    "NumberofShares"=>$data['DNumberofShares'],
                    "NumberofSharesInwords"=>$data['DNumberofSharesInwords'],
                    "CertificateNo"=>$data['DCertificateNo'],
                    "DateofBirth"=>$data['DDateofBirth'],
                    "ClassofShares"=>$data['DClassofShares'],
                    "Currency"=>$data['DCurrency'],
                    "Placeofbirth"=>$data['DPlaceofbirth'],
                    "Nricdateofissue"=>$data['DNricdateofissues'],
                    "nricplaceofissue"=>$data['DNricplaceofissue'],
                    "passportno"=>$data['DPassportno'],
                    "passportdateofissue"=>$data['DPassportDateOfIssue'],
                    "passportplaceofissue"=>$data['DPassportPlaceOfIssue'],
                    "NatureOfContract"=>$data['DNatureofContract'],
                    "Remarks"=>$data['DRemarks'],
                    "ConsentToActAsDirector"=>$data['DConsenttoactasdirectorofthecompany'],
                    "FormerName"=>$data['DFormerNameifany']
            );
                //ChromePhp::log($name." ".$nric.$address1.$address2.$nationality.$occupation);
                $this->StakeHolder->create();
                $stakeholder_data = array(
                    "company_id"=>$company_id,
                    "name"=>$dData['name'],
                    "address_1"=>$dData['addressline1_Singapore'],
                    "address_2"=>$dData['addressline2_Singapore']." ".$dData['addressline3_Singapore'],
                    "nric"=>$dData["NRIC/Passport"],
                    "created_at"=>date('Y-m-d H:i:s'),
                    "nationality"=>$dData['NationalityCurrent'],
                    "Director"=>1       
                 );
                $this->StakeHolder->save($stakeholder_data);
//                $director=$this->StakeHolder->find("first",array(
//                    "conditions"=>array(
//                                    "nric"=>$nric
//                    )
//                ));
                $this->Director->create();
            $director_data = array(
                "id"=>$this->StakeHolder->id,
                "Mode"=>"appointed",
                "primary_address"=>1,
                "addressline1_Singapore"=>$dData['addressline1_Singapore'],
                "addressline2_Singapore"=>$dData['addressline2_Singapore'],
                "addressline3_Singapore"=>$dData['addressline3_Singapore'],
//                "addressline1_OverSea"=>$dData['addressline1_OverSea'],
//                "addressline2_OverSea"=>$dData['addressline2_OverSea'],
//                "addressline3_OverSea"=>$dData['addressline3_OverSea'],
//                "addressline1_Other"=>$dData['addressline1_Other'],
//                "addressline2_Other"=>$dData['addressline2_Other'],
//                "addressline3_Other"=>$dData['addressline3_Other'],
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
//                $create_data=array(
//                    "company"=>$company_id,
//                    "functionCorp"=>1,
//                    "director_id"=> $this->Director->id
//                );
//                array_push($created_datas,$create_data);
//
//            $this->Session->write('created_director', $created_datas);
//                
//
           $this->Session->setFlash(
			    'Director created',
			    'default',
			    array('class' => 'alert alert-success')
			);
             return $this->redirect(array("controller"=>"FunctionCorps","action"=>"AppointResignD","?"=>array(
                 "company"=>$company_id
             )));
	}

	public function editDirectorForm() {
		$director_id = $this->request->params['id'];

		$director = $this->Director->find('first', array('conditions' => array('director_id' => $director_id)));

		$this->set('title', 'EDIT DIRECTOR PROFILE');
		$this->set('director', $director);
	}

	public function editDirector() {
		$director = $this->Director->find('first', array('conditions' => array('director_id' => $this->request->data['Director']['director_id'])));

		if ($this->request->is('post')) {
			$this->Director->updateAll(
				array(
					'Director.name' => "'".$this->request->data['Director']['name']."'",
					'Director.nric' => "'".$this->request->data['Director']['nric']."'",
					'Director.address_1' => "'".$this->request->data['Director']['address_1']."'",
					'Director.address_2' => "'".$this->request->data['Director']['address_2']."'",
					'Director.nationality' => "'".$this->request->data['Director']['nationality']."'",
					'Director.occupation' => "'".$this->request->data['Director']['occupation']."'",
					'Director.Mode' => "'".($this->request->data['Director']['status'] == 0 ? 'appointed' :($this->request->data['Director']['status']==1?'resigned':null) )."'",
					'Director.updated_at' => "'".date('Y-m-d H:i:s')."'"
				),
				array(
					'Director.director_id' => $this->request->data['Director']['director_id']
				)
			);

			$this->Session->setFlash(
			    'Update is successful',
			    'default',
			    array('class' => 'alert alert-success')
			);

			return $this->redirect(array('action' => 'viewCompany', 'id' => $director['Director']['company_id']));
		}

		$this->Session->setFlash(
		    'Oops, something went wrong. Please try again!',
		    'default',
		    array('class' => 'alert alert-danger')
		);

		return $this->redirect(array('action' => 'editDirectorForm', 'id' => $this->request->data['Director']['director_id']));
	}

	public function deleteDirector() {
		$id = $this->request->params['id'];

		$director = $this->Director->find('first', array('conditions' => array('director_id = ' => $id)));
		$this->Director->delete(array('Director.director_id' => $id));

		$this->Session->setFlash(
		    'Delete is successful',
		    'default',
		    array('class' => 'alert alert-success')
		);

		return $this->redirect(array('action' => 'viewCompany', 'id' => $director['Director']['company_id']));
	}

	public function getDirectors() {
		$this->autoRender = false;
		$company_id = $this->request->params['id'];

		$directors = $this->StakeHolder->find('all', array('conditions' => array(
                            'StakeHolder.company_id' => $company_id,
                            'StakeHolder.Director'  => 1
                        )            
                    ));
               //ChromePhp::log(json_encode($directors) );
                
		return json_encode($directors);
	}
        public function getStakeholders(){
            $this->autoRender = false;
            $company_id = $this->params['url']['id'];

            $stakeholders = $this->StakeHolder->find('all', array('conditions' => array(
                        'StakeHolder.company_id' => $company_id
                    )            
                ));
               //ChromePhp::log($stakeholders);
                
		return json_encode($stakeholders);
        }
        

}