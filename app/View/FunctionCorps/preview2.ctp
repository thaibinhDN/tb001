<?php
    $secretary = $preview_data['Secretary'];
    $auditor =  $preview_data['auditor'];
 
?>

<br />
<h1>Preview Form</h1>

<form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'generateAppointAs')); ?>" method="post" accept-charset="utf-8">
                <input type="hidden" name="sec_id" value="<?php echo $secretary['Secretary']['id']; ?>" />
                <input type="hidden" name="company" value="<?php echo $preview_data['company']; ?>" />
		<div class="form-header">Secretary</div>
	
	<table class="table table-striped ">
		<tr >
			<td style="text-align:left;padding-left:20px;">
				Name
			</td>
			<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
                            <select class="form-control" name="sec_name">
                            <?php foreach($total_secretaries as $sec){ 
                                        if($sec['Secretary']['id'] === $secretary['Secretary']['id']){
                                    ?>
                                    <option value = "<?php echo $sec['StakeHolder']['name']; ?>" selected><?php echo $sec['StakeHolder']['name']; ?></option>
                                
                               <?php }else{ ?>
                                    <option value = "<?php echo $sec['StakeHolder']['name']; ?>"><?php echo  $sec['StakeHolder']['name']; ?></option>
                                <?php }}?>	
                            </select>
                            
                           
			</td>
		</tr>
		<tr>
			<td style="text-align:left;padding-left:20px;">
				NRIC
			</td>
			<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
				<input class="form-control" type="text" name="sec_nric" value="<?php echo $secretary['StakeHolder']['nric']; ?>"/>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;padding-left:20px;">
				Address
			</td>
			<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
				<input style="margin-bottom:10px" class="form-control" type="text" name="sec_addr1" value="<?php echo $secretary['StakeHolder']['address_1']; ?>"/>
				<br />
				<input  class="form-control" type="text" name="sec_addr2" value="<?php echo $secretary['StakeHolder']['address_2']; ?>"/>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;padding-left:20px;">
				Nationality
			</td>
			<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
				<input class="form-control" type="text" name="sec_nationality" value="<?php echo $secretary['StakeHolder']['nationality']; ?>"/>
			</td>
		</tr>
           
            

	</table>
                
        <div class="form-header">Auditor</div>
        <table class="table table-striped">
            <tr>
			<td style="text-align:left;padding-left:20px;">
				Name
			</td>
			<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
				<input class="form-control" type="text" name="a_name" value="<?php echo $auditor['name']; ?>"/>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;padding-left:20px;">
				Address
			</td>
			<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
				<input class="form-control" type="text" name="a_address" value="<?php echo $auditor['address']; ?>"/>
			</td>
		</tr>
            
        </table>
        
      
                 <?php if(isset($preview_data['edit'])){?>
                    <input class="form-control" type="hidden" name="edit" value="<?php echo $preview_data['edit']?>"/>
                <?php } ?>
                <label for="Prepared by">*Director/Secretary</label>
                <input class="form-control" type="text" name="prepared_by" value="<?php echo $preview_data['prepared_by'] ?>"/>

	<input style="float:right;" class="form-control" type="submit" value="Confirm and Generate" />
</form>