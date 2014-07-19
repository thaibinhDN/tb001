<div class="form-header">
	Loan Resolution
</div>
<div class="form-content">
    <form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'generateLoanResolution')); ?>" method="post" accept-charset="utf-8">
        <label>Loan To</label>
        <input  style="margin-bottom:10px" name="Loaner" class="form-control" placeholder="Mr Loaner" required/>
        <br>
        <label>Amount of Loan</label>
        <input  style="margin-bottom:10px" name="AmountLoan" class="form-control" placeholder="S$200.000" required/>
        <input name="company_id" value="<?php echo $view_data['company'] ?>" type="hidden" />
        <input  name="function_id" value="<?php echo $view_data['functionCorp'] ?>" type="hidden" />
        <br>
        <label>Article No</label>
        <input  style="margin-bottom:10px" name="articleNo" class="form-control" placeholder="100" required/>
        <input type="submit" value="Generate Form" class="form-control"/>
    </form>
</div>