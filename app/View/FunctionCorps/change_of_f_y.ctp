<div class="form-header">
	Generate Forms
</div>
<div class="form-content">
    <form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'generateChangeFY')); ?>" method="post" accept-charset="utf-8">
        <label>From</label>
        <input  style="margin-bottom:10px" name="previousFY" class="form-control" value="<?php echo $view_data["fy"] ?>" disabled/>
        <br>
        <label>To</label>
        <input name="newFY" class="form-control" value="" required/>
        <input name="company_id" value="<?php echo $view_data['company_id'] ?>" type="hidden" />
        <input  name="function_id" value="<?php echo $view_data['function_id'] ?>" type="hidden" />
        <input type="submit" value="Generate Form" class="form-control"/>
    </form>
</div>