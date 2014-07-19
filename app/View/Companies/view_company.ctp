<br />

<div class="table-control-big">
	
	<div class="clear"></div>
</div>
<table class="table table-striped">
	<thead>
		<th>Name</th>
		<th>NRIC</th>
		<th>Address</th>
		<th>Nationality</th>
		<th>Occupation</th>
		<th>Status</th>
		<th>Action</th>
	</thead>
	<tbody>
		<?php foreach ($directors as $director) { ?>
			<tr>
				<td><?php echo $director['Director']['name']; ?></td>
				<td><?php echo $director['Director']['nric']; ?></td>
				<td><?php echo $director['Director']['address_1'].'<br />'.$director['Director']['address_2']; ?></td>
				<td><?php echo $director['Director']['nationality']; ?></td>
				<td><?php echo $director['Director']['occupation']; ?></td>
				<td><?php 
                                    $mode = $director['Director']['Mode'];
                                    if($mode == null){
                                        $mode = 'Waiting for Appointment approval';
                                    }    
                                    echo ucfirst($mode); ?></td>
				<td>
					<?php echo $this->Html->link('Edit', array('controller' => 'companies', 'action' => 'editDirectorForm', 'id' => $director['Director']['director_id'])); ?>
					<br />
					<?php echo $this->Html->link('Delete', array('controller' => 'companies', 'action' => 'deleteDirector', 'id' => $director['Director']['director_id']), array('escape' => false, 'confirm' => 'Are you sure you want to delete this director?')); ?>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>