<?php
    $secretaries = $preview_data['Secretary'];
?>

<br />
<h1><?php echo " ".$preview_data['title'] ?></h1>

<form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'generateAppointResignS')); ?>" method="post" accept-charset="utf-8">

  <input type="hidden" name="company" value="<?php echo $preview_data['company']; ?>" />   
    <?php for ($i = 0; $i < count($secretaries); $i++) { ?>
	<?php if (count($secretaries) > 1) { ?>
		<div class="form-header">SECRETARY<?php echo ($i+1); ?></div>
	<?php } else { ?>
		<div class="form-header">SECRETARY</div>
	<?php } ?>
	<table class="table table-striped">
		<tr>
			<td style="text-align:left;padding-left:20px;">
				Name
			</td>
			<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
                            
                            
                            <select class="form-control" name="secretary[]">
                                <?php foreach($total_secretaries as $secretary){ 
                                        if($secretary['Secretary']['id'] === $secretaries[$i]['Secretary']['id']){
                                    ?>
                                    <option value = "<?php echo $secretary['Secretary']['id']; ?>" selected><?php echo $secretary['StakeHolder']['name']; ?></option>
                                
                               <?php }else{ ?>
                                    <option value = "<?php echo $secretary['Secretary']['id']; ?>"><?php echo  $secretary['StakeHolder']['name']; ?></option>
                                <?php }}?>
                            </select>
                            
                            
			
			</td>
		</tr>
		<tr>
			<td style="text-align:left;padding-left:20px;">
				NRIC
			</td>
			<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
				<input   class="form-control" type="text" name="sec_nric[]" value="<?php echo $secretaries[$i]['StakeHolder']['nric']; ?>"/>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;padding-left:20px;">
				Address
			</td>
			<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
				<input   style="margin-bottom:10px" class="form-control" type="text" name="sec_addr1[]" value="<?php echo $secretaries[$i]['StakeHolder']['address_1']; ?>"/>
				<br />
				<input   class="form-control" type="text" name="sec_addr2[]" value="<?php echo $secretaries[$i]['StakeHolder']['address_2']; ?>"/>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;padding-left:20px;">
				Nationality
			</td>
			<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
				<input class="form-control" type="text" name="sec_nationality[]" value="<?php echo $secretaries[$i]['StakeHolder']['nationality']; ?>"/>
			</td>
		</tr>
                <tr>
                    <td style="text-align:left;padding-left:20px;">
					Type
				</td>
				<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
                                         <?php $types = array('cessation','appointment') ?>
                                    <select class="form-control" name="type[]"> 
                                      
                                        <?php foreach($types as $type){ 
                                         
                                            if($secretaries[$i]['type'] === $type){
                                            ?>
                                            <option value="<?php echo $type ?>" selected><?php echo $type ?></option>           
                                        <?php }else{ ?>
                                           <option value="<?php echo $type ?>"><?php echo $type ?></option>  
                                        <?php }} ?>
                                    </select>
					
				</td>
                </tr>
                <?php if($secretaries[$i]['type'] === "cessation" ){?>
                <tr>
                    <td style="text-align:left;padding-left:20px;">
					Attention
				</td>
				<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
					 <input  class="form-control" type="text" name = "reportedTo[]" value="<?php echo $secretaries[$i]['reportedTo']; ?>"/>
				</td>
                </tr>
                <?php }else{?>
                      <input type="hidden" name = "reportedTo[]" value="None">      
                <?php } ?>

	</table>
               
<?php } ?>
                
                 <?php if(isset($preview_data['edit'])){?>
                    <input class="form-control" type="hidden" name="edit" value="<?php echo $preview_data['edit']?>"/>
                <?php } ?>
                    <label>*Director/Secretary</label>    
                <input class="form-control" type="text" name="prepared_by" value="<?php echo $preview_data['prepared_by'] ?>"/>
                
            
	<input style="float:right;" class="form-control" type="submit" value="Confirm and Generate" />
</form>