<br />

<div class="form-header">
	CREATE NEW
</div>

<div class="form-content">
	<?php echo $this->Form->create('Director', array('url' => array('controller' => 'companies', 'action' => 'addDirector'))); ?>
        <div id="new_director_block">
	<?php echo $this->Form->input('company_id', array('type' => 'hidden', 'default' => $company_id)); ?>
	<h4>Director</h4>
            <label>Name of the Appoint Director :</label>
            <input class='form-control' name='DNameoftheAppointDirector' value="" required> 
            <br>
            <label>Address (Singapore):</label>
            <br>
            <label>Addressline 1</label><input class='form-control' name='DAddressline1S' value="DIRECTORS ROAD 1 " required><br>
            <label>Addressline 2</label><input class='form-control' name='DAddressline2S' value="STREET D1" required><br>
            <label>Addressline 3</label><input class='form-control' name='DAddressline3S' value="#01-01 SINGAPORE 010101" required><br>
<!--            <label>Address (Oversea):</label><br>
            <label>Addressline 1</label><input class='form-control' name='DAddressline1OS[]' value="Nil"><br>
            <label>Addressline 2</label><input class='form-control' name='DAddressline2OS[]' value="Nil"><br>
            <label>Addressline 3</label><input class='form-control' name='DAddressline3OS[]' value="Nil"><br>
            <label>Address (Others):</label><br>
            <label>Addressline 1</label><input class='form-control' name='DAddressline1OT[]' value="Nil"></br>
            <label>Addressline 2</label><input class='form-control' name='DAddressline2OT[]' value="Nil"></br>
            <label>Addressline 3</label><input class='form-control' name='DAddressline3OT[]'value="Nil"><br>-->
            <label>Class of Shares:</label><input class='form-control' name='DClassofShares'value="Ordinary"><br>
            <label>Number of Shares:</label><input class='form-control' name='DNumberofShares' value="40"><br>
            <label>Number of Shares In words</label><input class='form-control' name='DNumberofSharesInwords' value="Forty"><br>
            <label>Certificate No:</label><input class='form-control' name='DCertificateNo' value="1"><br>
            <label>NRIC / Passport:</label><input class='form-control' name='DNRIC/Passport' value="" required><br>
            <label>Nationality at Birth:</label><input class='form-control' name='DNationalityatBirth' value="SINGAPOREAN" ><br>
            <label>Nationality Current :</label><input class='form-control' name='DNationalityCurrent' value="SINGAPOREAN" required><br>
            <label>Occupation:</label><input class='form-control' name='DOccupation' value="DIRECTOR"><br>
            <label>Date of Birth:</label><input class='form-control' name='DDateofBirth' value="01-01-1981"><br>
            <label>Currency:</label><input class='form-control' name='DCurrency' value="UNITED STATES DOLLARS"><br>
            <label>Place of birth:</label><input class='form-control' name='DPlaceofbirth' value="SINGAPORE"><br>
            <label>Nric date of issue:</label><input class='form-control' name='DNricdateofissues' value="" ><br>
            <label>Nric place of issue:</label><input class='form-control' name='DNricplaceofissue' value=""><br>
            <label>Passport  no:</label><input class='form-control' name='DPassportno' value=""><br>
            <label>Passport Date Of  Issue:</label><input class='form-control' name='DPassportDateOfIssue' value=""><br>
            <label>Passport Place Of  Issue:</label><input class='form-control' name='DPassportPlaceOfIssue' value=""><br>
            <label>Nature of Contract:</label><input class='form-control' name='DNatureofContract' value=""><br>
            <label>Remarks:</label><input class='form-control' name='DRemarks' value=""><br>
            <label>Consent to act as director of the company</label><input class='form-control' name='DConsenttoactasdirectorofthecompany' value=""><br>
            <label>Former Name if any:</label><input class='form-control' name='DFormerNameifany' value=""><br>
	
	<br />
	<br />
	
	<br />
	<br />
        </div>
	

     <?php echo $this->Form->submit('Create', array('class' => 'form-control')); ?>
</div>

