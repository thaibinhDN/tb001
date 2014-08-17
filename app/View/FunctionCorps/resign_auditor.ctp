<br />
<div class="form-header" data-toggle="collapse" data-target="#appoint">
	[Appoint Auditor]
</div>
<div class="form-content collapse in" id="appoint">
    <form action="<?php echo $this->Html->url(array('controller' => 'Auditors', 'action' => 'addAuditor')); ?>" method="post" accept-charset="utf-8">
            <label>Name of the Auditor:</label><input class='form-control' name='NameoftheAuditor' required><br>
            <label>Address :</label><br>
            <label>Addressline 1</label><input class='form-control' name='AUAddressline1' requireed><br>
            <label>Addressline 2</label><input class='form-control' name='AUAddressline2' required><br>
            <label>Addressline 3</label><input class='form-control' name='AUAddressline3' required><br>
            <label>NRIC / Passport:</label><input class='form-control' name='AUNRIC/Passport' required><br>
            <label>Other Occupation:</label><input class='form-control' name='AUOtherOccupation' required><br>
            <label>Nationality:</label><input class='form-control' name='AUNationality' required><br><br>
             <input type="hidden" name="company" value="<?php echo $view_data['company']; ?>" />
            <div class="submit">
			<input  style="margin-left:3.5em" class="form-control" type="submit" value="Create" />
		</div>
    </form>
</div>
<div class="form-header" data-toggle="collapse" data-target="#resign">
	[Resign Auditor]
</div>
<div class="form-content collapse" id="resign">
	<form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'generateResignAuditor')); ?>" method="post" accept-charset="utf-8">
		<div style="display:none;">
			<input type="hidden" name="_method" value="POST"/>
		</div>	
		
                <input type="hidden" name="company" value="<?php echo $view_data['company']; ?>" />
              
                    <label>Auditor</label>
                    <br />
                    <select name="auditor" class="form-control" id="auditor">
                            <option value=""> -- Select Auditor -- </option>
                             <?php foreach($auditors as $auditor){ ?>
                            <option value="<?php echo $auditor['StakeHolder']['id']; ?>"><?php echo $auditor['StakeHolder']['name']; ?></option>
                            <?php }?>
                    </select>
         
		 <div class="input text required">
                        <label style="width:auto">Name of *Directors/Secretaries</label>
                        <input style="margin-bottom:10px" name="prepared_by" class="form-control" type="text" id="prepared_by" required="required"/>
                </div>
		<div class="submit">
			<input  style="margin-left:3.5em" class="form-control" type="submit" value="Generate Forms" />
		</div>
	</form>
         
</div>
