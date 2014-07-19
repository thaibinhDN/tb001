<div class="form-header">
	Generate Change Registered Address Forms
</div>
<div class="form-content">
    <form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'generateChangeRegisteredAddress')); ?>" method="post" accept-charset="utf-8">
        <label>Last Registered Address</label> <label style="padding-left:4em">New Register Address</label>
        <br>
        <input  style="margin-bottom:10px" name="previousAddress" class="form-control" value="<?php echo $view_data["last_address"] ?>" />
        <input  style="margin-bottom:10px" name="address_1" class="form-control" value="" placeholder="Address Line 1" required/>
        <input  style="margin-bottom:10px" name="address_2" class="form-control" value="" placeholder="Address Line 2"/>
        <br>
        <label >Name Of *Director/Secretary</label>
       <br>
        <input name="nameDS" class="form-control" value="" required/>
        <input name="company" value="<?php echo $view_data['company_id'] ?>" type="hidden" />
        <input  name="function_id" value="<?php echo $view_data['function_id'] ?>" type="hidden" />
        <input type="submit" value="Generate Form" class="form-control"/>
    </form>
</div>