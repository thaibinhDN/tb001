<div class="form-header">
	Property Disposal
</div>
<div class="form-content">
    <form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'generatePropertyDisposal')); ?>" method="post" accept-charset="utf-8">
        <label>Header of Resolution</label>
        <input  style="margin-bottom:10px" name="header_resolution" class="form-control"  required/>
        <br>
        <label>Property Name&Address</label>
        <input  style="margin-bottom:10px" name="property" class="form-control"  required/>
        <input name="company" value="<?php echo $view_data['company'] ?>" type="hidden" />
        <input  name="function_id" value="<?php echo $view_data['functionCorp'] ?>" type="hidden" />
        <br>
        <label>Buyer</label>
        <input  style="margin-bottom:10px" name="buyer" class="form-control"  required/>
        <br>
        <label>Price with Currency</label>
        <input  style="margin-bottom:10px" name="price" class="form-control"  required/>
        <br>
        <label>The undersigned</label>
        <input  style="margin-bottom:10px" name="undersigned" class="form-control"  required/>
        <br>
        <label>Article No</label>
        <input  style="margin-bottom:10px" name="articleNo" class="form-control"  required/>
        <br>
        <label>Meeting Address</label><br>
        <input  style="margin-bottom:10px" name="AddressLine1" class="form-control" placeholder="AddressLine1" required/>
        <input  style="margin-bottom:10px" name="AddressLine2" class="form-control" placeholder="AddressLine2" required/>
        <input type="submit" value="Generate Form" class="form-control"/>
    </form>
</div>