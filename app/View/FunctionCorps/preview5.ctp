<br />
<?php
    $shareholders = $data['Shareholder'];
    $directors = $data['Director'];
?>
<div class="form-header">
	Previews
</div>
<div class="form-content">
	<form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'generateChangeOfMAA')); ?>" method="post" accept-charset="utf-8">
            <input type="hidden" name="company" value="<?php echo $data['company']; ?>" />    
            <label>ShareHolder</label>
                 <?php foreach($shareholders as $shareholder){ ?>
                <div style="margin-top:10px" class="shareholder-block">
               <input class= "form-control" value="<?php echo $shareholder; ?>" name="shareholder[]"/>
                </div>	
                <?php }?>
                
                 <label>Director</label> 
                 <?php foreach($directors as $director){ ?>
                <div  class="director-block">
                  <input class ="form-control" value="<?php echo $director; ?>" name="director[]"/>
                </div>	
                 <?php }?>
          
                <br>

		 <div class="input text required">
                        <label style="width:auto">Name of *Directors/Secretaries</label>
                        <input style="margin-bottom:10px" name="prepared_by" class="form-control" value="<?php echo $data['nameDS'] ?>" type="text" id="prepared_by" required="required"/>
                </div>
                 <div class="input text required">
                        <label style="margin-left:8em">Meeting Place</label>
                        <input name="m_address1" class="form-control" type="text" value="<?php echo $data['meeting1'] ?>" required="required" style="margin-left:-4.3em" placeholder="Address Line 1">
                        <input name="m_address2" class="form-control" type="text" value="<?php echo $data['meeting2'] ?>" required="required" placeholder="Address Line 1" >
                </div>
                <div class="input text required">
                        <label style="margin-left:8em">Chair Man</label>
                        <input name="chairman" class="form-control" type="text" value="<?php echo $data['chairman'] ?>" required="required" style="margin-left:-4.3em" >
                       
                </div>
				<?php if(!empty($data['edit'])){?>
                    <input class="form-control" type="hidden" name="edit" value="<?php echo $data['edit']?>"/>
                <?php } ?>  
		<div class="submit">
			<input  style="margin-left:3.5em" class="form-control" type="submit" value="Generate" />
		</div>
	</form>
         
</div>
