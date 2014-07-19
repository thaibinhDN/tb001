<head>

<meta http-equiv="X-UA-Compatible" content="chrome=1">
<title>Styled input[type="file"]</title>
<link href="http://fonts.googleapis.com/css?family=Open+Sans:300" rel="stylesheet" type="text/css">
<style>


</style>
</head>    
    
</head>
<br />
<h3>Choose the company</h3>
<br />
<?php echo $this->Form->create(null,array(
                            "url"=>array("controller"=>"Incorporations","action"=>"editIncorporationForm","?"=>array("edit"=>"y")))); ?>
<div class="input select required">
			
			<select name="company" class="form-control" id="companyField" required="required">
				<option value="">-- Select Company --</option>
				<?php 
                             
                                foreach ($companies as $company) { ?>
					<option value="<?php echo $company['Company']['company_id']; ?>"><?php echo $company['Company']['name']; ?></option>
				<?php } ?>
			</select>
                        
               
		</div>
    <div class="submit">
			<input class="form-control" type="submit" value="Submit" />
		</div>
             
               
   
        
</form>

