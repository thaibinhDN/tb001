<br />

<div class="form-header">
	CREATE NEW
</div>
<div class="form-content">
	<?php echo $this->Form->create('Company', array('url' => array('controller' => 'companies', 'action' => 'addCompany'))); ?>
	<?php echo $this->Form->input('name', array('class' => 'form-control', 'label' => 'Company Name', 'placeholder' => 'e.g. ABC Pte Ltd')); ?>
	<?php echo $this->Form->input('register_number', array('class' => 'form-control', 'label' => 'Company Number', 'placeholder' => 'e.g. 0111222A')); ?>
	<?php echo $this->Form->input('address_1', array('class' => 'form-control', 'label' => 'Address Line 1', 'placeholder' => 'e.g. 123 Bukit Batok Road')); ?>
	<?php echo $this->Form->input('address_2', array('class' => 'form-control', 'label' => 'Address Line 2', 'placeholder' => 'e.g. Singapore 123456')); ?>
	<?php echo $this->Form->input('telephone', array('class' => 'form-control', 'label' => 'Telephone', 'placeholder' => 'e.g. 68888888')); ?>
	<?php echo $this->Form->input('fax', array('class' => 'form-control', 'label' => 'Fax', 'placeholder' => 'e.g. 68888888')); ?>
	<br />
	<br />
	<?php echo $this->Form->submit('Create', array('class' => 'form-control')); ?>
</div>