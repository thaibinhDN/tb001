<?php
	$function_name = str_replace(' ', '', $fields[0]['Form']['name']);
	$director_ids = "";
	foreach ($directors as $director) {
		$director_ids .= ($director['Director']['director_id'].",");
	}

	// for form 49
	if (isset($types) && isset($effects)) {
		$type_values = "";
		$effect_values = "";

		foreach ($types as $type) {
			if (!empty($type)) {
				$type_values .= ($type.",");
			}
		}

		foreach ($effects as $effect) {
			if (!empty($effect)) {
				$effect_values .= ($effect.",");
			}
		}
	}	
?>

<br />

<div class="form-header">
	PREVIEW
</div>
<table class="table table-striped">
	<?php for ($i = 0; $i < count($fields); $i++) { ?>
		<tr>
			<td style="text-align:left;padding-left:20px;">
				<?php echo $fields[$i]['FormField']['label']; ?>
			</td>
			<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
				<?php echo $data[$fields[$i]['FormField']['field_name']]; ?>
			</td>
		</tr>
	<?php } ?>
</table>
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
		<?php if (isset($types) && isset($effects)) { ?>
			<tr>
				<td style="text-align:left;padding-left:20px;">
					Type
				</td>
				<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
					<?php echo ucfirst($types[$i]); ?>
				</td>
			</tr>
			<tr>
				<td style="text-align:left;padding-left:20px;">
					Effect From
				</td>
				<td style="text-align:left;padding-left:20px;border-left:1px solid #dbdfe6">
					<?php echo $effects[$i]; ?>
				</td>
			</tr>
		<?php } ?>
	</table>
<?php } ?>

<form action="<?php echo $this->Html->url(array('controller' => 'forms', 'action' => 'generate'.$function_name)); ?>" method="post" accept-charset="utf-8">
	<div style="display:none;">
		<input type="hidden" name="_method" value="POST"/>
	</div>
	<input type="hidden" name="form_id" value="<?php echo $fields[0]['Form']['form_id']; ?>" />
	<input type="hidden" name="directors" value="<?php echo $director_ids; ?>" />
	<?php if (isset($type_values) && isset($effect_values)) { ?>
		<input type="hidden" name="types" value="<?php echo $type_values; ?>" />
		<input type="hidden" name="effects" value="<?php echo $effect_values; ?>" />
	<?php } ?>
	<?php for ($i = 0; $i < count($fields); $i++) { ?>
                
		<input type="hidden" name="<?php echo $fields[$i]['FormField']['field_name']; ?>" value="<?php echo $data[$fields[$i]['FormField']['field_name']]; ?>" />
	<?php } ?>
	<br />
	<br />
	<input style="float:right;" class="form-control" type="submit" value="Confirm and Generate" />
</form>