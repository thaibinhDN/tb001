<br />

<div class="form-header">
	Generate Forms
</div>
<div class="form-content">
	<form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'preview1')); ?>" method="post" accept-charset="utf-8">
		<div style="display:none;">
			<input type="hidden" name="_method" value="POST"/>
		</div>	
		
                <input type="hidden" name="company" value="<?php echo $company; ?>" />
                <div>
           <?php echo $this->Html->link('New Director', array('controller' => 'companies', 'action' => 'directorForm', 'id' => $company),
                   array("class"=>"btn btn-default pull-right")); ?>
              
               </div>
               <br>
               <br>
                <div id="container-director-block">
                     <?php  $count = 0; 
                        if(!is_null($directors_created)){
                       
                        foreach($directors_created as $director_created){
                            $count++;
                      ?>
			<div class="director-block">
				<label><?php echo "Director"." ".$count?></label>
				<br />
                               
                                <select name="director[]" class="form-control" id="director">
					<option value="<?php echo $director_created['StakeHolder']['id'] ?>" selected><?php echo $director_created['StakeHolder']['name'] ?> </option>
				</select> 
                                
                                <select name="type[]" class="form-control" style="margin:0px 25px;">
					<option value="appointment" selected>Appointment</option>
				</select>
                                <input type="hidden" class = "attn" name="attn[]" style="margin:0px 25px;height:35px" value = "" size="8" />
                                <button class="btn btn-danger remove_directors" type="button">Remove</button>
                                </div>
                                 <?php 
                                            }
                                    } else{ ?>
                                <div class="director-block">
                                <label>Director 1</label>
				<br />
				<select name="director[]" class="form-control" id="director">
					<option value=""> -- Select Director -- </option>
                                         <?php foreach($directors as $director){ ?>
                                        <option value="<?php echo $director['StakeHolder']['id']; ?>"><?php echo $director['StakeHolder']['name']; ?></option>
                                        <?php }?>
				</select>
                                <select name="type[]" class="form-control select_type" style="margin:0px 25px;">
                                <option value=""> -- Select Type -- </option>
                                <option value="appointment">Appointment</option>
                                <option value="cessation">Cessation</option>
				</select>
                                <input type="hidden" class = "attn" name="attn[]" style="margin:0px 25px;height:35px" value = "" size="8" />
                                <button class="btn btn-danger remove_directors" type="button">Remove</button>
                                </div>
                                 <?php }?>
			
				
			
                </div>
                <input type="hidden" id="trackNumberDirector" value="<?php echo $count ?>"/>
                <button class="btn btn-small btn-primary" type="button" id="add_directors">Add</button>
                <p style="color:red;font-size:small">(* Maximum 6 directors at a time and not more than <br>3 for each type(cessation or appointment))</p>
		 <div class="input text required">
                        <label style="width:auto">Name of *Directors/Secretaries</label>
                        <input style="margin-bottom:10px" name="prepared_by" class="form-control" type="text" id="prepared_by" required="required"/>
                </div>
		<div class="submit">
			<input  style="margin-left:3.5em" class="form-control" type="submit" value="Preview" />
		</div>
	</form>
         
</div>
