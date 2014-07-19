<br />

<div class="form-header">
	Generate Forms
</div>
<div class="form-content">
	<form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'generateChangeOfPassport')); ?>" method="post" accept-charset="utf-8">
		<div style="display:none;">
			<input type="hidden" name="_method" value="POST"/>
		</div>	
		
                <input type="hidden" name="company" value="<?php echo $company; ?>" />
               <br>
               <br>
                <div id="container-stakeholder-block">
             
                       
                    <div class="stakeholder-block">
                    <label>StakeHolder</label>
                    <label style="padding-left:5em">Passport No</label>
                    <br />
                    <select name="stakeholder[]" class="form-control" id="stakeholder" required="required">
                            <option value=""> -- Select Stakeholder -- </option>
                             <?php foreach($stakeholders as $stakeholder){ ?>
                            <option value="<?php echo $stakeholder['StakeHolder']['id']; ?>"><?php echo $stakeholder['StakeHolder']['name']; ?></option>
                            <?php }?>
                    </select>
                    <input name="passportNo[]" placeholder="Eg: B3478590P" class="form-control" required="required"/> 
                     <select name="occupation[]" class="form-control" id="stakeholder" required="required">
                            <option value=""> -- Select Occupation -- </option>
                        
                            <option value="Director">Director</option>
                            <option value="Secretary">Secretary</option>
                            <option value="Auditor">Auditor</option>
                            <option value="Shareholder">Shareholder</option>
       
                    </select>
                    <button class="btn btn-danger remove_stakeholders" type="button">Remove</button>
                    </div>
               
			
				
			
                </div>
                <button class="btn btn-small btn-primary" type="button" id="add_stakeholders">Add</button>
                <p style="color:red;font-size:small">(* Maximum four stakeholders at a time )</p>
		 <div class="input text required">
                        <label style="width:auto">Name of *Directors/Secretaries</label>
                        <input style="margin-bottom:10px" name="prepared_by" class="form-control" type="text" id="prepared_by" required="required"/>
                </div>
		<div class="submit">
			<input  style="margin-left:3.5em" class="form-control" type="submit" value="Generate" />
		</div>
	</form>
         
</div>
