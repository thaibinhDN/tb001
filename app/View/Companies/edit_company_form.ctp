<br />

<div class="form-header">
	EDIT PROFILE
</div>
<div class="form-content">
	<?php echo $this->Form->create('Company', array('url' => array('controller' => 'companies', 'action' => 'editCompany'))); ?>
	<?php echo $this->Form->input('company_id', array('type' => 'hidden', 'default' => $company['Company']['company_id'])); ?>
	<?php echo $this->Form->input('name', array('class' => 'form-control', 'label' => 'Company Name', 'placeholder' => 'e.g. ABC Pte Ltd', 'default' => $company['Company']['name'])); ?>
	<?php echo $this->Form->input('register_number', array('class' => 'form-control', 'label' => 'Company Number', 'placeholder' => 'e.g. 0111222A', 'default' => $company['Company']['register_number'])); ?>
	<?php echo $this->Form->input('address_1', array('class' => 'form-control', 'label' => 'Address Line 1', 'placeholder' => 'e.g. 123 Bukit Batok Road', 'default' => $company['Company']['address_1'])); ?>
	<?php echo $this->Form->input('address_2', array('class' => 'form-control', 'label' => 'Address Line 2', 'placeholder' => 'e.g. Singapore 123456', 'default' => $company['Company']['address_2'])); ?>
	<?php echo $this->Form->input('telephone', array('class' => 'form-control', 'label' => 'Telephone', 'placeholder' => 'e.g. 68888888', 'default' => $company['Company']['telephone'])); ?>
	<?php echo $this->Form->input('fax', array('class' => 'form-control', 'label' => 'Fax', 'placeholder' => 'e.g. 68888888', 'default' => $company['Company']['fax'])); ?>
	<br />
	<br />
	<?php echo $this->Form->submit('Update', array('class' => 'form-control')); ?>
</div>