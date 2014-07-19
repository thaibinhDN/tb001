<form action="<?php echo $this->Html->url(array('controller' => 'Incorporations', 'action' => 'generateIncorporationForms')); ?>" method="post" accept-charset="utf-8">
<div class="form-header" data-toggle="collapse" data-target="#company_info">
	Company information
</div>
<div class="form-content collapse in" id="company_info">
    <label>Company Name</label>
    <input class="form-control" name="Cname" value="DREAMSMART PTE LTD"/> 
    <br>
<!--    <label>Company number</label>
    <input class="form-control" name="Cnumber" value="20008888A"/> 
    <br>-->
    <br>
    <label>Registered Office</label> 
    <br>
    <label>Address line 1</label>
    <input class="form-control" name="Raddress1" value="ABC ROAD 123"/> 
    <br>
    <label>Address line 2</label>
    <input class="form-control" name="Raddress2" value="#01-23 SINGAPORE 1234567"/> 
    <br>
    <br>
     <label>Principal Activity 1</label>
    <input class="form-control" name="Pactivity1" value="PRODUCTION OF BOOKS"/> 
    <br>
     <label>Description</label>
    <input class="form-control" name="Pactivity1description" value="BOOKS FOR PRE-SCHOOL" /> 
    <br>
      <label>Principal Activity 2</label>
    <input class="form-control" name="Pactivity2" value="PRODUCTION OF STATIONARIES"/> 
    <br>
     <label>Description</label>
    <input class="form-control" name="Pactivity2description" value="INCLUDING ARTS STATIONARIES AND SCHOOL STATION"/> 
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
             <input class="form-control" name="Ccurrency" value="UNITED STATES DOLLARS"/> 
            <br>
            <label>Number of Shares:</label>
             <input class="form-control" name="Cnoshares" value="100"/> 
            <br>
            <label>Nominal Amount of each share:</label>
             <input class="form-control" name="CnominalAmount" value="1"/> 
            <br>
            <label>Amount paid:</label>
             <input class="form-control" name="CamountPaid" value="100"/> 
            <br>
            <label>Due & Payable:</label>
             <input class="form-control" name="CduePayable" value=""/> 
            <br>
            <label>Amount Premium Paid:</label>
             <input class="form-control" name="CamountPremiumPaid" value=""/> 
            <br>
            <label>Authorised Share Capital:</label>
             <input class="form-control" name="CauthorizedShareCapital" value="100"/> 
            <br>
            <label>Issued Share Capital:</label>
             <input class="form-control" name="CissuedShareCapital" value="100"/> 
            <br>
            <label>Paid up Share Capital:</label>
             <input class="form-control" name="CpaidupShareCapital" value="100"/> 
            <br>
            <label>Issued Ordinary:</label>
             <input class="form-control" name="CIssuedOrdinary" value="100"/> 
            <br>
            <label>Paid-Up Ordinary:</label>
             <input class="form-control" name="CPaidupOrdinary" value="100"/> 
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
    <div class="row">
        <div class="col-md-6">
            <h4>Director1</h4>
            <label>Name of the Appoint Director :</label>
            <input class='form-control' name='DNameoftheAppointDirector[]' value="MR DIRECTOR 1"> 
            <br>
            <label>Address (Singapore):</label>
            <br>
            <label>Addressline 1</label><input class='form-control' name='DAddressline1S[]' value="DIRECTORS ROAD 1 "><br>
            <label>Addressline 2</label><input class='form-control' name='DAddressline2S[]' value="STREET D1"><br>
            <label>Addressline 3</label><input class='form-control' name='DAddressline3S[]' value="#01-01 SINGAPORE 010101"><br>
            <label>Address (Oversea):</label><br>
            <label>Addressline 1</label><input class='form-control' name='DAddressline1OS[]' value=""><br>
            <label>Addressline 2</label><input class='form-control' name='DAddressline2OS[]' value=""><br>
            <label>Addressline 3</label><input class='form-control' name='DAddressline3OS[]' value=""><br>
            <label>Address (Others):</label><br>
            <label>Addressline 1</label><input class='form-control' name='DAddressline1OT[]' value=""></br>
            <label>Addressline 2</label><input class='form-control' name='DAddressline2OT[]' value=""></br>
            <label>Addressline 3</label><input class='form-control' name='DAddressline3OT[]'value=""><br>
            <label>Class of Shares:</label><input class='form-control' name='DClassofShares[]'value="Ordinary"><br>
            <label>Number of Shares:</label><input class='form-control' name='DNumberofShares[]' value="40"><br>
            <label>Number of Shares In words</label><input class='form-control' name='DNumberofSharesInwords[]' value="Forty"><br>
            <label>Certificate No:</label><input class='form-control' name='DCertificateNo[]' value="1"><br>
            <label>NRIC / Passport:</label><input class='form-control' name='DNRIC/Passport[]' value="S1234567A"><br>
            <label>Nationality at Birth:</label><input class='form-control' name='DNationalityatBirth[]' value="SINGAPOREAN"><br>
            <label>Nationality Current :</label><input class='form-control' name='DNationalityCurrent[]' value="SINGAPOREAN"><br>
            <label>Occupation:</label><input class='form-control' name='DOccupation[]' value="DIRECTOR"><br>
            <label>Date of Birth:</label><input class='form-control' name='DDateofBirth[]' value="01-01-1981"><br>
            <label>Currency:</label><input class='form-control' name='DCurrency[]' value="UNITED STATES DOLLARS"><br>
            <label>Place of birth:</label><input class='form-control' name='DPlaceofbirth[]' value="SINGAPORE"><br>
            <label>Nric date of issue:</label><input class='form-control' name='DNricdateofissues[]' value="01-01-2001"><br>
            <label>Nric place of issue:</label><input class='form-control' name='DNricplaceofissue[]' value="SINGAPORE"><br>
            <label>Passport  no:</label><input class='form-control' name='DPassportno[]' value=""><br>
            <label>Passport Date Of  Issue:</label><input class='form-control' name='DPassportDateOfIssue[]' value=""><br>
            <label>Passport Place Of  Issue:</label><input class='form-control' name='DPassportPlaceOfIssue[]' value=""><br>
            <label>Nature of Contract:</label><input class='form-control' name='DNatureofContract[]' value=""><br>
            <label>Remarks:</label><input class='form-control' name='DRemarks[]' value=""><br>
            <label>Consent to act as director of the company</label><input class='form-control' name='DConsenttoactasdirectorofthecompany[]' value=""><br>
            <label>Former Name if any:</label><input class='form-controlr' name='DFormerNameifany[]' value=""><br>

                
        </div>
        
        <div class="col-md-6">
            <h4>Director2</h4>
            <label>Name of the Appoint Director :</label>
            <input class='form-control' name='DNameoftheAppointDirector[]' value="MR DIRECTOR 2">
            <br>
            <label>Address (Singapore):</label>
            <br>
            <label>Addressline 1</label><input class='form-control' name='DAddressline1S[]' value="DIRECTORS ROAD 2"><br>
            <label>Addressline 2</label><input class='form-control' name='DAddressline2S[]' value="STREET D2"><br>
            <label>Addressline 3</label><input class='form-control' name='DAddressline3S[]' value="#02-02 MALAYSIA 020202"><br>
            <label>Address (Oversea):</label><br>
            <label>Addressline 1</label><input class='form-control' name='DAddressline1OS[]' value=""><br>
            <label>Addressline 2</label><input class='form-control' name='DAddressline2OS[]' value=""><br>
            <label>Addressline 3</label><input class='form-control' name='DAddressline3OS[]' value=""><br>
            <label>Address (Others):</label><br>
            <label>Addressline 1</label><input class='form-control' name='DAddressline1OT[]' value=""></br>
            <label>Addressline 2</label><input class='form-control' name='DAddressline2OT[]' value=""></br>
            <label>Addressline 3</label><input class='form-control' name='DAddressline3OT[]' value=""><br>
            <label>Class of Shares:</label><input class='form-control' name='DClassofShares[]' value="Ordinary"><br>
            <label>Number of Shares:</label><input class='form-control' name='DNumberofShares[]' value="30"><br>
            <label>Number of Shares In words</label><input class='form-control' name='DNumberofSharesInwords[]' value="Thirty"><br>
            <label>Certificate No:</label><input class='form-control' name='DCertificateNo[]' value="2"><br>
            <label>NRIC / Passport:</label><input class='form-control' name='DNRIC/Passport[]' value="987654-32-1098"><br>
            <label>Nationality at Birth:</label><input class='form-control' name='DNationalityatBirth[]' value="MALAYSIAN"><br>
            <label>Nationality Current :</label><input class='form-control' name='DNationalityCurrent[]' value="MALAYSIAN"><br>
            <label>Occupation:</label><input class='form-control' name='DOccupation[]' value="DIRECTOR"><br>
            <label>Date of Birth:</label><input class='form-control' name='DDateofBirth[]' value="02-02-1982"><br>
            <label>Currency:</label><input class='form-control' name='DCurrency[]' value="UNITED STATES DOLLARS"><br>
            <label>Place of birth:</label><input class='form-control' name='DPlaceofbirth[]' value="SINGAPORE"><br>
            <label>Nric date of issue:</label><input class='form-control' name='DNricdateofissues[]' value="02-02-2002"><br>
            <label>Nric place of issue:</label><input class='form-control' name='DNricplaceofissue[]' value="MALAYSIA"><br>
            <label>Passport  no:</label><input class='form-control' name='DPassportno[]' value=""><br>
            <label>Passport Date Of  Issue:</label><input class='form-control' name='DPassportDateOfIssue[]' value=""><br>
            <label>Passport Place Of  Issue:</label><input class='form-control' name='DPassportPlaceOfIssue[]' value=""><br>
            <label>Nature of Contract:</label><input class='form-control' name='DNatureofContract[]' value=""><br>
            <label>Remarks:</label><input class='form-control' name='DRemarks[]' value=""><br>
            <label>Consent to act as director of the company</label><input class='form-control' name='DConsenttoactasdirectorofthecompany[]' value=""><br>
            <label>Former Name if any:</label><input class='form-controlr' name='DFormerNameifany[]' value=""><br>

                
        </div>
        
    </div>
    <br>
    <br>
    <br>
    <div class="row">
            <div class="col-md-9">
                <h4>Director3</h4>
                <label>Name of the Appoint Director :</label>
                <input class='form-control' name='DNameoftheAppointDirector[]' value="MR DIRECTOR 3">
                <br>
                <label>Address (Singapore):</label>
                <br>
                <label>Addressline 1</label><input class='form-control' name='DAddressline1S[]' value="DIRECTORS ROAD 3"><br>
                <label>Addressline 2</label><input class='form-control' name='DAddressline2S[]' value="STREET D3"><br>
                <label>Addressline 3</label><input class='form-control' name='DAddressline3S[]' value="#03-03 SINGAPORE 030303"><br>
                <label>Address (Oversea):</label><br>
                <label>Addressline 1</label><input class='form-control' name='DAddressline1OS[]' value=""><br>
                <label>Addressline 2</label><input class='form-control' name='DAddressline2OS[]'value=""><br>
                <label>Addressline 3</label><input class='form-control' name='DAddressline3OS[]'value=""><br>
                <label>Address (Others):</label><br>
                <label>Addressline 1</label><input class='form-control' name='DAddressline1OT[]'value=""></br>
                <label>Addressline 2</label><input class='form-control' name='DAddressline2OT[]' value=""></br>
                <label>Addressline 3</label><input class='form-control' name='DAddressline3OT[]' value=""><br>
                <label>Class of Shares:</label><input class='form-control' name='DClassofShares[]' value="Ordinary"><br>
                <label>Number of Shares:</label><input class='form-control' name='DNumberofShares[]' value="30"><br>
                <label>Number of Shares In words</label><input class='form-control' name='DNumberofSharesInwords[]' value="Thirty"><br>
                <label>Certificate No:</label><input class='form-control' name='DCertificateNo[]' value="3"><br>
                <label>NRIC / Passport:</label><input class='form-control' name='DNRIC/Passport[]' value="S7654321A"><br>
                <label>Nationality at Birth:</label><input class='form-control' name='DNationalityatBirth[]' value="SINGAPOREAN"><br>
                <label>Nationality Current :</label><input class='form-control' name='DNationalityCurrent[]' value="SINGAPOREAN"><br>
                <label>Occupation:</label><input class='form-control' name='DOccupation[]' value="DIRECTOR"><br>
                <label>Date of Birth:</label><input class='form-control' name='DDateofBirth[]' value="03-03-1983"><br>
                <label>Currency:</label><input class='form-control' name='DCurrency[]' value="UNITED STATES DOLLARS"><br>
                <label>Place of birth:</label><input class='form-control' name='DPlaceofbirth[]' value="MALAYSIA"><br>
                <label>Nric date of issue:</label><input class='form-control' name='DNricdateofissues[]' value="03-03-2003"><br>
                <label>Nric place of issue:</label><input class='form-control' name='DNricplaceofissue[]' value="SINGAPORE"><br>
                <label>Passport  no:</label><input class='form-control' name='DPassportno[]' value=""><br>
                <label>Passport Date Of  Issue:</label><input class='form-control' name='DPassportDateOfIssue[]' value=""><br>
                <label>Passport Place Of  Issue:</label><input class='form-control' name='DPassportPlaceOfIssue[]' value=""><br>
                <label>Nature of Contract:</label><input class='form-control' name='DNatureofContract[]'><br>
                <label>Remarks:</label><input class='form-control' name='DRemarks[]' value=""><br>
                <label>Consent to act as director of the company</label><input class='form-control' name='DConsenttoactasdirectorofthecompany[]' value=""><br>
                <label>Former Name if any:</label><input class='form-controlr' name='DFormerNameifany[]' value=""><br>


            </div>
        </div>
    
           

</div>
<div class="form-header" data-toggle="collapse" data-target="#shareholders_info">
   ShareHolders information
</div>
<div class="form-content collapse" id="shareholders_info">
    <div class="row">
        <div class="col-md-6">
            <h4>ShareHolder 1</h4>
            <label>Name of the Shareholder  :</label><input class='form-control' name='SNameoftheShareholder[]' value="MR SHAREHOLDER 1"><br>
            <label>Address (Singapore):</label><br>
            <label>Addressline 1</label><input class='form-control' name='SAddressline1S[]' value="SHAREHOLDER ROAD 1"><br>
            <label>Addressline 2</label><input class='form-control' name='SAddressline2S[]' value="STREET S1"><br>
            <label>Addressline 3</label><input class='form-control' name='SAddressline3S[]' value="#11-11 MALAYSIA 111111"><br>
            <label>Address (Oversea):</label><br>
            <label>Addressline 1</label><input class='form-control' name='SAddressline1OS[]' value=""><br>
            <label>Addressline 2</label><input class='form-control' name='SAddressline2OS[]' value=""><br>
            <label>Addressline 3</label><input class='form-control' name='SAddressline3OS[]' value=""><br>
            <label>Address (Others):</label><br>
            <label>Addressline 1</label><input class='form-control' name='SAddressline1OT[]' value=""><br>
            <label>Addressline 2</label><input class='form-control' name='SAddressline2OT[]' value=""><br>
            <label>Addressline 3</label><input class='form-control' name='SAddressline3OT[]' value=""><br>
            <label>NRIC / Passport:</label><input class='form-control' name='SNRIC/Passport[]' value="676751-65-6763"><br>
            <label>Nationality at Birth:</label><input class='form-control' name='SNationalityatBirth[]' value="MALAYSIAN"><br>
            <label>Nationality Current :</label><input class='form-control' name='SNationalityCurrent[]' value="MALAYSIAN"><br>
            <label>Occupation:</label><input class='form-control' name='SOccupation[]' value="DIRECTOR"><br>
            <label>Number of Shares:</label><input class='form-control' name='SNumberofShares[]' value="40"><br>
            <label>Number of Shares In words</label><input class='form-control' name='SNumberofSharesInwords[]' value="Forty"><br>
            <label>Certificate No:</label><input class='form-control' name='SCertificateNo[]' value="1"><br>
            <label>Members Register No:</label><input class='form-control' name='SMembersRegisterNo[]' value="1"><br>
            <label>Date of Birth:</label><input class='form-control' name='SDateofBirth[]' value="11-11-1971"><br>
            <label>Class of Shares:</label><input class='form-control' name='SClassofShares[]' value="ORDINARY"><br>
            <label>Currency:</label><input class='form-control' name='SCurrency[]' value="UNITED STATES DOLLARS"><br>
            <label>Place of birth:</label><input class='form-control' name='SPlaceofbirth[]' value="MALAYSIA"><br>
            <label>Nric date of issue:</label><input class='form-control' name='SNricdateofissue[]' value="11-11-1991"><br>
            <label>nric place of issue:</label><input class='form-control' name='Snricplaceofissue[]' value="MALAYSIA"><br>
            <label>passport no:</label><input class='form-control' name='Spassportno[]' value=""><br>
            <label>passport date of issue:</label><input class='form-control' name='Spassportdateofissue[]' value=""><br>
            <label>passport place of issue:</label><input class='form-control' name='Spassportplaceofissue[]' value=""><br>
            <label>Remarks:</label><input class='form-control' name='SRemarks[]' value=""><br>
        </div>
        
        <div class="col-md-6">
             <h4>ShareHolder 2</h4>
            <label>Name of the Shareholder  :</label><input class='form-control' name='SNameoftheShareholder[]' value="MR SHAREHOLDER 2"><br>
            <label>Address (Singapore):</label><br>
            <label>Addressline 1</label><input class='form-control' name='SAddressline1S[]' value="SHAREHOLDER ROAD 2"><br>
            <label>Addressline 2</label><input class='form-control' name='SAddressline2S[]' value="STREET S2"><br>
            <label>Addressline 3</label><input class='form-control' name='SAddressline3S[]' value="#22-22 SINGAPORE 222222"><br>
            <label>Address (Oversea):</label><br>
            <label>Addressline 1</label><input class='form-control' name='SAddressline1OS[]' value=""><br>
            <label>Addressline 2</label><input class='form-control' name='SAddressline2OS[]' value=""><br>
            <label>Addressline 3</label><input class='form-control' name='SAddressline3OS[]' value=""><br>
            <label>Address (Others):</label><br>
            <label>Addressline 1</label><input class='form-control' name='SAddressline1OT[]' value=""><br>
            <label>Addressline 2</label><input class='form-control' name='SAddressline2OT[]' value=""><br>
            <label>Addressline 3</label><input class='form-control' name='SAddressline3OT[]' value=""><br>
            <label>NRIC / Passport:</label><input class='form-control' name='SNRIC/Passport[]' value="S9876543A"><br>
            <label>Nationality at Birth:</label><input class='form-control' name='SNationalityatBirth[]' value="MALAYSIAN"><br>
            <label>Nationality Current :</label><input class='form-control' name='SNationalityCurrent[]' value="SINGAPOREAN"><br>
            <label>Occupation:</label><input class='form-control' name='SOccupation[]' value="DIRECTOR"><br>
            <label>Number of Shares:</label><input class='form-control' name='SNumberofShares[]' value="30"><br>
            <label>Number of Shares In words</label><input class='form-control' name='SNumberofSharesInwords[]' value=Thirty""><br>
            <label>Certificate No:</label><input class='form-control' name='SCertificateNo[]' value="2"><br>
            <label>Members Register No:</label><input class='form-control' name='SMembersRegisterNo[]' value="2"><br>
            <label>Date of Birth:</label><input class='form-control' name='SDateofBirth[]' value="22-12-1972"><br>
            <label>Class of Shares:</label><input class='form-control' name='SClassofShares[]' value="Ordinary"><br>
            <label>Currency:</label><input class='form-control' name='SCurrency[]' value="UNITED STATES DOLLARS"><br>
            <label>Place of birth:</label><input class='form-control' name='SPlaceofbirth[]' value="SNGAPORE"><br>
            <label>Nric date of issue:</label><input class='form-control' name='SNricdateofissue[]' value="22-12-1992"><br>
            <label>nric place of issue:</label><input class='form-control' name='Snricplaceofissue[]' value="SINGAPORE"><br>
            <label>passport no:</label><input class='form-control' name='Spassportno[]' value=""><br>
            <label>passport date of issue:</label><input class='form-control' name='Spassportdateofissue[]' value=""><br>
            <label>passport place of issue:</label><input class='form-control' name='Spassportplaceofissue[]' value=""><br>
            <label>Remarks:</label><input class='form-control' name='SRemarks[]' value=""><br>
        </div>
    </div>
    <br>
    <br>
    <div class="row">
         <div class="col-md-9">
             <h4>ShareHolder 3</h4>
            <label>Name of the Shareholder  :</label><input class='form-control' name='SNameoftheShareholder[]' value="MR SHAREHOLDER 3"><br>
            <label>Address (Singapore):</label><br>
            <label>Addressline 1</label><input class='form-control' name='SAddressline1S[]' value="SHAREHOLDER ROAD 3"><br>
            <label>Addressline 2</label><input class='form-control' name='SAddressline2S[]' value="STREET S3"><br>
            <label>Addressline 3</label><input class='form-control' name='SAddressline3S[]' value="#33-33 MALAYSIA 333333"><br>
            <label>Address (Oversea):</label><br>
            <label>Addressline 1</label><input class='form-control' name='SAddressline1OS[]' value=""><br>
            <label>Addressline 2</label><input class='form-control' name='SAddressline2OS[]' value=""><br>
            <label>Addressline 3</label><input class='form-control' name='SAddressline3OS[]' value=""><br>
            <label>Address (Others):</label><br>
            <label>Addressline 1</label><input class='form-control' name='SAddressline1OT[]' value=""><br>
            <label>Addressline 2</label><input class='form-control' name='SAddressline2OT[]' value=""><br>
            <label>Addressline 3</label><input class='form-control' name='SAddressline3OT[]' value=""><br>
            <label>NRIC / Passport:</label><input class='form-control' name='SNRIC/Passport[]' value="910723-41-9450"><br>
            <label>Nationality at Birth:</label><input class='form-control' name='SNationalityatBirth[]' value="MALAYSIAN"><br>
            <label>Nationality Current :</label><input class='form-control' name='SNationalityCurrent[]' value="MALAYSIAN"><br>
            <label>Occupation:</label><input class='form-control' name='SOccupation[]' value="DIRECTOR"><br>
            <label>Number of Shares:</label><input class='form-control' name='SNumberofShares[]' value="30"><br>
            <label>Number of Shares In words</label><input class='form-control' name='SNumberofSharesInwords[]' value="Thirty"><br>
            <label>Certificate No:</label><input class='form-control' name='SCertificateNo[]' value="3"><br>
            <label>Members Register No:</label><input class='form-control' name='SMembersRegisterNo[]' value="3"><br>
            <label>Date of Birth:</label><input class='form-control' name='SDateofBirth[]' value="13-03-1973"><br>
            <label>Class of Shares:</label><input class='form-control' name='SClassofShares[]' value="Ordinary"><br>
            <label>Currency:</label><input class='form-control' name='SCurrency[]' value="UNITED STATES DOLLARS"><br>
            <label>Place of birth:</label><input class='form-control' name='SPlaceofbirth[]' value="MALAYSIA"><br>
            <label>Nric date of issue:</label><input class='form-control' name='SNricdateofissue[]' value="13-03-1993"><br>
            <label>nric place of issue:</label><input class='form-control' name='Snricplaceofissue[]' value="MALAYSIA"><br>
            <label>passport no:</label><input class='form-control' name='Spassportno[]' value=""><br>
            <label>passport date of issue:</label><input class='form-control' name='Spassportdateofissue[]' value=""><br>
            <label>passport place of issue:</label><input class='form-control' name='Spassportplaceofissue[]' value=""><br>
            <label>Remarks:</label><input class='form-control' name='SRemarks[]' value=""><br>
        </div>
        
    </div>
    </div>
    
<div class="form-header" data-toggle="collapse" data-target="#others_info">
    Other information
</div>
<div class="form-content collapse" id="others_info">
    <div class="row">
        <div class="col-md-6">
            <label>Lodged Office Info:</label><br>
            <label>Name:</label><input class='form-control' name='LOName' value="XYZ PTE LTD"><br>
            <label>Addressline 1</label><input class='form-control' name='LOAddressline1' value="77 XYZ ROAD"><br>
            <label>Addressline 2</label><input class='form-control' name='LOAddressline2' value="#02-777 SINGAPORE 070707"><br>
            <label>A/c No:</label><input class='form-control' name='LOAcNo' value="5167-8923"><br>
            <label>Tel No:</label><input class='form-control' name='LOTelNo' value="67770707"><br>
            <label>Tel Fax:</label><input class='form-control' name='LOTelFax' value="67771212"><br>
        </div>  
        <div class="col-md-6">
            <label>Article No.:</label><input class='form-control' name='ArticleNo.' value=""><br>
            <label>Date Of Incorporation:</label><input class='form-control' name='DateOfIncorporation' value=""><br>
            <label>First Meeting</label><input class='form-control' name='FirstMeeting' value=""><br>
            <label>Chairman of the Meeting:</label><input class='form-control' name='ChairmanoftheMeeting' value="MR CHAIRMAN"><br>
            <label>Subscribers:</label><input class='form-control' name='Subscribers' value="MR SUBSCRIBER"><br>
            <label>Subscriber's Share </label><input class='form-control' name='Subscriber_Share' value=""><br>
        </div>
    </div>
    <br>
    <br>
     <div class="row">
         <div class="col-md-6">
              <label>Name of the Secretary:</label><input class='form-control' name='NameoftheSecretary' value="MR SECRETARY"><br>
            <label>Address :</label><br>
            <label>Addressline 1</label><input class='form-control' name='SEAddressline1' value="SECRETARY ROAD 1"><br>
            <label>Addressline 2</label><input class='form-control' name='SEAddressline2' value="STREET SEC 1"><br>
            <label>Addressline 3</label><input class='form-control' name='SEAddressline3' value="#09-09 SINGAPORE 676723"><br>
            <label>NRIC / Passport:</label><input class='form-control' name='SENRIC/Passport' value="S0192837B"><br>
            <label>Nationality:</label><input class='form-control' name='SENationality' value="SINGAPOREAN"><br>
            <label>Occupation:</label><input class='form-control' name='SEOccupation' value="SECRETARY"><br>
            <label>Other Occupation:</label><input class='form-control' name='SEOtherOccupation' value=""><br>
         </div>
         <div class="col-md-6">
             <label>Name of the Auditor:</label><input class='form-control' name='NameoftheAuditor' value="MR AUDITOR"><br>
            <label>Address :</label><br>
            <label>Addressline 1</label><input class='form-control' name='AUAddressline1' value="AUDITOR ROAD 1"><br>
            <label>Addressline 2</label><input class='form-control' name='AUAddressline2' value="STREET A1"><br>
            <label>Addressline 3</label><input class='form-control' name='AUAddressline3' value="#03-33 SINGAPORE 689218"><br>
            <label>NRIC / Passport:</label><input class='form-control' name='AUNRIC/Passport' value="S5647382Z"><br>
            <label>Other Occupation:</label><input class='form-control' name='AUOtherOccupation' value="IT"><br>
            <label>Nationality:</label><input class='form-control' name='AUNationality' value="SINGAPOREAN"><br>
         </div>
        
    </div>
</div>
    <input class="form-control" type="submit" value="Submit">
</form>