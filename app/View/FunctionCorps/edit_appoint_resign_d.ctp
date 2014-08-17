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
                 <input type="hidden" name="edit" value="edit" />
                 <input type="hidden" name="event_id" value="<?php echo $event_id ?>" />
                 <?php
                    foreach($doc_ids as $id){?>
                        <input type="hidden" name="doc_ids[]" value="<?php echo $id ?>" /> 
                 <?php   }
                 ?>
                <div>
           <?php //echo $this->Html->link('New Director', array('controller' => 'companies', 'action' => 'directorForm', 'id' => $company),
                  // array("class"=>"btn btn-default pull-right")); ?>
              
               </div>
               <br>
               <br>
               
                <div id="container-director-block">
                             <label>Director</label>
                            <br />
                            <?php foreach($lastSubmitDirectors as $last_director){ ?>
                                <div class="director-block">
				<select name="director[]" class="form-control" id="director">
					<option value=""> -- Select Director -- </option>
                                         <?php foreach($directors as $director){  
     
     
                                                    if($director['StakeHolder']['id']==$last_director[0]['StakeHolder']['id']){ 
                                                      
                                                      ?>
                                                      <option value="<?php echo $director['StakeHolder']['id']; ?>" selected><?php echo $director['StakeHolder']['name']; ?></option>
                                           <?php  } else {?>
                                                    <option value="<?php echo $director['StakeHolder']['id']; ?>"><?php echo $director['StakeHolder']['name']; ?></option>
                                           <?php }}?>
				</select>
                                <select name="type[]" class="form-control select_type" style="margin:0px 25px;">
                                    <option value=""> -- Select Type -- </option>
                                    <?php foreach($types  as $type){  
                                          if($type == $last_director['type']){ ?>
                                                <option value="<?php echo $type ?>" selected><?php echo $type ?></option>
                                         <?php }else{?>
                                                 <option value="<?php echo $type ?>"><?php echo $type ?></option>
                                    <?php }} ?>
                                    
				</select>
                                    <?php if(!empty($last_director['attn'])){ ?>
                                        <input  class="attn" name="attn[]" style="margin:0px 25px;height:35px" value = "<?php echo $last_director['attn'] ?>" size="8" />
                                    <?php }else{ ?>
                                          <input  type="hidden" class="attn" name="attn[]" style="margin:0px 25px;height:35px" value = "<?php echo $last_director['attn'] ?>" size="8" />   
                                    <?php } ?>
                                
                                <button class="btn btn-danger remove_directors" type="button">Remove</button>
                                
                                </div>
                                    <?php } ?>
                </div>

                <button class="btn btn-small btn-primary" type="button" id="add_directors">Add</button>
<!--                <p style="color:red;font-size:small">(* Maximum 6 directors at a time and not more than <br>3 for each type(cessation or appointment))</p>-->
		 <div class="input text required">
                        <label style="width:auto">Name of *Directors/Secretaries</label>
                        <input style="margin-bottom:10px" name="prepared_by" class="form-control" type="text" id="prepared_by" required="required"/>
                </div>
		<div class="submit">
			<input  style="margin-left:3.5em" class="form-control" type="submit" value="Preview" />
		</div>
	</form>
         
</div>
