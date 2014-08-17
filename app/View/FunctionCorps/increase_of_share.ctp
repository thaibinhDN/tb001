<br />

<div class="form-header">
	Generate Forms
</div>
<div class="form-content">
	<form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'generateIncreaseOfShare')); ?>" method="post" accept-charset="utf-8">
            <input type="hidden" name="company" value="<?php echo $company; ?>" />
            <div style='padding-bottom:2em'>
                <label>Current Capital Shares</label><input  style="margin-bottom:10px" name="capitalShare" class="form-control"  required/><br>
                <label>Issued Paid</label><input  style="margin-bottom:10px" name="issuedPaid" class="form-control"  required/><br>
                 <label>Each Share paid</label><input  style="margin-bottom:10px" name="eachShare" class="form-control"  required/><br>
                 <label>Currency</label><input  style="margin-bottom:10px" name="currency" class="form-control"  placeholder="Eg; S$" required/><br>
                
            </div>
		<div id="container-stakeholder-block">
                    <label>Name of Allottee</label>
                    <label style="padding-left:4.5em">Shares Alloted</label>
                    <label style="padding-left:7.7em">Shares In Cash</label>
                    <div class="stakeholder-block">
                                <select name="stakeholder[]" class="form-control" id="director">
                                        <option>---Select---</option>
                                        <?php foreach($stakeholders as $stakeholder) {?>
					<option value="<?php echo $stakeholder['StakeHolder']['id'] ?>" ><?php echo $stakeholder['StakeHolder']['name'] ?> </option>
                                        <?php }?>
				</select> 
                             <input class="form-control" name="SharesAlloted[]" value="" required></input>
                             <input style="margin-bottom:2em" class="form-control" name="SharesInCash[]" value="" required></input><br>
                             <label >Cheque(if any)</label><label style="padding-left:4.5em" >Class of Share</label><br>
                             
                             <input  value="" class="form-control" name="cheque[]"/>
                             <input  placeholder="Eg. Ordinary" class="form-control" name="class[]" required/>
                                <button class="btn btn-danger remove_stakeholders" type="button">Remove</button>
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
