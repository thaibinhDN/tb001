<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Corporate Secretary Application</title>

    <?php 
    	echo $this->fetch('meta');
		echo $this->Html->css('bootstrap.min.css');
		echo $this->Html->css('parsley.css');
		echo $this->Html->css('jquery-ui.css');
		echo $this->Html->css('styles.css');
    ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
	<div class="container">
		<div id="admin-header">
			<div id="logo">
				Corporate Secretary Application
			</div>
			<div id="admin-header-content">
				
			</div>
			<div class="clear"></div>
		</div>
		<div class="content-container">
			<div id="admin-sidebar-nav">
				<div id="admin-sidebar-title" class="divider">
					MAIN NAVIGATION
				</div>
				<ul id="admin-sidebar-links">
					<li>
						<?php echo $this->Html->link('Home', array('controller' => 'pages', 'action' => 'display', 'home')); ?>
					</li>
					<li>
						<?php echo $this->Html->link('Company Management', array('controller' => 'companies', 'action' => 'index')); ?>
					</li>
					<li>
						<?php echo $this->Html->link('Document Management', array('controller' => 'documents', 'action' => 'index')); ?>
					</li>
					<li>
						<?php echo $this->Html->link('Generating Form', array('controller' => 'forms', 'action' => 'generateForm')); ?>
					</li>
                                        <li data-toggle="collapse" data-target="#company_info"><a href="#">Incorporation</a>
                                            <ul class="collapse" id="company_info">
                                                <li><?php echo $this->Html->link('Create New Incorporation', array('controller' => 'Incorporations', 'action' => 'IncorporationForm')); ?></li>
                                                <li><?php echo $this->Html->link('Edit Incorporation', array('controller' => 'Incorporations', 'action' => 'editIncorporation')); ?></li></li>
                                                <li><?php echo $this->Html->link('Approve|Delete', array('controller' => 'Incorporations', 'action' => 'approveIncorporation')); ?></li></li>
                                            </ul>
					</li>
                                        <li>
						<?php echo $this->Html->link('Form Downloads', array('controller' => 'forms', 'action' => 'index')); ?>
					</li>
                                         <li>
						<?php echo $this->Html->link('System Logs', array('controller' => 'events', 'action' => 'index')); ?>
					</li>
                                        <?php 
                                            $token = $this->Session->read('token');
                                            if($token){
                                         ?>  
                                        <li>
						<?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout')); ?>
					</li>
                                        <?php }else{?>

                                         <li>
                                            <?php echo $this->Html->link('Login/Register', array('controller' => 'users', 'action' => 'index',"?"=>array(
                                                            "item"=>"login"
                                            ))); ?>
                                        </li>   
                                        <?php }?>
			</ul>
			<div class="divider"></div>
			</div>
			<div id="admin-content">
				<h1><?php echo isset($title) ? $title : ''; ?></h1>
                                
				<?php echo $this->Session->flash(); ?>
				<?php echo $this->fetch('content'); ?>
			</div>
		</div>
		
		<div id="admin-footer">
			
		</div>
	</div>
    
    <?php 
    
    	echo $this->Html->script('jquery.min.js');
    	echo $this->Html->script('jquery-ui.js');
    	echo $this->Html->script('bootstrap.min.js');
    	echo $this->Html->script('parsley.min.js');
        echo $this->Html->script('jquery.pajinate.min.js');
        
        $controller = $this->params['controller'];
        $action = $this->action;
        
         if( $this->params['controller']== "FunctionCorps" && $this->action=="AppointResign"){
             echo $this->Html->script('application.js');
         }
        
        if( ($this->params['controller']== "FunctionCorps" && $this->action=="AppointResignS") || ($this->params['controller']== "secretaries" && $this->action=="secretaryForm")){
            
            echo $this->Html->script('AppointResignS.js');
        }else if ($this->params['controller']== "documents"){
            echo $this->Html->script('applicationDocument.js');
        }else if(($controller=="Shareholders" && $action=="shareholderForm") || ($controller =="FunctionCorps" && ($action == "changeOfCompanyName" || $action == "changeOfMAA"))){
            echo $this->Html->script('addShareHolder.js');
        }else if ($controller=="FunctionCorps" && $action=="ChangeOfPassport") {
            echo $this->Html->script('changePassport.js');
        }else if ($controller=="FunctionCorps" && $action=="NormalStruckOff") {
            echo $this->Html->script('normalStruckOff.js');
        }
        else{
            echo $this->Html->script('application.js');
        }
       // ChromePhp::log($this->action);
   
        
    ?>
  </body>
</html>
