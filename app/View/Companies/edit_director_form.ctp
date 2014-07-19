<br />

<div class="form-header">
	EDIT PROFILE
</div>
<div class="form-content">
	<?php echo $this->Form->create('Director', array('url' => array('controller' => 'companies', 'action' => 'editDirector'))); ?>
	<?php echo $this->Form->input('director_id', array('type' => 'hidden', 'default' => $director['Director']['director_id'])); ?>
	<?php echo $this->Form->input('name', array('class' => 'form-control', 'label' => 'Name', 'placeholder' => 'e.g. John Doe', 'default' => $director['Director']['name'])); ?>
	<?php echo $this->Form->input('nric', array('class' => 'form-control', 'label' => 'NRIC', 'placeholder' => 'e.g. S1234567Z', 'default' => $director['Director']['nric'])); ?>
	<?php echo $this->Form->input('address_1', array('class' => 'form-control', 'label' => 'Address Line 1', 'placeholder' => 'e.g. 123 Bukit Batok Road', 'default' => $director['Director']['address_1'])); ?>
	<?php echo $this->Form->input('address_2', array('class' => 'form-control', 'label' => 'Address Line 2', 'placeholder' => 'e.g. Singapore 123456', 'default' => $director['Director']['address_2'])); ?>
	<?php echo $this->Form->input('nationality', array('class' => 'form-control', 'label' => 'Nationality', 'placeholder' => 'e.g. Singaporean', 'default' => $director['Director']['nationality'])); ?>
	<?php echo $this->Form->input('occupation', array('class' => 'form-control', 'label' => 'Occupation', 'placeholder' => 'e.g. Director', 'default' => $director['Director']['occupation'])); ?>
	<?php echo $this->Form->input('status', array('options' => array('Appointed', 'Resigned','Waiting approval'), 'class' => 'form-control', 'default' => $director['Director']['Mode'] == 'appointed' ? 0 : ($director['Director']['Mode']=='resigned'?1:2))); ?>
	<br />
	<br />
	<?php echo $this->Form->submit('Update', array('class' => 'form-control')); ?>
</div>