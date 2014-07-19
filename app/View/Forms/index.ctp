</br >
<style>
   span{
	padding:4px 12px;
	border: 1px solid #ddd;
}
</style>
<table class="table table-striped">
	<thead>
		<th>Company</th>
		<th>Type</th>
		<th>Created On</th>
		<th>Action</th>
	</thead>
     
	<tbody>
        
		<?php foreach ($pdfs as $pdf) { ?>
			<tr>
				<td><?php echo $pdf['Company']['name']; ?></td>
				<td><?php echo $pdf['FunctionCorp']['description']; ?></td>
				<td>
					<?php echo date('D, m/d/Y', strtotime($pdf['ZipFile']['created_at'])); ?>
					<br />
					at <?php echo date('H:i:s', strtotime($pdf['ZipFile']['created_at'])); ?>
				</td>
				<td>
					<?php echo $this->Html->link('Download', array('controller' => 'forms', 'action' => 'downloadForm', 'id' => $pdf['ZipFile']['id'])); ?>
<!--					<br />-->
					<?php //echo $this->Html->link('Delete', array('controller' => 'forms', 'action' => 'deleteForm', 'id' => $pdf['ZipFile']['id']), array('escape' => false, 'confirm' => 'Are you sure you want to delete this form?')); ?>
				</td>
			</tr>
		<?php } ?>
        </tbody>
     

</table>
<?php
// Shows the page numbers
echo $this->Paginator->numbers(array("separator"=>""));

// Shows the next and previous links
echo $this->Paginator->prev(
  '« Previous',
  null,
  null,
  array('class' => 'disabled')
);
echo $this->Paginator->next(
  'Next »',
  null,
  null,
  array('class' => 'disabled')
);

// prints X of Y, where X is current page and Y is number of pages
echo $this->Paginator->counter();
?>