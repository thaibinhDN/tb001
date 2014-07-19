<head>

<meta http-equiv="X-UA-Compatible" content="chrome=1">
<title>Styled input[type="file"]</title>
<link href="http://fonts.googleapis.com/css?family=Open+Sans:300" rel="stylesheet" type="text/css">
<style>


</style>
</head>    
    
</head>
<br />
<h3>Choose the company and function type</h3>
<br />
<form id="view_doc" action="" method="post" accept-charset="utf-8">
<div class="input select required">
			
			<select name="company" class="form-control" id="companyField" required="required">
				<option value="">-- Select Company --</option>
				<?php 
                             
                                foreach ($companies as $company) { ?>
					<option value="<?php echo $company['Company']['company_id']; ?>"><?php echo $company['Company']['name']; ?></option>
				<?php } ?>
			</select>
                        
               
			<select name="functionCorp" class="form-control" id="functionField" required="required">
				<option value="">-- Select Function --</option>
				<?php 
                             
                                foreach ($functions as $function) { ?>
					<option value="<?php echo $function['FunctionCorp']['function_id']; ?>"><?php echo $function['FunctionCorp']['description']; ?></option>
				<?php } ?>
			</select>
		</div>
    <div class="submit">
			<input class="form-control" type="submit" value="Submit" />
		</div>
             
               
   
        
</form>

