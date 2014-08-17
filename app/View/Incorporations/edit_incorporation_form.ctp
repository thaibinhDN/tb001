<form action="<?php echo $this->Html->url(array('controller' => 'Incorporations', 'action' => 'generateIncorporationForms')); ?>" method="post" accept-charset="utf-8">
<div class="form-header" data-toggle="collapse" data-target="#company_info">
	Company information
</div>
<div class="form-content collapse in" id="company_info">
    <label>Company Name</label>
    <input class="form-control" name="Cname" value="<?php echo $company_info['Company']['name'] ?>"/> 
    <input class="form-control" type="hidden" name="existCname" value="<?php echo $company_info['Company']['name'] ?>"/> 
    <br>
<!--    <label>Company number</label>
    <input class="form-control" name="Cnumber" value="20008888A"/> 
    <br>-->
    <br>
    <label>Registered Office</label> 
    <br>
    <label>Address line 1</label>
    <input class="form-control" name="Raddress1" value="<?php echo $company_info['Company']['address_1'] ?>"/> 
    <br>
    <label>Address line 2</label>
    <input class="form-control" name="Raddress2" value="<?php echo $company_info['Company']['address_2'] ?>"/> 
    <br>
    <br>
     <label>Principal Activity 1</label>
    <input class="form-control" name="Pactivity1" value="<?php echo $company_info['Company']['PrincipalActivity1'] ?>"/> 
    <br>
     <label>Description</label>
    <input class="form-control" name="Pactivity1description" value="<?php echo $company_info['Company']['P1Description'] ?>" /> 
    <br>
      <label>Principal Activity 2</label>
    <input class="form-control" name="Pactivity2" value="<?php echo $company_info['Company']['PrincipalActivity2'] ?>"/> 
    <br>
     <label>Description</label>
    <input class="form-control" name="Pactivity2description" value="<?php echo $company_info['Company']['P2Description'] ?>"/> 
    <br>   
    <label>Financial Year</label>
    <input class="form-control datepicker" name="financial_year" value="<?php echo $company_info['Company']['FinancialYear'] ?>" required/> 
    <br>  
</div>
<div class="form-header" data-toggle="collapse" data-target="#shares_info">
	Shares information
</div>
<div class="form-content collapse" id="shares_info">
    <div class="row">
        <div class="col-md-6">
            <h4>Shares(Payable in cash)</h4>
            <label>Currency:</label>
             <input class="form-control" name="Ccurrency" value="<?php echo $company_info['Company']['Currency'] ?>"/> 
            <br>
            <label>Number of Shares:</label>
             <input class="form-control" name="Cnoshares" value="<?php echo $company_info['Company']['NumberOfShares'] ?>"/> 
            <br>
            <label>Nominal Amount of each share:</label>
             <input class="form-control" name="CnominalAmount" value="<?php echo $company_info['Company']['NominalAmountOfEachShare'] ?>"/> 
            <br>
            <label>Amount paid:</label>
             <input class="form-control" name="CamountPaid" value="<?php echo $company_info['Company']['AmountPaid'] ?>"/> 
            <br>
            <label>Due & Payable:</label>
             <input class="form-control" name="CduePayable" value="<?php echo $company_info['Company']['Due&Payable'] ?>"/> 
            <br>
            <label>Amount Premium Paid:</label>
             <input class="form-control" name="CamountPremiumPaid" value="<?php echo $company_info['Company']['AmountPremiumPaid'] ?>"/> 
            <br>
            <label>Authorised Share Capital:</label>
             <input class="form-control" name="CauthorizedShareCapital" value="<?php echo $company_info['Company']['AuthorizedShareCapital'] ?>"/> 
            <br>
            <label>Issued Share Capital:</label>
             <input class="form-control" name="CissuedShareCapital" value="<?php echo $company_info['Company']['IssuedShareCapital'] ?>"/> 
            <br>
            <label>Paid up Share Capital:</label>
             <input class="form-control" name="CpaidupShareCapital" value="<?php echo $company_info['Company']['PaidupShareCapital'] ?>"/> 
            <br>
            <label>Issued Ordinary:</label>
             <input class="form-control" name="CIssuedOrdinary" value="<?php echo $company_info['Company']['IssuedOrdinary'] ?>"/> 
            <br>
            <label>Paid-Up Ordinary:</label>
             <input class="form-control" name="CPaidupOrdinary" value="<?php echo $company_info['Company']['PaidUpOrdinary'] ?>"/> 
        </div>
        <div class="col-md-6">
            <h4>Shares(Other than cash)</h4>
            <label>Currency:</label>
             <input class="form-control" name="NCcurrency" value=""/> 
            <br>
            <label>Number of Shares:</label>
             <input class="form-control" name="NCnoshares" value=""/> 
            <br>
            <label>Nominal Amount of each share:</label>
             <input class="form-control" name="NCnominalAmount" value=""/> 
            <br>          
            <label>Amount to be Treated as in paid in each share:</label>
             <input class="form-control" name="NCamountTreatedAsPaid" value=""/> 
            <br>
             <label>Amount paid:</label>
             <input class="form-control" name="NCamountPaid" value=""/> 
            <br>
            <label>Authorised Share Capital:</label>
             <input class="form-control" name="NCauthorizedShareCapital" value=""/> 
            <br>
            <label>Issued Share Capital:</label>
             <input class="form-control" name="NCissuedShareCapital" value=""/> 
            <br>
            <label>Paid up Share Capital:</label>
             <input class="form-control" name="NCpaidupShareCapital" value=""/> 
            <br>
            <label>Issued Ordinary:</label>
             <input class="form-control" name="NCIssuedOrdinary" value=""/> 
            <br>
            <label>Paid-Up Ordinary:</label>
             <input class="form-control" name="NCPaidupOrdinary" value=""/>
        </div>
        
    </div>  
</div>
<div class="form-header" data-toggle="collapse" data-target="#directors_info">
    Directors information
</div>
<div class="form-content collapse" id="directors_info">
    <?php for($i = 0;$i < count($directors);$i = $i + 2) {?>
         
    
    <div class="row">
        <div class="col-md-6">
            <h4><?php echo "Director"." ".($i+1) ?></h4>
            <label>Name of the Appoint Director :</label>
            <input class='form-control' name='DNameoftheAppointDirector[]' value="<?php echo $directors[$i]['StakeHolder']['name'] ?>"> 
            <br>
            <label>Address (Singapore):</label>
            <br>
            <label>Addressline 1</label><input class='form-control' name='DAddressline1S[]' value="<?php echo $directors[$i]['Director']['addressline1_Singapore'] ?>"><br>
            <label>Addressline 2</label><input class='form-control' name='DAddressline2S[]' value="<?php echo $directors[$i]['Director']['addressline2_Singapore'] ?>"><br>
            <label>Addressline 3</label><input class='form-control' name='DAddressline3S[]' value="<?php echo $directors[$i]['Director']['addressline3_Singapore'] ?>"><br>
            <label>Address (Oversea):</label><br>
            <label>Addressline 1</label><input class='form-control' name='DAddressline1OS[]' value="<?php echo $directors[$i]['Director']['addressline1_OverSea'] ?>"><br>
            <label>Addressline 2</label><input class='form-control' name='DAddressline2OS[]' value="<?php echo $directors[$i]['Director']['addressline2_OverSea'] ?>"><br>
            <label>Addressline 3</label><input class='form-control' name='DAddressline3OS[]' value="<?php echo $directors[$i]['Director']['addressline3_OverSea'] ?>"><br>
            <label>Address (Others):</label><br>
            <label>Addressline 1</label><input class='form-control' name='DAddressline1OT[]' value="<?php echo $directors[$i]['Director']['addressline1_Other'] ?>"></br>
            <label>Addressline 2</label><input class='form-control' name='DAddressline2OT[]' value="<?php echo $directors[$i]['Director']['addressline2_Other'] ?>"></br>
            <label>Addressline 3</label><input class='form-control' name='DAddressline3OT[]'value="<?php echo $directors[$i]['Director']['addressline3_Other'] ?>"><br>
            <label>Class of Shares:</label><input class='form-control' name='DClassofShares[]'value="<?php echo $directors[$i]['Director']['ClassofShares'] ?>"><br>
            <label>Number of Shares:</label><input class='form-control' name='DNumberofShares[]' value="<?php echo $directors[$i]['Director']['NumberofShares'] ?>"><br>
            <label>Number of Shares In words</label><input class='form-control' name='DNumberofSharesInwords[]' value="<?php echo $directors[$i]['Director']['NumberofSharesInwords'] ?>"><br>
            <label>Certificate No:</label><input class='form-control' name='DCertificateNo[]' value="<?php echo $directors[$i]['Director']['CertificateNo'] ?>"><br>
            <label>NRIC / Passport:</label><input class='form-control' name='DNRIC/Passport[]' value="<?php echo $directors[$i]['StakeHolder']['nric'] ?>"><br>
            <label>Nationality at Birth:</label><input class='form-control' name='DNationalityatBirth[]' value="<?php echo $directors[$i]['Director']['NationalityatBirth'] ?>"><br>
            <label>Nationality Current :</label><input class='form-control' name='DNationalityCurrent[]' value="<?php echo $directors[$i]['StakeHolder']['nationality'] ?>"><br>
            <label>Occupation:</label><input class='form-control' name='DOccupation[]' value="<?php echo $directors[$i]['Director']['Occupation'] ?>"><br>
            <label>Date of Birth:</label><input class='form-control' name='DDateofBirth[]' value="<?php echo $directors[$i]['Director']['DateofBirth'] ?>"><br>
            <label>Currency:</label><input class='form-control' name='DCurrency[]' value="<?php echo $directors[$i]['Director']['Currency'] ?>"><br>
            <label>Place of birth:</label><input class='form-control' name='DPlaceofbirth[]' value="<?php echo $directors[$i]['Director']['Placeofbirth'] ?>"><br>
            <label>Nric date of issue:</label><input class='form-control' name='DNricdateofissues[]' value="<?php echo $directors[$i]['Director']['Nricdateofissue'] ?>"><br>
            <label>Nric place of issue:</label><input class='form-control' name='DNricplaceofissue[]' value="<?php echo $directors[$i]['Director']['nricplaceofissue'] ?>"><br>
            <label>Passport  no:</label><input class='form-control' name='DPassportno[]' value="<?php echo $directors[$i]['Director']['passportno'] ?>"><br>
            <label>Passport Date Of  Issue:</label><input class='form-control' name='DPassportDateOfIssue[]' value="<?php echo $directors[$i]['Director']['passportdateofissue'] ?>"><br>
            <label>Passport Place Of  Issue:</label><input class='form-control' name='DPassportPlaceOfIssue[]' value="<?php echo $directors[$i]['Director']['passportplaceofissue'] ?>"><br>
            <label>Nature of Contract:</label><input class='form-control' name='DNatureofContract[]' value="<?php echo $directors[$i]['Director']['NatureOfContract'] ?>"><br>
            <label>Remarks:</label><input class='form-control' name='DRemarks[]' value="<?php echo $directors[$i]['Director']['Remarks'] ?>"><br>
            <label>Consent to act as director of the company</label><input class='form-control' name='DConsenttoactasdirectorofthecompany[]' value="<?php echo $directors[$i]['Director']['ConsentToActAsDirector'] ?>"><br>
            <label>Former Name if any:</label><input class='form-controlr' name='DFormerNameifany[]' value="<?php echo $directors[$i]['Director']['FormerName'] ?>"><br>

                
        </div>
        <?php if(($i + 1) < count($directors)){ ?>
        
        <div class="col-md-6">
            <h4><?php echo "Director"." ".($i+2) ?></h4>
           <label>Name of the Appoint Director :</label>
            <input class='form-control' name='DNameoftheAppointDirector[]' value="<?php echo $directors[$i+1]['StakeHolder']['name'] ?>"> 
            <br>
            <label>Address (Singapore):</label>
            <br>
            <label>Addressline 1</label><input class='form-control' name='DAddressline1S[]' value="<?php echo $directors[$i+1]['Director']['addressline1_Singapore'] ?>"><br>
            <label>Addressline 2</label><input class='form-control' name='DAddressline2S[]' value="<?php echo $directors[$i+1]['Director']['addressline2_Singapore'] ?>"><br>
            <label>Addressline 3</label><input class='form-control' name='DAddressline3S[]' value="<?php echo $directors[$i+1]['Director']['addressline3_Singapore'] ?>"><br>
            <label>Address (Oversea):</label><br>
            <label>Addressline 1</label><input class='form-control' name='DAddressline1OS[]' value="<?php echo $directors[$i+1]['Director']['addressline1_OverSea'] ?>"><br>
            <label>Addressline 2</label><input class='form-control' name='DAddressline2OS[]' value="<?php echo $directors[$i+1]['Director']['addressline2_OverSea'] ?>"><br>
            <label>Addressline 3</label><input class='form-control' name='DAddressline3OS[]' value="<?php echo $directors[$i+1]['Director']['addressline3_OverSea'] ?>"><br>
            <label>Address (Others):</label><br>
            <label>Addressline 1</label><input class='form-control' name='DAddressline1OT[]' value="<?php echo $directors[$i+1]['Director']['addressline1_Other'] ?>"></br>
            <label>Addressline 2</label><input class='form-control' name='DAddressline2OT[]' value="<?php echo $directors[$i+1]['Director']['addressline2_Other'] ?>"></br>
            <label>Addressline 3</label><input class='form-control' name='DAddressline3OT[]'value="<?php echo $directors[$i+1]['Director']['addressline3_Other'] ?>"><br>
            <label>Class of Shares:</label><input class='form-control' name='DClassofShares[]'value="<?php echo $directors[$i+1]['Director']['ClassofShares'] ?>"><br>
            <label>Number of Shares:</label><input class='form-control' name='DNumberofShares[]' value="<?php echo $directors[$i+1]['Director']['NumberofShares'] ?>"><br>
            <label>Number of Shares In words</label><input class='form-control' name='DNumberofSharesInwords[]' value="<?php echo $directors[$i+1]['Director']['NumberofSharesInwords'] ?>"><br>
            <label>Certificate No:</label><input class='form-control' name='DCertificateNo[]' value="<?php echo $directors[$i+1]['Director']['CertificateNo'] ?>"><br>
            <label>NRIC / Passport:</label><input class='form-control' name='DNRIC/Passport[]' value="<?php echo $directors[$i+1]['StakeHolder']['nric'] ?>"><br>
            <label>Nationality at Birth:</label><input class='form-control' name='DNationalityatBirth[]' value="<?php echo $directors[$i+1]['Director']['NationalityatBirth'] ?>"><br>
            <label>Nationality Current :</label><input class='form-control' name='DNationalityCurrent[]' value="<?php echo $directors[$i+1]['StakeHolder']['nationality'] ?>"><br>
            <label>Occupation:</label><input class='form-control' name='DOccupation[]' value="<?php echo $directors[$i+1]['Director']['Occupation'] ?>"><br>
            <label>Date of Birth:</label><input class='form-control' name='DDateofBirth[]' value="<?php echo $directors[$i+1]['Director']['DateofBirth'] ?>"><br>
            <label>Currency:</label><input class='form-control' name='DCurrency[]' value="<?php echo $directors[$i+1]['Director']['Currency'] ?>"><br>
            <label>Place of birth:</label><input class='form-control' name='DPlaceofbirth[]' value="<?php echo $directors[$i+1]['Director']['Placeofbirth'] ?>"><br>
            <label>Nric date of issue:</label><input class='form-control' name='DNricdateofissues[]' value="<?php echo $directors[$i+1]['Director']['Nricdateofissue'] ?>"><br>
            <label>Nric place of issue:</label><input class='form-control' name='DNricplaceofissue[]' value="<?php echo $directors[$i+1]['Director']['nricplaceofissue'] ?>"><br>
            <label>Passport  no:</label><input class='form-control' name='DPassportno[]' value="<?php echo $directors[$i+1]['Director']['passportno'] ?>"><br>
            <label>Passport Date Of  Issue:</label><input class='form-control' name='DPassportDateOfIssue[]' value="<?php echo $directors[$i+1]['Director']['passportdateofissue'] ?>"><br>
            <label>Passport Place Of  Issue:</label><input class='form-control' name='DPassportPlaceOfIssue[]' value="<?php echo $directors[$i+1]['Director']['passportplaceofissue'] ?>"><br>
            <label>Nature of Contract:</label><input class='form-control' name='DNatureofContract[]' value="<?php echo $directors[$i+1]['Director']['NatureOfContract'] ?>"><br>
            <label>Remarks:</label><input class='form-control' name='DRemarks[]' value="<?php echo $directors[$i+1]['Director']['Remarks'] ?>"><br>
            <label>Consent to act as director of the company</label><input class='form-control' name='DConsenttoactasdirectorofthecompany[]' value="<?php echo $directors[$i+1]['Director']['ConsentToActAsDirector'] ?>"><br>
            <label>Former Name if any:</label><input class='form-controlr' name='DFormerNameifany[]' value="<?php echo $directors[$i+1]['Director']['FormerName'] ?>"><br>
        </div>
        <?php } ?>
    </div>
    <?php } ?>
</div>  
<div class="form-header" data-toggle="collapse" data-target="#shareholders_info">
   ShareHolders information
</div>
<div class="form-content collapse" id="shareholders_info">
    <?php for($i = 0;$i < count($shareholders);$i = $i + 2) {?>
    <div class="row">
        <div class="col-md-6">
            <h4><?php echo "ShareHolder"." ".($i+1) ?></h4>
            <label>Name of the Shareholder  :</label><input class='form-control' name='SNameoftheShareholder[]' value="<?php echo $shareholders[$i]['StakeHolder']['name'] ?>"><br>
            <label>Address (Singapore):</label><br>
            <label>Addressline 1</label><input class='form-control' name='SAddressline1S[]' value="<?php echo $shareholders[$i]['ShareHolder']['addressline1_Singapore'] ?>"><br>
            <label>Addressline 2</label><input class='form-control' name='SAddressline2S[]' value="<?php echo $shareholders[$i]['ShareHolder']['addressline2_Singapore'] ?>"><br>
            <label>Addressline 3</label><input class='form-control' name='SAddressline3S[]' value="<?php echo $shareholders[$i]['ShareHolder']['addressline3_Singapore'] ?>"><br>
            <label>Address (Oversea):</label><br>
            <label>Addressline 1</label><input class='form-control' name='SAddressline1OS[]' value="<?php echo $shareholders[$i]['ShareHolder']['addressline1_OverSea'] ?>"><br>
            <label>Addressline 2</label><input class='form-control' name='SAddressline2OS[]' value="<?php echo $shareholders[$i]['ShareHolder']['addressline2_OverSea'] ?>"><br>
            <label>Addressline 3</label><input class='form-control' name='SAddressline3OS[]' value="<?php echo $shareholders[$i]['ShareHolder']['addressline3_OverSea'] ?>"><br>
            <label>Address (Others):</label><br>
            <label>Addressline 1</label><input class='form-control' name='SAddressline1OT[]' value="<?php echo $shareholders[$i]['ShareHolder']['addressline1_Other'] ?>"><br>
            <label>Addressline 2</label><input class='form-control' name='SAddressline2OT[]' value="<?php echo $shareholders[$i]['ShareHolder']['addressline2_Other'] ?>"><br>
            <label>Addressline 3</label><input class='form-control' name='SAddressline3OT[]' value="<?php echo $shareholders[$i]['ShareHolder']['addressline3_Other'] ?>"><br>
            <label>NRIC / Passport:</label><input class='form-control' name='SNRIC/Passport[]' value="<?php echo $shareholders[$i]['StakeHolder']['nric'] ?>"><br>
            <label>Nationality at Birth:</label><input class='form-control' name='SNationalityatBirth[]' value="<?php echo $shareholders[$i]['ShareHolder']['NationalityatBirth'] ?>"><br>
            <label>Nationality Current :</label><input class='form-control' name='SNationalityCurrent[]' value="<?php echo $shareholders[$i]['StakeHolder']['nationality'] ?>"><br>
            <label>Occupation:</label><input class='form-control' name='SOccupation[]' value="<?php echo $shareholders[$i]['ShareHolder']['Occupation'] ?>"><br>
            <label>Number of Shares:</label><input class='form-control' name='SNumberofShares[]' value="<?php echo $shareholders[$i]['ShareHolder']['NumberofShares'] ?>"><br>
            <label>Number of Shares In words</label><input class='form-control' name='SNumberofSharesInwords[]' value="<?php echo $shareholders[$i]['ShareHolder']['NumberofSharesInwords'] ?>"><br>
            <label>Certificate No:</label><input class='form-control' name='SCertificateNo[]' value="<?php echo $shareholders[$i]['ShareHolder']['CertificateNo'] ?>"><br>
            <label>Members Register No:</label><input class='form-control' name='SMembersRegisterNo[]' value="<?php echo $shareholders[$i]['ShareHolder']['MembersRegisterNo'] ?>"><br>
            <label>Date of Birth:</label><input class='form-control' name='SDateofBirth[]' value="<?php echo $shareholders[$i]['ShareHolder']['DateofBirth'] ?>"><br>
            <label>Class of Shares:</label><input class='form-control' name='SClassofShares[]' value="<?php echo $shareholders[$i]['ShareHolder']['ClassofShares'] ?>"><br>
            <label>Currency:</label><input class='form-control' name='SCurrency[]' value="<?php echo $shareholders[$i]['ShareHolder']['Currency'] ?>"><br>
            <label>Place of birth:</label><input class='form-control' name='SPlaceofbirth[]' value="<?php echo $shareholders[$i]['ShareHolder']['Placeofbirth'] ?>"><br>
            <label>Nric date of issue:</label><input class='form-control' name='SNricdateofissue[]' value="<?php echo $shareholders[$i]['ShareHolder']['Nricdateofissue'] ?>"><br>
            <label>nric place of issue:</label><input class='form-control' name='Snricplaceofissue[]' value="<?php echo $shareholders[$i]['ShareHolder']['nricplaceofissue'] ?>"><br>
            <label>passport no:</label><input class='form-control' name='Spassportno[]' value="<?php echo $shareholders[$i]['ShareHolder']['passportno'] ?>"><br>
            <label>passport date of issue:</label><input class='form-control' name='Spassportdateofissue[]' value="<?php echo $shareholders[$i]['ShareHolder']['passportdateofissue'] ?>"><br>
            <label>passport place of issue:</label><input class='form-control' name='Spassportplaceofissue[]' value="<?php echo $shareholders[$i]['ShareHolder']['passportplaceofissue'] ?>"><br>
            <label>Remarks:</label><input class='form-control' name='SRemarks[]' value="<?php echo $shareholders[$i]['ShareHolder']['Remarks'] ?>"><br>
        </div>
        <?php if(($i + 1) < count($shareholders)){ ?>
        <div class="col-md-6">
             <h4><?php echo "ShareHolder"." ".($i+2) ?></h4>
            <label>Name of the Shareholder  :</label><input class='form-control' name='SNameoftheShareholder[]' value="<?php echo $shareholders[$i+1]['StakeHolder']['name'] ?>"><br>
            <label>Address (Singapore):</label><br>
            <label>Addressline 1</label><input class='form-control' name='SAddressline1S[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['addressline1_Singapore'] ?>"><br>
            <label>Addressline 2</label><input class='form-control' name='SAddressline2S[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['addressline2_Singapore'] ?>"><br>
            <label>Addressline 3</label><input class='form-control' name='SAddressline3S[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['addressline3_Singapore'] ?>"><br>
            <label>Address (Oversea):</label><br>
            <label>Addressline 1</label><input class='form-control' name='SAddressline1OS[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['addressline1_OverSea'] ?>"><br>
            <label>Addressline 2</label><input class='form-control' name='SAddressline2OS[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['addressline2_OverSea'] ?>"><br>
            <label>Addressline 3</label><input class='form-control' name='SAddressline3OS[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['addressline3_OverSea'] ?>"><br>
            <label>Address (Others):</label><br>
            <label>Addressline 1</label><input class='form-control' name='SAddressline1OT[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['addressline1_Other'] ?>"><br>
            <label>Addressline 2</label><input class='form-control' name='SAddressline2OT[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['addressline2_Other'] ?>"><br>
            <label>Addressline 3</label><input class='form-control' name='SAddressline3OT[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['addressline3_Other'] ?>"><br>
            <label>NRIC / Passport:</label><input class='form-control' name='SNRIC/Passport[]' value="<?php echo $shareholders[$i+1]['StakeHolder']['nric'] ?>"><br>
            <label>Nationality at Birth:</label><input class='form-control' name='SNationalityatBirth[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['NationalityatBirth'] ?>"><br>
            <label>Nationality Current :</label><input class='form-control' name='SNationalityCurrent[]' value="<?php echo $shareholders[$i+1]['StakeHolder']['nationality'] ?>"><br>
            <label>Occupation:</label><input class='form-control' name='SOccupation[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['Occupation'] ?>"><br>
            <label>Number of Shares:</label><input class='form-control' name='SNumberofShares[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['NumberofShares'] ?>"><br>
            <label>Number of Shares In words</label><input class='form-control' name='SNumberofSharesInwords[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['NumberofSharesInwords'] ?>"><br>
            <label>Certificate No:</label><input class='form-control' name='SCertificateNo[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['CertificateNo'] ?>"><br>
            <label>Members Register No:</label><input class='form-control' name='SMembersRegisterNo[]' value="<?php echo $shareholders[$i]['ShareHolder']['MembersRegisterNo'] ?>"><br>
            <label>Date of Birth:</label><input class='form-control' name='SDateofBirth[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['DateofBirth'] ?>"><br>
            <label>Class of Shares:</label><input class='form-control' name='SClassofShares[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['ClassofShares'] ?>"><br>
            <label>Currency:</label><input class='form-control' name='SCurrency[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['Currency'] ?>"><br>
            <label>Place of birth:</label><input class='form-control' name='SPlaceofbirth[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['Placeofbirth'] ?>"><br>
            <label>Nric date of issue:</label><input class='form-control' name='SNricdateofissue[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['Nricdateofissue'] ?>"><br>
            <label>nric place of issue:</label><input class='form-control' name='Snricplaceofissue[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['nricplaceofissue'] ?>"><br>
            <label>passport no:</label><input class='form-control' name='Spassportno[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['passportno'] ?>"><br>
            <label>passport date of issue:</label><input class='form-control' name='Spassportdateofissue[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['passportdateofissue'] ?>"><br>
            <label>passport place of issue:</label><input class='form-control' name='Spassportplaceofissue[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['passportplaceofissue'] ?>"><br>
            <label>Remarks:</label><input class='form-control' name='SRemarks[]' value="<?php echo $shareholders[$i+1]['ShareHolder']['Remarks'] ?>"><br>
        </div>
        <?php } ?>
    </div>
    <?php } ?>
</div>  
<div class="form-header" data-toggle="collapse" data-target="#others_info">
    Other information
</div>
<div class="form-content collapse" id="others_info">
    <div class="row">
        <div class="col-md-6">
            <label>Lodged Office Info:</label><br>
            <label>Name:</label><input class='form-control' name='LOName' value="<?php echo $company_info['Company']['LOName'] ?>"><br>
            <label>Addressline 1</label><input class='form-control' name='LOAddressline1' value="<?php echo $company_info['Company']['LOAddressline1'] ?>"><br>
            <label>Addressline 2</label><input class='form-control' name='LOAddressline2' value="<?php echo $company_info['Company']['LOAddressline2'] ?>"><br>
            <label>A/c No:</label><input class='form-control' name='LOAcNo' value="<?php echo $company_info['Company']['LOAcNo'] ?>"><br>
            <label>Tel No:</label><input class='form-control' name='LOTelNo' value="<?php echo $company_info['Company']['LOTelNo'] ?>"><br>
            <label>Tel Fax:</label><input class='form-control' name='LOTelFax' value="<?php echo $company_info['Company']['LOTelFax'] ?>"><br>
        </div>  
        <div class="col-md-6">
            <label>Article No.:</label><input class='form-control' name='ArticleNo.' value=""><br>
<!--            <label>Date Of Incorporation:</label><input class='form-control' name='DateOfIncorporation' value="<?php echo $company_info['Company'][''] ?>"><br>-->
            <label>First Meeting</label><input class='form-control' name='FirstMeeting' value=""><br>
            <label>Chairman of the Meeting:</label><input class='form-control' name='ChairmanoftheMeeting' value="<?php echo $chairman ?>"><br>
            <label>Subscribers:</label><input class='form-control' name='Subscribers' value="<?php echo $company_info['Company']['suscriberName'] ?>"><br>
            <label>Subscriber's Share </label><input class='form-control' name='Subscriber_Share' value="<?php echo $company_info['Company']['suscriberShares'] ?>"><br>
        </div>
    </div>
    <br>
    <br>
     <div class="row">
         <div class="col-md-6">
             <?php for($i = 0 ; $i < count($secretaries);$i++){ ?>
                 
             
              <label>Name of the Secretary:</label><input class='form-control' name='NameoftheSecretary' value="<?php echo $secretaries[$i]['StakeHolder']['name'] ?>"><br>
            <label>Address :</label><br>
            <label>Addressline 1</label><input class='form-control' name='SEAddressline1' value="<?php echo $secretaries[$i]['StakeHolder']['address_1'] ?>"><br>
            <label>Addressline 2</label><input class='form-control' name='SEAddressline2' value="<?php echo $secretaries[$i]['StakeHolder']['address_2'] ?>"><br>
            <label>Addressline 3</label><input class='form-control' name='SEAddressline3' value=""><br>
            <label>NRIC / Passport:</label><input class='form-control' name='SENRIC/Passport' value="<?php echo $secretaries[$i]['StakeHolder']['nric'] ?>"><br>
            <label>Nationality:</label><input class='form-control' name='SENationality' value="<?php echo $secretaries[$i]['StakeHolder']['nationality'] ?>"><br>
            <label>Occupation:</label><input class='form-control' name='SEOccupation' value="<?php echo $secretaries[$i]['Secretary']['Occupation'] ?>"><br>
            <label>Other Occupation:</label><input class='form-control' name='SEOtherOccupation' value="<?php echo $secretaries[$i]['Secretary']['OtherOccupation'] ?>"><br>
        <?php } ?>
         </div>
         <div class="col-md-6">
             <?php for($i = 0 ; $i < count($auditors);$i++){ ?>
             <label>Name of the Auditor:</label><input class='form-control' name='NameoftheAuditor' value="<?php echo $auditors[$i]['StakeHolder']['name'] ?>"><br>
            <label>Address :</label><br>
            <label>Addressline 1</label><input class='form-control' name='AUAddressline1' value="<?php echo $auditors[$i]['StakeHolder']['address_1'] ?>"><br>
            <label>Addressline 2</label><input class='form-control' name='AUAddressline2' value="<?php echo $auditors[$i]['StakeHolder']['address_2'] ?>"><br>
            <label>Addressline 3</label><input class='form-control' name='AUAddressline3' value=""><br>
            <label>NRIC / Passport:</label><input class='form-control' name='AUNRIC/Passport' value="<?php echo $auditors[$i]['StakeHolder']['nric'] ?>"><br>
            <label>Other Occupation:</label><input class='form-control' name='AUOtherOccupation' value="<?php echo $auditors[$i]['Auditor']['OtherOccupation'] ?>"><br>
            <label>Nationality:</label><input class='form-control' name='AUNationality' value="<?php echo $auditors[$i]['StakeHolder']['nationality'] ?>"><br>
         </div>
        <?php } ?>
     </div>
</div>
    <input class="form-control" type="submit" value="Submit">
</form>