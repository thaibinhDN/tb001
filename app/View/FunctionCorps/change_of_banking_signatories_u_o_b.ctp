<h1>Change of Banking Signatories </h1>
<br>
<div class="form-header">
	Generate Forms
</div>
<div class="form-content">
	<form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'generateChangeOfBankingUOB')); ?>" method="post" accept-charset="utf-8">
			
		
                <input type="hidden" name="company" value="<?php echo $company; ?>" />
                <input type="hidden" name="function" value="<?php echo $function; ?>" />
           
               `<div style="margin-bottom:15px">
                <label>Director</label>
                <label class="text-right auditor">Bank</label>
                <br />
             
                <select name="director" class="form-control" id="director">
                        <option value=""> -- Select Director -- </option>
                         <?php foreach($directors as $director){ ?>
                        <option value="<?php echo $director['StakeHolder']['id']; ?>"><?php echo $director['StakeHolder']['name']; ?></option>
                        <?php }?>
                </select>
                <input type="text" style="width:60%;margin-bottom:10px" class="form-control pull-right" name="bankName" value="" placeholder="Eg. UNITED OVERSEAS BANK LIMITED"/>
                </div>
                           
		<div class="submit">
			<input class="form-control" type="submit" value="Generate Form" />
		</div>
	</form>
         
</div>
