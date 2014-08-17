<br />

<div class="form-header">
	CREATE NEW
</div>

<div class="form-content">
	<?php echo $this->Form->create("Auditor", array('url' => array('controller' => 'Auditors', 'action' => 'addAuditor'))); ?>
<!--        <div id="new_director_block">-->
	
        <?php //echo $this->Form->input('name', array('name'=>'name[]','class' => 'form-control', 'label' => 'Name', 'placeholder' => 'e.g. John Doe','required'=>true)); ?>
        
	<?php //echo $this->Form->input('nric', array('name'=>'nric[]','class' => 'form-control', 'label' => 'NRIC/Passport', 'placeholder' => 'e.g. S1234567Z','required'=>true)); ?>
	<?php //echo $this->Form->input('address_1', array('name'=>'address_1[]','class' => 'form-control', 'label' => 'Address Line 1', 'placeholder' => 'e.g. 123 Bukit Batok Road','required'=>true)); ?>
	<?php //echo $this->Form->input('address_2', array('name'=>'address_2[]','class' => 'form-control', 'label' => 'Address Line 2', 'placeholder' => 'e.g. Singapore 123456','required'=>true)); ?>
	<?php //echo $this->Form->input('nationality', array('name'=>'nationality[]','class' => 'form-control', 'label' => 'Nationality', 'placeholder' => 'e.g. Singaporean','required'=>true)); ?>
	<?php //echo $this->Form->input('occupation', array('name'=>'occupation[]','class' => 'form-control', 'label' => 'Occupation', 'value' => 'Secretary','required'=>true)); ?>
<!--	<br />
	<br />
        </div>-->
    <div >

            <input class='form-control' name='company' type="hidden" value="<?php echo $company_id ?>" required><br>
              <label>Name of the Auditor:</label><input class='form-control' name='NameoftheAuditor' value="" required><br>
            <label>Address :</label><br>
            <label>Addressline 1</label><input class='form-control' name='AUAddressline1' value="" required><br>
            <label>Addressline 2</label><input class='form-control' name='AUAddressline2' value=""><br>
            <label>Addressline 3</label><input class='form-control' name='AUAddressline3' value=""><br>
            <label>NRIC / Passport:</label><input class='form-control' name='AUNRIC/Passport' value="" required><br>
            <label>Other Occupation:</label><input class='form-control' name='AUOtherOccupation' value="" required><br>
            <label>Nationality:</label><input class='form-control' name='AUNationality' value="" required><br>
         </div>
	
     <!--<button class="btn btn-small btn-primary" type="button" id="create_directors">Add</button>-->
     <?php echo $this->Form->submit('Create', array('class' => 'form-control')); ?>
</div>

