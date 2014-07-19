<br />

<div class="form-header">
	GENERATE
</div>
<div class="form-content">
	<form action="<?php echo $this->Html->url(array('controller' => 'forms', 'action' => 'previewForm', 'id' => $form['Form']['form_id'])); ?>" method="post" accept-charset="utf-8">
		<div style="display:none;">
			<input type="hidden" name="_method" value="POST"/>
		</div>	
		<input type="hidden" name="form_id" value="<?php echo $form['Form']['form_id']; ?>" />
		<div class="input select required">
			<label for="companyField">Company</label>
			<select name="company" class="form-control" id="companyField" required="required">
				<option value="">-- Select Company --</option>
				<?php foreach ($companies as $company) { ?>
					<option value="<?php echo $company['Company']['company_id']; ?>"><?php echo $company['Company']['name']; ?></option>
				<?php } ?>
			</select>
		</div>	
		<div class="input select required">
			<label for="directorField">Director</label>
			<select name="director[]" class="form-control" id="directorField" required="required">
				
			</select>
		</div>	
		<?php foreach ($fields as $field) { ?>
			<?php if ($field['FormField']['field_type'] == 'text') { ?>
				<div class="input text required">
					<label for="<?php echo $field['FormField']['field_name']; ?>"><?php echo $field['FormField']['label']; ?></label>
					<input name="<?php echo $field['FormField']['field_name']; ?>" class="form-control" type="text" id="<?php echo $field['FormField']['field_name']; ?>" required="required"/>
				</div>	
			<?php } else if ($field['FormField']['field_type'] == 'datetime') { ?>
				<div class="input date required">
					<label for="<?php echo $field['FormField']['field_name']; ?>"><?php echo $field['FormField']['label']; ?></label>
					<input class="datepicker form-control" name="<?php echo $field['FormField']['field_name']; ?>" type="text" id="<?php echo $field['FormField']['field_name']; ?>" placeholder="MM/DD/YYYY" required="required"/>
				</div>
			<?php } ?>
		<?php } ?>		
		<br />
		<br />
		<div class="submit">
			<input class="form-control" type="submit" value="Preview" />
		</div>
	</form>
</div>