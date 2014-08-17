<br />

<div class="form-header">
	Generate Forms
</div>
<div class="form-content">
	<form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'preview0')); ?>" method="post" accept-charset="utf-8">
		<div style="display:none;">
			<input type="hidden" name="_method" value="POST"/>
		</div>	
                <input type="hidden" name="company" value="<?php echo $company; ?>" />
                <input type="hidden" name="function" value="<?php echo $this->action; ?>" />
                <div>
             <?php echo $this->Html->link('New Secretary', array('controller' => 'secretaries', 'action' => 'secretaryForm', "?"=>array('id' => $company)),
                   array("class"=>"btn btn-default pull-right")); ?>
              
               </div>
               <br>
               <br>
                <div id="container-director-block">
              
                    <div class="director-block">
                    <label>Secretary</label>
                    <br />
                    <select name="director[]" class="form-control" id="director">
                            <option value=""> -- Select Secretary -- </option>
                             <?php foreach($secretaries as $secretary){ ?>
                            <option value="<?php echo $secretary['StakeHolder']['id']; ?>"><?php echo $secretary['StakeHolder']['name']; ?></option>
                            <?php }?>
                    </select>
                    <select name="type[]" class="form-control select_type" style="margin:0px 25px;">
                    <option value=""> -- Select Type -- </option>
                    <option value="appointment">Appointment</option>
                    <option value="cessation">Cessation</option>
                    </select>
                    
                    <button class="btn btn-danger remove_directors" type="button">Remove</button>
                    </div>
                </div>
                <button class="btn btn-small btn-primary" type="button" id="add_directors">Add</button>
                <p style="color:red;font-size:small">(* Maximum 6 secretaries at a time and not more than <br>3 for each type(cessation or appointment))</p>
	
                <div class="input text required">
                        <label for="*Director/Scretary">* Directors/Secretaries</label>
                        <input name="prepared_by" class="form-control" type="text" id="prepared_by" required="required"/>
                </div>	
		
	
		<div class="submit">
			<input class="form-control" type="submit" value="Preview" />
		</div>
	</form>
         
</div>
