<br />

<div class="form-header">
	Generate Forms
</div>
<div class="form-content">
	<form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'preview2')); ?>" method="post" accept-charset="utf-8">
		<div style="display:none;">
			<input type="hidden" name="_method" value="POST"/>
		</div>	
                <input type="hidden" name="company" value="<?php echo $company; ?>" />
                <input type="hidden" name="function" value="<?php echo $this->action; ?>" />
                <div class="director-block">
                <label>Secretary</label>
                
                <label class="text-right auditor">Auditor</label>
                <br />
                <select name="secretary[]" class="form-control" id="director">
                        <option value=""> -- Select Secretary -- </option>
                         <?php foreach($secretaries as $secretary){ ?>
                        <option value="<?php echo $secretary['StakeHolder']['id']; ?>"><?php echo $secretary['StakeHolder']['name']; ?></option>
                        <?php }?>
                </select>
                <input type="text" style="width:60%;margin-bottom:10px" class="form-control pull-right" name="auditorName" value="" placeholder="Name of Appoint Auditor"/>
                <br>
                <input type="text" style="width:60%;margin-bottom:10px" class="form-control pull-right" name="auditorAddress" value="" placeholder="Address"/>
                
                <input type="hidden" class = "attn" name="attn[]" style="margin:0px 25px;height:35px" value = "" size="8" />
       
                </div>
              
                <div class="input text required">
                        <label for="*Director/Scretary">* Directors/Secretaries"</label>
                        <input name="prepared_by" class="form-control" type="text" id="prepared_by" required="required"/>
                </div>	

		
	
		<div class="submit">
			<input class="form-control" type="submit" value="Preview" />
		</div>
	</form>
         
</div>
