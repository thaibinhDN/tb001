<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<title>Styled input[type="file"]</title>
<link href="http://fonts.googleapis.com/css?family=Open+Sans:300" rel="stylesheet" type="text/css">

<style>
     span{
	padding:4px 12px;
	border: 1px solid #ddd;
}
    </style>
</head>
<h1><?php echo $header?></h1>
<br>
<div>
 <?php echo $this->Form->create("Documents",array("action"=>"ChangeOfBankingSignatoriesUOB"));?>
 <select name="director[]" class="form-control" id="director">
<option value="" selected>--Select Director--</option>

<?php foreach($directors as $director){ ?>
    
        <option value="<?php echo $director['StakeHolder']['id'] ?>"><?php echo $director['StakeHolder']['name'] ?> </option>
    
<?php }?>
</select> 
<input type="hidden" name="function_id" value="<?php echo $functionCorp ?>"/>
<input type="hidden" name="company_id" value="<?php echo $company ?>"/>
<button class="btn btn-small btn-primary" type="submit" id="Search">Search</button>
</form>
</div>
<br>
<br>

<table class="table table-striped">
	<thead>
		<th>Director</th>
                
		<th>Before Submission</th>
		<th>After Submission</th>
                <th></th>
                
	</thead>
	<tbody>
            
           <?php for($i = 0;$i<count($documents);$i++){ ?>
            <!-- Data for filtering -->
       
            <tr>
                <td>
                    <?php 
                            
                          $name = $CBSdirectors[$i]['StakeHolder']['name']."(".$CBSdirectors[$i]['Event']['created_time'].") ";
                    ?>
                    <?php echo $name;
                          
                    ?></td>
             
                <td>  
                 
                <?php if(!empty($CBSdirectors[$i]['Document']['before'])){?>
                        <?php echo $this->Html->link('Download', array('controller' => 'Documents', 'action' => 'downloadFile','?'=>array('company'=>$company,"function"=>$functionCorp,'type'=>'before','id' => $CBSdirectors[$i]['Document']['id']))); ?>
                        <br />
                        <?php echo $this->Html->link('Delete', array('controller' => 'Documents', 'action' => 'deleteFile', '?'=>array('company'=>$company,"function"=>$functionCorp,'type'=>'before','id' => $CBSdirectors[$i]['Document']['id'])), array('escape' => false, 'confirm' => 'Are you sure you want to delete this form?')); ?>
				
                     <?php }else{?>
                        <?php echo $this->Form->create("Documents",array("action"=>"uploadBeforeSubmission",'type'=>'file'));
                    
                 ?>
                        <input class ="form-control pathfield" type="text" value="" disabled/>
                         <input type="hidden" name="document_director_id" value="<?php echo $CBSdirectors[$i]['Document']['id']?>"/>
                         <div style="margin-top:12px">
                         <input class ="form-control" type="text" name="acra" value="" placeholder="ACRA"/>
                         </div>
                        <input name = "submissionType" type="hidden" value="Before"/>
                     <br>
                     <?php echo $this->Form->input("Browse",array("type"=>"file","class"=>"browse","div"=>array("class"=>"btn btn-default btn-file btn-xs browseWidth browseId "))) ?>
                       
                     <input type="hidden" name="function_id" value="<?php echo $functionCorp ?>"/>
                     <input type="hidden" name="company_id" value="<?php echo $company ?>"/> 
                     <input  class="btn btn-default btn-xs" type="submit"/>
                    
       
                     </form>
                     <?php }?>
                    
                </td>
              <td>  
                 
                  
                  <?php if(!empty($CBSdirectors[$i]['Document']['after'])){?>
                        <?php echo $this->Html->link('Download', array('controller' => 'Documents', 'action' => 'downloadFile', '?'=>array('company'=>$company,"function"=>$functionCorp,'type'=>'after','id' => $CBSdirectors[$i]['Document']['id']))); ?>
                        <br />
                        <?php echo $this->Html->link('Delete', array('controller' => 'Documents', 'action' => 'deleteFile', '?'=>array('company'=>$company,"function"=>$functionCorp,'type'=>'after','id' => $CBSdirectors[$i]['Document']['id'])), array('escape' => false, 'confirm' => 'Are you sure you want to delete this form?')); ?>
				
                     <?php }else{?>
                        <?php echo $this->Form->create("Documents",array("action"=>"uploadAfterSubmission",'type'=>'file'));
                    
                 ?>
                         <input class ="form-control pathfield" type="text" value="" disabled/>
                         
                        <div style="margin-top:12px">
                         <input class ="form-control" name="acra" type="text" value="" placeholder="ACRA"/>
                        </div>
                         <input name = "submissionType" type="hidden" value="after"/>
                     <br>
                     <?php echo $this->Form->input("Browse",array("type"=>"file","class"=>"browse","div"=>array("class"=>"btn btn-default btn-file btn-xs browseWidth browseId "))) ?>
               
                     
                     <input type="hidden" name="function_id" value="<?php echo $functionCorp ?>"/>
                     <input type="hidden" name="company_id" value="<?php echo $company ?>"/> 
                     
                     <input  class="btn btn-default btn-xs" type="submit"/>
                     
                     <input type="hidden" name="document_director_id" value="<?php echo $CBSdirectors[$i]['Document']['id']?>"/>
                     </form>
                     <?php }?>
                </td>
                
                <td><?php echo $this->Html->link('Delete', array('controller' => 'Documents', 'action' => 'deleteDocument','?'=>array('company'=>$company,"function"=>$functionCorp,'id' => $CBSdirectors[$i]['Document']['id'])),array('escape' => false, 'confirm' => 'Are you sure you want to delete this form?')); ?></td>
            </tr>
            
        
            
            <?php }?>
            
            
	</tbody>
</table>
<div id="paging"/>
<?php
// Shows the page numbers
echo $this->Paginator->numbers(array("separator"=>""));

// Shows the next and previous links
echo $this->Paginator->prev(
  '« Previous',
  null,
  null,
  array('class' => 'disabled')
);
echo $this->Paginator->next(
  'Next »',
  null,
  null,
  array('class' => 'disabled')
);

// prints X of Y, where X is current page and Y is number of pages
echo $this->Paginator->counter();
?>
</div>



