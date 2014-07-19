<h1>Login</h1>
<h3><?php echo $heading?></h3>
<div class="form-content">
	<form action="<?php echo $this->Html->url(array('controller' => 'Users', 'action' => 'login')); ?>" method="post" accept-charset="utf-8">
  
      <?php echo $this->Form->input('username', array('name'=>'username','class' => 'form-control', 'label' => 'Name')); ?>
     <?php echo $this->Form->input('password', array('name'=>'password','class' => 'form-control', 'label' => 'Password')); ?>
     <?php echo $this->Form->submit('Login', array('class' => 'form-control')); ?>
        </form>
</div>

