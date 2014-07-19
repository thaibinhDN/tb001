<br />

<div class="form-header">
	Generate Forms
</div>
<div class="form-content">
	<form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'preview4')); ?>" method="post" accept-charset="utf-8">
		<div style="display:none;">
			<input type="hidden" name="_method" value="POST"/>
		</div>	
		
                <input type="hidden" name="company" value="<?php echo $company; ?>" />
                <div>
           <?php echo $this->Html->link('New ShareHolder', array('controller' => 'Shareholders', 'action' => 'shareholderForm',"?"=>array('id' => $company)),array("class"=>"btn btn-default pull-right")); ?>
              
               </div>
               <br>
               <br>
                <div style="margin-bottom:10px" id="container-shareholder-block">
                <label>ShareHolder</label>
                <div  style="margin-top:-10px" class="shareholder-block">
                <br />
                <select name="shareholder[]" class="form-control" id="shareholder">
                        <option value=""> -- Select ShareHolder -- </option>
                         <?php foreach($shareholders as $shareholder){ ?>
                        <option value="<?php echo $shareholder['StakeHolder']['id']; ?>"><?php echo $shareholder['StakeHolder']['name']; ?></option>
                        <?php }?>
                </select>
                </div>	
                </div>
                <button class="btn btn-small btn-primary" type="button" id="add_shareholders">Add</button>
                <br>
                <br>
                <div  id="container-director-block">
                 <label>Director</label>   
                <div style="margin-top:-10px" class="director-block">
                <br />
                <select name="director[]" class="form-control" id="director">
                        <option value=""> -- Select Director -- </option>
                         <?php foreach($directors as $director){ ?>
                        <option value="<?php echo $director['StakeHolder']['id']; ?>"><?php echo $director['StakeHolder']['name']; ?></option>
                        <?php }?>
                </select>
                </div>	
                </div>
                 <button class="btn btn-small btn-primary" type="button" id="add_directors">Add</button>
                <br>
                <div class="input text required">
                        <label style="margin-left:3.5em">New Company Name</label>
                        <input style="margin-bottom:10px" name="new_company_name" class="form-control" type="text"  required="required"/>
                </div>
		 <div class="input text required">
                        <label style="width:auto">Name of *Directors/Secretaries</label>
                        <input style="margin-bottom:10px" name="prepared_by" class="form-control" type="text" id="prepared_by" required="required"/>
                </div>
                 <div class="input text required">
                        <label style="margin-left:8em">Meeting Place</label>
                        <input name="m_address1" class="form-control" type="text"  required="required" style="margin-left:-4.3em" placeholder="Address Line 1">
                        <input name="m_address2" class="form-control" type="text"  required="required" placeholder="Address Line 1" >
                </div>
                <div class="input text required">
                        <label style="margin-left:8em">Chair Man</label>
                        <input name="chairman" class="form-control" type="text"  required="required" style="margin-left:-4.3em" >
                       
                </div>
		<div class="submit">
			<input  style="margin-left:3.5em" class="form-control" type="submit" value="Preview" />
		</div>
	</form>
         
</div>
