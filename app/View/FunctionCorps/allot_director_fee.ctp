<br />

<div class="form-header">
	Generate Forms
</div>
<div class="form-content">
	<form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'generateAllotDirectorFee')); ?>" method="post" accept-charset="utf-8">
            <input type="hidden" name="company" value="<?php echo $company; ?>" />
            <div style='padding-bottom:2em'><label>Total Director's Fees</label><input tyle="margin-bottom:10px" name="totalFees" class="form-control"  required/></div>
		<div id="container-stakeholder-block">
                    <label>Director|Shareholder</label>
                    <label style="padding-left:4.5em">Fees</label>
                    <div class="stakeholder-block">
                                <select name="stakeholder[]" class="form-control" id="director">
                                        <option>---Select---</option>
                                        <?php foreach($stakeholders as $stakeholder) {?>
					<option value="<?php echo $stakeholder['StakeHolder']['id'] ?>" ><?php echo $stakeholder['StakeHolder']['name'] ?> </option>
                                        <?php }?>
				</select> 
                            <input class="form-control" name="feeAmount[]" value="" required></input>
                                <button class="btn btn-danger remove_stakeholders" type="button">Remove</button>
                                </div>
                </div>
                <button class="btn btn-small btn-primary" type="button" id="add_stakeholders">Add</button>
                <br>
                <label>Meeting Place</label><input  style="margin-bottom:10px" name="maddress" class="form-control"  required/><br>
                 <label>Chair man</label><input  style="margin-bottom:10px" name="chairman" class="form-control"  required/><br>
		<div class="submit">
			<input  style="margin-left:6em" class="form-control" type="submit" value="Generate" />
		</div>
	</form>
         
</div>
