<?php
	$function_name = "Appoint Resign Director";
	$director_ids = "";
        $directors = $preview_data['Director'];
      
       
	foreach ($directors as $director) {
		$director_ids .= $director['Director']['id'].",";
              
	}

	// for form 49
		
?>

<br />

<h1><?php echo " ".$preview_data['title']; ?></h1>
<form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'generate'.implode('',explode(" ",$function_name)))); ?>" method="post" accept-charset="utf-8">
    <input type="hidden" name="company" value="<?php echo $preview_data['data']['company']; ?>" />
    <?php for ($i = 0; $i < count($directors); $i++) { ?>
	<?php if (count($directors) > 1) { ?>
		<div class="form-header">DIRECTOR <?php echo ($i+1); ?></div>
	<?php } else { ?>
		<div class="form-header">DIRECTOR</div>
	<?php } ?>
	<table class="table table-striped">
		<tr>
			<td style="text-align:left;padding-left:20px;">
				Name
			</td>
			<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
                                
                            <select class="form-control" name="director[]">
                                <?php foreach($total_directors as $director){ 
                                        if($director['StakeHolder']['id'] === $directors[$i]['StakeHolder']['id']){
                                    ?>
                                    <option value = "<?php echo $director['StakeHolder']['id']; ?>" selected><?php echo $directors[$i]['StakeHolder']['name']; ?></option>
                                
                               <?php }else{ ?>
                                    <option value = "<?php echo $director['StakeHolder']['id']; ?>"><?php echo  $director['StakeHolder']['name']; ?></option>
                                <?php }}?>
                            </select>
                            
                            
			</td>
		</tr>
		<tr>
			<td style="text-align:left;padding-left:20px;">
				NRIC
			</td>
			<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
				<input  class="form-control" type="text" name="director_nric[]" value="<?php echo $directors[$i]['StakeHolder']['nric']; ?>">
			</td>
		</tr>
		<tr>
			<td style="text-align:left;padding-left:20px;">
				Address
			</td>
			<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
				<input style="margin-bottom:10px" class="form-control" type="text" name="director_addr1[]" value="<?php echo $directors[$i]['StakeHolder']['address_1']; ?>">
				<br />
				<input  class="form-control" type="text" name="director_addr2[]" value="<?php echo $directors[$i]['StakeHolder']['address_2']; ?>">
			</td>
		</tr>
		<tr>
			<td style="text-align:left;padding-left:20px;">
				Nationality
			</td>
			<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
				<input class="form-control" type="text" name="director_nationality[]" value="<?php echo $directors[$i]['StakeHolder']['nationality']; ?>">
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
                                            if($preview_data['data']['type'][$i] == $type){
                                            ?>
                                            <option value="<?php echo $type ?>" selected><?php echo $type ?></option>           
                                        <?php }else{ ?>
                                           <option value="<?php echo $type ?>"><?php echo $type ?></option>  
                                        <?php }} ?>
                                    </select>
                                    
				</td>
                </tr>
                <?php if(isset($directors[$i]['reportedTo'])){?>
                <tr>
                    <td style="text-align:left;padding-left:20px;">
					Attention
				</td>
				<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
					<input class="form-control" type="text" name="reportedTo[]" value="<?php echo $directors[$i]['reportedTo']; ?>" >
				</td>
                </tr>
                <?php }else{?>
                      <input type="hidden" name = "reportedTo[]" value="">      
                <?php } ?>
                      

	</table>
<?php } ?>
                <?php if(isset($preview_data['edit'])){?>
                    <input class="form-control" type="hidden" name="edit" value="<?php echo $preview_data['edit']?>"/>
                <?php } ?>
      
	
                    <label>*Director/Secretary</label>
                <input class="form-control" type="text" name="prepared_by" value="<?php echo $preview_data['data']['prepared_by'] ?>"/>
	<input style="float:right;" class="form-control" type="submit" value="Confirm and Generate" />
</form>