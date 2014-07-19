<br />

<div class="form-header">
	CREATE NEW
</div>

<div class="form-content">
	<?php echo $this->Form->create(null, array('url' => array('controller' => 'shareholders', 'action' => ''))); ?>
    <input type="radio" name="new_shareholder" value="add">New shareholder
    <br>
    
    <div id="shareholder_block">
	<?php echo $this->Form->input('company_id', array('name'=>'company_id','type' => 'hidden', 'default' => $company_id,"required"=>true)); ?>
	<?php echo $this->Form->input('name', array('name'=>'name','class' => 'form-control', 'label' => 'Name', 'placeholder' => 'e.g. John Doe',"required"=>true)); ?>
	<?php echo $this->Form->input('nric', array('name'=>'nric','class' => 'form-control', 'label' => 'NRIC/Passport', 'placeholder' => 'e.g. S1234567Z',"required"=>true)); ?>
	<?php echo $this->Form->input('address_1', array('name'=>'address_1','class' => 'form-control', 'label' => 'Address Line 1', 'placeholder' => 'e.g. 123 Bukit Batok Road',"required"=>true)); ?>
	<?php echo $this->Form->input('address_2', array('name'=>'address_2','class' => 'form-control', 'label' => 'Address Line 2', 'placeholder' => 'e.g. Singapore 123456',"required"=>true)); ?>
	<?php echo $this->Form->input('nationality', array('name'=>'nationality','class' => 'form-control', 'label' => 'Nationality', 'placeholder' => 'e.g. Singaporean',"required"=>true)); ?>
	 
        </div>

  
 
  <hr>

    <?php //echo $this->Form->create(null, array('url' => array('controller' => 'shareholders', 'action' => 'updateShareHolder'))); ?>
  <input type="radio" name="new_shareholder" value="update">Existing director  
        <input type="hidden" value="<?php echo $company_id ?>" name="company_id"/>
        <select id="stakeholder" style="margin-bottom:10px" class="form-control" name="director" required>
            <option value="">--Select Director--</option>
            <?php foreach($directors as $director){ ?>
            <option value='<?php echo $director['StakeHolder']['id']?>'> <?php echo $director['StakeHolder']['name'] ?> </option>
            <?php } ?>
        </select>
        <?php echo $this->Form->submit('Create', array('class' => 'form-control')); ?>
</form>
    </div>
     


