<br />

<div class="form-header">
	CREATE NEW
</div>

<div class="form-content">
	<?php echo $this->Form->create(null, array('url' => array('controller' => 'shareholders', 'action' => ''))); ?>
    <input type="radio" name="new_shareholder" value="add">New shareholder
    <br>
    
    <div id="shareholder_block">
	<?php echo $this->Form->input('company_id', array('name'=>'company_id','type' => 'hidden', 'default' => $company_id,"required"=>true)); ?>
	<?php //echo $this->Form->input('name', array('name'=>'name','class' => 'form-control', 'label' => 'Name', 'placeholder' => 'e.g. John Doe',"required"=>true)); ?>
	<?php //echo $this->Form->input('nric', array('name'=>'nric','class' => 'form-control', 'label' => 'NRIC/Passport', 'placeholder' => 'e.g. S1234567Z',"required"=>true)); ?>
	<?php //echo $this->Form->input('address_1', array('name'=>'address_1','class' => 'form-control', 'label' => 'Address Line 1', 'placeholder' => 'e.g. 123 Bukit Batok Road',"required"=>true)); ?>
	<?php //echo $this->Form->input('address_2', array('name'=>'address_2','class' => 'form-control', 'label' => 'Address Line 2', 'placeholder' => 'e.g. Singapore 123456',"required"=>true)); ?>
	<?php //echo $this->Form->input('nationality', array('name'=>'nationality','class' => 'form-control', 'label' => 'Nationality', 'placeholder' => 'e.g. Singaporean',"required"=>true)); ?>
	 
        
   
            <label>Name of the Shareholder  :</label><input class='form-control' name='SNameoftheShareholder'  required><br>
            <label>Address (Singapore):</label><br>
            <label>Addressline 1</label><input class='form-control' name='SAddressline1S'  required><br>
            <label>Addressline 2</label><input class='form-control' name='SAddressline2S' ><br>
            <label>Addressline 3</label><input class='form-control' name='SAddressline3S' ><br>
            <label>NRIC / Passport:</label><input class='form-control' name='SNRIC/Passport'  required><br>
            <label>Nationality at Birth:</label><input class='form-control' name='SNationalityatBirth'  ><br>
            <label>Nationality Current :</label><input class='form-control' name='SNationalityCurrent'  required><br>
            <label>Occupation:</label><input class='form-control' name='SOccupation' value="SHAREHOLDER" required><br>
            <label>Number of Shares:</label><input class='form-control' name='SNumberofShares'  required><br>
            <label>Number of Shares In words</label><input class='form-control' name='SNumberofSharesInwords'  required><br>
            <label>Certificate No:</label><input class='form-control' name='SCertificateNo' value="" required><br>
            <label>Members Register No:</label><input class='form-control' name='SMembersRegisterNo'  required><br>
            <label>Date of Birth:</label><input class='form-control' name='SDateofBirth'  required><br>
            <label>Class of Shares:</label><input class='form-control' name='SClassofShares'  required><br>
            <label>Currency:</label><input class='form-control' name='SCurrency' value="" required><br>
            <label>Place of birth:</label><input class='form-control' name='SPlaceofbirth' ><br>
            <label>Nric date of issue:</label><input class='form-control' name='SNricdateofissue'  ><br>
            <label>nric place of issue:</label><input class='form-control' name='Snricplaceofissue' ><br>
            <label>passport no:</label><input class='form-control' name='Spassportno' ><br>
            <label>passport date of issue:</label><input class='form-control' name='Spassportdateofissue' ><br>
            <label>passport place of issue:</label><input class='form-control' name='Spassportplaceofissue' ><br>
            <label>Remarks:</label><input class='form-control' name='SRemarks' ><br>
    </div>

  
 
  <hr>

    <?php //echo $this->Form->create(null, array('url' => array('controller' => 'shareholders', 'action' => 'updateShareHolder'))); ?>
  <input type="radio" name="new_shareholder" value="update">Existing director  
        <input type="hidden" value="<?php echo $company_id ?>" name="company_id"/>
        <select id="stakeholder" style="margin-bottom:10px" class="form-control" name="director" required>
            <option value="">--Select Director--</option>
            <?php foreach($directors as $director){ ?>
            <option value='<?php echo $director['StakeHolder']['id']?>'> <?php echo $director['StakeHolder']['name'] ?> </option>
            <?php } ?>
        </select>
        <?php echo $this->Form->submit('Create', array('class' => 'form-control')); ?>
</form>
    </div>
     


