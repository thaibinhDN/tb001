<br />

<div class="form-header">
	CREATE NEW
</div>

<div class="form-content">
	<?php echo $this->Form->create('Secretary', array('url' => array('controller' => 'secretaries', 'action' => 'addSecretary'))); ?>
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
        <?php echo $this->Form->input('company_id', array('type' => 'hidden', 'default' => $company_id)); ?>
              <label>Name of the Secretary:</label><input class='form-control' name='NameoftheSecretary'  required><br>
            <label>Address :</label><br>
            <label>Addressline 1</label><input class='form-control' name='SEAddressline1'  required><br>
            <label>Addressline 2</label><input class='form-control' name='SEAddressline2' ><br>
            <label>Addressline 3</label><input class='form-control' name='SEAddressline3' ><br>
            <label>NRIC / Passport:</label><input class='form-control' name='SENRIC/Passport'  required><br>
            <label>Nationality:</label><input class='form-control' name='SENationality'  requried><br>
            <label>Occupation:</label><input class='form-control' name='SEOccupation' value="SECRETARY" required><br>
            <label>Other Occupation:</label><input class='form-control' name='SEOtherOccupation' value="" ><br>
         </div>
	
     <!--<button class="btn btn-small btn-primary" type="button" id="create_directors">Add</button>-->
     <?php echo $this->Form->submit('Create', array('class' => 'form-control')); ?>
</div>

