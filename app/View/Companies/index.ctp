<br />
<div class="table-control-big">
	<ul>
		<li>
			<?php echo $this->Html->link('Create Company', array('controller' => 'companies', 'action' => 'companyForm')); ?>
		</li>
	</ul>
	<div class="clear"></div>
</div>
<table class="table table-striped">
	<thead>
		<th>Company Name</th>
		<th>Company Number</th>
		<th>Address</th>
		<th>Telephone</th>
		<th>Fax</th>
		<th>Action</th>
	</thead>
	<tbody>
		<?php foreach ($companies as $company) { ?>
			<tr>
				<td><?php echo $this->Html->link($company['Company']['name'], array('controller' => 'companies', 'action' => 'viewCompany', 'id' => $company['Company']['company_id'])); ?></td>
				<td><?php echo $company['Company']['register_number']; ?></td>
				<td><?php echo $company['Company']['address_1'].'<br />'.$company['Company']['address_2']; ?></td>
				<td><?php echo $company['Company']['telephone']; ?></td>
				<td><?php echo $company['Company']['fax']; ?></td>
				<td>
					<?php echo $this->Html->link('Edit', array('controller' => 'companies', 'action' => 'editCompanyForm', 'id' => $company['Company']['company_id'])); ?>
					<br />
					<?php echo $this->Html->link('Delete', array('controller' => 'companies', 'action' => 'deleteCompany', 'id' => $company['Company']['company_id']), array('escape' => false, 'confirm' => 'Are you sure you want to delete this company?')); ?>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>