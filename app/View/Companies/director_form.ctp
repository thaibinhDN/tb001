<br />

<div class="form-header">
	CREATE NEW
</div>

<div class="form-content">
	<?php echo $this->Form->create('Director', array('url' => array('controller' => 'companies', 'action' => 'addDirector'))); ?>
        <div id="new_director_block">
	<?php echo $this->Form->input('company_id', array('type' => 'hidden', 'default' => $company_id)); ?>
	<?php echo $this->Form->input('name', array('name'=>'name[]','class' => 'form-control', 'label' => 'Name', 'placeholder' => 'e.g. John Doe','required'=>true)); ?>
	<?php echo $this->Form->input('nric', array('name'=>'nric[]','class' => 'form-control', 'label' => 'NRIC', 'placeholder' => 'e.g. S1234567Z','required'=>true)); ?>
	<?php echo $this->Form->input('address_1', array('name'=>'address_1[]','class' => 'form-control', 'label' => 'Address Line 1', 'placeholder' => 'e.g. 123 Bukit Batok Road','required'=>true)); ?>
	<?php echo $this->Form->input('address_2', array('name'=>'address_2[]','class' => 'form-control', 'label' => 'Address Line 2', 'placeholder' => 'e.g. Singapore 123456','required'=>true)); ?>
	<?php echo $this->Form->input('nationality', array('name'=>'nationality[]','class' => 'form-control', 'label' => 'Nationality', 'placeholder' => 'e.g. Singaporean','required'=>true)); ?>
	
	<br />
	<br />
        </div>
	
     <button class="btn btn-small btn-primary" type="button" id="create_directors">Add</button>
     <?php echo $this->Form->submit('Create', array('class' => 'form-control')); ?>
</div>

