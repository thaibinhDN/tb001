<br />

<div class="form-header">
	Generate Forms
</div>
<div class="form-content">
	<form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'generateNormalStruckOff')); ?>" method="post" accept-charset="utf-8">
            <input type="hidden" name="company" value="<?php echo $company; ?>" />
		<div id="container-stakeholder-block">
                    <label>Director|Shareholder</label>
                    <label style="padding-left:4.5em">NoShares</label>
                    <label style="padding-left:8.5em">Witness</label>
                    <label></label>
                    <div class="stakeholder-block"><br>
                                <select name="stakeholder[]" class="form-control" id="director">
                                        <option>---Select---</option>
                                        <?php foreach($stakeholders as $stakeholder) {?>
					<option value="<?php echo $stakeholder['StakeHolder']['id'] ?>" ><?php echo $stakeholder['StakeHolder']['name'] ?> </option>
                                        <?php }?>
				</select> 
                            <input class="form-control" name="shareAmount[]" value="" required></input>
                             <input  class="form-control" name="witness[]" value=""  required></input>
                                <button class="btn btn-danger remove_stakeholders" type="button">Remove</button>
                                </div>
                </div>
                <button class="btn btn-small btn-primary" type="button" id="add_stakeholders">Add</button>
                <br>
                <label>Meeting Place</label><input  style="margin-bottom:10px" name="maddress" class="form-control"  required/><br>
                <label>ACRA address</label>
                <input  style="margin-bottom:10px" name="addressLine1" class="form-control"  placeholder = "Address Line 1" required/>
                <input  style="margin-bottom:10px" name="addressLine2" class="form-control" placeholder = "Address Line 2" required/>
                <input  style="margin-left:11em;margin-bottom:10px" name="addressLine3" class="form-control" placeholder = "Address Line 3" required/><br>
                
		<div class="submit">
			<input  style="margin-left:6em" class="form-control" type="submit" value="Generate" />
		</div>
	</form>
         
</div>
