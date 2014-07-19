<div class="form-header">
	Option To Purchase
</div>
<div class="form-content">
    <form action="<?php echo $this->Html->url(array('controller' => 'FunctionCorps', 'action' => 'generateOptionToPurchase')); ?>" method="post" accept-charset="utf-8">
        <label>Meeting Place</label><input  style="margin-bottom:10px" name="maddress" class="form-control" placeholder="50 HAVELOCK ROAD #02-767 SINGAPORE 160050" required/><br>
        
        <label>Address of the property</label><input  style="margin-bottom:10px" name="propertyAddress" class="form-control" placeholder="1 NEW Property Road" required/><br>
        <label>Selling Price</label><input  style="margin-bottom:10px" name="sellingPrice" class="form-control" placeholder="S2.700.000" required/><br>
        <label>Name Of the Vendor</label><input  style="margin-bottom:10px" name="vendor" class="form-control" placeholder="Vendor Development Pte. Ltd." required/><br>
        <label>OTP dated on</label><input  style="margin-bottom:10px" name="otpDate" class="form-control" placeholder="6 January 2014." required/><br>
          <label>Name of Authorized</label><input  style="margin-bottom:10px" name="authorizer" class="form-control" placeholder="Anthony Lum" required/>
          <select name="sign" class="form-control" required>
              <option value="singly" selected>singly</option>
              <option value="singly">jointly</option>
          </select><br>
           <label>Article No:</label><input  style="margin-bottom:10px" name="articleNo" class="form-control" placeholder="90A" required/>
         <input name="company_id" value="<?php echo $view_data['company'] ?>" type="hidden" />
        <input  name="function_id" value="<?php echo $view_data['functionCorp'] ?>" type="hidden" />
       
        <input type="submit" value="Generate Form" class="form-control"/>
    </form>
</div>