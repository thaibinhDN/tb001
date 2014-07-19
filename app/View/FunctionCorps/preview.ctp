<?php
	$function_name = "Appoint Resign Director";
	$director_ids = "";
        $directors = $preview_data['Director'];
       
	foreach ($directors as $director) {
		$director_ids .= $director['Director']['director_id'].",";
              
	}

	// for form 49
		
?>

<br />
<h1><?php echo " ".$function_name ?></h1>

<!--<table class="table table-striped">
	<?php 
        //$fields = $preview_data['Field'][45];
        //ChromePhp::log($fields);
//        for ($i = 0; $i < count($fields); $i++) { ?>
		<tr>
			<td style="text-align:left;padding-left:20px;">
				<?php // echo $fields[$i]['FormField']['label']; ?>
			</td>
			<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
				<?php // echo $preview_data['data'][$fields[$i]['FormField']['field_name']]; ?>
			</td>
		</tr>
	<?php // } ?>
</table>-->
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
				<?php echo $directors[$i]['Director']['name']; ?>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;padding-left:20px;">
				NRIC
			</td>
			<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
				<?php echo $directors[$i]['Director']['nric']; ?>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;padding-left:20px;">
				Address
			</td>
			<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
				<?php echo $directors[$i]['Director']['address_1']; ?>
				<br />
				<?php echo $directors[$i]['Director']['address_2']; ?>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;padding-left:20px;">
				Nationality
			</td>
			<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
				<?php echo $directors[$i]['Director']['nationality']; ?>
			</td>
		</tr>
                <tr>
                    <td style="text-align:left;padding-left:20px;">
					Type
				</td>
				<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
					<?php echo ucfirst($preview_data['data']['type'][$i]); ?>
				</td>
                </tr>
                <?php if(isset($preview_data['Director'][$i]['reportedTo'])){?>
                <tr>
                    <td style="text-align:left;padding-left:20px;">
					Attention
				</td>
				<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
					<?php echo $preview_data['Director'][$i]['reportedTo']; ?>
				</td>
                </tr>
                <?php }?>

	</table>
<?php } ?>

<form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'generate'.implode('',explode(" ",$function_name)))); ?>" method="post" accept-charset="utf-8">
       
                <!--<input type="hidden" name="director45" value="<?php //echo $preview_data['data']['director45']; ?>" />-->
                <input type="hidden" name="company" value="<?php echo $preview_data['data']['company']; ?>" />
		<?php 
                $directors_preview =  $preview_data['Director'];
                $types = $preview_data['data']['type'];
                foreach($directors_preview as $director){?>
                    <input  type="hidden" name="director[]" value="<?php echo $director['Director']['director_id']?>"/>
                <?php }
                    foreach($types as $type){?>
                       <input type = "hidden" name="type[]" value="<?php echo $type?>"/> 
                 <?php }?>
                    <?php for ($i = 0; $i < count($directors); $i++) { ?>   
                        <?php if(isset($preview_data['Director'][$i]['reportedTo'])){?>
                            <input type="hidden" name = "reportedTo[]" value="<?php echo $preview_data['Director'][$i]['reportedTo'];?>">
                        <?php }else{?>
                            <input type="hidden" name = "reportedTo[]" value="None">
                        <?php }}?>     
               
		<?php 
                //$all_fields =   $preview_data['Field'];  
                //foreach ($all_fields as $fields) { ?>
                        
			<?php 
                           //foreach($fields as $field){?>
                                <!--<input type="hidden" name="<?php //echo $field['FormField']['field_name']; ?>" class="form-control" type="text" id="<?php //echo $field['FormField']['field_name']; ?>" value="<?php //echo $preview_data['data'][$field['FormField']['field_name']]?>"/>-->
                        
                        <?php //} ?>
                        <?php// } ?>
		<br />
		<br />
                <input type="hidden" name="prepared_by" value="<?php echo $preview_data['data']['prepared_by'] ?>"/>
	<input style="float:right;" class="form-control" type="submit" value="Confirm and Generate" />
</form>