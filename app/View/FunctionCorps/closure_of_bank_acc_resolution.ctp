<div class="form-header">
	Closure of Bank Account
</div>
<div class="form-content">
    <form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'generateClosureOfBankAccResolution')); ?>" method="post" accept-charset="utf-8">
        <label>Name Of the Bank</label>
        <input  style="margin-bottom:10px" name="BankName" class="form-control"/>
        <br>
        <label>Account number</label>
        <input  style="margin-bottom:10px" name="AccNumber" class="form-control"/>
        <input name="company_id" value="<?php echo $view_data['company'] ?>" type="hidden" />
        <input  name="function_id" value="<?php echo $view_data['functionCorp'] ?>" type="hidden" />
        <input type="submit" value="Generate Form" class="form-control"/>
    </form>
</div>