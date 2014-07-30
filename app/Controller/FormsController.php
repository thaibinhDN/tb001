<?php
define('FPDF_FONTPATH', WWW_ROOT . 'fonts' . DS);
App::import('Vendor', 'fpdi/fpdi');
App::import('Controller', 'FunctionCorps');
App::import('Controller', 'Companies');
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
include 'ChromePhp.php';
/**
 * Forms Controller
 *
 * @property Forms $Forms
 * @property PaginatorComponent $Paginator
 */
class FormsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Session', 'Paginator');
        public $helpers  = array(
              'Html', 
              'Session',
              'Paginator'
              );
	public $uses = array('Auditor','ShareHolder','StakeHolder','Secretary','User','Document','Form', 'Company', 'Director','Pdf','FunctionCorp','ZipFile');
	public $template_path = 'files/templates/';
	public $pdf_path = 'files/pdf/';
        public $zip_path = 'files/zip/';
        public $form_downloads = array();
	public $paginate = array(
        'limit' => 7,
        'order' => array(
            'Pdf.created_at' => 'DESC'
        )
    );

	public function beforeFilter() {
		parent::beforeFilter();

	} 

/**
 * index method
 *
 * @return void
 *  
 */

        public function index() {
		//$pdfs = $this->Pdf->find('all', array('order' => array('Pdf.created_at DESC')));
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
                           "item"=>"formDownloads"
                       )
                    ));
                }else{
                    $this->set('title', 'LIST ALL FORM');
                    ChromePhp::log($this->paginate);
                    $this->Paginator->settings= array(
                      'limit'=>10, 
                       'order' => array(
                            'ZipFile.created_at' => 'DESC'
                            ),
                    );
                    $pdfs = $this->Paginator->paginate('ZipFile');
                    $this->set('pdfs', $pdfs);
                }
	}
        
     
        
	public function generateForm() {
            
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
                           "item"=>"formsGenerate"
                       )
                    ));
                }else{
                    $functions = $this->FunctionCorp->find('all',array(
                        "conditions"=>array(
                            "FunctionCorp.function_id !="=>10
                        )
                    ));
                    $companies = $this->Company->find('all',array(
                        "conditions"=>array(
                            "Company.Approved"=>1
                        )
                    ));
                    $this->set('title', 'GENERATE FORM');

                    $this->set('functions', $functions);
                    $this->set('companies', $companies);
                }
                
	}
     

	public function chooseForm() {
		$form_id = $this->request->params['id'];

		$form = $this->Form->find('first', array('conditions' => array('form_id = ' => $form_id)));
		$function_name = str_replace(' ', '', strtolower($form['Form']['name']));

		$this->redirect(array('action' => $function_name));
	}

	public function previewForm($data) {
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
                     }
                }
                $preview_data = array(
                    "title"=>'Preview Form',
                    "data"=>$data, //Remove or not

                    "Director"=>$directors,
                );

                 return $preview_data;   
	}
        
	public function form45() {
		/* 
			requirements:
				- one director
				- start date
				- language
				- interpreter name
				- interpreter nric
				- creation date
		*/

		$companies = $this->Company->find('all');
		$form = $this->Form->find('first', array('conditions' => array('name = ' => 'Form 45')));
		$fields = $this->FormField->find('all', array('conditions' => array('FormField.form_id = ' => $form['Form']['form_id'])));
                
                $form45_info = array(
                    "Company"=>$companies,
                    "Form"=>$form,
                    "Field"=>$fields,
                );
                return $form45_info;

	}

	public function generateForm45($data) {
		$form_id = 1;
		//$director_id = $data['director45'];
		$director_ids = $data['director'];
        
                $types = $data['type'];
                $directors = array();
                for ($i = 0; $i < count($director_ids); $i++) {
			$director = $this->StakeHolder->find('first', array('conditions' => array('StakeHolder.id' => $director_ids[$i])));
			$director['type'] = $types[$i];
                        
			array_push($directors, $director);
		}
               
                for($i = 0;$i<count($director_ids);$i++){
                    if ($directors[$i]['type']=="appointment"){
                    $director = $this->StakeHolder->find('first', array('conditions' => array('StakeHolder.id' => $director_ids[$i])));
                    $company_id = $director['StakeHolder']['company_id'];
                    $pdf_name = 'Form_45_'.time().$director_ids[$i];
                    $pdf = new FPDI(); // init
		// create overlays
                    $pdf->SetFont('Helvetica', '', 9); // set font type
                    $pdf->SetTextColor(0, 0, 0); // set font color

            // page 1
                     $pdf->addPage(); // add page
                    $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form45_template.pdf"); // load template
                    $tplIdx = $pdf->importPage(1); // import page 1
                    $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template


              
                    // write company name
                    $pdf->SetXY(70, 76);
                    $pdf->Write(10, $director['Company']['name']);

                    // write company number
                    $pdf->SetXY(62, 83.5);
                    $pdf->Write(10, $director['Company']['register_number']);

                    // page 2
                    $pdf->addPage(); // add page
                    $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form45_template.pdf"); // load template
                    $tplIdx = $pdf->importPage(2); // import page 2
                    $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template

                    // write company name
                    $pdf->SetXY(73, 45);
                    $pdf->Write(10, $director['Company']['name']);

                    // write company number
                    $pdf->SetXY(64, 51);
                    $pdf->Write(10, $director['Company']['register_number']);

                    // page 3
                    $pdf->addPage(); // add page
                    $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form45_template.pdf"); // load template
                    $tplIdx = $pdf->importPage(3); // import page 3
                    $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template

                    // write company name
                    $pdf->SetXY(73, 45);
                    $pdf->Write(10, $director['Company']['name']);

                    // write company number
                    $pdf->SetXY(64, 51);
                    $pdf->Write(10, $director['Company']['register_number']);

                  
                    // write director name
                    $pdf->SetXY(55, 127);
                    $pdf->Write(10, $director['StakeHolder']['name']);

                    // write director address
                    $pdf->SetXY(57, 133);
                    $pdf->Write(10, $director['StakeHolder']['address_1'].' '.$director['StakeHolder']['address_2']);

                    // write director nric
                    $pdf->SetXY(72, 139);
                    $pdf->Write(10, $director['StakeHolder']['nric']);

                    // write director nationality
                    $pdf->SetXY(125, 139);
                    $pdf->Write(10, $director['StakeHolder']['nationality']);

                    $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');

                    // save to database
                    //$this->Pdf->create();
                    $data = array(
                            'form_id' => $form_id,
                            'company_id' => $company_id,
                            'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                            'created_at' => date('Y-m-d H:i:s')
                    );
                    //$this->Pdf->save($data); 
                    array_push($this->form_downloads,$data['pdf_url']);
                    }
                }

		// generate PDF
		
	}

	public function form49() {
		/* 
			requirements:
				- one/more director(s)
				- creation date
				- prepared by
		*/
                
		$companies = $this->Company->find('all');
		$form = $this->Form->find('first', array('conditions' => array('name = ' => 'Form 49')));
		$fields = $this->FormField->find('all', array('conditions' => array('FormField.form_id = ' => $form['Form']['form_id'])));
                $form49_info = array(
                    "Form"=>$form,
                    "Field"=>$fields,
                ); 
             
                return $form49_info;
		//$this->set('title', 'FORM 49');
		//$this->set('companies', $companies);
		//$this->set('form', $form);
		//$this->set('fields', $fields);
	}
        public function generateResolutionSignatoriesUOB($data){
            $form_id = 3; 
            $company = $this->Company->find('first',array('conditions'=>array('company_id = '=> $data['company'])));
            //ChromePhp::log($company);
            $company_id = $company['Company']['company_id'];
            $director = $data['director'];
         
         
            

            $pdf_name = 'Resolution_Change_Signatories_UOB_template'.time();
            
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "Resolution Change Bank Signatories UOB_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); // import page 1esolution_template
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            // write company name
            $pdf->SetFont('Helvetica','',11);
            $pdf->SetXY(95,23);
            $pdf->Write(10, $company['Company']['name']);
            $pdf->SetXY(124,32);
            $pdf->Write(10, $company['Company']['register_number']);
            $pdf->SetXY(30,51);
            $pdf->Write(10,"RESOLUTIONS BY CIRCULAR BY DIRECTOR OF ".$director);
            $pdf->SetXY(30,61);
            $pdf->Write(10,"PASSED PURSUANT TO ARTICLE 100A OF THE ARTICLES OF ASSOCIATION");
            $pdf->SetXY(30,70);      
            $pdf->Write(10,"OF THE COMPANY");
            
            $pdf->SetFont('Helvetica','',12);
            $pdf->SetXY(85,82);      
            $pdf->Write(10,$data['bank']);
            
            
            
            
            
            
//            write AsIs Directors(The directors which are neither resigned or 
//            just be promoted
                $asIsStakeHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.company_id"=>$company_id
                    )
                ));
                $avail_ids=array();
                foreach($asIsStakeHolders as $asIsStakeHolder){
                    array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
                };
                
                $asIsDirectors = $this->Director->find("all",array(
                    "conditions"=>array(
                        "Director.Mode"=>"appointed",
                        "Director.id"=>$avail_ids
                    )
                ));
            $y = 210;
                 for($i = 0;$i<count($asIsDirectors);$i=$i+2){
                    $pdf->SetFont('Helvetica','',12);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsDirectors[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsDirectors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsDirectors[$i+1]['StakeHolder']['name']);
                            
                        }
                   
                    $y += 30;
             }
            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');

		// save to database
		///$this->Pdf->create();
                
		$data = array(
			'form_id' => $form_id,
			'company_id' => $company_id,
			'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
			'created_at' => date('Y-m-d H:i:s')
		);
		//$this->Pdf->save($data); 
                array_push($this->form_downloads,$data['pdf_url']);
        }
        public function generateIndemnityLetter($data){
            $form_id = 5;
            $company = $this->Company->find('first',array('conditions'=>array('company_id = '=> $data['company'])));
            $company_id = $data['company'];
    
             //write AsIs Directors(The directors which are neither resigned or 
            //just be promoted
                $asIsStakeHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.company_id"=>$company_id
                    )
                ));
                $avail_ids=array();
                foreach($asIsStakeHolders as $asIsStakeHolder){
                    array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
                };
                
                $asIsDirectors = $this->Director->find("all",array(
                    "conditions"=>array(
                        "Director.Mode"=>"appointed",
                        "Director.id"=>$avail_ids
                    )
                ));
            
             $pdf_name = 'Indemnity_Letter_'.time();
                    $pdf = new FPDI(); // init
                        // create overlays
                        $pdf->SetFont('Helvetica','',9); // set font type
                        $pdf->SetTextColor(0, 0, 0); // set font color
                     // page 1
                    $pdf->addPage(); // add page
                    $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "indemnityLetter_template.pdf"); // load template
                    $tplIdx = $pdf->importPage(1); // import page 1
                    $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
                    //Write Content
                    $pdf->setXY(59,47);
                    $pdf->Write(10, $company['Company']['name']);
                    $pdf->setXY(62,55);
                    $pdf->Write(10, $company['Company']['address_1']);
                    $pdf->setXY(62,58);
                    $pdf->Write(10, $company['Company']['address_2']);
                    $pdf->setXY(62,68);
                    $pdf->Write(10, $company['Company']['register_number']);
                    $pdf->setXY(55,129.5);
                    $pdf->Write(10, $company['Company']['LOName']);
                    $pdf->setXY(80,151);
                    $pdf->Write(10, $company['Company']['LOName']);
                    $pdf->setXY(80,171);
                    $pdf->Write(10, $company['Company']['LOName']);
                    
                    
                     $y = 240;
                 for($i = 0;$i<2;$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsDirectors[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsDirectors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsDirectors[$i+1]['StakeHolder']['name']);
                            
                        }
                   
                    $y += 30;
             }
                    
                    
                   

            // page 2
		$pdf->addPage(); // add page
		$pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "indemnityLetter_template.pdf"); // load template
		$tplIdx = $pdf->importPage(2); // import page 2
		$pdf->useTemplate($tplIdx, 10, 10, 200); // place the template
                    // save to database
                    //$this->Pdf->create();
                 $y = 70;
                 for($i = 2;$i<count($asIsDirectors);$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsDirectors[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsDirectors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsDirectors[$i+1]['StakeHolder']['name']);
                            
                        }
                   
                    $y += 30;
             }
                 $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
                    $data = array(
                            'form_id' => $form_id,
                            'company_id' => $company_id,
                            'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                            'created_at' => date('Y-m-d H:i:s')
                    );
                    //$this->Pdf->save($data);
                   array_push($this->form_downloads,$data['pdf_url']);
                   
                   
//                   
        }
        public function generateResignationLetter($data){
            $form_id = 4;
            $company = $this->Company->find('first',array('conditions'=>array('company_id = '=> $data['company'])));
            $company_id = $data['company'];
            
            $types = $data['type'];
            $directors_resigned = array();
      
            $function = $data['function'];        
            if ($data['function']==0){
                $director_ids = $data['secretary'];
                for ($i = 0; $i < count($director_ids); $i++) {
                    if($types[$i]=="cessation"){
                        $director = $this->Secretary->find('first', array('conditions' => array('Secretary.id ' => $director_ids[$i])));
                        $director['type'] = $types[$i];
                        $director['reportedTo']=$data['reportedTo'][$i];
                        array_push($directors_resigned, $director);
                    }
                }

            }else if($data['function']==1){
                 $director_ids = $data['director'];
                for ($i = 0; $i < count($director_ids); $i++) {
                    if($types[$i]=="cessation"){    
                        $director = $this->Director->find('first', array('conditions' => array('Director.id = ' => $director_ids[$i])));
                        $director['type'] = $types[$i];
                        $director['reportedTo']=$data['reportedTo'][$i];
                        array_push($directors_resigned, $director);
                    }
                }
            }

            
            if(count($directors_resigned)!=0){
                for ($i = 0; $i < count($directors_resigned); $i++) {
                   
                    $reportedTo=$directors_resigned[$i]['reportedTo'];
                    $name =  $directors_resigned[$i]['StakeHolder']['name'];
                    $address_1 = $directors_resigned[$i]['StakeHolder']['address_1'];
                    $nric = $directors_resigned[$i]['StakeHolder']['nric'];
                    $pdf_name = 'Resignation_Letter_'.time().$i;
                    $pdf = new FPDI(); // init
                        // create overlays
                        $pdf->SetFont('Helvetica','',12); // set font type
                        $pdf->SetTextColor(0, 0, 0); // set font color
                     // page 1
                    $pdf->addPage(); // add page
                    $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "resignationLetter_template.pdf"); // load template
                    $tplIdx = $pdf->importPage(1); // import page 1
                    $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
                    //Write Resigned Director's Name and address
                    $pdf->setXY(30,20);

                    $pdf->Write(10,$name);
                    $pdf->setXY(30,25);
                    $pdf->Write(10,$address_1);
                    
                    
                    //Write Company
                    $pdf->SetFont('Helvetica','',15);
                    $pdf->setXY(30,85);
                    $pdf->Write(10,$company['Company']['name']);
                    //Write the director in charge
                    $pdf->SetFont('Helvetica','',11);
                    $pdf->setXY(38,114);
                    $pdf->Write(10,$reportedTo);
                   //Write MainContent
                     $pdf->SetFont('Helvetica','',10);
                     $pdf->setXY(33,144.5);
                     $pdf->Write(10,$name);
                     
                      $pdf->setXY(74,144.5);
                     $pdf->Write(10,$nric);
                     
                     $pdf->setXY(48,149.5);
                    $pdf->Write(10,$company['Company']['name']);
                    $pdf->setXY(110,149.5);
                    $pdf->Write(10,$company['Company']['register_number']);
                    
                    $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');

                    // save to database
                    //$this->Pdf->create();
                    $data = array(
                            'form_id' => $form_id,
                            'company_id' => $company_id,
                            'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                            'created_at' => date('Y-m-d H:i:s')
                    );
                    //$this->Pdf->save($data);
                    array_push($this->form_downloads,$data['pdf_url']);
                }
            }
            
        }
        public function generateResolution($data){
            $form_id = 3;
            
            $company = $this->Company->find('first',array('conditions'=>array('company_id = '=> $data['company'])));
            //ChromePhp::log($company);
            $company_id = $company['Company']['company_id'];
            $director_ids = $data['director'];
            $types = $data['type'];
            $directors = array();
            for ($i = 0; $i < count($director_ids); $i++) {
			$director = $this->StakeHolder->find('first', array('conditions' => array('StakeHolder.id = ' => $director_ids[$i])));
			$director['type'] = $types[$i];
                        
			array_push($directors, $director);
            }
 
            $pdf_name = 'Resolution_'.time();
            
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "resolution_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); // import page 1
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            // write company name
                $pdf->SetFont('Helvetica','',15 );
		$pdf->SetXY(93,24);
		$pdf->Write(10, $company['Company']['name']);
                $pdf->SetXY(124,32);
		$pdf->Write(10, $company['Company']['register_number']);
                
                $y1 = 78;
            //Write Director resigned
            for($i = 0;$i<count($director_ids);$i++){
                if($directors[$i]['type']=='cessation'){
                    $pdf->SetFont('Helvetica','',10);
                    $pdf->SetXY(30,$y1);
                    $pdf->Write(10,"IT WAS RESOLVED that the resignation of " .$directors[$i]['StakeHolder']['name']);
                    $pdf->SetXY(30,$y1 + 8);
                    $pdf->Write(10,"as the directors of the Company, be and are hereby accepted with immediate effect.");
                     $y1 = $y1 + 18;
                }
            }
            
            //write AsIs Directors(The directors which are neither resigned or 
            //just be promoted
               $asIsStakeHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.company_id"=>$company_id
                    )
                ));
                $avail_ids=array();
                foreach($asIsStakeHolders as $asIsStakeHolder){
                    array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
                };
                
                $asIsDirectors = $this->Director->find("all",array(
                    "conditions"=>array(
                        "Director.Mode"=>"appointed",
                        "Director.id"=>$avail_ids
                    )
                ));
                 $y = 187;
                 for($i = 0;$i<count($asIsDirectors);$i=$i+2){
                    $pdf->SetFont('Helvetica','',15);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                        $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsDirectors[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsDirectors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                            $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsDirectors[$i+1]['StakeHolder']['name']);
                            
                        }
                   
                    $y += 30;
               }
                
                //ChromePhp::log($asIsDirectors);
            //page 2
            $pdf->addPage();// add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "resolution_template.pdf"); // load template
            $tplIdx = $pdf->importPage(2); // import page 1
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            // write company name
                $pdf->SetFont('Helvetica','',15 );
		$pdf->SetXY(93,24);
		$pdf->Write(10, $company['Company']['name']);
                $pdf->SetXY(124,32);
		$pdf->Write(10, $company['Company']['register_number']);
                //$x2 = 75;
                $y1 = 78;
            for($i = 0;$i<count($director_ids);$i++){
                if($directors[$i]['type']=='appointment'){
                    $pdf->SetFont('Helvetica','',10);
                    $pdf->SetXY(30,$y1);
                    $pdf->Write(10,"IT WAS RESOLVED that " .$directors[$i]['StakeHolder']['name']);
                    $pdf->SetXY(30,$y1 + 8);
                    $pdf->Write(10,"having consented to act as the directors of the Company, be and are hereby appointed as directors.");
                    $y1 = $y1 + 18;
                }
            }
            
            $y = 187;
                 for($i = 0;$i<count($asIsDirectors);$i=$i+2){
                    $pdf->SetFont('Helvetica','',15);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsDirectors[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsDirectors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsDirectors[$i+1]['StakeHolder']['name']);
                            
                        }
                   
                    $y += 30;
             }
            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');

		// save to database
		//$this->Pdf->create();
           
		$data = array(
			'form_id' => $form_id,
			'company_id' => $company_id,
			'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
			'created_at' => date('Y-m-d H:i:s')
		);
		//$this->Pdf->save($data);
                array_push($this->form_downloads,$data['pdf_url']);
        }
	public function generateForm49($data) {
		$form_id =2;
		//ChromePhp::log($data);
		$prepared_by = $data['prepared_by'];
                //ChromePhp::log($prepared_by);
		$types = $data['type'];
		$directors = array();//Change $directors to stakeholders
                if ($data['function']==0){
                    $director_ids = $data['secretary'];
                    for ($i = 0; $i < count($director_ids); $i++) {
                            $director = $this->StakeHolder->find('first', array('conditions' => array('StakeHolder.id ' => $director_ids[$i])));
                            $director['type'] = $types[$i];
                            $occupation = "Secretary";
                            array_push($directors, $director);
                    }

                }else if($data['function']==1){
                     $director_ids = $data['director'];
                    for ($i = 0; $i < count($director_ids); $i++) {
                            $director = $this->StakeHolder->find('first', array('conditions' => array('StakeHolder.id = ' => $director_ids[$i])));
                            $director['type'] = $types[$i];
                            $occupation = "Director";
                            array_push($directors, $director);
                    }
                }
                
		$company_id = $directors[0]['Company']['company_id'];
		$company_name = $directors[0]['Company']['name'];
		$company_number = $directors[0]['Company']['register_number'];
		
                $LOLName = $directors[0]['Company']['LOName'];
                $LOAddressLine1 = $directors[0]['Company']['LOAddressline1'];
                $LOAddressLine2 = $directors[0]['Company']['LOAddressline2'];
                $LOTelNo = $directors[0]['Company']['LOTelNo'];
                 $LOTelFax = $directors[0]['Company']['LOTelFax'];
                 $LOAcNo = $directors[0]['Company']['LOAcNo'];

		$pdf_name = 'Form_49_'.time();

		// generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica', '', 9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color

    	// page 1
    	$pdf->addPage(); // add page
    	$pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form49_template.pdf"); // load template
		$tplIdx = $pdf->importPage(1); // import page 1
		$pdf->useTemplate($tplIdx, 10, 10, 200); // place the template

		// write company name
		$pdf->SetXY(63, 56);
		$pdf->Write(10, $company_name);

		// write company number
		$pdf->SetXY(55, 63.8);
		$pdf->Write(10, $company_number);

		

		// write prepared by
		$pdf->SetXY(138, 197.5);
		$pdf->Write(10, $prepared_by);

		// write company name
		$pdf->SetXY(44, 251.5);
		$pdf->Write(10, $LOLName);

		// write company address 1
		$pdf->SetXY(46, 256.5);
		$pdf->Write(10, $LOAddressLine1);

		// write company address 2
		$pdf->SetXY(46.5, 259.5);
		$pdf->Write(10, $LOAddressLine2);

		// write company number
		$pdf->SetXY(85, 266.9);
		$pdf->Write(10, $LOTelFax );
                
                // write LO acc
		$pdf->SetXY(44, 263.6);
		$pdf->Write(10, $LOAcNo);

		// write company telp
		$pdf->SetXY(85, 263.6);
		$pdf->Write(10, $LOTelNo);
		$y_pos = 110;
		for ($i = 0; $i < 3; $i++) {
			if (!empty($directors[$i])) {
				// write director name
                                $name = $directors[$i]['StakeHolder']['name'];
                                $address_1 = $directors[$i]['StakeHolder']['address_1'];
                                $address_2 = $directors[$i]['StakeHolder']['address_2'];
                                $nric = $directors[$i]['StakeHolder']['nric'];
                                $nationality = $directors[$i]['StakeHolder']['nationality'];
                               
                                $type = ($data['function']==1)?$directors[$i]['type']:$directors[$i]['type'];
                                
                                
				$pdf->SetXY(40, $y_pos);
				$pdf->Write(10,  $name);

				// write director address
				$pdf->SetXY(40, $y_pos+5);
				$pdf->Write(10, $address_1);
				$pdf->SetXY(40, $y_pos+10);
				$pdf->Write(10, $address_2);

				// write director nric
				$pdf->SetXY(105, $y_pos);
				$pdf->Write(10, $nric);

				// write director nationality
				$pdf->SetXY(105, $y_pos+5);
				$pdf->Write(10,  $nationality );

				// write director occupationoccupation
				$pdf->SetXY(105, $y_pos+10);
				$pdf->Write(10, $occupation );

				// write type
				$pdf->SetXY(145, $y_pos);
				$pdf->Write(10, ucfirst($type).' with');
				
				$pdf->SetXY(145, $y_pos+5);
				$pdf->Write(10, 'effect from');

			

				$y_pos += 25;
			}
		}

		// page 2
		$pdf->addPage(); // add page
		$pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form49_template.pdf"); // load template
		$tplIdx = $pdf->importPage(2); // import page 2
		$pdf->useTemplate($tplIdx, 10, 10, 200); // place the template
		
		$pdf->SetXY(64, 33);
		$pdf->Write(10, $company_name);

		// write company number
		$pdf->SetXY(56, 45);
		$pdf->Write(10, $company_number);

		// write directors
		$y_pos = 90;
		for ($i = 3; $i < count($directors); $i++) {
			if (!empty($directors[$i])) {
				// write director name
                            
                            $name = $directors[$i]['StakeHolder']['name'];
                            $address_1 = $directors[$i]['StakeHolder']['address_1'];
                            $address_2 = $directors[$i]['StakeHolder']['address_2'];
                            $nric = $directors[$i]['StakeHolder']['nric'];
                            $nationality = $directors[$i]['StakeHolder']['nationality'];
                            $occupation = $directors[$i]['StakeHolder']['occupation'];
                            $type = $directors[$i]['type'];
                            
				$pdf->SetXY(40, $y_pos);
				$pdf->Write(10,  $name);

				// write director address
				$pdf->SetXY(40, $y_pos+5);
				$pdf->Write(10, $address_1);
				$pdf->SetXY(40, $y_pos+10);
				$pdf->Write(10, $address_2);

				// write director nric
				$pdf->SetXY(105, $y_pos);
				$pdf->Write(10, $nric);

				// write director nationality
				$pdf->SetXY(105, $y_pos+5);
				$pdf->Write(10,  $nationality );

				// write director occupationoccupation
				$pdf->SetXY(105, $y_pos+10);
				$pdf->Write(10, $occupation );

				// write type
				$pdf->SetXY(145, $y_pos);
				$pdf->Write(10, ucfirst($type).' with');
				
				$pdf->SetXY(145, $y_pos+5);
				$pdf->Write(10, 'effect from');

			

				$y_pos += 25;
			}
		}

		$pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');

		
		//$this->Pdf->create();
               
		$data = array(
			'form_id' => $form_id,
			'company_id' => $company_id,
			'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
			'created_at' => date('Y-m-d H:i:s')
		);
		//$this->Pdf->save($data);
                array_push($this->form_downloads,$data['pdf_url']);
	}

	public function downloadForm() {
		$id = $this->request->params['id'];
		$pdf = $this->ZipFile->find('first', array('conditions' => array('id = ' => $id)));

		$filename_arr = explode('/', $pdf['ZipFile']['path']);
		$file = $filename_arr[count($filename_arr)-1];
		$extension_arr = explode('.', $file);
		$filename = $extension_arr[0];
		$extension = $extension_arr[count($extension_arr)-1];

        $this->viewClass = 'Media';
        $params = array(
            'id'        => $file,
            'name'      => $filename,
            'download'  => true,
            'extension' => $extension,
            'path'      => APP . WEBROOT_DIR . DS . $this->zip_path
        );
        ChromePhp::log( APP . WEBROOT_DIR . DS . $this->zip_path);
        $this->set($params);
    }

    public function deleteForm() {
    	$id = $this->request->params['id'];
            ChromePhp::log($id);
		$pdf = $this->Pdf->find('first', array('conditions' => array('pdf_id = ' => $id)));

		// delete file
		$file = new File(WWW_ROOT . $pdf['Pdf']['pdf_url'], false, 0777);
		if ($file->delete()) {
			$this->Pdf->delete(array('Pdf.pdf_id' => $id));
		} else {
			$this->Session->setFlash(
			    'Error deleting file. Please try again',
			    'default',
			    array('class' => 'alert alert-danger')
			);
		}

		$this->Session->setFlash(
		    'Delete is successful',
		    'default',
		    array('class' => 'alert alert-success')
		);
    	
    	return $this->redirect(array('action' => 'index'));
    }
    
    function generateForm45B($data){
        $form_id = 6;
     
        $ids = $data['secretary'];

        $types = $data['type'];
        $secs = array();
        for ($i = 0; $i < count($ids); $i++) {
                $secretary = $this->StakeHolder->find('first', array('conditions' => array('StakeHolder.id = ' => $ids[$i])));
                $secretary['type'] = $types[$i];

                array_push($secs, $secretary);
        }

        for($i = 0;$i<count($secs);$i++){
            if ($secs[$i]['type']=="appointment"){
            
            $company_id = $secs[$i]['Company']['company_id'];
            $pdf_name = 'Form_45B_'.time().$ids[$i];
            $pdf = new FPDI(); // init
        // create overlays
            $pdf->SetFont('Helvetica', '', 9); // set font type
            $pdf->SetTextColor(0, 0, 0); // set font color

    // page 1
             $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "Form45B_AAS_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); // import page 1
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template



            // write company name
            $pdf->SetXY(62, 61);
            $pdf->Write(10, $secs[$i]['Company']['name']);

            // write company number
            $pdf->SetXY(58, 66);
            $pdf->Write(10,$secs[$i]['Company']['register_number']);

            // write secretary name
            $pdf->SetXY(50, 173);
            $pdf->Write(10, $secs[$i]['StakeHolder']['name']);

            // write secretary address
            $pdf->SetXY(50, 181);
            $pdf->Write(10, $secs[$i]['StakeHolder']['address_1'].' '.$secs[$i]['StakeHolder']['address_2']);

            // write secretary nric
            $pdf->SetXY(66, 193);
            $pdf->Write(10, $secs[$i]['StakeHolder']['nric']);

            // write secretary nationality
            $pdf->SetXY(160, 193);
            $pdf->Write(10, $secs[$i]['StakeHolder']['nationality']);

            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');

            // save to database
   
            $data = array(
                    'form_id' => $form_id,
                    'company_id' => $company_id,
                    'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                    'created_at' => date('Y-m-d H:i:s')
            );

            array_push($this->form_downloads,$data['pdf_url']);
            }
        }
    }
    
    function generateResolutionASRS($data){
        $form_id = 3;
            
            $company = $this->Company->find('first',array('conditions'=>array('company_id = '=> $data['company'])));
            //ChromePhp::log($company);
            $company_id = $company['Company']['company_id'];
            $ids = $data['secretary'];
            $types = $data['type'];
            $secretaries = array();
         
            for ($i = 0; $i < count($ids); $i++) {
                    $secretary = $this->StakeHolder->find('first', array('conditions' => array('StakeHolder.id ' => $ids[$i])));
                    $secretary['type'] = $types[$i];

                    array_push($secretaries, $secretary);
            }
                //ChromePhp::log($directors);
                
            
            
          //ChromePhp::log($directors);
            $pdf_name = 'Resolution_ASRS'.time();
            
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "resolution_ASRS_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); // import page 1esolution_template
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            
         
          
            
            
            // write company name
            $pdf->SetFont('Helvetica','',13 );
            $pdf->SetXY(93,20);
            $pdf->Write(10, $company['Company']['name']);
            $pdf->SetXY(124,28);
            $pdf->Write(10, $company['Company']['register_number']);
                
            $y1 = 70;
            $y2 = 124;
            //Write Director resigned
            for($i = 0;$i<count($ids);$i++){
                if($secretaries[$i]['type']=='appointment'){
                    
                    $pdf->SetFont('Helvetica','',10);
                    $pdf->SetXY(30,$y1);
                    $pdf->Write(10,"IT WAS RESOLVED that " . $secretaries[$i]['StakeHolder']['name'].",having consented to act as secretary of.");
                    $y1+=10;
                  
                }else{
                    $pdf->SetFont('Helvetica','',10);
                    $pdf->SetXY(30,$y2);
                    $pdf->Write(10,"IT WAS RESOLVED that the resignation of " .$secretaries[$i]['StakeHolder']['name'].",as the secretary of.");
                    $pdf->SetXY(30,106);
                    $y2+=10;
                   
                }
            }
            
            //write AsIs Directors(The directors which are neither resigned or 
            //just be promoted
                $asIsStakeHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.company_id"=>$company_id
                    )
                ));
                $avail_ids=array();
                foreach($asIsStakeHolders as $asIsStakeHolder){
                    array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
                };
                
                $asIsDirectors = $this->Director->find("all",array(
                    "conditions"=>array(
                        "Director.Mode"=>"appointed",
                        "Director.id"=>$avail_ids
                    )
                ));
            $y = 210;
                 for($i = 0;$i<count($asIsDirectors);$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsDirectors[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsDirectors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsDirectors[$i+1]['StakeHolder']['name']);
                            
                        }
                   
                    $y += 30;
             }
            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');

		// save to database

		$data_pdf = array(
			'form_id' => $form_id,
			'company_id' => $company_id,
			'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
			'created_at' => date('Y-m-d H:i:s')
		);
		 
                array_push($this->form_downloads,$data_pdf['pdf_url']);
                
    }
    
    function  generateForm45B_ASA($data){
        $form_id = 6;
        //$director_id = $data['director45'];
        $id= $data['sec_id'];
        $secretary = $this->StakeHolder->find('first', array('conditions' => array('StakeHolder.id = ' => $id)));
  
    
      
            
            $company_id = $secretary ['Company']['company_id'];
            $pdf_name = 'Form_45B_'.time().$id;
            $pdf = new FPDI(); // init
        // create overlays
            $pdf->SetFont('Helvetica', '', 9); // set font type
            $pdf->SetTextColor(0, 0, 0); // set font color

    // page 1
             $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "Form45B_AAS_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); // import page 1
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template



            // write company name
            $pdf->SetXY(62, 61);
            $pdf->Write(10, $secretary['Company']['name']);

            // write company number
            $pdf->SetXY(58, 66);
            $pdf->Write(10,$secretary['Company']['register_number']);

            // write director name
            $pdf->SetXY(50, 173);
            $pdf->Write(10, $secretary['StakeHolder']['name']);

            // write director address
            $pdf->SetXY(50, 181);
            $pdf->Write(10, $secretary['StakeHolder']['address_1'].' '.$secretary['StakeHolder']['address_2']);

            // write director nric
            $pdf->SetXY(66, 193);
            $pdf->Write(10, $secretary['StakeHolder']['nric']);

            // write director nationality
            $pdf->SetXY(160, 193);
            $pdf->Write(10, $secretary['StakeHolder']['nationality']);

            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');

            // save to database
         
            $data_pdf = array(
                    'form_id' => $form_id,
                    'company_id' => $company_id,
                    'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                    'created_at' => date('Y-m-d H:i:s')
            );

            array_push($this->form_downloads,$data_pdf['pdf_url']);

   
    }
   function generateForm49ASA($data){
       $form_id =2;
		
        $prepared_by = $data['prepared_by'];
        ChromePhp::log($prepared_by);



        $id = $data['sec_id'];
        //ChromePhp::log($director_ids);

        $secretary = $this->StakeHolder->find('first', array('conditions' => array('StakeHolder.id ' => $id)));

                
		$company_id = $secretary['Company']['company_id'];
		$company_name = $secretary['Company']['name'];
		$company_number = $secretary['Company']['register_number'];
		
                $office_name =  $secretary['Company']['LOName'];
                $office_address1 = $secretary['Company']['LOAddressline1'];
		$office_address2 =  $secretary['Company']['LOAddressline2'];
		$office_telp = $secretary['Company']['LOTelNo'];
		$office_fax =  $secretary['Company']['LOTelFax'];

		$pdf_name = 'Form_49_'.time();

		// generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica', '', 11); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color

    	// page 1
    	$pdf->addPage(); // add page
    	$pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form49AS_template.pdf"); // load template
		$tplIdx = $pdf->importPage(1); // import page 1
		$pdf->useTemplate($tplIdx, 10, 10, 200); // place the template

		// write company name
		$pdf->SetXY(63, 60);
		$pdf->Write(10, $company_name);

		// write company number
		$pdf->SetXY(55, 66);
		$pdf->Write(10, $company_number);

		

		// write prepared by
		$pdf->SetXY(115, 181);
		$pdf->Write(10, $prepared_by);

                
		// write company name
             
                
		$pdf->SetXY(44, 227);
		$pdf->Write(10, $office_name);

		// write company address 1
		$pdf->SetXY(46, 231);
		$pdf->Write(10, $office_address1);

		// write company address 2
		$pdf->SetXY(46.5, 235);
		$pdf->Write(10, $office_address2);

		// write company number
		$pdf->SetXY(114, 240);
		$pdf->Write(10, $office_fax);

		// write company telp
		$pdf->SetXY(114, 244);
		$pdf->Write(10, $office_telp);

	
		$y_pos = 105;

                //write auditor name
                 $auditor_name = $data['a_name'];
                 $auditor_address = $data['a_address'];
                
               
                // write secretary name
                $name = $secretary['StakeHolder']['name'];
                $address_1 = $secretary['StakeHolder']['address_1'];
                $address_2 = $secretary['StakeHolder']['address_2'];
                $nric = $secretary['StakeHolder']['nric'];
                $nationality = $secretary['StakeHolder']['nationality'];
                $occupation = "Secretary";

                $pdf->SetXY(30, $y_pos);
                $pdf->Write(10,  $auditor_name);
                
                 $pdf->SetXY(30, $y_pos+5);
                 $pdf->Write(10, $auditor_address);
                
                $pdf->SetXY(30, $y_pos +33);
                $pdf->Write(10,  $name);
                $y_pos+=33;

                $pdf->SetXY(30, $y_pos+5);
                $pdf->Write(10, $address_1);
                $pdf->SetXY(30, $y_pos+10);
                $pdf->Write(10, $address_2);


                $pdf->SetXY(85, $y_pos);
                $pdf->Write(10, $nric);

         
                $pdf->SetXY(85, $y_pos+5);
                $pdf->Write(10,  $nationality );


                $pdf->SetXY(85, $y_pos+10);
                $pdf->Write(10, $occupation );

  
                $pdf->SetXY(115, $y_pos);
                $pdf->Write(10, 'APPOINTMENT WITH EFFECT FROM');
                $pdf->SetXY(115, $y_pos-31);
                $pdf->Write(10, 'APPOINTMENT WITH EFFECT FROM');

          

		$pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');

		// save to database

               
		$data_pdf = array(
			'form_id' => $form_id,
			'company_id' => $company_id,
			'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
			'created_at' => date('Y-m-d H:i:s')
		);
	
                array_push($this->form_downloads,$data_pdf['pdf_url']);
		
   }
   
   function generateResolutionASA($data){
       $form_id = 3;
            
            $company = $this->Company->find('first',array('conditions'=>array('company_id = '=> $data['company'])));
            //ChromePhp::log($company);
            $company_id = $company['Company']['company_id'];
            $id= $data['sec_id'];
    
         
          
            $secretary = $this->Secretary->find('first', array('conditions' => array('Secretary.id ' =>$id)));
            
          //ChromePhp::log($directors);
            $pdf_name = 'Resolution_ASA'.time();
            
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "ResolutionASA_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); // import page 1esolution_template
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            
         
          
            
            
            // write company name
            $pdf->SetFont('Helvetica','',13 );
            $pdf->SetXY(93,30);
            $pdf->Write(10, $company['Company']['name']);
            $pdf->SetXY(120,37);
            $pdf->Write(10, $company['Company']['register_number']);
                
            $y1 = 70;

                    
                    $pdf->SetFont('Helvetica','',10);
                    $pdf->SetXY(30,$y1);
                    $pdf->Write(10,"IT WAS RESOLVED That "  . $data['a_name'].",");
                     $pdf->SetXY(30,$y1+5);
                     $pdf->Write(10,"having consented to act as Auditors of the Company, be and hereby appointed as Auditors with immediate ");
                     $pdf->SetXY(30,$y1+10);
                     $pdf->Write(10,"effect.");
            $y1 = 104;
                   $pdf->SetXY(30,$y1);
                    $pdf->Write(10,"IT WAS RESOLVED That "  . $secretary['StakeHolder']['name'].",");
                     $pdf->SetXY(30,$y1+5);
                     $pdf->Write(10,"having consented to act as Secretary of the Company, be and hereby appointed as Secretary with  ");
                     $pdf->SetXY(30,$y1+10);
                     $pdf->Write(10,"immediate effect.");
         
                   
                
            
            
            //write AsIs Directors(The directors which are neither resigned or 
            //just be promoted
             $asIsStakeHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.company_id"=>$company_id
                    )
                ));
                $avail_ids=array();
                foreach($asIsStakeHolders as $asIsStakeHolder){
                    array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
                };
                
                $asIsDirectors = $this->Director->find("all",array(
                    "conditions"=>array(
                        "Director.Mode"=>"appointed",
                        "Director.id"=>$avail_ids
                    )
                ));
            $y = 200;
                 for($i = 0;$i<count($asIsDirectors);$i=$i+2){
                    $pdf->SetFont('Helvetica','',12);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsDirectors[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsDirectors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsDirectors[$i+1]['StakeHolder']['name']);
                            
                        }
                   
                    $y += 30;
             }
            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');

		// save to database

		$data_pdf = array(
			'form_id' => $form_id,
			'company_id' => $company_id,
			'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
			'created_at' => date('Y-m-d H:i:s')
		);

                array_push($this->form_downloads,$data_pdf['pdf_url']);
   }
   
   function generateForm11($data){
//       
       $pdf_name = 'Form11'.time();
            
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form11_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            
            $company = $this->Company->find("first",array(
                "conditions"=>array(
                    "Company.company_id"=>$data['company']
                )
            ));
            // write company name
            $pdf->SetFont('Helvetica','',9 );
            $pdf->SetXY(60,74);
            $pdf->Write(10, $company ['Company']['name']);
            $pdf->SetXY(52,78);
            $pdf->Write(10, $company['Company']['register_number']);
            

            $pdf->SetXY(105,100);
            $pdf->Write(10, $data['meeting_address1']." ".$data['meeting_address2']);
             $pdf->SetXY(109,126);
            $pdf->Write(10, $data['new_company']);  
            
            $pdf->SetXY(65,135);
            $pdf->Write(10, $data['new_company']); 
            
            $pdf->SetXY(76,143);
            $pdf->Write(10, $company ['Company']['name']); 
            
            $pdf->SetXY(50,169);
            $pdf->Write(10, $data['directors']); 
            
             $pdf->SetXY(98,203);
            $pdf->Write(10, $data['nameDS']); 
            
            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');

		// save to database
	
		$data_pdf = array(
			'form_id' => 7,
			'company_id' => $data['company'],
			'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
			'created_at' => date('Y-m-d H:i:s')
		);
		
                array_push($this->form_downloads,$data_pdf['pdf_url']);
   }
   function generateEOGM($data){
       $shareholders = explode(",",$data['shareholders']);
        $directors  = explode(",",$data['directors']);
       $pdf_name = 'EOGM_ChangeCompanyName'.time();
             
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            
            $company = $this->Company->find("first",array(
                "conditions"=>array(
                    "Company.company_id"=>$data['company']
                )
            ));
            // write company name
            $pdf->SetFont('Helvetica','',11);
            $pdf->SetXY(100,20);
            $pdf->Write(10, $company ['Company']['name']);
            $pdf->SetXY(100,25);
            $pdf->Write(10, $company['Company']['register_number']);
            
            $pdf->SetXY(90,38);
            $pdf->Write(10, $company['Company']['address_1']);
            $pdf->SetXY(100,43);
            $pdf->Write(10, $company['Company']['address_2']);
            

            $pdf->SetXY(20,90);
            $pdf->Write(10, $data['meeting_address1']);
             $pdf->SetXY(20,95);
            $pdf->Write(10,$data['meeting_address2']);
             $pdf->SetXY(20,100);
            $pdf->Write(10, $data['new_company']);  
            
            $y = 150;
                 for($i = 0;$i<count($shareholders);$i=$i+2){
                    $pdf->SetFont('Helvetica','',15);
                        $x = 20;
                        $pdf->SetXY($x,$y);
                        $pdf->Write(10,$shareholders[$i]);
                        if($i+1<count($shareholders)){
                            $k = 170;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                            $pdf->Write(10,$shareholders[$i+1]);
                            
                        }
                   
                    $y += 30;
               }
            // page 2
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_template.pdf"); // load template
            $tplIdx = $pdf->importPage(2); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            
            $pdf->SetFont('Helvetica','',11);
            $pdf->SetXY(100,22);
            $pdf->Write(10, $company ['Company']['name']);
            $pdf->SetXY(118,27);
            $pdf->Write(10, $company['Company']['register_number']);
            
            $pdf->SetXY(100,35);
            $pdf->Write(10, $company['Company']['address_1']);
            $pdf->SetXY(100,40);
            $pdf->Write(10, $company['Company']['address_2']);
            
             $pdf->SetXY(63,65);
            $pdf->Write(10, $data['meeting_address1']);
             $pdf->SetXY(63,70);
            $pdf->Write(10,$data['meeting_address2']);

             $pdf->SetXY(40,100);
            $pdf->Write(10, $data['chairman']." was appointed Chairman of the meeting");
          
            $pdf->SetXY(96,158);
            $pdf->Write(10, $data['new_company']); 
            $pdf->SetXY(66,169);
            $pdf->Write(10, $data['new_company']); 
            
            $pdf->SetXY(68,179);
            $pdf->Write(10, $company ['Company']['name']); 
            
             $pdf->SetXY(101,240);
            $pdf->Write(10, $data['chairman']);
            
               
            // page 3
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_template.pdf"); // load template
            $tplIdx = $pdf->importPage(3); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            
       
            // write company name
            $pdf->SetFont('Helvetica','',11);
           $pdf->SetXY(100,22);
            $pdf->Write(10, $company ['Company']['name']);
            $pdf->SetXY(112,27);
            $pdf->Write(10, $company['Company']['register_number']);
            
            $pdf->SetXY(100,35);
            $pdf->Write(10, $company['Company']['address_1']);
            $pdf->SetXY(100,40);
            $pdf->Write(10, $company['Company']['address_2']);
            
            $y = 100;
                 for($i = 0;$i<count($shareholders);$i=$i+1){
                    $pdf->SetFont('Helvetica','',15);
                        $x = 90;
                        $pdf->SetXY($x,$y);
                        $pdf->Write(10,$shareholders[$i]);

                    $y += 20;
               }
               // page 4
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_template.pdf"); // load template
            $tplIdx = $pdf->importPage(4); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            // write company name
            $pdf->SetFont('Helvetica','',11);
           $pdf->SetXY(90,10);
            $pdf->Write(10, $company ['Company']['name']);
            $pdf->SetXY(112,16);
            $pdf->Write(10, $company['Company']['register_number']);
            
            $pdf->SetXY(104,25);
            $pdf->Write(10, $company['Company']['address_1']);
            $pdf->SetXY(104,30);
            $pdf->Write(10, $company['Company']['address_2']);
            
             $pdf->SetXY(36,78);
            $pdf->Write(10, $data['meeting_address1']." ".$data['meeting_address2']);
            
            
            $pdf->SetXY(97,118);
            $pdf->Write(10, $data['new_company']); 
            $pdf->SetXY(65,123);
            $pdf->Write(10, $data['new_company']); 
            
            $pdf->SetXY(67,128);
            $pdf->Write(10, $company ['Company']['name']); 
            
            $y = 200;
                 for($i = 0;$i<count($directors);$i=$i+2){
                    $pdf->SetFont('Helvetica','',15);
                        $x = 20;
                        $pdf->SetXY($x,$y);
                        $pdf->Write(10,$directors[$i]);
                        if($i+1<count($directors)){
                            $k = 170;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                            $pdf->Write(10,$directors[$i+1]);
                            
                        }
                   
                    $y += 30;
               }
            
               // page 4
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_template.pdf"); // load template
            $tplIdx = $pdf->importPage(5); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            $pdf->SetFont('Helvetica','',11);
           $pdf->SetXY(90,15);
            $pdf->Write(10, $company ['Company']['name']);
            $pdf->SetXY(114,19);
            $pdf->Write(10, $company['Company']['register_number']);
            
            $pdf->SetXY(108,28);
            $pdf->Write(10, $company['Company']['address_1']);
            $pdf->SetXY(108,33);
            $pdf->Write(10, $company['Company']['address_2']);
            
            $pdf->SetXY(43,37);
            $pdf->Write(10, $data['directors']);
            $pdf->SetXY(68.1,65.9);
            $pdf->Write(10, $data['meeting_address1']);
             $pdf->SetXY(68.1,70);
            $pdf->Write(10,$data['meeting_address2']);
            $pdf->SetXY(152.9,136.9);
            $pdf->Write(10, $data['new_company']); 
            $pdf->SetXY(94.7,142.5);
            $pdf->Write(10, $data['new_company']); 
            
            $pdf->SetXY(91.9,147.6);
            $pdf->Write(10, $company ['Company']['name']); 
            
            $pdf->SetXY(36.1,184.7);
            $pdf->Write(10, $data['chairman']); 


            
            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');

		// save to database

		$data_pdf = array(
			'form_id' => 8,
			'company_id' => $data['company'],
			'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
			'created_at' => date('Y-m-d H:i:s')
		);
		
                array_push($this->form_downloads,$data_pdf['pdf_url']);
   }
   public function generateResolutionFY($data){
       $pdf_name = 'Resolution_newFY'.time();
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "ResolutionFY_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            
            $company = $this->Company->find("first",array(
                "conditions"=>array(
                    "Company.company_id"=>$data['company']
                )
            ));
            // write company name
            $pdf->SetFont('Helvetica','',12);
            $pdf->SetXY(97,20.2);
            $pdf->Write(10, $company ['Company']['name']);
            $pdf->SetXY(125,27.4);
            $pdf->Write(10, $company['Company']['register_number']);
            $pdf->SetXY(28.4,96.5);
            $pdf->Write(10, $data['pre_FY']." to ".$data['new_FY']);

          $asIsStakeHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.company_id"=>$data['company']
                    )
                ));
                $avail_ids=array();
                foreach($asIsStakeHolders as $asIsStakeHolder){
                    array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
                };
                
                $asIsDirectors = $this->Director->find("all",array(
                    "conditions"=>array(
                        "Director.Mode"=>"appointed",
                        "Director.id"=>$avail_ids
                    )
                ));
            $y = 160;
                 for($i = 0;$i<count($asIsDirectors);$i=$i+2){
                    $pdf->SetFont('Helvetica','',15);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsDirectors[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsDirectors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsDirectors[$i+1]['StakeHolder']['name']);
                            
                        }
                   
                    $y += 30;
             }
            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
		// save to database
		
                $this->Document->create();
		$data = array(
			'form_id' => 3,
			'company_id' => $data['company'],
			'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
			'created_at' => date('Y-m-d H:i:s')
		);
		
                array_push($this->form_downloads,$data['pdf_url']);
   }
   
   public function generateform11_MAA($data){
       $pdf_name = 'Form11'.time();
            
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "Form11_M&AA_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            
            $company = $this->Company->find("first",array(
                "conditions"=>array(
                    "Company.company_id"=>$data['company']
                )
            ));
            // write company name
            $pdf->SetFont('Helvetica','',10 );
            $pdf->SetXY(60.1,74.4);
            $pdf->Write(10, $company ['Company']['name']);
            $pdf->SetXY(50.7,78.5);
            $pdf->Write(10, $company['Company']['register_number']);
            

            $pdf->SetXY(72.6,110.3);
            $pdf->Write(10, $data['meeting_address1']." ".$data['meeting_address2']);
              $pdf->SetXY(42.9,158);
            $pdf->Write(10, $data['directors']); 
            
            $pdf->SetXY(140.7,198.2);
            $pdf->Write(10, $data['nameDS']); 
            
           
            
            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');

		// save to database
     
        $data_pdf = array(
                'form_id' => 7,
                'company_id' => $data['company'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
       
        array_push($this->form_downloads,$data_pdf['pdf_url']);
   }
   public function generateEOGM_MAA($data){
       $shareholders = explode(",",$data['shareholders']);
        $directors  = explode(",",$data['directors']);
       $pdf_name = 'EOGM_ChangeCompanyName'.time();
             
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_MAA_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            
            $company = $this->Company->find("first",array(
                "conditions"=>array(
                    "Company.company_id"=>$data['company']
                )
            ));
            // write company name
            $pdf->SetFont('Helvetica','',11);
            $pdf->SetXY(87.9,27.9);
            $pdf->Write(10, $company ['Company']['name']);
            $pdf->SetXY(111.7,32.7);
            $pdf->Write(10, $company['Company']['register_number']);
            
            $pdf->SetXY(105.2,41.4);
            $pdf->Write(10, $company['Company']['address_1']);
            $pdf->SetXY(105.2,45);
            $pdf->Write(10, $company['Company']['address_2']);
            

//            $pdf->SetXY(20,90);
//            $pdf->Write(10, $data['meeting_address1']);
//             $pdf->SetXY(20,95);
//            $pdf->Write(10,$data['meeting_address2']);

            
            $y = 135;
                 for($i = 0;$i<count($shareholders);$i=$i+2){
                    $pdf->SetFont('Helvetica','',11);
                        $x = 20;
                        $pdf->SetXY($x,$y);
                        $pdf->Write(10,$shareholders[$i]);
                        if($i+1<count($shareholders)){
                            $k = 170;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                            $pdf->Write(10,$shareholders[$i+1]);
                            
                        }
                   
                    $y += 25;
               }
            
               // page 2
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_MAA_template.pdf"); // load template
            $tplIdx = $pdf->importPage(2); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            

            // write company name
            $pdf->SetFont('Helvetica','',11);
            $pdf->SetXY(97.2,30.7);
            $pdf->Write(10, $company ['Company']['name']);
            $pdf->SetXY(122.4,34.5);
            $pdf->Write(10, $company['Company']['register_number']);
            
            $pdf->SetXY(109,43.2);
            $pdf->Write(10, $company['Company']['address_1']);
            $pdf->SetXY(109.2,47);
            $pdf->Write(10, $company['Company']['address_2']);
            

            $pdf->SetXY(59.9,69.8);
            $pdf->Write(10, $data['meeting_address1']);
             $pdf->SetXY(59.9,74);
            $pdf->Write(10,$data['meeting_address2']);
            
            $pdf->SetXY(31.2,112);
            $pdf->Write(10,$data['chairman']." was appointed chairman of the meeting");
            
            // page 3
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_MAA_template.pdf"); // load template
            $tplIdx = $pdf->importPage(3); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            

            // write company name
            $pdf->SetFont('Helvetica','',11);
            $pdf->SetXY(88.1,51.8);
            $pdf->Write(10, $company ['Company']['name']);
            $pdf->SetXY(113.2,54.8);
            $pdf->Write(10, $company['Company']['register_number']);
            
            $pdf->SetXY(105.2,63.8);
            $pdf->Write(10, $company['Company']['address_1']);
            $pdf->SetXY(105.2,67.8);
            $pdf->Write(10, $company['Company']['address_2']);
            

            $pdf->SetXY(58.9,88.3);
            $pdf->Write(10, $data['meeting_address1']);
             $pdf->SetXY(58.9,92.6);
            $pdf->Write(10,$data['meeting_address2']);
            
            $pdf->SetXY(100.6,250.8);
            $pdf->Write(10,$data['chairman']);
            
             // page 4
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_MAA_template.pdf"); // load template
            $tplIdx = $pdf->importPage(4); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            

            // write company name
            $pdf->SetFont('Helvetica','',11);
            $pdf->SetXY(89.7,29.1);
            $pdf->Write(10, $company ['Company']['name']);
            $pdf->SetXY(113,32.2);
            $pdf->Write(10, $company['Company']['register_number']);
            
            $pdf->SetXY(104.9,41.4);
            $pdf->Write(10, $company['Company']['address_1']);
            $pdf->SetXY(104.9,45.4);
            $pdf->Write(10, $company['Company']['address_2']);
            

             $y = 90;
                 for($i = 0;$i<count($shareholders);$i=$i+1){
                    $pdf->SetFont('Helvetica','',15);
                        $x = 90;
                        $pdf->SetXY($x,$y);
                        $pdf->Write(10,$shareholders[$i]);

                    $y += 25;
               }
            // page 5
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_MAA_template.pdf"); // load template
            $tplIdx = $pdf->importPage(5); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            

            // write company name
            $pdf->SetFont('Helvetica','',11);
            $pdf->SetXY(91.2,37.2);
            $pdf->Write(10, $company ['Company']['name']);
            $pdf->SetXY(113.5,40.6);
            $pdf->Write(10, $company['Company']['register_number']);
            
            $pdf->SetXY(106.9,48.8);
            $pdf->Write(10, $company['Company']['address_1']);
            $pdf->SetXY(106.9,52.4);
            $pdf->Write(10, $company['Company']['address_2']);
            
            $pdf->SetXY(29,96.8);
            $pdf->Write(10, $data['meeting_address1']." ".$data['meeting_address2']);
            
            // page 6
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_MAA_template.pdf"); // load template
            $tplIdx = $pdf->importPage(6); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            

            // write company name
            $pdf->SetFont('Helvetica','',11);
            $pdf->SetXY(89.9,32.5);
            $pdf->Write(10, $company ['Company']['name']);
            $pdf->SetXY(112.3,36.3);
            $pdf->Write(10, $company['Company']['register_number']);
            
            $pdf->SetXY(104.4,44.9);
            $pdf->Write(10, $company['Company']['address_1']);
            $pdf->SetXY(104.4,49.9);
            $pdf->Write(10, $company['Company']['address_2']);
            
             $y = 195;
                 for($i = 0;$i<count($directors);$i=$i+2){
                    $pdf->SetFont('Helvetica','',11);
                        $x = 50;
                        $pdf->SetXY($x,$y);
                        $pdf->Write(10,$directors[$i]);
                        if($i+1<count($directors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                            $pdf->Write(10,$directors[$i+1]);
                            
                        }
                   
                    $y += 30;
               }
               
               // page 7
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_MAA_template.pdf"); // load template
            $tplIdx = $pdf->importPage(7); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            

            // write company name
            $pdf->SetFont('Helvetica','',11);
            $pdf->SetXY(89.9,29.2);
            $pdf->Write(10, $company ['Company']['name']);
            $pdf->SetXY(112.3,32.3);
            $pdf->Write(10, $company['Company']['register_number']);
            
            $pdf->SetXY(105.6,40.9);
            $pdf->Write(10, $company['Company']['address_1']);
            $pdf->SetXY(105.6,45.9);
            $pdf->Write(10, $company['Company']['address_2']);
            $mrShareHolders = "";
            for($i = 0;$i<count($directors);$i=$i+1){
                $mrShareHolders .= $directors[$i].",";
            };
            
            $pdf->SetXY(34.8,53.1);
            $pdf->Write(10, $mrShareHolders );
            $pdf->SetXY(57.9,83.3);
            $pdf->Write(10,$data['meeting_address1']." ".$data['meeting_address2']);
            
             // page 8
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_MAA_template.pdf"); // load template
            $tplIdx = $pdf->importPage(8); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            

            // write company name
            $pdf->SetFont('Helvetica','',11);
            $pdf->SetXY(89.7,33.3);
            $pdf->Write(10, $company ['Company']['name']);
            $pdf->SetXY(113.5,36.6);
            $pdf->Write(10, $company['Company']['register_number']);
            
            $pdf->SetXY(104.1,44.7);
            $pdf->Write(10, $company['Company']['address_1']);
            $pdf->SetXY(104.1,48.9);
            $pdf->Write(10, $company['Company']['address_2']);
           
            
            $pdf->SetXY(29.5,160.7);
            $pdf->Write(10, $data['chairman']);
            
             // page 9
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_MAA_template.pdf"); // load template
            $tplIdx = $pdf->importPage(9); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            

            // write company name
            $pdf->SetFont('Helvetica','',11);
            $pdf->SetXY(92.7,23.9);
            $pdf->Write(10, $company ['Company']['name']);
            $pdf->SetXY(116,27.1);
            $pdf->Write(10, $company['Company']['register_number']);

             $pdf->SetXY(29,180.9);
            $pdf->Write(10, $data['chairman']);
            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');

		// save to database
        
        $data_pdf = array(
                'form_id' => 8,
                'company_id' => $data['company'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
    
        array_push($this->form_downloads, $data_pdf['pdf_url']);
   }
   function generateForm24($shareholders,$directors,$shares_info,$LO_info,$company_info){
       $pdf_name = 'Form24'.time();            
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form24_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',10 );
            $pdf->SetXY(58.1,58.9);
            $pdf->Write(10, $company_info['name']);
            $pdf->SetXY(50.2,65.5);
            $pdf->Write(10, $company_info['number']);
            $pdf->SetXY(126.5,111.3);
            $pdf->Write(10,$shares_info['NumberOfShares']);
            $pdf->SetXY(128.3,117.9);
            $pdf->Write(10,$shares_info['NominalAmountOfEachShare']);            
            $pdf->SetXY(126.0,124.2);
            $pdf->Write(10,$shares_info['AmountPaid']); 
             $pdf->SetXY(126.5,131.8);
            $pdf->Write(10,$shares_info['Due&Payable']); 
             $pdf->SetXY(125.2,137.2);
            $pdf->Write(10,$shares_info['AmountPremiumPaid']); 
             $pdf->SetXY(41.8,220.4);
            $pdf->Write(10,$LO_info['LOName']); 
             $pdf->SetXY(43.8,223.9);
            $pdf->Write(10,$LO_info['LOAddressline1']); 
            $pdf->SetXY(43.8,227.9);
            $pdf->Write(10,$LO_info['LOAddressline2']);
             $pdf->SetXY(42.7,237.5);
            $pdf->Write(10,$LO_info['LOAcNo']); 
             $pdf->SetXY(80.7,234.3);
            $pdf->Write(10,$LO_info['LOTelNo']); 
             $pdf->SetXY(80.7,238.2);
            $pdf->Write(10,$LO_info['LOTelFax']); 
            
             // page 2
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form24_template.pdf"); // load template
            $tplIdx = $pdf->importPage(2); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',10 );
            $pdf->SetXY(57.8,32.9);
            $pdf->Write(10, $company_info['name']);
            $pdf->SetXY(50.9,40.0);
            $pdf->Write(10, $company_info['number']);
            $y=75.2;
            $x=36.8;
            for($i = 0;$i<count($shareholders);$i++){
                $pdf->SetXY($x,$y);
                $pdf->Write(10,empty($shareholders[$i]['addressline1_Singapore'])?$shareholders[$i]['addressline1_OverSea']:$shareholders[$i]['addressline1_Singapore']);
                $pdf->SetXY($x,$y+4);
                $pdf->Write(10,empty($shareholders[$i]['addressline2_Singapore'])?$shareholders[$i]['addressline2_OverSea']:$shareholders[$i]['addressline2_Singapore']);
                $pdf->SetXY($x,$y+8);
                $pdf->Write(10,empty($shareholders[$i]['addressline3_Singapore'])?$shareholders[$i]['addressline3_OverSea']:$shareholders[$i]['addressline3_Singapore']);
                $pdf->SetXY($x,$y+12);
                $pdf->Write(10,$shareholders[$i]['DateofBirth']);
                $pdf->SetXY($x,$y+16);
                $pdf->Write(10,$shareholders[$i]['NRIC/Passport']);
                $pdf->SetXY($x,$y+20);
                $pdf->Write(10,$shareholders[$i]['NationalityCurrent']);
                $pdf->SetXY($x+95.4,$y);
                $pdf->Write(10,$shareholders[$i]['NumberofShares']);
                $pdf->SetXY($x+86.4,$y+4);
                $pdf->Write(10,$shareholders[$i]['ClassofShares']);
                $y += 28;
            }
            

             // page 3
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form24_template.pdf"); // load template
            $tplIdx = $pdf->importPage(3); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',10 );
            $pdf->SetXY(58.5,28.7);
            $pdf->Write(10, $company_info['name']);
            $pdf->SetXY(57.5,36.0);
            $pdf->Write(10, $company_info['number']);
            

            $pdf->SetXY(102.6,67.3);
            $pdf->Write(10, $shares_info['AuthorizedShareCapital']);
            $pdf->SetXY(101.3,75.2);
            $pdf->Write(10, $shares_info['IssuedShareCapital']); 
            
            $pdf->SetXY(102.1,83.3);
            $pdf->Write(10, $shares_info['PaidupShareCapital']); 
            $pdf->SetXY(116.7,209.9);
            $pdf->Write(10, $directors[0]['name']);
             $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
             // save to database
        //$this->Pdf->create();
        $data_pdf = array(
                'form_id' => 10,
                'company_id' => $company_info['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
        //$this->Pdf->save($data_pdf);
        array_push($this->form_downloads,$data_pdf['pdf_url']);
   }
   function generateForm44($directors,$company_info,$LO_info){
            $pdf_name = 'Form44'.time();
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
             // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form44_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',10 );
            $pdf->SetXY(60.7,69.9);
            $pdf->Write(10, $company_info['name']);
            $pdf->SetXY(54.3,84.1);
            $pdf->Write(10, $company_info['number']);
            

            $pdf->SetXY(36.6,150.4);
            $pdf->Write(10, $company_info['address1']." ".$company_info['address2']);
            
            $pdf->SetXY(138.7,218.3);
            $pdf->Write(10, $directors[0]['name']); 
             $pdf->SetXY(45.1,249.4);
            $pdf->Write(10,$LO_info['LOName']); 
             $pdf->SetXY(45.8,253.9);
            $pdf->Write(10,$LO_info['LOAddressline1']); 
            $pdf->SetXY(45.8,257.5);
            $pdf->Write(10,$LO_info['LOAddressline2']);
             $pdf->SetXY(45.8,263.8);
            $pdf->Write(10,$LO_info['LOAcNo']); 
             $pdf->SetXY(94.9,263.8);
            $pdf->Write(10,$LO_info['LOTelNo']); 
             $pdf->SetXY(95.7,266.9);
            $pdf->Write(10,$LO_info['LOTelFax']); 
  

            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
           // save to database
     
        $data_pdf = array(
                'form_id' => 11,
                'company_id' => $company_info['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
    
        array_push($this->form_downloads,$data_pdf['pdf_url']);
   }
   function  generateForm45Inc($directors,$company_info){
       
       for($i = 0;$i < count($directors);$i++){
          $pdf_name = 'Form45'.$i.time();
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
        // page 1
            $pdf->addPage(); // add page
           $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form45_template.pdf"); // load template
           $tplIdx = $pdf->importPage(1); // import page 1
           $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template
           // write company name
           $pdf->SetXY(70, 76);
           $pdf->Write(10, $company_info['name']);

           // write company number
           $pdf->SetXY(62, 83.5);
           $pdf->Write(10, $company_info['number']);

           // page 2
           $pdf->addPage(); // add page
           $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form45_template.pdf"); // load template
           $tplIdx = $pdf->importPage(2); // import page 2
           $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template

           // write company name
           $pdf->SetXY(73, 45);
           $pdf->Write(10, $company_info['name']);

           // write company number
           $pdf->SetXY(64, 51);
           $pdf->Write(10, $company_info['number']);

           // page 3
           $pdf->addPage(); // add page
           $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form45_template.pdf"); // load template
           $tplIdx = $pdf->importPage(3); // import page 3
           $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template

           // write company name
           $pdf->SetXY(73, 45);
           $pdf->Write(10, $company_info['name']);

           // write company number
           $pdf->SetXY(64, 51);
           $pdf->Write(10, $company_info['number']);


           // write director name
           $pdf->SetXY(55, 127);
           $pdf->Write(10, $directors[$i]['name']);

           // write director address
           $pdf->SetXY(57, 133);
           $pdf->Write(10,$directors[$i]['addressline1_Singapore'].' '.$directors[$i]['addressline2_Singapore']);

           // write director nric
           $pdf->SetXY(72, 139);
           $pdf->Write(10, $directors[$i]['NRIC/Passport']);

           // write director nationality
           $pdf->SetXY(125, 139);
           $pdf->Write(10,$directors[$i]['NationalityCurrent']);

           $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
           // save to database
       
        $data_pdf = array(
                'form_id' => 1,
                'company_id' => $company_info['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
      
        array_push($this->form_downloads,$data_pdf['pdf_url']);
       }
   }
   function generateFormParticular($directors,$company_info){
       for($i = 0;$i < count($directors);$i++){
          $pdf_name = 'Directors_Particulars'.$i.time();
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
        // page 1
            $pdf->addPage(); // add page
           $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "Particular_template.pdf"); // load template
           $tplIdx = $pdf->importPage(1); // import page 1
           $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template
           // write company name
           $pdf->SetXY(58.4,23.8);
           $pdf->Write(10, $company_info['name']);


           $pdf->SetXY(40.8, 43.9);
           $pdf->Write(10, $directors[$i]['name']);
           $pdf->SetXY(66.5,52.0);
           $pdf->Write(10, $directors[$i]['Placeofbirth']);
           $pdf->SetXY(155.9,51.9);
           $pdf->Write(10, $directors[$i]['DateofBirth']);

           $pdf->SetXY(61.5,63.9);
           $pdf->Write(10, $directors[$i]['NationalityCurrent']);
            $pdf->SetXY(132.1,63.9);
           $pdf->Write(10, $directors[$i]['NationalityCurrent']);
            $pdf->SetXY(132.4,71.5);
           $pdf->Write(10, $directors[$i]['NationalityatBirth']);

           $pdf->SetXY(67.3,77.8);
           $pdf->Write(10,$directors[$i]['passportno']);
           $pdf->SetXY(133.3,77.7);
           $pdf->Write(10, $directors[$i]['passportdateofissue']);
           
           $pdf->SetXY(137.2,85.9);
           $pdf->Write(10, $directors[$i]['passportplaceofissue']);


           $pdf->SetXY(60.9,93.2);
           $pdf->Write(10, $directors[$i]['NRIC/Passport']);
           $pdf->SetXY(133.1,93.7);
           $pdf->Write(10, $directors[$i]['Nricdateofissue']);
           $pdf->SetXY(137.7,100.8);
           $pdf->Write(10, $directors[$i]['nricplaceofissue']);

           // write director address
           $pdf->SetXY(89.1,114.6);
           $pdf->Write(10,$directors[$i]['addressline1_Singapore']);
           $pdf->SetXY(89.1,117.6);       
           $pdf->Write(10,$directors[$i]['addressline2_Singapore']);
           $pdf->SetXY(89.1,120.6);       
           $pdf->Write(10,$directors[$i]['addressline3_Singapore']);
           
           $pdf->SetXY(89.1,129.0);
           $pdf->Write(10,$directors[$i]['addressline1_OverSea']);
           $pdf->SetXY(89.1,132.5);       
           $pdf->Write(10,$directors[$i]['addressline2_OverSea']);
           $pdf->SetXY(89.1,136);       
           $pdf->Write(10,$directors[$i]['addressline3_OverSea']);
           
            $pdf->SetXY(89.1,143.5);
           $pdf->Write(10,$directors[$i]['addressline1_Other']);
           $pdf->SetXY(89.1,146.5);       
           $pdf->Write(10,$directors[$i]['addressline2_Other']);
           $pdf->SetXY(89.1,149.6);       
           $pdf->Write(10,$directors[$i]['addressline3_Other']);

         
           $pdf->SetXY(114.2,220.6);
           $pdf->Write(10, $directors[$i]['name']);

           

           $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
            // save to database
       
        $data_pdf = array(
                'form_id' => 12,
                'company_id' => $company_info['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
      
        array_push($this->form_downloads,$data_pdf['pdf_url']);
       }
   }
   function generateForm45BInc($secretary,$company_info,$LO_info){
       $form_id = 6;
       $pdf_name = 'Form_45B_'.time();
        $pdf = new FPDI(); // init
    // create overlays
        $pdf->SetFont('Helvetica', '', 9); // set font type
        $pdf->SetTextColor(0, 0, 0); // set font color

    // page 1
         $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form45B_Inc_template.pdf"); // load template
        $tplIdx = $pdf->importPage(1); // import page 1
        $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template



        // write company name
        $pdf->SetXY(54.4,44.1);
        $pdf->Write(10, $company_info['name']);

        // write company number
        $pdf->SetXY(48.3,47.9);
        $pdf->Write(10,$company_info['number']);

        // write secretary name
        $pdf->SetXY(41.1, 137.9);
        $pdf->Write(10, $secretary['name']);

        // write secretary address
        $pdf->SetXY(43.9, 145.9);
        $pdf->Write(10, $secretary['addressLine1'].' '.$secretary['addressLine2'].' '.$secretary['addressLine3']);

        // write secretary nric
        $pdf->SetXY(141.6, 154.7);
        $pdf->Write(10, $secretary['Nationality']);

        // write secretary nationality
        $pdf->SetXY(60.2, 154.7);
        $pdf->Write(10,$secretary['NRIC/Passport']);
        
        $pdf->SetXY(38.1,247.4);
            $pdf->Write(10,$LO_info['LOName']); 
             $pdf->SetXY(40.8,251.9);
            $pdf->Write(10,$LO_info['LOAddressline1']); 
            $pdf->SetXY(40.8,255.9);
            $pdf->Write(10,$LO_info['LOAddressline2']);
             $pdf->SetXY(38.5,266.8);
            $pdf->Write(10,$LO_info['LOAcNo']); 
             $pdf->SetXY(70.7,263.8);
            $pdf->Write(10,$LO_info['LOTelNo']); 
             $pdf->SetXY(70.7,266.8);
            $pdf->Write(10,$LO_info['LOTelFax']); 

        $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
         // save to database
    
        $data_pdf = array(
                'form_id' => 6,
                'company_id' => $company_info['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
  
        array_push($this->form_downloads,$data_pdf['pdf_url']);
   }
   function generateForm49Inc($secretary,$directors,$company_info,$LO_info){
       // generate PDF
       $pdf_name = 'Form_49_Inc_'.time();
        $pdf = new FPDI(); // init
        // create overlays
        $pdf->SetFont('Helvetica', '', 9); // set font type
        $pdf->SetTextColor(0, 0, 0); // set font color

    	// page 1
    	$pdf->addPage(); // add page
    	$pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form49_Inc_template.pdf"); // load template
        $tplIdx = $pdf->importPage(1); // import page 1
        $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template

        // write company name
        $pdf->SetXY(56.6,50.2);
        $pdf->Write(10, $company_info['name']);

        // write company number
        $pdf->SetXY(49.9,56.8);
        $pdf->Write(10,$company_info['number']);
        $y = 105.8;
        for($i=0;$i<count($directors);$i++){
            $pdf->SetXY(34.1,$y);
            $pdf->Write(10, $directors[$i]['name']);
            $pdf->SetXY(124.1,$y);
            $pdf->Write(10, $directors[$i]['NRIC/Passport']);
            $pdf->SetXY(34.1, $y+4);
            $pdf->Write(10, $directors[$i]['addressline1_Singapore']);
            $pdf->SetXY(124.1, $y+4);
            $pdf->Write(10, $directors[$i]['NationalityCurrent']);
            $pdf->SetXY(34.1, $y+8);
            $pdf->Write(10, $directors[$i]['addressline2_Singapore']);
            $pdf->SetXY(124.1, $y+8);
            $pdf->Write(10, $directors[$i]['Occupation']);
            $pdf->SetXY(34.1, $y+12);
            $pdf->Write(10, $directors[$i]['addressline3_Singapore']);
            $y += 18;
        }
        $pdf->SetXY(34.1, 166.1);
        $pdf->Write(10, $secretary['name']);
        $pdf->SetXY(124.1,166.1);
        $pdf->Write(10, $secretary['NRIC/Passport']);
        $pdf->SetXY(34.1, 169.1);
        $pdf->Write(10, $secretary['addressLine1']);
        $pdf->SetXY(124.1,169.1);
        $pdf->Write(10, $secretary['Nationality']);
        $pdf->SetXY(34.1, 172.1);
        $pdf->Write(10,$secretary['addressLine2']);
        $pdf->SetXY(124.1,172.1);
        $pdf->Write(10, $secretary['Occupation']);
        $pdf->SetXY(34.1, 175.1);
        $pdf->Write(10,$secretary['addressLine3']);
        
        
        $pdf->SetXY(135.9,195.5);
        $pdf->Write(10,$directors[0]['name']);
        
        $pdf->SetXY(40.4,248.7);
        $pdf->Write(10,$LO_info['LOName']); 
         $pdf->SetXY(44.4,252.9);
        $pdf->Write(10,$LO_info['LOAddressline1']); 
        $pdf->SetXY(44.4,255.9);
        $pdf->Write(10,$LO_info['LOAddressline2']);
         $pdf->SetXY(42.5,259.8);
        $pdf->Write(10,$LO_info['LOAcNo']); 
         $pdf->SetXY(74.7,259.8);
        $pdf->Write(10,$LO_info['LOTelNo']); 
         $pdf->SetXY(74.7,262.8);
        $pdf->Write(10,$LO_info['LOTelFax']);
         $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
          // save to database
 
        $data_pdf = array(
                'form_id' => 2,
                'company_id' => $company_info['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
    
        array_push($this->form_downloads,$data_pdf['pdf_url']);
       
   }
   function generateBusinessProfile($directors,$shareholders,$company_info,$shares_info){
        $pdf_name = 'BusinessProfile'.time();
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
        // page 1
            $pdf->addPage(); // add page
           $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "BusinessProfile_template.pdf"); // load template
           $tplIdx = $pdf->importPage(1); // import page 1
           $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template
           // write company name
           $pdf->SetXY(48.5,55.9);
           $pdf->Write(10, $company_info['name']);
           // write company number
           $pdf->SetXY(48.5,83.6);
           $pdf->Write(10, $company_info['number']);
           $pdf->SetXY(52.5,109.5);
           $pdf->Write(10, $company_info['PrincipalActivity1']);
           $pdf->SetXY(52.5,112.6);
           $pdf->Write(10, $company_info['P1Description']);
           $pdf->SetXY(52.5,124.5);
           $pdf->Write(10, $company_info['PrincipalActivity2']);
           $pdf->SetXY(52.5,127.6);
           $pdf->Write(10, $company_info['P2Description']);
           
           $pdf->SetXY(86.6,156.9);
           $pdf->Write(10, $shares_info['IssuedOrdinary']);
           $pdf->SetXY(86.6,164.9);
           $pdf->Write(10, $shares_info['PaidUpOrdinary']);
           $pdf->SetXY(120.9,156.9);
           $pdf->Write(10,  $shares_info['Currency']);
           $pdf->SetXY(120.9,164.9);
           $pdf->Write(10,  $shares_info['Currency']);

           // page 2
           $pdf->addPage(); // 
           $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "BusinessProfile_template.pdf"); // load template
           $tplIdx = $pdf->importPage(2); // import page 2
           $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template

           $y = 35.5;
        for($i=0;$i<count($directors);$i++){
            $pdf->SetXY(124.1,$y);
            $pdf->Write(10, $directors[$i]['name']);
            $pdf->SetXY(124.1,$y+3);
            $pdf->Write(10, $directors[$i]['Placeofbirth']);
            $pdf->SetXY(124.1,$y+6);
            $pdf->Write(10, $directors[$i]['NRIC/Passport']);
            $pdf->SetXY(124.1,$y+9);
            $pdf->Write(10, $directors[$i]['Nricdateofissue']);
            $pdf->SetXY(124.1, $y+12);
            $pdf->Write(10, $directors[$i]['nricplaceofissue']);
            $pdf->SetXY(124.1, $y+15);
            $pdf->Write(10, $directors[$i]['DateofBirth']);
            $pdf->SetXY(124.1, $y+18);
            $pdf->Write(10, $directors[$i]['NationalityatBirth']);
            $pdf->SetXY(124.1, $y+22);
            $pdf->Write(10, $directors[$i]['NationalityCurrent']);
            $pdf->SetXY(124.1, $y+30);
            $pdf->Write(10, $directors[$i]['addressline1_Singapore']);
            $pdf->SetXY(124.1, $y+33);
            $pdf->Write(10, $directors[$i]['addressline2_Singapore']);
            $pdf->SetXY(124.1, $y+36);
            $pdf->Write(10, $directors[$i]['addressline3_Singapore']);
            $pdf->SetXY(124.1, $y+42);
            $pdf->Write(10, $directors[$i]['addressline1_OverSea']);
            $pdf->SetXY(124.1, $y+45);
            $pdf->Write(10, $directors[$i]['addressline2_OverSea']);
            $pdf->SetXY(124.1, $y+48);
            $pdf->Write(10, $directors[$i]['addressline3_OverSea']);
            $pdf->SetXY(124.1, $y+54);
            $pdf->Write(10, $directors[$i]['addressline1_Other']);
            $pdf->SetXY(124.1, $y+57);
            $pdf->Write(10, $directors[$i]['addressline2_Other']);
            $pdf->SetXY(124.1, $y+60);
            $pdf->Write(10, $directors[$i]['addressline3_Other']);
            $y += 65.99;
        }
        // page 3
           $pdf->addPage(); // 
           $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "BusinessProfile_template.pdf"); // load template
           $tplIdx = $pdf->importPage(3); // import page 2
           $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template

           $y = 31.5;
        for($i=0;$i<count($shareholders);$i++){
            $pdf->SetXY(124.1,$y);
            $pdf->Write(10, $shareholders[$i]['name']);
            $pdf->SetXY(124.1,$y+3);
            $pdf->Write(10, $shareholders[$i]['Placeofbirth']);
            $pdf->SetXY(124.1,$y+6);
            $pdf->Write(10, $shareholders[$i]['NRIC/Passport']);
            $pdf->SetXY(124.1,$y+9);
            $pdf->Write(10, $shareholders[$i]['Nricdateofissue']);
            $pdf->SetXY(124.1, $y+12);
            $pdf->Write(10, $shareholders[$i]['nricplaceofissue']);
            $pdf->SetXY(124.1, $y+15);
            $pdf->Write(10, $shareholders[$i]['DateofBirth']);
            $pdf->SetXY(124.1, $y+18);
            $pdf->Write(10, $shareholders[$i]['NationalityatBirth']);
            $pdf->SetXY(124.1, $y+22);
            $pdf->Write(10, $shareholders[$i]['NationalityCurrent']);
            $pdf->SetXY(124.1, $y+30);
            $pdf->Write(10, $shareholders[$i]['addressline1_Singapore']);
            $pdf->SetXY(124.1, $y+33);
            $pdf->Write(10, $shareholders[$i]['addressline2_Singapore']);
            $pdf->SetXY(124.1, $y+36);
            $pdf->Write(10, $shareholders[$i]['addressline3_Singapore']);
            $pdf->SetXY(124.1, $y+42);
            $pdf->Write(10,$shareholders[$i]['addressline1_OverSea']);
            $pdf->SetXY(124.1, $y+45);
            $pdf->Write(10, $shareholders[$i]['addressline2_OverSea']);
            $pdf->SetXY(124.1, $y+48);
            $pdf->Write(10, $shareholders[$i]['addressline3_OverSea']);
            $pdf->SetXY(124.1, $y+54);
            $pdf->Write(10, $shareholders[$i]['addressline1_Other']);
            $pdf->SetXY(124.1, $y+57);
            $pdf->Write(10, $shareholders[$i]['addressline2_Other']);
            $pdf->SetXY(124.1, $y+60);
            $pdf->Write(10, $shareholders[$i]['addressline3_Other']);
            $pdf->SetXY(55.1, $y+68);
            $pdf->Write(10, $shareholders[$i]['ClassofShares']);
             $pdf->SetXY(85.1, $y+68);
            $pdf->Write(10, $shareholders[$i]['NumberofShares']);
             $pdf->SetXY(145.1, $y+68);
            $pdf->Write(10, $shareholders[$i]['Currency']);
            $y += 78;
        }
        // page 4
           $pdf->addPage(); // 
           $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "BusinessProfile_template.pdf"); // load template
           $tplIdx = $pdf->importPage(4); // import page 2
           $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template

           $y = 82.9;
        for($i=0;$i<count($directors);$i++){
            $pdf->SetXY(37.4,$y);
            $pdf->Write(10, $directors[$i]['name']);   
            $y +=27;
        }
           $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
            // save to database
    
        $data_pdf = array(
                'form_id' => 13,
                'company_id' => $company_info['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
    
        array_push($this->form_downloads,$data_pdf['pdf_url']);
   }
   function generateFormMAA($company_info,$LO_info){
        $pdf_name = 'FormM&AA'.time();
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
        // page 1
            $pdf->addPage(); // add page
           $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "M&AA_template.pdf"); // load template
           $tplIdx = $pdf->importPage(1); // import page 1
           $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template
           // write company name
           $pdf->SetXY(97,131.3);
           $pdf->Write(10, $company_info['name']);
           $pdf->SetXY(97.1,182.9);
           $pdf->Write(10, $LO_info['LOName']);
           $pdf->SetXY(97.1,186.2);
           $pdf->Write(10,$LO_info['LOAddressline1']);
           $pdf->SetXY(92.1,189.5);
           $pdf->Write(10,$LO_info['LOAddressline2']);
           
           $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
            // save to database
  
        $data_pdf = array(
                'form_id' => 14,
                'company_id' => $company_info['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
  
        array_push($this->form_downloads,$data_pdf['pdf_url']);
   }
   function generateForm9($company_info){  
        $pdf_name = 'Form9'.time();
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
        // page 1
            $pdf->addPage(); // add page
           $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "Form9_template.pdf"); // load template
           $tplIdx = $pdf->importPage(1); // import page 1
           $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template
        $pdf->SetXY(70.1,80.8);
        $pdf->Write(10, $company_info['number']);
        $pdf->SetXY(79.8,169.9);
        $pdf->Write(10, $company_info['name']);
        $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
         // save to database
    
        $data_pdf = array(
                'form_id' => 15,
                'company_id' => $company_info['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
    
        array_push($this->form_downloads,$data_pdf['pdf_url']);
   }
   function generateFormFirstMeeting($company_info,$directors,$subscriber,$chairman){
       $pdf_name = 'FormFirstMeeting'.time();
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
        // page 1
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "firstMeeting1_template.pdf"); // load template
        $tplIdx = $pdf->importPage(1); // import page 1
        
        $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template
        $pdf->SetXY(75.7,24.4);
        $pdf->Write(10, $company_info['name']);
        $pdf->SetXY(19.9,49.9);
        $pdf->Write(10,$company_info['address1'].$company_info['address2']);
         $y = 79;
         for($i=0;$i<count($directors);$i++){
            $pdf->SetXY(47.8,$y);
            $pdf->Write(10, $directors[$i]['name']);   
            $y +=15;
        }
        $pdf->SetXY(22.7,141.9);
        $pdf->Write(10,$chairman);
        $pdf->SetXY(51.9,205.9);
        $pdf->Write(10,$directors[0]['name'].",".$directors[1]['name']);
        $pdf->SetXY(22.7,209.2);
        $pdf->Write(10, $directors[2]['name']);
        
        // page 2
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "firstMeeting1_template.pdf"); // load template
        $tplIdx = $pdf->importPage(2); // import page 1
        
        $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template
        $pdf->SetXY(25,64.5);
        $pdf->Write(10,$company_info['address1'].$company_info['address2']);
        $pdf->SetXY(86.9,83.1);
        $pdf->Write(10,$subscriber['name']);
         $pdf->SetXY(111.5,87.6);
        $pdf->Write(10,$subscriber['name']); 
        $pdf->SetXY(108.0,91.4);
        $pdf->Write(10,$directors[0]['name'].",".$directors[1]['name']); 
         $pdf->SetXY(31.2,96.3);
        $pdf->Write(10,$directors[2]['name']); 
 
        $pdf->SetXY(161.6,167.7);
        $pdf->Write(10,$chairman);

        $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
         // save to database
  
        $data_pdf = array(
                'form_id' => 16,
                'company_id' => $company_info['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
  
        array_push($this->form_downloads,$data_pdf['pdf_url']);
   }
   function generateRegisterOfApplicationsAndAI($shareholders,$company_info,$shares_info){
       $pdf_name = 'RegisterApplicationAllotment'.time();
    // generate PDF
        $pdf = new FPDI(); // init
        // create overlays
        $pdf->SetFont('Helvetica','',9); // set font type
        $pdf->SetTextColor(0, 0, 0); // set font color
        // page 1
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "RegisterApplicationAllotment_template.pdf"); // load template
        $tplIdx = $pdf->importPage(1); // import page 1
        
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template
        $pdf->SetXY(121.5,18.5);
        $pdf->Write(10,$company_info['name']);
        $y = 44.2;
        $x = 63.6;
       for($i=0;$i<count($shareholders);$i++){
            $pdf->SetXY($x,$y);
            $pdf->Write(10, $shareholders[$i]['name']);  
            $pdf->SetXY($x,$y+3);
            $pdf->Write(10, $shareholders[$i]['addressline1_Singapore']); 
            $pdf->SetXY($x,$y+6);
            $pdf->Write(10, $shareholders[$i]['addressline2_Singapore'].$shareholders[$i]['addressline3_Singapore']); 
            $pdf->SetXY($x+67,$y);
            $pdf->Write(10, $shareholders[$i]['NumberofShares']);  
            $pdf->SetXY($x+87,$y);
            $pdf->Write(10, $shareholders[$i]['NumberofShares']);  
            $pdf->SetXY($x+97,$y);
            $pdf->Write(10,$shares_info['NominalAmountOfEachShare']); 
            $pdf->SetXY($x+117,$y);
            $pdf->Write(10,$shareholders[$i]['NumberofShares']*$shares_info['NominalAmountOfEachShare']); 
            $pdf->SetXY($x+157,$y);
            $pdf->Write(10,$shareholders[$i]['CertificateNo']);   
            $pdf->SetXY($x+177,$y);
            $pdf->Write(10,$shareholders[$i]['MembersRegisterNo']);  
            $y +=13;
        };

        $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
         // save to database
  
        $data_pdf = array(
                'form_id' => 17,
                'company_id' => $company_info['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
    
        array_push($this->form_downloads,$data_pdf['pdf_url']);
   }
   function generateRegisterDirectorsAlternateD($directors,$company_info){
      
       for($i=0;$i<count($directors);$i++){
            $pdf_name = 'RegisterDirectorsAlternateD'.$i.time();
            // generate PDF
            $pdf = new FPDI(); // init
            // create overlays
            $pdf->SetFont('Helvetica','',9); // set font type
            $pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "RegisterAlternateD_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); // import page 1

            $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template
            $pdf->SetXY(130.99,34.6);
            $pdf->Write(10,$company_info['name']);   
            $pdf->SetXY(24.7,57.7);
            $pdf->Write(10, $directors[$i]['name']);  
            $pdf->SetXY(44.2,67.5);
            $pdf->Write(10, $directors[$i]['FormerName']); 
            $pdf->SetXY(43.6,77.9);
            $pdf->Write(10,$directors[$i]['addressline1_Singapore'].$directors[$i]['addressline2_Singapore']); 
            $pdf->SetXY(43.6,83.1);
            $pdf->Write(10,$directors[$i]['addressline3_Singapore']);  
            $pdf->SetXY(45.7,89.1);
            $pdf->Write(10, $directors[$i]['Occupation']);  
            $pdf->SetXY(52.8,97.9);
            $pdf->Write(10,$directors[$i]['NRIC/Passport']);
            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
             // save to database
     
        $data_pdf = array(
                'form_id' => 18,
                'company_id' => $company_info['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
      
        array_push($this->form_downloads,$data_pdf['pdf_url']);
        };   
   }
      function generateMemberForm($shareholders,$company_info,$shares_info){
            for($i=0;$i<count($shareholders);$i++){
            $pdf_name = 'RegisterMember'.$i.time();
            // generate PDF
            $pdf = new FPDI(); // init
            // create overlays
            $pdf->SetFont('Helvetica','',6); // set font type
            $pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "RegisterOfMember_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); // import page 1

            $pdf->useTemplate($tplIdx, null,null, 0,0,true); // place the template
            $pdf->SetXY(140.5,12.7);
            $pdf->Write(10,$company_info['name']);   
            $pdf->SetXY(26.2,23.1);
            $pdf->Write(10, $shareholders[$i]['name']);  
            $pdf->SetXY(28.2,26.2);
            $pdf->Write(10,$shareholders[$i]['addressline1_Singapore']); 
            $pdf->SetXY(28.2,29.1);
            $pdf->Write(10,$shareholders[$i]['addressline2_Singapore'].$shareholders[$i]['addressline3_Singapore']); 
            $pdf->SetXY(125.5,22.9);
            $pdf->Write(10,$shareholders[$i]['ClassofShares']);  
            $pdf->SetXY(69.1,42.5);
            $pdf->Write(10, $shareholders[$i]['CertificateNo']);  
            $pdf->SetXY(91.1,42.5);
            $pdf->Write(10,$shareholders[$i]['NumberofShares']*$shares_info['NominalAmountOfEachShare']);
             $pdf->SetXY(165.9,42.5);
            $pdf->Write(10, $shareholders[$i]['NumberofShares']);
             $pdf->SetXY(195.8,42.5);
            $pdf->Write(10, $shareholders[$i]['NumberofShares']);
            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
              // save to database
  
        $data_pdf = array(
                'form_id' => 19,
                'company_id' => $company_info['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );

        array_push($this->form_downloads,$data_pdf['pdf_url']);
            }
        }
        function generateDirectorInterest($directors,$company_info,$shares_info){
            for($i=0;$i<count($directors);$i++){
            $pdf_name = 'RegisterDirectorInterest'.$i.time();
            // generate PDF
            $pdf = new FPDI(); // init
            // create overlays
            $pdf->SetFont('Helvetica','',10); // set font type
            $pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "RegisterDirectorInterest_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); // import page 1

            $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template
            $pdf->SetXY(110.3,10.2);
            $pdf->Write(10,$company_info['name']);   
            $pdf->SetXY(27.3,32.1);
            $pdf->Write(10, $directors[$i]['name']);  
            $pdf->SetXY(125.7,29.9);
            $pdf->Write(10,$directors[$i]['ClassofShares']); 
            $pdf->SetXY(77.0,61.3);
            $pdf->Write(10,$directors[$i]['NatureOfContract']==""?0:$directors[$i]['NatureOfContract']); 
            $pdf->SetXY(107,61.3);
            $pdf->Write(10,$shares_info['NominalAmountOfEachShare']);  
            $pdf->SetXY(137,61.3);
            $pdf->Write(10, $directors[$i]['NumberofShares']);  
             $pdf->SetXY(185,61.3);
            $pdf->Write(10, $directors[$i]['NumberofShares']);
            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F'); 
             // save to database
 
        $data_pdf = array(
                'form_id' => 20,
                'company_id' => $company_info['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
   
        array_push($this->form_downloads,$data_pdf['pdf_url']);
            } 
        }
        function generateCertificationSH($shareholders,$company_info){
            for($i=0;$i<count($shareholders);$i++){
            $pdf_name = 'RegisterCertificationSH'.$i.time();
            // generate PDF
            $pdf = new FPDI(); // init
            // create overlays
            $pdf->SetFont('Helvetica','',6); // set font type
            $pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "CertificateSH_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); // import page 1

            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template
            $pdf->SetXY(54.4,20.2);
            $pdf->Write(10,$shareholders[$i]['CertificateNo']);   
            $pdf->SetXY(175.1,20.2);
            $pdf->Write(10, $shareholders[$i]['NumberofShares']);  
            $pdf->SetXY(85.7,25.1);
            $pdf->Write(10,$shareholders[$i]['name']); 
            $pdf->SetXY(85.7,31.1);
            $pdf->Write(10,$shareholders[$i]['addressline1_Singapore'].$shareholders[$i]['addressline2_Singapore'].$shareholders[$i]['addressline3_Singapore']); 
            $pdf->SetXY(32.4,44.9);
            $pdf->Write(10,$shareholders[$i]['CertificateNo']);  
            $pdf->SetXY(175.5,44.9);
            $pdf->Write(10, $shareholders[$i]['NumberofShares']);  
            $pdf->SetXY(95.1,44.9);
            $pdf->Write(10,$company_info['name']);
             $pdf->SetXY(90.3,53.2);
            $pdf->Write(10,$company_info['address1'].$company_info['address2']);
             $pdf->SetXY(80.4,73.1);
            $pdf->Write(10, $shareholders[$i]['name']);
            $pdf->SetXY(31.1,78.2);
            $pdf->Write(10,$shareholders[$i]['addressline1_Singapore']." ".$shareholders[$i]['addressline2_Singapore']." ".$shareholders[$i]['addressline3_Singapore']);
             $pdf->SetXY(71.1,84.1);
            $pdf->Write(10,$shareholders[$i]['NumberofShares']." ".$shareholders[$i]['NumberofSharesInwords']);
             $pdf->SetXY(91.3,89.3);
            $pdf->Write(10, $company_info['name']);
            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F'); 
           // save to database
   
        $data_pdf = array(
                'form_id' => 21,
                'company_id' => $company_info['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
     
        array_push($this->form_downloads,$data_pdf['pdf_url']);
        }

    }
    function generateRegisterMortgagesCharges($company_info){
        $pdf_name = 'RegisterMortgagesCharges'.time();
        // generate PDF
        $pdf = new FPDI(); // init
        // create overlays
        $pdf->SetFont('Helvetica','',6); // set font type
        $pdf->SetTextColor(0, 0, 0); // set font color
        // page 1
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "RegisterMortgage_template.pdf"); // load template
        $tplIdx = $pdf->importPage(1); // import page 1
        $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template
         $pdf->SetXY(99.5,19.5);
        $pdf->Write(10, $company_info['name']);
        $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F'); 
         // save to database
    
        $data_pdf = array(
                'form_id' => 22,
                'company_id' => $company_info['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );

        array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    function generateRegisterTransfer($company_info){
        $pdf_name = 'RegisterOfTransfer'.time();
        // generate PDF
        $pdf = new FPDI(); // init
        // create overlays
        $pdf->SetFont('Helvetica','',6); // set font type
        $pdf->SetTextColor(0, 0, 0); // set font color
        // page 1
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "RegisterOfTransfer_template.pdf"); // load template
        $tplIdx = $pdf->importPage(1); // import page 1
        $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template
         $pdf->SetXY(102.5,17.5);
        $pdf->Write(10, $company_info['name']);
        $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
        // save to database

        $data_pdf = array(
                'form_id' => 23,
                'company_id' => $company_info['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
   
        array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    function generateRegisterSealing($company_info){
        $pdf_name = 'RegisterOfSealing'.time();
        // generate PDF
        $pdf = new FPDI(); // init
        // create overlays
        $pdf->SetFont('Helvetica','',6); // set font type
        $pdf->SetTextColor(0, 0, 0); // set font color
        // page 1
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "RegisterOfSealings_template.pdf"); // load template
        $tplIdx = $pdf->importPage(1); // import page 1
        $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template
         $pdf->SetXY(102.5,16.5);
        $pdf->Write(10, $company_info['name']);
        $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F'); 
         // save to database
       
        $data_pdf = array(
                'form_id' => 24,
                'company_id' =>$company_info['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
     
        array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    function generateSecretaryAuditor($company_info,$secretary,$auditor){
        $pdf_name = 'RegisterOfSecretaryAuditor'.time();
        // generate PDF
        $pdf = new FPDI(); // init
        // create overlays
        $pdf->SetFont('Helvetica','',6); // set font type
        $pdf->SetTextColor(0, 0, 0); // set font color
        // page 1
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "RegisterAuditorsSecretaries_template.pdf"); // load template
        $tplIdx = $pdf->importPage(1); // import page 1
        $pdf->useTemplate($tplIdx, 10, 10,200); // place the template
         $pdf->SetXY(100.6,29.3);
        $pdf->Write(10, $company_info['name']);
        $pdf->SetXY(21.6,54.3);
        $pdf->Write(10,$secretary['name']);
        $pdf->SetXY(65.6,54.3);
        $pdf->Write(10,$secretary['addressLine1']);
        $pdf->SetXY(65.6,57.3);
        $pdf->Write(10,$secretary['addressLine2']);
         $pdf->SetXY(65.6,60.3);
        $pdf->Write(10,$secretary['addressLine3']);
        $pdf->SetXY(120.6,54.3);
        $pdf->Write(10,$secretary['OtherOccupation']);
        $pdf->SetXY(185.6,54.3);
        $pdf->Write(10,$secretary['NRIC/Passport']);
        $pdf->SetXY(21.6,85.2);
        $pdf->Write(10,$auditor['name']);
        $pdf->SetXY(65.6,85.2);
        $pdf->Write(10,$auditor['addressline1']);
        $pdf->SetXY(65.6,88.2);
        $pdf->Write(10,$auditor['addressline2']);
        $pdf->SetXY(65.6,91.2);
        $pdf->Write(10,$auditor['addressline3']);
        $pdf->SetXY(120.6,85.2);
        $pdf->Write(10,$auditor['otherOccupation']);
        $pdf->SetXY(185.6,85.2);
        $pdf->Write(10,$auditor['nric/passport']);
        
        $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
        // save to database
   
        $data_pdf = array(
                'form_id' => 25,
                'company_id' => $company_info['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );

        array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    // end incorporation
    function generateClosureOfBankAccResolution($data){
        $company_info = $this->Company->find("first",array(
            "condition"=>array(
                    "company_id"=>$data['company_id']
                )
            ));
        $bank = $data['BankName'];
        $acc = $data['AccNumber'];
        
        
        $pdf_name = 'Closure of Bank Account RESOLUTION'.time();
        // generate PDF
        $pdf = new FPDI(); // init
        // create overlays
        $pdf->SetFont('Arial',"",14);
        $pdf->SetTextColor(0, 0, 0); // set font color
        // page 1
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "Closure of Bank Account RESOLUTION_template.pdf"); // load template
        $tplIdx = $pdf->importPage(1); // import page 1
        $pdf->useTemplate($tplIdx,10, 10,200); // place the template
        
        $pdf->SetXY(93.7,25.1);
        $pdf->Write(10, $company_info['Company']['name']);      
        $pdf->SetXY(120.1,31.9);
        $pdf->Write(10,$company_info['Company']['register_number']);
        $pdf->SetFontSize(10);
        $pdf->SetXY(28.4,72.9);
        $pdf->Write(10,$bank);
        $pdf->SetXY(89.1,83.5);
        $pdf->Write(10,$bank);
         $pdf->SetXY(60.9,88.4);
        $pdf->Write(10,$acc);
        
        $asIsStakeHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.company_id"=>$data['company_id']
                    )
                ));
                $avail_ids=array();
                foreach($asIsStakeHolders as $asIsStakeHolder){
                    array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
                };
                
                $asIsDirectors = $this->Director->find("all",array(
                    "conditions"=>array(
                        "Director.Mode"=>"appointed",
                        "Director.id"=>$avail_ids
                    )
                ));
            $y = 185;
                 for($i = 0;$i<count($asIsDirectors);$i=$i+2){
                    $pdf->SetFont('Helvetica','',15);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsDirectors[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsDirectors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsDirectors[$i+1]['StakeHolder']['name']);
                            
                        }
                   
                    $y += 30;
             }
        
        $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
        // save to database
     
        $data_pdf = array(
                'form_id' => 3,
                'company_id' => $data['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
     
        array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    function generateLoanResolution($data){
        $company_info = $this->Company->find("first",array(
            "condition"=>array(
                    "company_id"=>$data['company_id']
                )
            ));
        $loaner = $data['Loaner'];
        $amount = $data['AmountLoan'];
        $articleNo = $data['articleNo'];
        $pdf_name = 'Loan Resolution'.time();
        // generate PDF
        $pdf = new FPDI(); // init
        // create overlays
        $pdf->SetFont('Arial',"",14);
        $pdf->SetTextColor(0, 0, 0); // set font color
        // page 1
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "Loan_Resolution_template.pdf"); // load template
        $tplIdx = $pdf->importPage(1); // import page 1
        $pdf->useTemplate($tplIdx,10, 10,200); // place the template
        
        $pdf->SetXY(93.7,25.1);
        $pdf->Write(10, $company_info['Company']['name']);      
        $pdf->SetXY(121.1,32.7);
        $pdf->Write(10,$company_info['Company']['register_number']);
        $pdf->SetFontSize(11);
        $pdf->SetXY(168.7,42.9);
        $pdf->Write(10,$articleNo);
        $pdf->SetXY(152.9,75.2);
        $pdf->Write(10,$amount);
         $pdf->SetXY(34,80);
        $pdf->Write(10,$loaner);
        
       $asIsStakeHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.company_id"=>$data['company_id']
                    )
                ));
                $avail_ids=array();
                foreach($asIsStakeHolders as $asIsStakeHolder){
                    array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
                };
                
                $asIsDirectors = $this->Director->find("all",array(
                    "conditions"=>array(
                        "Director.Mode"=>"appointed",
                        "Director.id"=>$avail_ids
                    )
                ));
            $y = 180;
                 for($i = 0;$i<count($asIsDirectors);$i=$i+2){
                    $pdf->SetFont('Helvetica','',15);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsDirectors[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsDirectors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsDirectors[$i+1]['StakeHolder']['name']);
                            
                        }
                   
                    $y += 30;
             }
        
        $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
        // save to database
     
        $data_pdf = array(
                'form_id' => 3,
                'company_id' => $data['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
   
        array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    function generateOptionToPurchase($data){
        $company_info = $this->Company->find("first",array(
            "condition"=>array(
                    "company_id"=>$data['company_id']
                )
            ));
        $authorizer = $data['authorizer'];
        $property_address = $data['propertyAddress'];
        $meeting = $data['maddress'];
        $sellingPrice = $data['sellingPrice'];
        $vendor = $data['vendor'];
        $otp = $data['otpDate'];
        $sign = $data['sign'];
        $articleNo = $data['articleNo'];
        $pdf_name = 'Option_to_purchase'.time();
        // generate PDF
        $pdf = new FPDI(); // init
        // create overlays
        $pdf->SetFont('Arial',"",11);
        $pdf->SetTextColor(0, 0, 0); // set font color
        // page 1
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "Option_to_purchase_template.pdf"); // load template
        $tplIdx = $pdf->importPage(1); // import page 1
        $pdf->useTemplate($tplIdx,10, 10,200); // place the template
        
        $pdf->SetXY(98.7,15.2);
        $pdf->Write(10, $company_info['Company']['name']);      
        $pdf->SetXY(121.1,20.1);
        $pdf->Write(10,$company_info['Company']['register_number']);
        $pdf->SetXY(101.3,28.9);
        $pdf->Write(10,$company_info['Company']['address_1']." ".$company_info['Company']['address_2']);

        $pdf->SetXY(31.9,49.0);
        $pdf->Write(10,$authorizer);
        $pdf->SetXY(127.0,53.6);
        $pdf->Write(10,$company_info['Company']['name']);
         $pdf->SetXY(123.7,58.4);
        $pdf->Write(10,$company_info['Company']['name']);
        $pdf->SetXY(73.6,63.8);
        $pdf->Write(10,$otp);
        $pdf->SetXY(37.0,121.7);
        $pdf->Write(10,$property_address);
        $pdf->SetXY(46.9,126.5);
        $pdf->Write(10,$vendor);
        $pdf->SetXY(60.9,131.6);
        $pdf->Write(10,$sellingPrice);
        $pdf->SetXY(36.2,140.3);
        $pdf->Write(10,$otp);
        
        $pdf->SetXY(47.2,163.3);
        $pdf->Write(10,$authorizer);
        $pdf->SetXY(125.0,167.7);
        $pdf->Write(10,$sign);
        $pdf->SetXY(136.9,258.6);
        $pdf->Write(10,$authorizer);
        
        // page 2
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "Option_to_purchase_template.pdf"); // load template
        $tplIdx = $pdf->importPage(2); // import page 1
        $pdf->useTemplate($tplIdx,10, 10,200); // place the template
        $pdf->SetXY(101.6,16.2);
        $pdf->Write(10, $company_info['Company']['name']);      
        $pdf->SetXY(122.9,21.1);
        $pdf->Write(10,$company_info['Company']['register_number']);
        $pdf->SetXY(103.3,29.2);
        $pdf->Write(10,$company_info['Company']['address_1']." ".$company_info['Company']['address_2']);
        $pdf->SetXY(91.9,45.2);
        $pdf->Write(10,$articleNo);
         $pdf->SetXY(41.9,84.8);
        $pdf->Write(10,$meeting);
        $pdf->SetXY(38.4,125.0);
        $pdf->Write(10,$property_address);
        $pdf->SetXY(47.7,129.6);
        $pdf->Write(10,$vendor);
        $pdf->SetXY(62.9,134.6);
        $pdf->Write(10,$sellingPrice);
        $pdf->SetXY(37.2,144.1);
        $pdf->Write(10,$otp);
        
        $pdf->SetXY(48.4,162.9);
        $pdf->Write(10,$authorizer);
        $pdf->SetXY(127.0,167.7);
        $pdf->Write(10,$sign);
       
        
        $asIsStakeHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.company_id"=>$data['company_id']
                    )
                ));
                $avail_ids=array();
                foreach($asIsStakeHolders as $asIsStakeHolder){
                    array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
                };
                
                $asIsDirectors = $this->Director->find("all",array(
                    "conditions"=>array(
                        "Director.Mode"=>"appointed",
                        "Director.id"=>$avail_ids
                    )
                )); 
            $y = 250;
                 for($i = 0;$i<count($asIsDirectors);$i=$i+2){
                    $pdf->SetFont('Helvetica','',11);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsDirectors[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsDirectors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsDirectors[$i+1]['StakeHolder']['name']);
                            
                        }
                   
                    $y += 30;
             }
        $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
        // save to database
   
        $data_pdf = array(
                'form_id' => 9,
                'company_id' => $data['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );

        array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    function generateForm49COP($data){
        $form_id =2;
        $prepared_by = $data['prepared_by'];
        $directors = array();
     
        $director_ids = $data['stakeholder'];
        for ($i = 0; $i < count($director_ids); $i++) {
                $director = $this->StakeHolder->find('first', array('conditions' => array('StakeHolder.id ' => $director_ids[$i])));
                //$director['Occupation'] = $director['StakeHolder']['Director']==1?"Director";
               $director['occupation']=$data["occupation"][$i];
                array_push($directors, $director);
        }
        $company_id = $directors[0]['Company']['company_id'];
        $company_name = $directors[0]['Company']['name'];
        $company_number = $directors[0]['Company']['register_number'];
         $LOL_name = $directors[0]['Company']['LOName'];
        $LOL_acc = $directors[0]['Company']['LOAcNo'];
        $company_address1 = $directors[0]['Company']['LOAddressline1'];
        $company_address2 = $directors[0]['Company']['LOAddressline2'];
        $company_telp = $directors[0]['Company']['LOTelNo'];
        $company_fax = $directors[0]['Company']['LOTelFax'];

        $pdf_name = 'Form_49_'.time();

        // generate PDF
        $pdf = new FPDI(); // init
        // create overlays
        $pdf->SetFont('Helvetica', '', 9); // set font type
        $pdf->SetTextColor(0, 0, 0); // set font color

    	// page 1
    	$pdf->addPage(); // add page
    	$pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form49_template.pdf"); // load template
		$tplIdx = $pdf->importPage(1); // import page 1
		$pdf->useTemplate($tplIdx, 10, 10, 200); // place the template

		// write company name
		$pdf->SetXY(63, 56);
		$pdf->Write(10, $company_name);

		// write company number
		$pdf->SetXY(55, 63.8);
		$pdf->Write(10, $company_number);

		

		// write prepared by
		$pdf->SetXY(138, 197.5);
		$pdf->Write(10, $prepared_by);

		// write company name
		$pdf->SetXY(44, 251.5);
		$pdf->Write(10,  $LOL_name );

		// write company address 1
		$pdf->SetXY(46, 256.5);
		$pdf->Write(10, $company_address1);

		// write company address 2
		$pdf->SetXY(46.5, 259.5);
		$pdf->Write(10, $company_address2);
                
                $pdf->SetXY(46.5, 263.6);
		$pdf->Write(10, $LOL_acc);

		// write company number
		$pdf->SetXY(85, 266.9);
		$pdf->Write(10, $company_fax );

		// write company telp
		$pdf->SetXY(85, 263.6);
		$pdf->Write(10, $company_telp);
		$y_pos = 110;
		for ($i = 0; $i < 2; $i++) {
			if (!empty($directors[$i])) {
				// write director name
                                $name = $directors[$i]['StakeHolder']['name'];
                                $address_1 = $directors[$i]['StakeHolder']['address_1'];
                                $address_2 = $directors[$i]['StakeHolder']['address_2'];
                                $nric = $directors[$i]['StakeHolder']['nric'];
                                $nationality = $directors[$i]['StakeHolder']['nationality'];
                                $occupation = $directors[$i]['occupation'];
                             
                                
                                
				$pdf->SetXY(40, $y_pos);
				$pdf->Write(10,  $name);

				// write director address
				$pdf->SetXY(40, $y_pos+5);
				$pdf->Write(10, $address_1);
				$pdf->SetXY(40, $y_pos+10);
				$pdf->Write(10, $address_2);

				// write director nric
				$pdf->SetXY(105, $y_pos);
				$pdf->Write(10, $nric);

				// write director nationality
				$pdf->SetXY(105, $y_pos+5);
				$pdf->Write(10,  $nationality );

				// write director occupation
				$pdf->SetXY(105, $y_pos+10);
				$pdf->Write(10, $occupation );

				// write type
				$pdf->SetXY(145, $y_pos);
				$pdf->Write(10, 'Change Of Passport No');
				
				$pdf->SetXY(145, $y_pos+5);
				$pdf->Write(10, 'with effect from');

			

				$y_pos += 25;
			}
		}

		// page 2
		$pdf->addPage(); // add page
		$pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form49_template.pdf"); // load template
		$tplIdx = $pdf->importPage(2); // import page 2
		$pdf->useTemplate($tplIdx, 10, 10, 200); // place the template
		
		$pdf->SetXY(64, 33);
		$pdf->Write(10, $company_name);

		// write company number
		$pdf->SetXY(56, 45);
		$pdf->Write(10, $company_number);

		// write directors
		$y_pos = 90;
		for ($i = 2; $i < count($directors); $i++) {
			if (!empty($directors[$i])) {
				// write director name
                            
                            $name = $directors[$i]['StakeHolder']['name'];
                            $address_1 = $directors[$i]['StakeHolder']['address_1'];
                            $address_2 = $directors[$i]['StakeHolder']['address_2'];
                            $nric = $directors[$i]['StakeHolder']['nric'];
                            $nationality = $directors[$i]['StakeHolder']['nationality'];
                            $occupation = $directors[$i]['occupation'];
                         
                            
				$pdf->SetXY(40, $y_pos);
				$pdf->Write(10,  $name);

				// write director address
				$pdf->SetXY(40, $y_pos+5);
				$pdf->Write(10, $address_1);
				$pdf->SetXY(40, $y_pos+10);
				$pdf->Write(10, $address_2);

				// write director nric
				$pdf->SetXY(105, $y_pos);
				$pdf->Write(10, $nric);

				// write director nationality
				$pdf->SetXY(105, $y_pos+5);
				$pdf->Write(10,  $nationality );

				// write director occupation
				$pdf->SetXY(105, $y_pos+10);
				$pdf->Write(10, $occupation );

				$pdf->SetXY(145, $y_pos);
				$pdf->Write(10, 'Change Of Passport No');
				
				$pdf->SetXY(145, $y_pos+5);
				$pdf->Write(10, 'with effect from');

			

				$y_pos += 25;
			}
		}

		$pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');

		
		//$this->Pdf->create();
               
		$data = array(
			'form_id' => $form_id,
			'company_id' => $company_id,
			'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
			'created_at' => date('Y-m-d H:i:s')
		);
		//$this->Pdf->save($data);
                array_push($this->form_downloads,$data['pdf_url']);
    }
    public function generateForm44A($data){
        $pdf_name = 'Form44'.time();
        $company_info = $this->Company->find("first",array(
            "conditions"=>array(
                "Company.company_id"=>$data['company']
            )
        ));
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
             // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form44A_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',10 );
            $pdf->SetXY(60.7,65.9);
            $pdf->Write(10, $company_info['Company']['name']);
            $pdf->SetXY(54.3,71.1);
            $pdf->Write(10, $company_info['Company']['register_number']);
            

            $pdf->SetXY(44.6,118.4);
            $pdf->Write(10, $data['address_1']." ".$data['address_2']);
            
            $pdf->SetXY(134.7,190.3);
            $pdf->Write(10, $data['nameDS']); 
             $pdf->SetXY(45.1,249.4);
            $pdf->Write(10,$company_info['Company']['LOName']); 
             $pdf->SetXY(45.8,253.9);
            $pdf->Write(10,$company_info['Company']['LOAddressline1']); 
            $pdf->SetXY(45.8,257.5);
            $pdf->Write(10,$company_info['Company']['LOAddressline2']);
             $pdf->SetXY(45.8,263.8);
            $pdf->Write(10,$company_info['Company']['LOAcNo']); 
             $pdf->SetXY(94.9,263.8);
            $pdf->Write(10,$company_info['Company']['LOTelNo']); 
             $pdf->SetXY(95.7,266.9);
            $pdf->Write(10,$company_info['Company']['LOTelFax']); 
  

            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
           // save to database
     
        $data_pdf = array(
                'form_id' => 26,
                'company_id' => $company_info['Company']['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
    
        array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    function generateResolutionRegisteredAddress($data){
        $pdf_name = 'ResolutionRegisteredAddress'.time();
        $company_info = $this->Company->find("first",array(
            "conditions"=>array(
                "Company.company_id"=>$data['company']
            )
        ));
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
             // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "Resolution_registerAddress_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',10 );
            $pdf->SetXY(84.7,27.9);
            $pdf->Write(10, $company_info['Company']['name']);
            $pdf->SetXY(117.3,33.1);
            $pdf->Write(10, $company_info['Company']['register_number']);
            

            $pdf->SetXY(30.6,90.4);
            $pdf->Write(10, $data['address_1']." ".$data['address_2']);
            
            
            $asIsStakeHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.company_id"=>$data['company']
                    )
                ));
                $avail_ids=array();
                foreach($asIsStakeHolders as $asIsStakeHolder){
                    array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
                };
                
                $asIsDirectors = $this->Director->find("all",array(
                    "conditions"=>array(
                        "Director.Mode"=>"appointed",
                        "Director.id"=>$avail_ids
                    )
                ));
             $y = 180;
                 for($i = 0;$i<count($asIsDirectors);$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsDirectors[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsDirectors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsDirectors[$i+1]['StakeHolder']['name']);
                            
                        }
                   
                    $y += 30;
             }
             
             
            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
           // save to database
     
        $data_pdf = array(
                'form_id' => 26,
                'company_id' => $company_info['Company']['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
    
        array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    public function generateEOGM_SALES_ASSET($data){
        $pdf_name = 'EOGM_SALES_ASSET'.time();
        $company_info = $this->Company->find("first",array(
            "conditions"=>array(
                "Company.company_id"=>$data['company']
            )
        ));
           
        $asIsStakeHolders = $this->StakeHolder->find("all",array(
                "conditions"=>array(
                    "StakeHolder.company_id"=>$data['company']
                )
            ));
            $avail_ids=array();
            foreach($asIsStakeHolders as $asIsStakeHolder){
                array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
            };

            $asIsShareHolders = $this->ShareHolder->find("all",array(
                "conditions"=>array(
                    "ShareHolder.id"=>$avail_ids
                )
            ));
            $asIsDirectors = $this->Director->find("all",array(
                "conditions"=>array(
                    "Director.id"=>$avail_ids,
                    "Director.mode"=>"Appointed"
                )
            ));
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
             // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_SalesOfAsset_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',12);
            $pdf->SetXY(98.9,28);
            $pdf->Write(10, $company_info['Company']['name']);
            $pdf->SetFont('Helvetica','',8);
            $pdf->SetXY(130,32.7);
            $pdf->Write(10, $company_info['Company']['register_number']);
            

            $pdf->SetXY(98.9,40.4);
            $pdf->Write(10, $company_info['Company']['address_1']." ".$company_info['Company']['address_2']);
            
//         
             $y = 135;
                 for($i = 0;$i<count($asIsShareHolders);$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsShareHolders[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsShareHolders)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsShareHolders[$i+1]['StakeHolder']['name']);
                            
                        }
                   
                    $y += 30;
             }
             // page 2
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_SalesOfAsset_template.pdf"); // load template
            $tplIdx = $pdf->importPage(2); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',12);
            $pdf->SetXY(98.9,26);
            $pdf->Write(10, $company_info['Company']['name']);
            $pdf->SetFont('Helvetica','',8);
            $pdf->SetXY(130,29.7);
            $pdf->Write(10, $company_info['Company']['register_number']);
            

            $pdf->SetXY(98.9,39.8);
            $pdf->Write(10, $company_info['Company']['address_1']." ".$company_info['Company']['address_2']);
            $pdf->SetFont('Helvetica','',10);
            $pdf->SetXY(40,62);
            $pdf->Write(10, $data['m_address1']." ".$data['m_address1']);
            
            $pdf->SetXY(28.8,93);
            $pdf->Write(10, $data['chairman']." was appointed Chairman of the meeting");
            
            $pdf->SetXY(103,133.8);
            $pdf->Write(10, $data['seller']);
            
            $pdf->SetXY(33.8,139);
            $pdf->Write(10, $data['buyer']);
            
            $pdf->SetXY(78,144);
            $pdf->Write(10, $data['price']);
            
            $pdf->SetXY(111,252);
            $pdf->Write(10, $data['chairman']);
//          
            // page 3
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_SalesOfAsset_template.pdf"); // load template
            $tplIdx = $pdf->importPage(3); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',12);
            $pdf->SetXY(98.9,27.8);
            $pdf->Write(10, $company_info['Company']['name']);
            $pdf->SetFont('Helvetica','',8);
            $pdf->SetXY(130,32.4);
            $pdf->Write(10, $company_info['Company']['register_number']);
            

            $pdf->SetXY(98.9,40.9);
            $pdf->Write(10, $company_info['Company']['address_1']." ".$company_info['Company']['address_2']);
            $pdf->SetFont('Helvetica','',12);
            $pdf->SetXY(69,65.8);
            $pdf->Write(10, $data['m_address1']." ".$data['m_address1']);
             $y = 110;
                 for($i = 0;$i<count($asIsShareHolders);$i=$i+1){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 90;
                        $pdf->SetXY($x,$y);
                        $pdf->Write(10,$asIsShareHolders[$i]['StakeHolder']['name']);
                   
                    $y += 30;
             }
             
             // page 4
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_SalesOfAsset_template.pdf"); // load template
            $tplIdx = $pdf->importPage(4); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',12);
            $pdf->SetXY(98.9,21.9);
            $pdf->Write(10, $company_info['Company']['name']);
            $pdf->SetFont('Helvetica','',8);
            $pdf->SetXY(130,26.4);
            $pdf->Write(10, $company_info['Company']['register_number']);
            

            $pdf->SetXY(99.9,34.5);
            $pdf->Write(10, $company_info['Company']['address_1']." ".$company_info['Company']['address_2']);
            $pdf->SetFont('Helvetica','',12);
            $pdf->SetXY(119,50);
            $pdf->Write(10, $data['articleNo']);
             $pdf->SetXY(27.8,78);
            $pdf->Write(10, $data['m_address1']." ".$data['m_address1']);
             $pdf->SetXY(112,108.8);
            $pdf->Write(10, $data['seller']);
            
            $pdf->SetXY(33.8,113.9);
            $pdf->Write(10, $data['buyer']);
            
            $pdf->SetXY(83,118.5);
            $pdf->Write(10, $data['price']);
             $y = 215;
                 for($i = 0;$i<count($asIsDirectors);$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsDirectors[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsDirectors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsDirectors[$i+1]['StakeHolder']['name']);
                            
                        }
                   
                    $y += 30;
             }
            // page 5
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_SalesOfAsset_template.pdf"); // load template
            $tplIdx = $pdf->importPage(5); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',12);
            $pdf->SetXY(91.9,15.8);
            $pdf->Write(10, $company_info['Company']['name']);
            $pdf->SetFont('Helvetica','',8);
            $pdf->SetXY(118,21);
            $pdf->Write(10, $company_info['Company']['register_number']);
            $word = "";
            for($i = 0;$i<count($asIsShareHolders);$i=$i+1){
                
                if($i == count($asIsShareHolders)-1){
                    $word .= $asIsShareHolders[$i]['StakeHolder']['name'];
                }else{
                   $word .= $asIsShareHolders[$i]['StakeHolder']['name'].","; 
                }
             }
            $pdf->SetXY(36,40.1);
            $pdf->Write(10, $word);

            $pdf->SetXY(98.9,31);
            $pdf->Write(10, $company_info['Company']['address_1']." ".$company_info['Company']['address_2']);
            $pdf->SetFont('Helvetica','',12);
             $pdf->SetXY(39,70);
            $pdf->Write(10, $data['m_address1']." ".$data['m_address1']);
             $pdf->SetXY(130,115.5);
            $pdf->Write(10, $data['seller']);
            
            $pdf->SetXY(60,120);
            $pdf->Write(10, $data['buyer']);
            
            $pdf->SetXY(106,130.4);
            $pdf->Write(10, $data['price']);
            
            $pdf->SetXY(26,205);
            $pdf->Write(10, $data['chairman']);
             
            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
           // save to database
     
        $data_pdf = array(
                'form_id' => 8,
                'company_id' => $company_info['Company']['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
    
        array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    public function generateResolutionPropertyDisposal($data){
        $pdf_name = 'PropertyDisposal'.time();
        $company_info = $this->Company->find("first",array(
            "conditions"=>array(
                "Company.company_id"=>$data['company']
            )
        ));
           
        $asIsStakeHolders = $this->StakeHolder->find("all",array(
                "conditions"=>array(
                    "StakeHolder.company_id"=>$data['company']
                )
            ));
            $avail_ids=array();
            foreach($asIsStakeHolders as $asIsStakeHolder){
                array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
            };
            $asIsDirectors = $this->Director->find("all",array(
                "conditions"=>array(
                    "Director.id"=>$avail_ids,
                    "Director.mode"=>"Appointed"
                )
            ));
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
             // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "ResolutionPropertyDisposal_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',12);
            $pdf->SetXY(90.9,30.9);
            $pdf->Write(10, $company_info['Company']['name']);
            $pdf->SetFont('Helvetica','',8);
            $pdf->SetXY(122,36.5);
            $pdf->Write(10, $company_info['Company']['register_number']);
            

            $pdf->SetXY(98,45);
            $pdf->Write(10, $company_info['Company']['address_1']." ".$company_info['Company']['address_2']);
            $pdf->SetFont('Helvetica','',10);
            $pdf->SetXY(33,72.8);
            $pdf->Write(10, $data['undersigned'].",the undersigned,");
             $pdf->SetXY(100.5,78);
            $pdf->Write(10,$company_info['Company']['name']);
            $pdf->SetXY(124,83.1);
            $pdf->Write(10,$company_info['Company']['name']);
            $pdf->SetFont('Helvetica','',12);
             $pdf->SetXY(28.2,129);
            $pdf->Write(10,$data['header_resolution']);
            $pdf->Line(28.4,136,$pdf->GetX(),136);
             $pdf->SetFont('Helvetica','',10);
            $pdf->SetXY(28.2,145);
            $pdf->Write(10,$data['property']);
            $pdf->SetXY(34,149.6);
            $pdf->Write(10,$data['buyer']);
           
            $pdf->SetXY(54,154.3);
            $pdf->Write(10,$data['price']);
            // page 2
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "ResolutionPropertyDisposal_template.pdf"); // load template
            $tplIdx = $pdf->importPage(2); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',12);
            $pdf->SetXY(90.9,31.9);
            $pdf->Write(10, $company_info['Company']['name']);
            $pdf->SetXY(74.4,68.4);
            $pdf->Write(10, $data['articleNo']);
            $pdf->SetFont('Helvetica','',8);
            $pdf->SetXY(121,37.3);
            $pdf->Write(10, $company_info['Company']['register_number']);
            $pdf->SetXY(98,45.9);
            $pdf->Write(10, $company_info['Company']['address_1']." ".$company_info['Company']['address_2']);
            $pdf->SetFont('Helvetica','',10);
            $pdf->SetXY(28.2,88.8);
            $pdf->Write(10, $data['AddressLine1']." ".$data['AddressLine2']);
            $pdf->SetFont('Helvetica','',12);
             $pdf->SetXY(28.2,113);
            $pdf->Write(10,$data['header_resolution']);
            $pdf->Line(28.2,120,$pdf->GetX(),120);
             $pdf->SetFont('Helvetica','',10);
            $pdf->SetXY(28.2,126);
            $pdf->Write(10,$data['property']);
            $pdf->SetXY(33.5,130.6);
            $pdf->Write(10,$data['buyer']);
           
            $pdf->SetXY(52,135);
            $pdf->Write(10,$data['price']);
            $y = 211;
                 for($i = 0;$i<count($asIsDirectors);$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsDirectors[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsDirectors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsDirectors[$i+1]['StakeHolder']['name']);
                            
                        }
                   
                    $y += 30;
             }
            
             
        $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');  
        $data_pdf = array(
                'form_id' => 8,
                'company_id' => $company_info['Company']['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
    
        array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    public function generateForm49RA($data){
        $form_id =2;
		
		$prepared_by = $data['prepared_by'];
                $id = $data['auditor'];
             
                $auditor = $this->StakeHolder->find('first', array('conditions' => array('StakeHolder.id ' => $id)));
                $occupation = "AUDITOR";
         
		$company_name = $auditor['Company']['name'];
		$company_number = $auditor['Company']['register_number'];
		
                $LOLName = $auditor['Company']['LOName'];
                $LOAddressLine1 = $auditor['Company']['LOAddressline1'];
                $LOAddressLine2 = $auditor['Company']['LOAddressline2'];
                $LOTelNo = $auditor['Company']['LOTelNo'];
                 $LOTelFax = $auditor['Company']['LOTelFax'];
                 $LOAcNo = $auditor['Company']['LOAcNo'];
                 $au = $this->Auditor->find('first', array('conditions' => array('Auditor.id ' => $id)));

		$pdf_name = 'Form_49_ResignAuditor'.time();

		// generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica', '', 9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color

    	// page 1
    	$pdf->addPage(); // add page
    	$pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form49_template.pdf"); // load template
		$tplIdx = $pdf->importPage(1); // import page 1
		$pdf->useTemplate($tplIdx, 10, 10, 200); // place the template

		// write company name
		$pdf->SetXY(63, 56);
		$pdf->Write(10, $company_name);

		// write company number
		$pdf->SetXY(55, 63.8);
		$pdf->Write(10, $company_number);

		

		// write prepared by
		$pdf->SetXY(138, 197.5);
		$pdf->Write(10, $prepared_by);

		// write company name
		$pdf->SetXY(44, 251.5);
		$pdf->Write(10, $LOLName);

		// write company address 1
		$pdf->SetXY(46, 256.5);
		$pdf->Write(10, $LOAddressLine1);

		// write company address 2
		$pdf->SetXY(46.5, 259.5);
		$pdf->Write(10, $LOAddressLine2);

		// write company number
		$pdf->SetXY(85, 266.9);
		$pdf->Write(10, $LOTelFax );
                
                // write LO acc
		$pdf->SetXY(44, 263.6);
		$pdf->Write(10, $LOAcNo);

		// write company telp
		$pdf->SetXY(85, 263.6);
		$pdf->Write(10, $LOTelNo);
		$y_pos = 115;
				
                $name = $auditor['StakeHolder']['name'];
                $address_1 = $au['Auditor']['addressLine1'];
                $address_2 = $au['Auditor']['addressLine2'];
                $address_3 = $au['Auditor']['addressLine3'];
                $pdf->SetXY(40, $y_pos);
                $pdf->Write(10,  $name);

                // write director address
                $pdf->SetXY(40, $y_pos+5);
                $pdf->Write(10, $address_1);
                $pdf->SetXY(40, $y_pos+10);
                $pdf->Write(10, $address_2);
                $pdf->SetXY(40, $y_pos+15);
                $pdf->Write(10, $address_3);
                
                $pdf->SetXY(40, 106);
                $pdf->Write(10, $occupation );
                $pdf->Line(40,113,$pdf->GetX(),113);

                // write type
                $pdf->SetXY(145, $y_pos);
                $pdf->Write(10, 'CESSATION WITH');
				
                $pdf->SetXY(145, $y_pos+5);
                $pdf->Write(10, 'EFFECT FROM');
			
		
                $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');

		

               
		$data = array(
			'form_id' => $form_id,
			'company_id' => $data['company'],
			'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
			'created_at' => date('Y-m-d H:i:s')
		);
	
                array_push($this->form_downloads,$data['pdf_url']);
    }
    public function generateResolutionRA($data){
        $form_id = 3;
            
            $company = $this->Company->find('first',array('conditions'=>array('company_id = '=> $data['company'])));
            //ChromePhp::log($company);
            $company_id = $company['Company']['company_id'];
            $id = $data['auditor'];
           
            $auditor = $this->StakeHolder->find('first', array('conditions' => array('StakeHolder.id ' => $id)));

          //ChromePhp::log($directors);
            $pdf_name = 'Resolution_ResignAuditor'.time();
            
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "resolutionResignAuditor_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); // import page 1esolution_template
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            // write company name
            $pdf->SetFont('Helvetica','',13 );
            $pdf->SetXY(87,25);
            $pdf->Write(10, $company['Company']['name']);
            $pdf->SetXY(124,33);
            $pdf->Write(10, $company['Company']['register_number']);
            $pdf->SetFont('Helvetica','',10);
            $pdf->SetXY(103,73.1);
            $pdf->Write(10,$auditor['StakeHolder']['name']);
            //write AsIs Directors(The directors which are neither resigned or 
            //just be promoted
                $asIsStakeHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.company_id"=>$company_id
                    )
                ));
                $avail_ids=array();
                foreach($asIsStakeHolders as $asIsStakeHolder){
                    array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
                };
                
                $asIsDirectors = $this->Director->find("all",array(
                    "conditions"=>array(
                        "Director.Mode"=>"appointed",
                        "Director.id"=>$avail_ids
                    )
                ));
            $y = 120;
                 for($i = 0;$i<count($asIsDirectors);$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsDirectors[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsDirectors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsDirectors[$i+1]['StakeHolder']['name']);
                            
                        }
                   
                    $y += 30;
             }
            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');

		// save to database

		$data_pdf = array(
			'form_id' => $form_id,
			'company_id' => $company_id,
			'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
			'created_at' => date('Y-m-d H:i:s')
		);
		 
                array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    function generateEGM($data){
         $form_id = 27;
         $pdf_name = 'EGM_NormalStruckOff'.time(); 
            $company = $this->Company->find('first',array('conditions'=>array('company_id = '=> $data['company'])));
            //ChromePhp::log($company);
            $shareAmount = $data['shareAmount'];
            $ids = $data['stakeholder'];
             $asIsShareHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.id"=>$ids
                    )
                ));
            $total = "";
            $name = "";
            foreach($asIsShareHolders as $shareholder){
                $name .=  $shareholder['StakeHolder']['name'].",";
            }
            foreach($shareAmount as $share){
                $total += $share;
            }
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EGM_normalStruck_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); // import page 1esolution_template
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            // write company name
            $pdf->SetFont('Helvetica','',13 );
            $pdf->SetXY(92,26);
            $pdf->Write(10, $company['Company']['name']);
            $pdf->SetXY(123,32);
            $pdf->Write(10, $company['Company']['register_number']);
            $pdf->SetFont('Helvetica','',10);
            $pdf->SetXY(92,41);
            $pdf->Write(10, $company['Company']['address_1']." ".$company['Company']['address_2']);
            $pdf->SetXY(122,86.4);
            $pdf->Write(10,$company['Company']['name']);
            //write AsIs Directors(The directors which are neither resigned or 
            //just be promoted
            $y = 165;
                 for($i = 0;$i<count($asIsShareHolders);$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsShareHolders[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsShareHolders)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsShareHolders[$i+1]['StakeHolder']['name']);
                            
                        }
                   
                    $y += 30;
             }
             // page 2
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EGM_normalStruck_template.pdf"); // load template
            $tplIdx = $pdf->importPage(2); // import page 1esolution_template
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            // write company name
            $pdf->SetFont('Helvetica','',13 );
            $pdf->SetXY(92,25);
            $pdf->Write(10, $company['Company']['name']);
            $pdf->SetXY(125,32);
            $pdf->Write(10, $company['Company']['register_number']);
            $pdf->SetFont('Helvetica','',10);
            $pdf->SetXY(100,42);
            $pdf->Write(10, $company['Company']['address_1']." ".$company['Company']['address_2']);
            $pdf->SetXY(68,60);
            $pdf->Write(10,$data['maddress']);
            //write AsIs Directors(The directors which are neither resigned or 
            //just be promoted
            $y = 135;
                 for($i = 0;$i<count($asIsShareHolders);$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsShareHolders[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsShareHolders)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsShareHolders[$i+1]['StakeHolder']['name']);
                            
                        }
                   
                    $y += 30;
             }
             // page 3
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EGM_normalStruck_template.pdf"); // load template
            $tplIdx = $pdf->importPage(3); // import page 1esolution_template
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            // write company name
            $pdf->SetFont('Helvetica','',13 );
            $pdf->SetXY(90.4,29);
            $pdf->Write(10, $company['Company']['name']);
            $pdf->SetXY(124,37);
            $pdf->Write(10, $company['Company']['register_number']);
            $pdf->SetFont('Helvetica','',11);
            $pdf->SetXY(93.4,44.8);
            $pdf->Write(10, $company['Company']['address_1']." ".$company['Company']['address_2']);
            $pdf->SetXY(86.5,90.4);
            $pdf->Write(10,$data['maddress']);
            //write AsIs Directors(The directors which are neither resigned or 
            //just be promoted
            $y = 195;
                 for($i = 0;$i<count($asIsShareHolders);$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsShareHolders[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsShareHolders)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsShareHolders[$i+1]['StakeHolder']['name']);
                            
                        }
                   
                    $y += 30;
             }
             // page 4
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EGM_normalStruck_template.pdf"); // load template
            $tplIdx = $pdf->importPage(4); // import page 1esolution_template
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            // write company name
            $pdf->SetFont('Helvetica','',13 );
            $pdf->SetXY(96,29);
            $pdf->Write(10, $company['Company']['name']);
            $pdf->SetXY(124,33.5);
            $pdf->Write(10, $company['Company']['register_number']);
            $pdf->SetFont('Helvetica','',11);
            $pdf->SetXY(93,42.2);
            $pdf->Write(10, $company['Company']['address_1']." ".$company['Company']['address_2']);
            $pdf->SetXY(60.5,57.9);
            $pdf->Write(10,$company['Company']['name']);
            $pdf->SetXY(74,63);
            $pdf->Write(10,$data['maddress']);
            
            
            $pdf->SetXY(43,83);
            $pdf->Write(10,$name);
            //write AsIs Directors(The directors which are neither resigned or 
            //just be promoted
            $y = 195;
                 for($i = 0;$i<count($asIsShareHolders);$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsShareHolders[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsShareHolders)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsShareHolders[$i+1]['StakeHolder']['name']);
                            
                        }
                   
                    $y += 30;
             }
            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');

		// save to database

		$data_pdf = array(
			'form_id' => $form_id,
			'company_id' => $company['Company']['company_id'],
			'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
			'created_at' => date('Y-m-d H:i:s')
		);
		 
                array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    public function generateLetterAcra($data){
        $form_id = 28;
         $pdf_name = 'LetterAcra'.time(); 
            $company = $this->Company->find('first',array('conditions'=>array('company_id = '=> $data['company'])));
            $shareAmount = $data['shareAmount'];
            $ids = $data['stakeholder'];
             $asIsShareHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.id"=>$ids
                    )
                ));
            $total = "";
            for($i = 0;$i < count($asIsShareHolders);$i++){
                $asIsShareHolders[$i]['amountShare'] = $shareAmount[$i]; 
            }
            foreach($shareAmount as $share){
                $total += $share;
            }
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "lettertoAcra_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); // import page 1esolution_template
            $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
            // write company name
            $pdf->SetFont('Helvetica','',13 );
            $pdf->SetXY(21,40);
            $pdf->Write(10, $data['addressLine1']);
            $pdf->SetXY(21,45);
            $pdf->Write(10, $data['addressLine2']);
            $pdf->SetXY(21,50);
            $pdf->Write(10, $data['addressLine3']);
            $pdf->SetFont('Helvetica','',12);
            $pdf->SetXY(35,74);
            $pdf->Write(10,$company['Company']['name']);
            $pdf->SetXY(75,79);
            $pdf->Write(10, $company['Company']['register_number']);
            $pdf->SetFont('Helvetica','',10);
            $y = 107;
             for($i = 0;$i < count($asIsShareHolders);$i++){
                 $pdf->SetXY(41,$y);
                 $pdf->Write(10, $asIsShareHolders[$i]['StakeHolder']['name']);
                 $pdf->SetXY(133,$y);
                 $pdf->Write(10, $asIsShareHolders[$i]['amountShare']);  
                 $y += 7;
             }
               $pdf->Line(133,$y+1.5,$pdf->getX()+20,$y+1.5);
               $pdf->SetXY(133,$y + 5);
               $pdf->Write(10, $total);
              
            //write AsIs Directors(The directors which are neither resigned or 
            //just be promoted
            $y =240;
            if(count($asIsShareHolders)<=2){
                 for($i = 0;$i<count($asIsShareHolders);$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsShareHolders[$i]['StakeHolder']['name']);
                        $pdf->SetXY($x,$y+4);
                        $pdf->Write(10,"NRIC No:".$asIsShareHolders[$i]['StakeHolder']['nric']);
                        $pdf->SetXY($x,$y+8);
                        $pdf->Write(10,"Director and Shareholder");
                        if($i+1<count($asIsShareHolders)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsShareHolders[$i+1]['StakeHolder']['name']);  
                            $pdf->SetXY($k,$y+4);
                            $pdf->Write(10,"NRIC No:".$asIsShareHolders[$i]['StakeHolder']['nric']);
                            $pdf->SetXY($k,$y+8);
                            $pdf->Write(10,"Director and Shareholder");
                        }
                        
                   
                    $y += 30;
                }
            }else{
                for($i = 0;$i<2;$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsShareHolders[$i]['StakeHolder']['name']);
                        $pdf->SetXY($x,$y+4);
                        $pdf->Write(10,"NRIC No:".$asIsShareHolders[$i]['StakeHolder']['nric']);
                        $pdf->SetXY($x,$y+8);
                        $pdf->Write(10,"Director and Shareholder");
                        if($i+1<count($asIsShareHolders)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsShareHolders[$i+1]['StakeHolder']['name']);
                            $pdf->SetXY($k,$y+4);
                            $pdf->Write(10,"NRIC No:".$asIsShareHolders[$i]['StakeHolder']['nric']);
                            $pdf->SetXY($k,$y+8);
                            $pdf->Write(10,"Director and Shareholder");
                        }
                    $y += 30;
                }
                // page 2
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "lettertoAcra_template.pdf"); // load template
            $tplIdx = $pdf->importPage(2); // import page 1esolution_template
            $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
            $y = 50;
            for($i = 2;$i<count($asIsShareHolders);$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 30;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsShareHolders[$i]['StakeHolder']['name']);
                        $pdf->SetXY($x,$y+4);
                        $pdf->Write(10,"NRIC No:".$asIsShareHolders[$i]['StakeHolder']['nric']);
                        $pdf->SetXY($x,$y+8);
                        $pdf->Write(10,"Director and Shareholder");
                        if($i+1<count($asIsShareHolders)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsShareHolders[$i+1]['StakeHolder']['name']);  
                            $pdf->SetXY($k,$y+4);
                            $pdf->Write(10,"NRIC No:".$asIsShareHolders[$i]['StakeHolder']['nric']);
                            $pdf->SetXY($k,$y+8);
                            $pdf->Write(10,"Director and Shareholder");
                        }
                $y += 30;
            }
        }
        $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
        $data_pdf = array(
			'form_id' => $form_id,
			'company_id' =>$company['Company']['company_id'],
			'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
			'created_at' => date('Y-m-d H:i:s')
		);
		 
        array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    public function generateStatutoryDeclaration($data){
        $form_id = 29;
        $ids = $data['stakeholder'];
        $company = $this->Company->find('first',array('conditions'=>array('company_id = '=> $data['company'])));
        $asIsShareHolders = $this->StakeHolder->find("all",array(
            "conditions"=>array(
                "StakeHolder.id"=>$ids
            )
        ));
        for($i = 0;$i < count($asIsShareHolders);$i++){
            $pdf_name = 'StatutoryDeclaration'.$i.time(); 
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "statutory_declaration_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); // import page 1esolution_template
            $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
            // write company name
            $pdf->SetXY(20,39);
            $pdf->Write(10,"I,".$asIsShareHolders[$i]['StakeHolder']['name'].",NRIC NO.:".$asIsShareHolders[$i]['StakeHolder']['nric'].",of ".$asIsShareHolders[$i]['StakeHolder']['address_1'].",".$asIsShareHolders[$i]['StakeHolder']['address_2']);
            $pdf->SetXY(47,43.8);
            $pdf->Write(10, $company['Company']['name']);
            $pdf->SetXY(76,56.4);
            $pdf->Write(10,$company['Company']['name']);
            $pdf->SetXY(66,61.2);
            $pdf->Write(10,$company['Company']['register_number']);
            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
            $data_pdf = array(
			'form_id' => $form_id,
			'company_id' => $company['Company']['company_id'],
			'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
			'created_at' => date('Y-m-d H:i:s')
		);
		 
        array_push($this->form_downloads,$data_pdf['pdf_url']);
        }  
       
    }
    public function generateForm94($data){
        $form_id = 30;
        $ids = $data['stakeholder'];
        $company = $this->Company->find('first',array('conditions'=>array('company_id = '=> $data['company'])));
        $asIsShareHolders = $this->StakeHolder->find("all",array(
            "conditions"=>array(
                "StakeHolder.id"=>$ids
            )
        ));
        $LOLName = $company['Company']['LOName'];
        $LOAddressLine1 = $company['Company']['LOAddressline1'];
        $LOAddressLine2 = $company['Company']['LOAddressline2'];
        $LOTelNo = $company['Company']['LOTelNo'];
         $LOTelFax = $company['Company']['LOTelFax'];
         $LOAcNo = $company['Company']['LOAcNo'];
        for($i = 0;$i < count($asIsShareHolders);$i++){
            $pdf_name = 'Form94'.$i.time(); 
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form94_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); // import page 1esolution_template
            $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
            // write company name
            $pdf->SetXY(54,55.5);
            $pdf->Write(10,$company['Company']['name']);
            $pdf->SetXY(45,64.5);
            $pdf->Write(10, $company['Company']['register_number']);
            $pdf->SetXY(79,73);
            $pdf->Write(10,$asIsShareHolders[$i]['StakeHolder']['name']);
            $pdf->SetXY(56,80.5);
            $pdf->Write(10,$asIsShareHolders[$i]['StakeHolder']['nric']);
            $pdf->SetXY(35,90);
            $pdf->Write(10,$asIsShareHolders[$i]['StakeHolder']['address_1']." ".$asIsShareHolders[$i]['StakeHolder']['address_2']);
            $pdf->SetXY(43,100);
            $pdf->Write(10,"DIRECTOR AND SHAREHOLDER");
            $pdf->SetXY(117,205.8);
            $pdf->Write(10,$data['witness'][$i]);
            
       
		$pdf->SetXY(33, 243.5);
		$pdf->Write(10, $LOLName);

		$pdf->SetXY(34, 248.8);
		$pdf->Write(10, $LOAddressLine1);
		$pdf->SetXY(22, 252.8);
		$pdf->Write(10, $LOAddressLine2);
		$pdf->SetXY(85, 259.6);
		$pdf->Write(10, $LOTelFax );
		$pdf->SetXY(34, 259.6);
		$pdf->Write(10, $LOAcNo);

		// write company telp
		$pdf->SetXY(85, 253.4);
		$pdf->Write(10, $LOTelNo);
            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
            $data_pdf = array(
			'form_id' => $form_id,
			'company_id' => $company['Company']['company_id'],
			'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
			'created_at' => date('Y-m-d H:i:s')
		);
		 
        array_push($this->form_downloads,$data_pdf['pdf_url']);
        }  
    }
    public function generateEOGMAllotDirector($data){
        $form_id = 8;
        $ids = $data['stakeholder'];
        $company = $this->Company->find('first',array('conditions'=>array('company_id = '=> $data['company'])));
        $directors = $this->StakeHolder->find("all",array(
            "conditions"=>array(
                "StakeHolder.id"=>$ids
            )
        ));
        $asIsShareHolders = $this->StakeHolder->find("all",array(
            "conditions"=>array(
                "StakeHolder.company_id"=>$company['Company']['company_id'],
                "StakeHolder.Shareholder"=>1
            )
        ));
        $name = "";
        foreach($asIsShareHolders as $asIsShareHolder){
            $name .= $asIsShareHolder['StakeHolder']['name'].",";
        };
        $pdf_name = 'EOGMAllotDirector'.time(); 
        // generate PDF
            $pdf = new FPDI(); // init
            // create overlays
            $pdf->SetFont('Helvetica','',12); // set font type
            $pdf->SetTextColor(0, 0, 0); // set font color
        // page 1
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGMAllotedDirector_template.pdf"); // load template
        $tplIdx = $pdf->importPage(1); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
        $pdf->SetXY(83,19);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(114,24);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetXY(87,33);
        $pdf->Write(10,$company['Company']['address_1']." ".$company['Company']['address_2']);
        $y = 140;
            for($i = 0;$i<count($asIsShareHolders);$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 27;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsShareHolders[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsShareHolders)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsShareHolders[$i+1]['StakeHolder']['name']);  
                        }
                $y += 30;
            }
        // page 2
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGMAllotedDirector_template.pdf"); // load template
        $tplIdx = $pdf->importPage(2); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
        $pdf->SetXY(83,19);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(114,24);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetXY(92,33);
        $pdf->Write(10,$company['Company']['address_1']." ".$company['Company']['address_2']);
        $pdf->SetFont('Helvetica','',11);
        $pdf->SetXY(31,58);
        $pdf->Write(10,$data['maddress']);
        $pdf->SetXY(24,101);
        $pdf->Write(10,$data['chairman']." was appointed Chairman of the meeting");
        $pdf->SetXY(120.6,144);
        $pdf->Write(10,$data['totalFees']);
        $y = 163;
        $x = 58;
        for($i = 0;$i < count($directors);$i++){
            $pdf->SetXY($x,$y);
            $pdf->Write(10,$directors[$i]['StakeHolder']['name']);
            $pdf->SetXY($x+70,$y);
            $pdf->Write(10,$data['feeAmount'][$i]);
            $y += 4;
        }
        $pdf->Line($x+70,$y+4,$pdf->GetX()+7,$y+4);
        $pdf->Line($x+70,$y+5,$pdf->GetX()+7,$y+5);
        $pdf->SetXY($x+70,$y+6);
        $pdf->Write(10,$data['totalFees']);
        $pdf->SetXY(94,247);
        $pdf->Write(10,$data['chairman']);
        // page 3
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGMAllotedDirector_template.pdf"); // load template
        $tplIdx = $pdf->importPage(3); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
        $pdf->SetXY(83,19);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(114,24);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetXY(92,33);
        $pdf->Write(10,$company['Company']['address_1']." ".$company['Company']['address_2']);
        $pdf->SetFont('Helvetica','',11);
        $y = 110;
        for($i = 0;$i<count($asIsShareHolders);$i=$i+1){
            $pdf->SetXY(82,$y);
            $pdf->Write(10,$asIsShareHolders[$i]['StakeHolder']['name']);
            $y += 30;
        }
        // page 4
         $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGMAllotedDirector_template.pdf"); // load template
        $tplIdx = $pdf->importPage(4); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
        $pdf->SetFont('Helvetica','',11);
        $pdf->SetXY(83,19);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(114,24);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetXY(94,33);
        $pdf->Write(10,$company['Company']['address_1']." ".$company['Company']['address_2']);
        $pdf->SetFont('Helvetica','',11);
        $pdf->SetXY(21,68);
        $pdf->Write(10,$data['maddress']);
        $pdf->SetXY(122,105);
        $pdf->Write(10,$data['totalFees']);
        $y = 118;
        $x = 58;
        for($i = 0;$i < count($directors);$i++){
            $pdf->SetXY($x,$y);
            $pdf->Write(10,$directors[$i]['StakeHolder']['name']);
            $pdf->SetXY($x+70,$y);
            $pdf->Write(10,$data['feeAmount'][$i]);
            $y += 4;
        }
        $pdf->Line($x+70,$y+4,$pdf->GetX()+7,$y+4);
        $pdf->Line($x+70,$y+5,$pdf->GetX()+7,$y+5);
        $pdf->SetXY($x+70,$y+6);
        $pdf->Write(10,$data['totalFees']);
        $y = 210;
        for($i = 0;$i<count($directors);$i=$i+2){
            $pdf->SetFont('Helvetica','',10);
                $x = 26;
                $pdf->SetXY($x,$y);
                $pdf->Line($x,$y-1,$x+40,$y-1);
                $pdf->Write(10,$directors[$i]['StakeHolder']['name']);
                if($i+1<count($directors)){
                    $k = 140;
                    //ChromePhp::log($y);
                    $pdf->SetXY($k,$y);
                     $pdf->Line($k,$y-1,$k+40,$y-1);
                    $pdf->Write(10,$directors[$i+1]['StakeHolder']['name']);  
                }
            $y += 30;
        }
        // page 5
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGMAllotedDirector_template.pdf"); // load template
        $tplIdx = $pdf->importPage(5); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
        $pdf->SetFont('Helvetica','',11);
        $pdf->SetXY(83,19);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(114,24);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetXY(94,33);
        $pdf->Write(10,$company['Company']['address_1']." ".$company['Company']['address_2']);
        $pdf->SetFont('Helvetica','',10);
        $pdf->SetXY(30,42);
        $pdf->Write(10,$name);
        $pdf->SetXY(53,78);
        $pdf->Write(10,$data['maddress']);
        $pdf->SetXY(150,141);
        $pdf->Write(10,$data['totalFees']);
        $y = 160;
        $x = 58;
        for($i = 0;$i < count($directors);$i++){
            $pdf->SetXY($x,$y);
            $pdf->Write(10,$directors[$i]['StakeHolder']['name']);
            $pdf->SetXY($x+73,$y);
            $pdf->Write(10,$data['feeAmount'][$i]);
            $y += 4;
        }
        $pdf->Line($x+70,$y+4,$pdf->GetX()+7,$y+4);
        $pdf->Line($x+70,$y+5,$pdf->GetX()+7,$y+5);
        $pdf->SetXY($x+70,$y+6);
        $pdf->Write(10,$data['totalFees']);
        $pdf->SetFont('Helvetica','',12);
        $pdf->SetXY(20,217);
        $pdf->Write(10,$data['chairman']);
        
        $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
        $data_pdf = array(
            'form_id' => $form_id,
            'company_id' => $company['Company']['company_id'],
            'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
            'created_at' => date('Y-m-d H:i:s')
        );

     array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    public function generateEOGMFirstFinalDividend($data){
        $form_id = 8;
        $ids = $data['stakeholder'];
        $company = $this->Company->find('first',array('conditions'=>array('company_id = '=> $data['company'])));
        $sentence = "";
        if($data['devidendType']=="interim"){
            $sentence = "That an Interim Dividend of ";
        }else{
            $sentence = "That a First and Final Dividend of ";
        }
        $shareholders = $this->StakeHolder->find("all",array(
            "conditions"=>array(
                "StakeHolder.id"=>$ids
            )
        ));
       $asIsStakeHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.company_id"=>$data['company']
                    )
                ));
        $avail_ids=array();
        foreach($asIsStakeHolders as $asIsStakeHolder){
            array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
        };

        $asIsDirectors = $this->Director->find("all",array(
            "conditions"=>array(
                "Director.Mode"=>"appointed",
                "Director.id"=>$avail_ids
            )
        ));
        $name = "";
        foreach($shareholders as $asIsShareHolder){
            $name .= $asIsShareHolder['StakeHolder']['name'].",";
        };
        $pdf_name = 'EOGMFirstFinalDividend'.time(); 
        // generate PDF
            $pdf = new FPDI(); // init
            // create overlays
            $pdf->SetFont('Helvetica','',12); // set font type
            $pdf->SetTextColor(0, 0, 0); // set font color
        // page 1
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_firstFinalDivident_template.pdf"); // load template
        $tplIdx = $pdf->importPage(1); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
        $pdf->SetXY(83,19);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(116,23.2);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetXY(92,31.2);
        $pdf->Write(10,$company['Company']['address_1']." ".$company['Company']['address_2']);
        $y = 140;
            for($i = 0;$i<count($shareholders);$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 27;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$shareholders[$i]['StakeHolder']['name']);
                        if($i+1<count($shareholders)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$shareholders[$i+1]['StakeHolder']['name']);  
                        }
                $y += 30;
            }
        // page 2
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_firstFinalDivident_template.pdf"); // load template
        $tplIdx = $pdf->importPage(2); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
        $pdf->SetXY(83,13);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(116,17.4);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetXY(93,26.4);
        $pdf->Write(10,$company['Company']['address_1']." ".$company['Company']['address_2']);
        $pdf->SetXY(29,37);
        $pdf->Write(10, $name);
        $pdf->SetFont('Helvetica','',11);
         $pdf->SetXY(50,79.6);
        $pdf->Write(10, $data['maddress']);
         $pdf->SetXY(46.5,144.2);
        $pdf->Write(10,$sentence.$data['amountDividend']." on ".$data['totalShares']);
        $pdf->SetXY(20,204);
        $pdf->Write(10, $data['chairman']);
       // page 3
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_firstFinalDivident_template.pdf"); // load template
        $tplIdx = $pdf->importPage(3); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
        $pdf->SetXY(83,13);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(116,17.4);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetXY(93,26.4);
        $pdf->Write(10,$company['Company']['address_1']." ".$company['Company']['address_2']);
        $pdf->SetFont('Helvetica','',11);
         $pdf->SetXY(51.8,53);
        $pdf->Write(10, $data['maddress']);
        $pdf->SetXY(23.7,91);
        $pdf->Write(10, $data['chairman']." was appointed Chairman of the meeting");
         $pdf->SetXY(19.5,150.8);
        $pdf->Write(10,$sentence.$data['amountDividend']." on ".$data['totalShares']);
        $pdf->SetXY(96,240);
        $pdf->Write(10, $data['chairman']);
        // page 4
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_firstFinalDivident_template.pdf"); // load template
        $tplIdx = $pdf->importPage(4); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
        $pdf->SetXY(80,13);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(109,17.4);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetXY(93,26.4);
        $pdf->Write(10,$company['Company']['address_1']." ".$company['Company']['address_2']);
        $pdf->SetFont('Helvetica','',12);
         $pdf->SetXY(40,68);
        $pdf->Write(10, $data['maddress']);
       $y = 140;
            for($i = 0;$i<count($shareholders);$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 27;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$shareholders[$i]['StakeHolder']['name']);
                        if($i+1<count($shareholders)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$shareholders[$i+1]['StakeHolder']['name']);  
                        }
                $y += 30;
            }
         // page 5
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_firstFinalDivident_template.pdf"); // load template
        $tplIdx = $pdf->importPage(5); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
        $pdf->SetXY(84,13);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(116,17.4);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetXY(93,26.4);
        $pdf->Write(10,$company['Company']['address_1']." ".$company['Company']['address_2']);
        $pdf->SetFont('Helvetica','',12);
         $pdf->SetXY(22,86);
        $pdf->Write(10, $data['maddress']);
         $pdf->SetXY(19.5,130.4);
        $pdf->Write(10,$sentence.$data['amountDividend']." on ".$data['totalShares']);
       $y = 220;
            for($i = 0;$i<count($asIsDirectors);$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 27;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsDirectors[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsDirectors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsDirectors[$i+1]['StakeHolder']['name']);  
                        }
                $y += 30;
            }
        // page 6
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_firstFinalDivident_template.pdf"); // load template
        $tplIdx = $pdf->importPage(6); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
        $pdf->SetXY(86,5);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(116,9);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetFont('Helvetica','',12);
        $y = 79;
        $totalNoShares = 0;
         for($i = 0;$i<count($shareholders);$i=$i+1){
             $totalNoShares +=$data['noShares'][$i];
         }
        for($i = 0;$i<count($shareholders);$i=$i+1){
                $x = 38;
                $pdf->SetXY($x,$y);
                $pdf->Write(10,$shareholders[$i]['StakeHolder']['name']);
                $pdf->SetXY($x + 60,$y);
                $pdf->Write(10,$data['noShares'][$i]);
                $pdf->SetXY($x + 95,$y);
                $pdf->Write(10,$data['noShares'][$i]);
                $pdf->SetXY($x + 130,$y);
                $pdf->Write(10,$data['noShares'][$i]/$totalNoShares*$data['amountDividend']);
                $y += 6;
        }
        $pdf->SetXY($x+130,$y+6);
        $pdf->Write(10,$data['amountDividend']);
        $pdf->SetXY($x + 60,$y+6);
        $pdf->Write(10,$totalNoShares);
        $pdf->SetXY($x + 95,$y+6);
        $pdf->Write(10,$totalNoShares);
        $y = 200;
            for($i = 0;$i<count($asIsDirectors);$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 27;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsDirectors[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsDirectors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsDirectors[$i+1]['StakeHolder']['name']);  
                        }
                $y += 30;
            }
        // page 7,8,9
        for($i = 0;$i < count($shareholders);$i++){
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_firstFinalDivident_template.pdf"); // load template
            $tplIdx = $pdf->importPage(7); // import page 1esolution_template
            $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
            // write company name
            $pdf->SetXY(84,2);
            $pdf->Write(10,$company['Company']['name']);
            $pdf->SetFont('Helvetica','',9);
            $pdf->SetXY(116,7);
            $pdf->Write(10, $company['Company']['register_number']);
            $pdf->SetXY(94,18);
            $pdf->Write(10,$company['Company']['address_1']." ".$company['Company']['address_2']);
            $pdf->SetFont('Helvetica','',10);
            $pdf->SetXY(29,50);
            $pdf->Write(10,$shareholders[$i]['StakeHolder']['name']);
             $pdf->SetXY(29,54);
            $pdf->Write(10,$shareholders[$i]['StakeHolder']['address_1']);
            $pdf->SetXY(29,58);
            $pdf->Write(10,$shareholders[$i]['StakeHolder']['address_2']);
             $pdf->SetXY(155.5,42.9);
            $pdf->Write(10, $i+1);
             $pdf->SetXY(32,143);
            $pdf->Write(10, $data['dividendNo']);
             $pdf->SetXY(62,181);
            $pdf->Write(10, $data['noShares'][$i]);
            $pdf->SetXY(85,144);
            $pdf->Write(10, $data['devidendType']=="interim"?"INTERIM":"First And Final");
             $pdf->SetXY(129,181);
           $pdf->Write(10,$data['noShares'][$i]/$totalNoShares*$data['amountDividend']);
            $pdf->SetXY(28,211);
            $pdf->Write(10, $company['Company']['name']);
        }
        $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
        $data_pdf = array(
            'form_id' => $form_id,
            'company_id' => $company['Company']['company_id'],
            'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
            'created_at' => date('Y-m-d H:i:s')
        );

     array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    public function generateEOGMIncreaseOfShares($data){
        $form_id = 8;
        $ids = $data['stakeholder'];
        $company = $this->Company->find('first',array('conditions'=>array('company_id = '=> $data['company'])));
        $shareholders = $this->StakeHolder->find("all",array(
            "conditions"=>array(
                "StakeHolder.id"=>$ids
            )
        ));
       $asIsStakeHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.company_id"=>$data['company']
                    )
                ));
        $avail_ids=array();
        foreach($asIsStakeHolders as $asIsStakeHolder){
            array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
        };
        $asIsDirectors = $this->Director->find("all",array(
            "conditions"=>array(
                "Director.Mode"=>"appointed",
                "Director.id"=>$avail_ids
            )
        ));
        $totalShares = 0;
        $name = "";
        for($i = 0;$i < count($shareholders);$i++){
            $totalShares += $data['SharesAlloted'][$i];
            $name .= $shareholders[$i]['StakeHolder']['name'].",";

        };
        $pdf_name = 'EOGMIncreaseOfShares'.time(); 
        // generate PDF
            $pdf = new FPDI(); // init
            // create overlays
            $pdf->SetFont('Helvetica','',12); // set font type
            $pdf->SetTextColor(0, 0, 0); // set font color
        // page 1
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_increaseShares_template.pdf"); // load template
        $tplIdx = $pdf->importPage(1); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
        $pdf->SetXY(83,19);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(116,23.2);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetXY(93.5,34.4);
        $pdf->Write(10,$company['Company']['address_1']." ".$company['Company']['address_2']);
        $y = 140;
            for($i = 0;$i<count($shareholders);$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 27;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$shareholders[$i]['StakeHolder']['name']);
                        if($i+1<count($shareholders)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$shareholders[$i+1]['StakeHolder']['name']);  
                        }
                $y += 30;
            }
         //page 2
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_increaseShares_template.pdf"); // load template
        $tplIdx = $pdf->importPage(2); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
        $pdf->SetXY(83,13);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(116,18.8);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetXY(93,28.4);
        $pdf->Write(10,$company['Company']['address_1']." ".$company['Company']['address_2']);
        $pdf->SetFont('Helvetica','',11);
         $pdf->SetXY(75.4,50.7);
        $pdf->Write(10, $data['maddress']);
         $pdf->SetXY(21,79);
        $pdf->Write(10,$data['chairman']." was appointed Chairman of the meeting");
        $pdf->SetXY(68,152);
        $pdf->Write(10,$totalShares);
        $y = 170;
        for($i = 0;$i<count($shareholders);$i=$i+1){
                $pdf->SetFont('Helvetica','',10);
                $x = 45;
                $pdf->SetXY($x,$y);
                $pdf->Write(10,$shareholders[$i]['StakeHolder']['name']);
                $pdf->SetXY($x+60,$y);
                $pdf->Write(10,$data['SharesAlloted'][$i]);
                $pdf->SetXY($x+79,$y);
                $pdf->Write(10,"shares");

            $y += 4;
        }
        $pdf->SetXY($x+62,$y+4);
        $pdf->Write(10, $totalShares);
        $pdf->Line($x+55,$y+5,$pdf->getX(),$y+5);
        $pdf->SetXY($x+79,$y+4);
        $pdf->Write(10,"shares");
        $pdf->SetXY(96,250);
        $pdf->Write(10, $data['chairman']);
       // page 3
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_increaseShares_template.pdf"); // load template
        $tplIdx = $pdf->importPage(3); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
        $pdf->SetXY(83,13);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(116,18.7);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetXY(97,28.9);
        $pdf->Write(10,$company['Company']['address_1']." ".$company['Company']['address_2']);
        $pdf->SetFont('Helvetica','',11);
        $y = 90;
        for($i = 0;$i<count($shareholders);$i=$i+1){
                $x = 92;
                $pdf->SetXY($x,$y);
                $pdf->Write(10,$shareholders[$i]['StakeHolder']['name']);
            $y += 30;
        }
        // page 4
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_increaseShares_template.pdf"); // load template
        $tplIdx = $pdf->importPage(4); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
        $pdf->SetXY(83,13);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(115,18.5);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetXY(94.5,28.9);
        $pdf->Write(10,$company['Company']['address_1']." ".$company['Company']['address_2']);
        $pdf->SetFont('Helvetica','',12);
         $pdf->SetXY(20,72.5);
        $pdf->Write(10, $data['maddress']);
        $pdf->SetXY(62,137.5);
        $pdf->Write(10,$totalShares);
        $y = 152;
        for($i = 0;$i<count($shareholders);$i=$i+1){
                $pdf->SetFont('Helvetica','',10);
                $x = 43;
                $pdf->SetXY($x,$y);
                $pdf->Write(10,$shareholders[$i]['StakeHolder']['name']);
                $pdf->SetXY($x+60,$y);
                $pdf->Write(10,$data['SharesAlloted'][$i]);
                $pdf->SetXY($x+79,$y);
                $pdf->Write(10,"shares");

            $y += 4;
        }
        $pdf->SetXY($x+62,$y+4);
        $pdf->Write(10, $totalShares);
        $pdf->Line($x+55,$y+5,$pdf->getX(),$y+5);
        $pdf->SetXY($x+79,$y+4);
        $pdf->Write(10,"shares");
        $pdf->SetFont('Helvetica','',9);
        $y = 240;
            for($i = 0;$i<count($asIsDirectors);$i=$i+2){
                    
                        $x = 27;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsDirectors[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsDirectors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsDirectors[$i+1]['StakeHolder']['name']);  
                        }
                $y += 20;
            }
         // page 5
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_increaseShares_template.pdf"); // load template
        $tplIdx = $pdf->importPage(5); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
        $pdf->SetXY(78,9);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(103,13.2);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetXY(95,23.8);
        $pdf->Write(10,$company['Company']['address_1']." ".$company['Company']['address_2']);
        $pdf->SetFont('Helvetica','',11);
         $pdf->SetXY(72,88);
        $pdf->Write(10, $data['maddress']);
        $pdf->SetXY(85,160);
        $pdf->Write(10,$totalShares);
        $y = 175;
        for($i = 0;$i<count($shareholders);$i=$i+1){
                $pdf->SetFont('Helvetica','',10);
                $x = 56;
                $pdf->SetXY($x,$y);
                $pdf->Write(10,$shareholders[$i]['StakeHolder']['name']);
                $pdf->SetXY($x+60,$y);
                $pdf->Write(10,$data['SharesAlloted'][$i]);
                $pdf->SetXY($x+79,$y);
                $pdf->Write(10,"shares");

            $y += 4;
        }
        $pdf->SetXY($x+62,$y+4);
        $pdf->Write(10, $totalShares);
        $pdf->Line($x+55,$y+5,$pdf->getX(),$y+5);
        $pdf->SetXY($x+79,$y+4);
        $pdf->Write(10,"shares");
        $pdf->SetXY(19,242);
        $pdf->Write(10,$data["chairman"]);
        $pdf->SetXY(28,30);
        $pdf->Write(10,$name);
        $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
        $data_pdf = array(
            'form_id' => $form_id,
            'company_id' => $company['Company']['company_id'],
            'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
            'created_at' => date('Y-m-d H:i:s')
        );

     array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    public function generateLetterAllotmentIncreaseOfShares($data){
       $form_id = 31;
        $ids = $data['stakeholder'];
        $company = $this->Company->find('first',array('conditions'=>array('company_id = '=> $data['company'])));
        $shareholders = $this->StakeHolder->find("all",array(
            "conditions"=>array(
                "StakeHolder.id"=>$ids
            )
        ));
       $asIsStakeHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.company_id"=>$data['company']
                    )
                ));
        $avail_ids=array();
        foreach($asIsStakeHolders as $asIsStakeHolder){
            array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
        };
        $asIsDirectors = $this->Director->find("all",array(
            "conditions"=>array(
                "Director.Mode"=>"appointed",
                "Director.id"=>$avail_ids
            )
        ));
        $totalShares = 0;
        $totalCost = 0;
        for($i = 0;$i < count($shareholders);$i++){
            $totalShares += $data['SharesAlloted'][$i];
            $totalCost += $data['SharesInCash'][$i];
        };
        $pdf_name = 'LetterAllotmentIncreaseOfShares'.time(); 
        // generate PDF
            $pdf = new FPDI(); // init
            // create overlays
            $pdf->SetFont('Helvetica','',12); // set font type
            $pdf->SetTextColor(0, 0, 0); // set font color
        for($i = 0;$i < count($shareholders);$i++){
        // page 1
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "letterOfAllotment_increaseShare_template.pdf"); // load template
        $tplIdx = $pdf->importPage(1); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        $pdf->SetXY(20,31);
        $pdf->Write(10,$shareholders[$i]['StakeHolder']['name']);
        $pdf->SetXY(20,35);
        $pdf->Write(10,$shareholders[$i]['StakeHolder']['address_1']);
        $pdf->SetXY(20,39);
        $pdf->Write(10,$shareholders[$i]['StakeHolder']['address_2']);
        // write company name
        $pdf->SetXY(20,81);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetXY(20,85);
        $pdf->Write(10, $company['Company']['address_1']);
        $pdf->SetXY(20,89);
        $pdf->Write(10,$company['Company']['address_2']);
        $pdf->SetXY(56.5,120.2);
        $pdf->Write(10,$data['SharesAlloted'][$i]." ORDINARY SHARES");
         $pdf->SetXY(20,127);
        $pdf->Write(10,"I,".$shareholders[$i]['StakeHolder']['name']." hereby conifrm");
        $pdf->SetXY(45,131.5);
        $pdf->Write(10,$data['SharesAlloted'][$i]);
        $pdf->SetXY(21,137);
        $pdf->Write(10,$data['SharesAlloted'][$i]);
        $pdf->SetXY(23,239);
        $pdf->Write(10,$shareholders[$i]['StakeHolder']['name']);
        // page 2
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "letterOfAllotment_increaseShare_template.pdf"); // load template
        $tplIdx = $pdf->importPage(2); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        $pdf->SetXY(44,41);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetXY(44,45);
        $pdf->Write(10, $company['Company']['address_1']);
        $pdf->SetXY(44,49);
        $pdf->Write(10,$company['Company']['address_2']);
         $pdf->SetXY(30,99);
        $pdf->Write(10,$data['SharesAlloted'][$i]);
        $pdf->SetXY(141,99);
        $pdf->Write(10,$data['SharesInCash'][$i]);
        $pdf->SetXY(148,125.5);
        $pdf->Write(10,$data['SharesAlloted'][$i]);
        $pdf->SetXY(131,130.5);
        $pdf->Write(10,$data['SharesInCash'][$i]);
        $pdf->SetXY(74,130.5);
        $pdf->Write(10,$data["cheque"][$i]);
        $pdf->SetXY(142,196);
        $pdf->Write(10,$shareholders[$i]['StakeHolder']['name']);
        $pdf->SetXY(87,233);
        $pdf->Write(10,$shareholders[$i]['StakeHolder']['name']);
        $pdf->SetXY(43,249.5);
        $pdf->Write(10,$shareholders[$i]['StakeHolder']['address_1']);
        $pdf->SetXY(43,253.5);
        $pdf->Write(10,$shareholders[$i]['StakeHolder']['address_2']);
        $pdf->SetXY(38,260);
        $pdf->Write(10,$shareholders[$i]['StakeHolder']['nric']);
        
        }
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "letterOfAllotment_increaseShare_template.pdf"); // load template
        $tplIdx = $pdf->importPage(5); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        $pdf->SetXY(87,2);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetXY(115,7);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetXY(20,29);
        $pdf->Write(10,$company['Company']['LOName']);
        $pdf->SetXY(20,34);
        $pdf->Write(10, $company['Company']['LOAddressline1']);
        $pdf->SetXY(20,39);
        $pdf->Write(10,$company['Company']['LOAddressline2']);
        $pdf->SetXY(56,68.6);
        $pdf->Write(10,$totalShares." ORDINARY SHARES");
        $x = 20;
        $y = 128; 
        $pdf->SetFont('Helvetica','',10);
        for($i = 0;$i < count($shareholders);$i++){
            $pdf->SetXY($x,$y);
            $pdf->Write(10,$shareholders[$i]['StakeHolder']['name']);
            $pdf->SetXY($x+44,$y);
            $pdf->Write(10,$data['SharesAlloted'][$i]);
            $pdf->SetXY($x+70,$y);
            $pdf->Write(10,$data['SharesInCash'][$i]);
            $pdf->SetXY($x+100,$y);
            $pdf->Write(10,$data['SharesInCash'][$i]);
            $y += 5.5;
        }
        
        $pdf->SetXY(54,168);
        $pdf->Write(10,$totalCost);
        $pdf->SetXY(97,168);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetXY(52,173.5);
        $pdf->Write(10,$data['bankAcc']);
        $pdf->SetXY(20,208);
        $pdf->Write(10,$company['Company']['name']);
         $y = 230;
            for($i = 0;$i<count($asIsDirectors);$i=$i+2){
                    
                        $x = 27;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsDirectors[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsDirectors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsDirectors[$i+1]['StakeHolder']['name']);  
                        }
                $y += 20;
            }
        $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
        $data_pdf = array(
            'form_id' => $form_id,
            'company_id' => $company['Company']['company_id'],
            'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
            'created_at' => date('Y-m-d H:i:s')
        );

     array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    public function generateForm24_IncreasingOfShares($data){
        $pdf_name = 'Form24'.time(); 
        $ids = $data['stakeholder'];
        $company = $this->Company->find('first',array('conditions'=>array('company_id = '=> $data['company'])));
        $shareholders = $this->StakeHolder->find("all",array(
            "conditions"=>array(
                "StakeHolder.id"=>$ids
            )
        ));
       $asIsStakeHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.company_id"=>$data['company']
                    )
                ));
        $avail_ids=array();
        foreach($asIsStakeHolders as $asIsStakeHolder){
            array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
        };
        $asIsDirectors = $this->Director->find("all",array(
            "conditions"=>array(
                "Director.Mode"=>"appointed",
                "Director.id"=>$avail_ids
            )
        ));
        $totalShares = 0;
        $totalCost = 0;
        for($i = 0;$i < count($shareholders);$i++){
            $totalShares += $data['SharesAlloted'][$i];
            $totalCost += $data['SharesInCash'][$i];
        };
        $totalpaid = $totalCost + $data['capitalShare'];
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form24_IncreaseShares_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',10 );
            $pdf->SetXY(58.1,58.9);
            $pdf->Write(10, $company['Company']['name']);
            $pdf->SetXY(51.2,65.5);
            $pdf->Write(10, $company['Company']['register_number']);
            $pdf->SetXY(126.5,111.3);
            $pdf->Write(10,$totalShares);
            $pdf->SetXY(128.3,117.9);
            $pdf->Write(10,$data['eachShare']);            
            $pdf->SetXY(126.0,124.2);
            $pdf->Write(10,$data['eachShare']); 
             $pdf->SetXY(41.8,220.4);
            $pdf->Write(10,$company['Company']['LOName']); 
             $pdf->SetXY(43.8,223.9);
            $pdf->Write(10,$company['Company']['LOAddressline1']); 
            $pdf->SetXY(43.8,227.9);
            $pdf->Write(10,$company['Company']['LOAddressline2']);
             $pdf->SetXY(80.7,234.3);
            $pdf->Write(10,$company['Company']['LOTelNo']); 
             $pdf->SetXY(80.7,238.2);
            $pdf->Write(10,$company['Company']['LOTelFax']); 
            
             // page 2
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form24_IncreaseShares_template.pdf"); // load template
            $tplIdx = $pdf->importPage(2); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',10 );
            $pdf->SetXY(57.8,32.9);
            $pdf->Write(10, $company['Company']['name']);
            $pdf->SetXY(50.9,40.0);
            $pdf->Write(10, $company['Company']['register_number']);
            $y=75.2;
            $x=36.8;
            for($i = 0;$i<count($shareholders);$i++){
                $pdf->SetXY($x,$y);
                $pdf->Write(10,$shareholders[$i]['StakeHolder']['name']);
                $pdf->SetXY($x,$y+4);
                $pdf->Write(10,$shareholders[$i]['StakeHolder']['address_1']);
                $pdf->SetXY($x,$y+8);
                $pdf->Write(10,$shareholders[$i]['StakeHolder']['address_2']);
                $pdf->SetXY($x,$y+12);
                $pdf->Write(10,$shareholders[$i]['StakeHolder']['nric']);
                $pdf->SetXY($x,$y+16);
                $pdf->Write(10,$shareholders[$i]['StakeHolder']['nationality']);
                $pdf->SetXY($x+95.4,$y);
                $pdf->Write(10,$data['SharesAlloted'][$i]);
                $pdf->SetXY($x+86.4,$y+4);
                $pdf->Write(10,"ORDINARY");
                $y += 28;
            }
//            
//
             // page 3
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form24_IncreaseShares_template.pdf"); // load template
            $tplIdx = $pdf->importPage(3); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',10 );
            $pdf->SetXY(58.5,28.7);
            $pdf->Write(10, $company['Company']['name']);
            $pdf->SetXY(57.5,36.0);
            $pdf->Write(10,$company['Company']['register_number']);
            

             $pdf->SetFont('Helvetica','',6);
            $pdf->SetXY(102.4,75.2);
            $pdf->Write(10, $totalpaid); 
            
            $pdf->SetXY(102.4,83.3);
            $pdf->Write(10, $totalpaid); 
            $pdf->SetFont('Helvetica','',10 );
            $pdf->SetXY(116.7,209.9);
            $pdf->Write(10, $data['chairman']);
             $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
             // save to database
        //$this->Pdf->create();
        $data_pdf = array(
                'form_id' => 10,
                'company_id' => $company['Company']['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
        //$this->Pdf->save($data_pdf);
        array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    public function generateForm11_IncreasingOfShares($data){
         $pdf_name = 'Form11'.time();
         $ids = $data['stakeholder'];
        $company = $this->Company->find('first',array('conditions'=>array('company_id = '=> $data['company'])));
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form11_increaseOfShares_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            // write company name
            $pdf->SetFont('Helvetica','',9 );
            $pdf->SetXY(60,74);
            $pdf->Write(10, $company ['Company']['name']);
            $pdf->SetXY(52,78);
            $pdf->Write(10, $company['Company']['register_number']);
            

            $pdf->SetXY(68,108);
            $pdf->Write(10, $data['maddress']);
            $asIsStakeHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.company_id"=>$data['company']
                    )
                ));
            $avail_ids=array();
            foreach($asIsStakeHolders as $asIsStakeHolder){
                array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
            };
            $asIsDirectors = $this->Director->find("all",array(
                "conditions"=>array(
                    "Director.Mode"=>"appointed",
                    "Director.id"=>$avail_ids
                )
            ));
            $name = "";
            foreach($asIsDirectors as $director){
                $name .= $director['StakeHolder']['name']." ";
            }
            $pdf->SetFont('Helvetica','',8);
            $pdf->SetXY(43,164);
            $pdf->Write(10, $name); 
            $pdf->SetFont('Helvetica','',9 );
            $pdf->SetXY(43,183);
            $pdf->Write(10,"DIRECTORS");
            $pdf->SetXY(140,204);
            $pdf->Write(10,$data['nameDS']);
            $pdf->SetXY(41.8,242.2);
            $pdf->Write(10,$company['Company']['LOName']); 
             $pdf->SetXY(43.8,246.8);
            $pdf->Write(10,$company['Company']['LOAddressline1']); 
            $pdf->SetXY(43.8,250.8);
            $pdf->Write(10,$company['Company']['LOAddressline2']);
             $pdf->SetXY(90,258.3);
            $pdf->Write(10,$company['Company']['LOTelNo']); 
             $pdf->SetXY(90,262.2);
            $pdf->Write(10,$company['Company']['LOTelFax']); 
            
            
            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');

		// save to database
	
		$data_pdf = array(
			'form_id' => 7,
			'company_id' => $data['company'],
			'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
			'created_at' => date('Y-m-d H:i:s')
		);
		
                array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    public function generateEOGMIncreaseNonCashCapital($data){
        $form_id = 8;
        $ids = $data['stakeholder'];
        $company = $this->Company->find('first',array('conditions'=>array('company_id = '=> $data['company'])));
        $shareholders = $this->StakeHolder->find("all",array(
            "conditions"=>array(
                "StakeHolder.id"=>$ids
            )
        ));
       $asIsStakeHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.company_id"=>$data['company']
                    )
                ));
        $avail_ids=array();
        foreach($asIsStakeHolders as $asIsStakeHolder){
            array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
        };
        $asIsDirectors = $this->Director->find("all",array(
            "conditions"=>array(
                "Director.Mode"=>"appointed",
                "Director.id"=>$avail_ids
            )
        ));

        $name = "";
        for($i = 0;$i < count($shareholders);$i++){
            $name .= $shareholders[$i]['StakeHolder']['name'].",";

        };
        $pdf_name = 'EOGM'.time(); 
        // generate PDF
            $pdf = new FPDI(); // init
            // create overlays
            $pdf->SetFont('Helvetica','',12); // set font type
            $pdf->SetTextColor(0, 0, 0); // set font color
        // page 1
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_CapitalOtherThanCash_template.pdf"); // load template
        $tplIdx = $pdf->importPage(1); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
        $pdf->SetXY(83,19);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(114.5,23.2);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetXY(88,34.4);
        $pdf->Write(10,$company['Company']['address_1']." ".$company['Company']['address_2']);
        $y = 160;
            for($i = 0;$i<count($shareholders);$i=$i+2){
                    $pdf->SetFont('Helvetica','',10);
                        $x = 27;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$shareholders[$i]['StakeHolder']['name']);
                        if($i+1<count($shareholders)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$shareholders[$i+1]['StakeHolder']['name']);  
                        }
                $y += 30;
            }
         //page 2
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_CapitalOtherThanCash_template.pdf"); // load template
        $tplIdx = $pdf->importPage(2); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
        $pdf->SetXY(83,19);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(116,23.5);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetXY(91,34.4);
        $pdf->Write(10,$company['Company']['address_1']." ".$company['Company']['address_2']);
        $pdf->SetFont('Helvetica','',11);
         $pdf->SetXY(61.4,58.7);
        $pdf->Write(10, $data['maddress']);
         $pdf->SetXY(20,88);
        $pdf->Write(10,$data['chairman']." was appointed Chairman of the meeting");
        $pdf->SetXY(104,144);
        $pdf->Write(10,$data['totalCash']);
        $pdf->SetXY(142,148);
        $pdf->Write(10,$data['totalSharesAlloted']);
        $pdf->SetXY(86,180);
        $pdf->Write(10,$data['totalSharesAlloted']);
        $y = 170;
        for($i = 0;$i<count($shareholders);$i=$i+1){


            $y += 4;
        }

        $pdf->SetXY(89,246);
        $pdf->Write(10, $data['chairman']);
       // page 3
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_CapitalOtherThanCash_template.pdf"); // load template
        $tplIdx = $pdf->importPage(3); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
        $pdf->SetXY(83,18);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(116,23.7);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetXY(89,33.9);
        $pdf->Write(10,$company['Company']['address_1']." ".$company['Company']['address_2']);
        $pdf->SetFont('Helvetica','',11);
        $pdf->SetXY(41,62);
        $pdf->Write(10,$data['maddress']);
        $y = 90;
        for($i = 0;$i<count($shareholders);$i=$i+1){
                $x = 69;
                $pdf->SetXY($x,$y);
                $pdf->Write(10,$shareholders[$i]['StakeHolder']['name']);
            $y += 30;
        }
        // page 4
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_CapitalOtherThanCash_template.pdf"); // load template
        $tplIdx = $pdf->importPage(4); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
        $pdf->SetXY(83,18);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetXY(66.5,47);
        $pdf->Write(10,$data['articleNo']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(115,23.5);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetXY(91.5,34.5);
        $pdf->Write(10,$company['Company']['address_1']." ".$company['Company']['address_2']);
        $pdf->SetFont('Helvetica','',11);
         $pdf->SetXY(20,74.1);
        $pdf->Write(10, $data['maddress']);
        $pdf->SetXY(104,136);
        $pdf->Write(10,$data['totalCash']);
        $pdf->SetXY(142,142);
        $pdf->Write(10,$data['totalSharesAlloted']);
        $pdf->SetXY(88,170);
        $pdf->Write(10,$data['totalSharesAlloted']);
        
        $pdf->SetFont('Helvetica','',9);
        $y = 240;
            for($i = 0;$i<count($asIsDirectors);$i=$i+2){
                    
                        $x = 27;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsDirectors[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsDirectors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsDirectors[$i+1]['StakeHolder']['name']);  
                        }
                $y += 20;
            }
         // page 5
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_CapitalOtherThanCash_template.pdf"); // load template
        $tplIdx = $pdf->importPage(5); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
         $pdf->SetFont('Helvetica','',12);
        $pdf->SetXY(81,13);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(117,18.2);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetXY(92,27.8);
        $pdf->Write(10,$company['Company']['address_1']." ".$company['Company']['address_2']);
        $pdf->SetXY(29,38);
        $pdf->Write(10,$name);
        $pdf->SetFont('Helvetica','',10);
         $pdf->SetXY(22,63);
        $pdf->Write(10, $data['maddress']);
        $pdf->SetXY(104,144.3);
        $pdf->Write(10,$data['totalCash']);
        $pdf->SetXY(142,150.2);
        $pdf->Write(10,$data['totalSharesAlloted']);

        $pdf->SetXY(18,198);
        $pdf->Write(10,$data["chairman"]);
        // page 6
        $pdf->addPage(); // add page
        $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "EOGM_CapitalOtherThanCash_template.pdf"); // load template
        $tplIdx = $pdf->importPage(6); // import page 1esolution_template
        $pdf->useTemplate($tplIdx, null, null, 0,0,true); // place the template 
        // write company name
         $pdf->SetFont('Helvetica','',12);
        $pdf->SetXY(81,19);
        $pdf->Write(10,$company['Company']['name']);
        $pdf->SetFont('Helvetica','',9);
        $pdf->SetXY(117,23.2);
        $pdf->Write(10, $company['Company']['register_number']);
        $pdf->SetFont('Helvetica','',10);
        $pdf->SetXY(88,88);
        $pdf->Write(10,$data['totalCash']);
        $pdf->SetXY(129,93);
        $pdf->Write(10,$data['totalSharesAlloted']);
        $y = 175;
            for($i = 0;$i<count($asIsDirectors);$i=$i+2){
                    
                        $x = 27;
                        $pdf->SetXY($x,$y);
                         $pdf->Line($x,$y-1,$x+40,$y-1);
                        $pdf->Write(10,$asIsDirectors[$i]['StakeHolder']['name']);
                        if($i+1<count($asIsDirectors)){
                            $k = 140;
                            //ChromePhp::log($y);
                            $pdf->SetXY($k,$y);
                             $pdf->Line($k,$y-1,$k+40,$y-1);
                            $pdf->Write(10,$asIsDirectors[$i+1]['StakeHolder']['name']);  
                        }
                $y += 35;
            }
        $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
        $data_pdf = array(
            'form_id' => $form_id,
            'company_id' => $company['Company']['company_id'],
            'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
            'created_at' => date('Y-m-d H:i:s')
        );

     array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    public function generateForm11IncreaseNonCashCapital($data){
        $pdf_name = 'Form11'.time();
         $ids = $data['stakeholder'];
        $company = $this->Company->find('first',array('conditions'=>array('company_id = '=> $data['company'])));
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form11_nonCashCapital_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
            // write company name
            $pdf->SetFont('Helvetica','',9 );
            $pdf->SetXY(60,74);
            $pdf->Write(10, $company ['Company']['name']);
            $pdf->SetXY(52,78);
            $pdf->Write(10, $company['Company']['register_number']);
            

            $pdf->SetXY(68,108);
            $pdf->Write(10, $data['maddress']);
            $asIsStakeHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.company_id"=>$data['company']
                    )
                ));
            $avail_ids=array();
            foreach($asIsStakeHolders as $asIsStakeHolder){
                array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
            };
            $asIsDirectors = $this->Director->find("all",array(
                "conditions"=>array(
                    "Director.Mode"=>"appointed",
                    "Director.id"=>$avail_ids
                )
            ));
            $name = "";
            foreach($asIsDirectors as $director){
                $name .= $director['StakeHolder']['name']." ";
            }
            $pdf->SetFont('Helvetica','',8);
            $pdf->SetXY(45,150);
            $pdf->Write(10, $name); 
            $pdf->SetFont('Helvetica','',9 );
            $pdf->SetXY(46,166.3);
            $pdf->Write(10,"DIRECTORS");
            $pdf->SetXY(140,193);
            $pdf->Write(10,$data['nameDS']);
            $pdf->SetXY(41.8,230.2);
            $pdf->Write(10,$company['Company']['LOName']); 
             $pdf->SetXY(43.8,235.8);
            $pdf->Write(10,$company['Company']['LOAddressline1']); 
            $pdf->SetXY(43.8,239.8);
            $pdf->Write(10,$company['Company']['LOAddressline2']);
             $pdf->SetXY(90,247.3);
            $pdf->Write(10,$company['Company']['LOTelNo']); 
             $pdf->SetXY(90,250.2);
            $pdf->Write(10,$company['Company']['LOTelFax']); 
            
            
            $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');

		// save to database
	
		$data_pdf = array(
			'form_id' => 7,
			'company_id' => $data['company'],
			'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
			'created_at' => date('Y-m-d H:i:s')
		);
		
                array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    public function generateForm24IncreaseNonCashCapital($data){
        $pdf_name = 'Form24'.time(); 
        $ids = $data['stakeholder'];
        $company = $this->Company->find('first',array('conditions'=>array('company_id = '=> $data['company'])));
        $shareholders = $this->StakeHolder->find("all",array(
            "conditions"=>array(
                "StakeHolder.id"=>$ids
            )
        ));
       $asIsStakeHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.company_id"=>$data['company']
                    )
                ));
        $avail_ids=array();
        foreach($asIsStakeHolders as $asIsStakeHolder){
            array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
        };
        $asIsDirectors = $this->Director->find("all",array(
            "conditions"=>array(
                "Director.Mode"=>"appointed",
                "Director.id"=>$avail_ids
            )
        ));

        $name = "";
        for($i = 0;$i < count($shareholders);$i++){
            $name .= $shareholders[$i]['StakeHolder']['name'].",";

        };
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "Form24_NonCashCapital_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',10 );
            $pdf->SetXY(58.1,58.9);
            $pdf->Write(10, $company['Company']['name']);
            $pdf->SetXY(51.2,62.9);
            $pdf->Write(10, $company['Company']['register_number']);
            $pdf->SetXY(111,167);
            $pdf->Write(10,$data['totalCash']);
            $pdf->SetXY(113.3,172);
            $pdf->Write(10,$data['eachShare']);            
            $pdf->SetXY(113.0,177.6);
            $pdf->Write(10,$data['eachShare']); 
            $pdf->SetXY(86,189);
            $pdf->Write(10,$data['totalCash']);
            $pdf->SetXY(57.3,193);
            $pdf->Write(10,$data['totalSharesAlloted']);
             $pdf->SetXY(41.8,234.4);
            $pdf->Write(10,$company['Company']['LOName']); 
             $pdf->SetXY(43.8,238.9);
            $pdf->Write(10,$company['Company']['LOAddressline1']); 
            $pdf->SetXY(43.8,241.9);
            $pdf->Write(10,$company['Company']['LOAddressline2']);
             $pdf->SetXY(84.7,246.3);
            $pdf->Write(10,$company['Company']['LOTelNo']); 
             $pdf->SetXY(84.7,250.2);
            $pdf->Write(10,$company['Company']['LOTelFax']); 
            
             // page 2
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "Form24_NonCashCapital_template.pdf"); // load template
            $tplIdx = $pdf->importPage(2); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',10 );
            $pdf->SetXY(57.8,32.9);
            $pdf->Write(10, $company['Company']['name']);
            $pdf->SetXY(50.9,40.0);
            $pdf->Write(10, $company['Company']['register_number']);
            $y=75.2;
            $x=36.8;
            for($i = 0;$i<count($shareholders);$i++){
                $pdf->SetXY($x,$y);
                $pdf->Write(10,$shareholders[$i]['StakeHolder']['name']);
                $pdf->SetXY($x,$y+4);
                $pdf->Write(10,$shareholders[$i]['StakeHolder']['address_1']);
                $pdf->SetXY($x,$y+8);
                $pdf->Write(10,$shareholders[$i]['StakeHolder']['address_2']);
                $pdf->SetXY($x,$y+12);
                $pdf->Write(10,$shareholders[$i]['StakeHolder']['nric']);
                $pdf->SetXY($x,$y+16);
                $pdf->Write(10,$shareholders[$i]['StakeHolder']['nationality']);
                $pdf->SetXY($x+115.4,$y);
                $pdf->Write(10,$data['SharesAlloted'][$i]);
                $pdf->SetXY($x+105.4,$y+4);
                $pdf->Write(10,"ORDINARY");
                $y += 28;
            }
//            
////
             // page 3
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form24_IncreaseShares_template.pdf"); // load template
            $tplIdx = $pdf->importPage(3); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',10 );
            $pdf->SetXY(58.5,28.7);
            $pdf->Write(10, $company['Company']['name']);
            $pdf->SetXY(57.5,36.0);
            $pdf->Write(10,$company['Company']['register_number']);
            

             $pdf->SetFont('Helvetica','',8);
            $pdf->SetXY(102.4,75.2);
            $pdf->Write(10, $data['totalIssuedShare']); 
            
            $pdf->SetXY(102.4,83.3);
            $pdf->Write(10, $data['totalIssuedShare']);  
            $pdf->SetFont('Helvetica','',10 );
            $pdf->SetXY(116.7,209.9);
            $pdf->Write(10, $data['chairman']);
             $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
             // save to database
        //$this->Pdf->create();
        $data_pdf = array(
                'form_id' => 10,
                'company_id' => $company['Company']['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
        //$this->Pdf->save($data_pdf);
        array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    public function generateForm25IncreaseNonCashCapital($data){
        $pdf_name = 'Form25'.time(); 
        $ids = $data['stakeholder'];
        $company = $this->Company->find('first',array('conditions'=>array('company_id = '=> $data['company'])));
        $shareholders = $this->StakeHolder->find("all",array(
            "conditions"=>array(
                "StakeHolder.id"=>$ids
            )
        ));
       $asIsStakeHolders = $this->StakeHolder->find("all",array(
                    "conditions"=>array(
                        "StakeHolder.company_id"=>$data['company']
                    )
                ));
        $avail_ids=array();
        foreach($asIsStakeHolders as $asIsStakeHolder){
            array_push($avail_ids,$asIsStakeHolder['StakeHolder']['id']);
        };
        $asIsDirectors = $this->Director->find("all",array(
            "conditions"=>array(
                "Director.Mode"=>"appointed",
                "Director.id"=>$avail_ids
            )
        ));

        $name = "";
        for($i = 0;$i < count($shareholders);$i++){
            $name .= $shareholders[$i]['StakeHolder']['name'].",";

        };
            // generate PDF
		$pdf = new FPDI(); // init
		// create overlays
		$pdf->SetFont('Helvetica','',9); // set font type
		$pdf->SetTextColor(0, 0, 0); // set font color
            // page 1
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form25_IncreaseNonCashCapital_template.pdf"); // load template
            $tplIdx = $pdf->importPage(1); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',10 );
            $pdf->SetXY(58.1,68.3);
            $pdf->Write(10, $company['Company']['name']);
            $pdf->SetXY(51.2,72.9);
            $pdf->Write(10, $company['Company']['register_number']);
             $pdf->SetXY(41.8,238.4);
            $pdf->Write(10,$company['Company']['LOName']); 
             $pdf->SetXY(43.8,244.9);
            $pdf->Write(10,$company['Company']['LOAddressline1']); 
            $pdf->SetXY(43.8,247.9);
            $pdf->Write(10,$company['Company']['LOAddressline2']);
             $pdf->SetXY(90.7,250.8);
            $pdf->Write(10,$company['Company']['LOTelNo']); 
             $pdf->SetXY(90.7,255.8);
            $pdf->Write(10,$company['Company']['LOTelFax']); 
            
             // page 2
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form25_IncreaseNonCashCapital_template.pdf"); // load template
            $tplIdx = $pdf->importPage(2); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',10 );
            $pdf->SetXY(59.8,34.9);
            $pdf->Write(10, $company['Company']['name']);
            $pdf->SetXY(50.9,39.0);
            $pdf->Write(10, $company['Company']['register_number']);
            $pdf->SetXY(99,96);
            $pdf->Write(10,$data['totalCash']);
            $pdf->SetXY(139,100);
            $pdf->Write(10,$data['totalSharesAlloted']);
            $pdf->SetXY(83,130.5);
            $pdf->Write(10,$data['totalSharesAlloted']);
             // page 3
            $pdf->addPage(); // add page
            $pageCount = $pdf->setSourceFile(WWW_ROOT . $this->template_path . "form25_IncreaseNonCashCapital_template.pdf"); // load template
            $tplIdx = $pdf->importPage(3); 
            $pdf->useTemplate($tplIdx, 10, 10, 200); // place the template 
           
            // write company name
            $pdf->SetFont('Helvetica','',10 );
            $pdf->SetXY(58.9,36.1);
            $pdf->Write(10, $company['Company']['name']);
            $pdf->SetXY(50.9,40.1);
            $pdf->Write(10,$company['Company']['register_number']);
            $pdf->SetFont('Helvetica','',10 );
            $pdf->SetXY(141.7,176.9);
            $pdf->Write(10, $data['chairman']);
             $pdf->Output(WWW_ROOT . $this->pdf_path . $pdf_name .'.pdf', 'F');
             // save to database
        //$this->Pdf->create();
        $data_pdf = array(
                'form_id' => 32,
                'company_id' => $company['Company']['company_id'],
                'pdf_url' => $this->pdf_path . $pdf_name .'.pdf',
                'created_at' => date('Y-m-d H:i:s')
        );
        //$this->Pdf->save($data_pdf);
        array_push($this->form_downloads,$data_pdf['pdf_url']);
    }
    public function generateLetterAllotmentIncreaseNonCashCapital($data){
        
    }
}