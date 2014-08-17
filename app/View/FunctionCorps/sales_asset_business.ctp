<br />

<div class="form-header">
	Generate Forms
</div>
<div class="form-content">
	<form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'generateSalesAssetBusiness')); ?>" method="post" accept-charset="utf-8">
		<div style="display:none;">
			<input type="hidden" name="_method" value="POST"/>
		</div>	
		
                <input type="hidden" name="company" value="<?php echo $view_data['company']; ?>" />
                <div class="input text required">
                        <label style="width:auto">Assets|Business</label>
                        <input style="margin-bottom:10px" name="saleAsset" class="form-control" type="text"  required="required"/>
                </div>
		 <div class="input text required">
                        <label style="width:auto">Name of Seller</label>
                        <input style="margin-bottom:10px" name="seller" class="form-control" type="text" id="seller" required="required"/>
                </div>
                <div class="input text required">
                        <label style="width:auto">Name of Buyer</label>
                        <input style="margin-bottom:10px" name="buyer" class="form-control" type="text" id="buyer" required="required"/>
                </div>
                <div class="input text required">
                        <label style="width:auto">Price with Currency</label>
                        <input style="margin-bottom:10px" name="price" class="form-control" type="text" id="price" placeholder="Eg.S$ 200000" required="required"/>
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
                <div class="input text required">
                        <label style="margin-left:8em">Article No</label>
                        <input name="articleNo" class="form-control" type="text"  required="required" style="margin-left:-4.3em" >
                       
                </div>
		<div class="submit">
			<input  style="margin-left:3.5em" class="form-control" type="submit" value="Generate" />
		</div>
	</form>
         
</div>
