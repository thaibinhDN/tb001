<br />

<div class="form-header">
	Generate Forms
</div>
<div class="form-content">
	<form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'generateIncreaseNonCashCapital')); ?>" method="post" accept-charset="utf-8">
            <input type="hidden" name="company" value="<?php echo $company; ?>" />
            <div style='padding-bottom:2em'>
                <label>Current Share Capital</label><input  style="margin-bottom:10px" name="capitalShare" class="form-control"  required/><br>
                <label>Total issued Share Capital</label><input  style="margin-bottom:10px" name="totalIssuedShare" class="form-control"  required/><br>
                <label>Total Cash</label><input  style="margin-bottom:10px" name="totalCash" class="form-control"  required/><br>
                 <label>Each Share cost</label><input  style="margin-bottom:10px" name="eachShare" class="form-control"  required/><br>
                 <label>Total Share Alloted</label><input  style="margin-bottom:10px" name="totalSharesAlloted" class="form-control"  required/><br>
                
            </div>
		<div id="container-stakeholder-block">
                    <div class="stakeholder-block">
                    <label>Name of Allottee</label> 
                                <select name="stakeholder[]" class="form-control" id="director" style="margin-bottom:1em">
                                        <option>---Select---</option>
                                        <?php foreach($stakeholders as $stakeholder) {?>
					<option value="<?php echo $stakeholder['StakeHolder']['id'] ?>" ><?php echo $stakeholder['StakeHolder']['name'] ?> </option>
                                        <?php }?>
                                </select><br>
                                
                    <label>Shares Alloted</label><input style="margin-bottom:1em" class="form-control" name="SharesAlloted[]" value="" required/><br>
                    <label >Classes Of Shares</label><input style="margin-bottom:1em"  value="ORDINARY" class="form-control" name="classes[]"/><br>
                    <label >Shares Paid(Non Cash)</label><input  style="margin-bottom:1em" class="form-control" name="SharesNonCash[]" value="" required/><br>
                    <button style="margin-bottom:1em" class="btn btn-danger remove_stakeholders" type="button">Remove</button>
                    </div>
                </div>
                <button class="btn btn-small btn-primary" type="button" id="add_stakeholders">Add</button>
                <br>
                <label style="padding-top:1em">Under Letters Allotment</label><br>
                <label>Deposit to bank Acc</label><input class="form-control" name="bankAcc" value=""/><br> 
                <label style="padding-top:2em">Meeting Place</label><input  style="margin-bottom:10px" name="maddress" class="form-control"  required/><br>
                 <label>Chair man</label><input  style="margin-bottom:10px" name="chairman" class="form-control"  required/><br>
                 <label>Article No</label><input  style="margin-bottom:10px" name="articleNo" class="form-control" /><br>
                 <label>Name of *Director/Secretary</label><input  style="margin-bottom:10px" name="nameDS" class="form-control" /><br>
		<div class="submit">
			<input  style="margin-left:6em" class="form-control" type="submit" value="Generate" />
		</div>
	</form>
         
</div>
